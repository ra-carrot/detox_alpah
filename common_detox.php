<?php

require_once 'pdo/PdoOne.php';
require_once 'pdo/PdoOneEncryption.php';
require_once 'pdo/Collection.php';
//require_once 'lib/imgResize.php';

//error_reporting(E_ALL);
//ini_set("display_errors",1);

use eftec\PdoOne;
use eftec\PdoOneEncryption;

function clear_text($str)
{
    $pattern1 = "/[\<\>\'\"\\\'\\\"\(\)]/";
    $pattern2 = "/\r\n|\r|\n|[^\x20-\x7e]/";

    $str = preg_replace($pattern1, "", clean_xss_tags($str, 1));
    $str = preg_replace($pattern2, "", $str);
    return $str;
}

// 공백 제거
function clean_whitespaces($str = ''){
    return preg_replace("/\s+/","",$str);
}

// - 제거
function clean_phone_text($str = ''){
    return preg_replace("/-/","",$str);
}

class DetoxDao
{
    /**
     */
    /**
     * PdoOne constructor.  It doesn't connect to the database.
     * @param string database ['mysql','sqlsrv','oracle'][$i]
     * @param string $server server ip. Ex. 127.0.0.1
     * @param string $user Ex. root
     * @param string $pwd Ex. 12345
     * @param string $db Ex. mybase
     * @param string $logFile Optional  log file. Example c:\\temp\log.log
     * @param string $charset Example utf8mb4
     * @param int $nodeId It is the id of the node (server). It is used for sequence. Up to 4096
     * @see PdoOne::connect()
     */
    private $dao;
    private $host = '127.0.0.1';
    private $user = 'root';
    private $pass = 'detox1!';
    private $db = 'detox';
    private $pdoEncrypt;

    /**
     * TestResult constructor.
     * @param $userID
     * _common.php 에서 세팅된 $member['mb_id']
     */
    function __construct()
    {
        $this->dao = new PdoOne('mysql', $this->host, $this->user, $this->pass, $this->db, "log_db_err_log.txt");

        try {
            $this->dao->connect();
            $this->pdoEncrypt = new PdoOneEncryption("detox", "detox_salt");
            $this->pdoEncrypt->encEnabled = true;
            //            $this->_login_success();
        } catch (Exception $e) {
            die(1);
        }
    }

    function getUserInfo($mb_id){
        try{
            $result = $this->dao->select("*")
                ->from("g5_member")
                ->where('mb_id=?' , $mb_id)
                ->limit(1)->first();
            return $result;
        }catch (Exception $e){
            return null;
        }
    }

    // 추천인 입력값 member 확인
    function checkMember($mb_id){
        try{
            $cnt = $this->dao->select("count(1) AS cnt")
                ->from("g5_member")
                ->where("mb_id=?",$mb_id)
                ->limit(1)->first();
            return $cnt["cnt"] > 0;
        }catch (Exception $e){
            var_dump($e->getTrace());
            return false;
        }
    }

    //마케팅 데이터 중복 입력 방지
    function checkMarketingLevelOverlap($it_id, $lower_seller){
        try{
            $cnt = $this->dao->select("count(1) AS cnt")
                ->from("detox_marketing_level")
                ->where("it_id =?" , $it_id)
                ->where("lower_seller =?" ,$lower_seller)
                ->where("order_state != 2")
                ->limit(1)->first();
            return $cnt["cnt"] > 0;
        }catch (Exception $e){
            var_dump($e->getTrace());
            return false;
        }
    }

    //처음 구매 시 마케팅 데이터 insert
    function setMarketingLevel($it_id,$top_seller,$lower_seller,$order_state){
        if($this->dao->startTransaction()){
            try{
                $order_date = date("Y-m-d H:i:s");

                $sql = $this->dao->prepare("INSERT INTO detox_marketing_level (it_id, top_seller, lower_seller, order_date, order_state)
                                    VALUES (?,?,?,?,?)");
                $sql->bindParam(1,$it_id,PDO::PARAM_INT);
                $sql->bindParam(2,$top_seller,PDO::PARAM_STR);
                $sql->bindParam(3,$lower_seller,PDO::PARAM_STR);
                $sql->bindParam(4,$order_date,PDO::PARAM_STR);
                $sql->bindParam(5,$order_state,PDO::PARAM_STR);
                $sql->execute();
                $this->dao->commit();
            }catch (Exception $e){

            }
        }
    }

    //마케팅 데이터 상태 update
    function setMarketingStatus($it_id,$mb_id,$before_order_state,$order_state){

        $it_id = clean_xss_tags($it_id);
        $update_date = date("Y-m-d H:i:s");
        if($this->dao->startTransaction()){
            try{
                $this->dao->from("detox_marketing_level")
                    ->set("order_state=?",$order_state)
                    ->set("last_update_date=?",$update_date)
                    ->where('it_id=?', $it_id)
                    ->where("lower_seller=?",$mb_id)
                    ->where("order_state=?",$before_order_state)
                    ->update();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }

    // 수익 구조 데이터 insert
    function setMarketingExpenses($it_id , $lower_seller,$od_id,$detox_ct_qty){
        try{
            //1단계 40% 2단계 30% 3단계 20% 4단계 10;

            //조회하는 마케팅 레벨 != 0 아닐때만 부모가 돈을 받는다 돈 지급될때 마다 $push_cnt ++;  push_cnt == 3까지 계속 부모를 찾는다.

            //나의 정보 (체크해야할 상위 마케팅 정보)
            $chkInfo = $this->getUserInfo($lower_seller);
            //나의 마케팅 레벨 (체크해야할 마케팅 레벨)
            $chkMarketingLevel = $chkInfo['marketing_level'];
            //작업 cnt
            $push_cnt = 0;
            //상품 마케팅 비용
            $item_expenses = $this->getItemExpenses($it_id);
            //마케팅 $
            $marketing_percent = $this->getMarketingPercent();

            $sellers = array();
            $expenses = array();
            while ($push_cnt <= 4){
                //계속 부모 조회했는데 끝까지 갔으며 push_cnt 가 3이 안될경우 체크
                if($chkInfo == null)break;
                //마케팅 레벨 0 아닌것만 (알파라인 아닌것만)
                if($chkMarketingLevel != 0){
                    //작업 cnt ++
                    $push_cnt++;
                    //단계별 추천인 입력
                    $sellers[$push_cnt] = $chkInfo["marketing_members_first"];
                    //단계별 마케팅 비용 입력
                    switch ($push_cnt){
                        case 1:
                            $expenses[$push_cnt] = $item_expenses["it_marketing_expenses"] * $marketing_percent["level_1"] * $detox_ct_qty;
                            break;
                        case 2:
                            $expenses[$push_cnt] = $item_expenses["it_marketing_expenses"] * $marketing_percent["level_2"] * $detox_ct_qty;
                            break;
                        case 3:
                            $expenses[$push_cnt] = $item_expenses["it_marketing_expenses"] * $marketing_percent["level_3"] * $detox_ct_qty;
                            break;
                        case 4:
                            $expenses[$push_cnt] = $item_expenses["it_marketing_expenses"] * $marketing_percent["level_4"] * $detox_ct_qty;
                            break;
                    }
                }
                //나의 부모 정보
                $chkInfo = $this->getUserInfo($chkInfo['marketing_members_first']);
                //나의 부모의 마케팅 레벨
                $chkMarketingLevel = $chkInfo['marketing_level'];
            }

            //만약 부모를 3까지 못찾고 나왔을때 array 에 빈값 set
            if($push_cnt != 4){
                $push_cnt = $push_cnt +1;
                for($i = $push_cnt; $i <= 4; $i++){
                    $sellers[$i] = null;
                    $expenses[$i] = 0;
                }
            }

//            echo '<br>';
//            var_dump($sellers);
//            echo '<br>';
//            var_dump($expenses);
//            return false;

            $flag = "N";
            $sql = $this->dao->prepare("INSERT INTO detox_marketing_expenses (it_id, od_id,top_seller1,top_seller2, top_seller3,top_seller4, top_seller1_expenses,
                                top_seller2_expenses,top_seller3_expenses, top_seller4_expenses ,it_marketing_expenses,calculate_FL)
                                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $sql->bindParam(1,$it_id,PDO::PARAM_STR);
            $sql->bindParam(2,$od_id);
            $sql->bindParam(3,$sellers[1],PDO::PARAM_STR);
            $sql->bindParam(4,$sellers[2],PDO::PARAM_STR);
            $sql->bindParam(5,$sellers[3],PDO::PARAM_STR);
            $sql->bindParam(6,$sellers[4],PDO::PARAM_STR);
            $sql->bindParam(7,$expenses[1],PDO::PARAM_INT);
            $sql->bindParam(8,$expenses[2],PDO::PARAM_INT);
            $sql->bindParam(9,$expenses[3],PDO::PARAM_INT);
            $sql->bindParam(10,$expenses[4],PDO::PARAM_INT);
            $sql->bindParam(11,$item_expenses["it_marketing_expenses"],PDO::PARAM_INT);
            $sql->bindParam(12, $flag,PDO::PARAM_STR);
            $sql->execute();
            $this->dao->commit();
        }catch (Exception $exception){
            var_dump($exception->getMessage());
        }
    }

    //구매한 상품에서 상위 판매자 select
    function getMarketingLevels($lower_seller,$it_id){
        try{
            $result = $this->dao->select("top_seller")
                ->from("detox_marketing_level")
                ->where("lower_seller =?" ,$lower_seller)
                ->where("it_id=?", $it_id)
                ->where("order_state = 4")
                ->limit(1)->first();
            return $result;
        }catch (Exception $e){
            return null;
        }
    }

    //상품의 마케팅비용
    function getItemExpenses($it_id){
        try{
            $expenses = $this->dao->select("it_marketing_expenses")
                ->from('g5_shop_item')
                ->where("it_id =?" , $it_id)
                ->limit(1)->first();
            return $expenses;
        }catch (Exception $exception){
            return null;
        }
    }

    //마케팅 단계별 % 가져옴
    function getMarketingPercent(){
        try{
            $percent = $this->dao->select("*")
                ->from("detox_marketing_percent")
                ->limit(1)->first();
            return $percent;
        }catch (Exception $e){
            return null;
        }
    }

    //주문취소 했을때 수익구조 데이터 flag 업데이트
    function setMarketingCancle($od_id){
        try{

            $flag = "X";
            $sql = $this->dao->prepare("UPDATE detox_marketing_expenses SET calculate_FL = ? WHERE od_id = ?");
            $sql->bindParam(1,$flag);
            $sql->bindParam(2,$od_id);
            $sql->execute();
//            $this->dao->from("detox_marketing_expenses")
//                ->set("calculate_FL=?",$flag)
//                ->where("od_id = ?",$od_id)
//                ->update();
        }catch (Exception $e){

        }
    }

    //추천인 중복 입력 방지
    function getTopSeller($it_id , $seller_id){
        try{
            $result = $this->dao->select("top_seller")
                ->from("detox_marketing_level")
                ->where("lower_seller =?" , $seller_id)
                ->where("it_id =? " ,$it_id)
                ->limit(1)->first();

            return $result;

        }catch (Exception $e){
            return null;
        }
    }

    //관리자 정산내역
    function getCalculate_history(){
        try{
            $sql = "select  B.it_name,
                            C.od_settle_case,
                            C.od_name,
                            A.calculate_FL,
                            C.od_cart_price,
                            A.it_marketing_expenses,
                            (select mb_name from g5_member B where B.mb_id = A.top_seller1) as seller1,
                            A.top_seller1_expenses,
                            (select mb_name from g5_member B where B.mb_id = A.top_seller2) as seller2,
                            A.top_seller2_expenses,
                            (select mb_name from g5_member B where B.mb_id = A.top_seller3) as seller3,
                            A.top_seller3_expenses,
                            (select mb_name from g5_member B where B.mb_id = A.top_seller4) as seller4,
                            A.top_seller4_expenses,
                            A.top_seller1_expenses+A.top_seller2_expenses+A.top_seller3_expenses+A.top_seller4_expenses AS total,
                            A.od_id
                    from    detox_marketing_expenses A ,
                            g5_shop_item B ,
                            g5_shop_order C
                    where   A.it_id = B.it_id
                    and     A.od_id = C.od_id
                    and   C.od_status in ('입금','준비','배송','완료')
                    and   A.top_seller1_expenses+A.top_seller2_expenses+A.top_seller3_expenses+A.top_seller4_expenses != 0";
            $result = sql_query($sql);
            return $result;
        }catch (Exception $e){
            return null;
        }
    }

    //개인별 정산 내역
    function getMembercalculate_history($member_id){
        try{
            $sql = "select  A.it_id,
                            A.it_name,
                            sum(A.A1) A1,
                            sum(A.A2) A2,
                            sum(A.A3) A3,
                            sum(A.A4) A4,
                            sum(A.A1) + sum(A.A2) + sum(A.A3) + sum(A.A4) as total
                    from (select A.it_id,
                                 C.it_name,
                                    case when top_seller1 = '{$member_id}' then top_seller1_expenses else 0 end A1,
                                    case when top_seller2 = '{$member_id}' then top_seller2_expenses else 0 end A2,
                                    case when top_seller3 = '{$member_id}' then top_seller3_expenses else 0 end A3,
                                    case when top_seller4 = '{$member_id}' then top_seller4_expenses else 0 end A4
                          from detox_marketing_expenses A
                                   join g5_shop_order B on A.od_id = B.od_id
                                   join g5_shop_item C on A.it_id = C.it_id
                          where B.od_status in ('입금','준비','배송','완료')
                            and top_seller1 = '{$member_id}'
                             or top_seller2 = '{$member_id}'
                             or top_seller3 = '{$member_id}'
                             or top_seller4 = '{$member_id}'
                         ) A
                            group by A.it_id";
            $result = sql_query($sql);
            return $result;
        }catch (Exception $e){

        }
    }

    //g5 멤버 토큰 리스트 (토큰 존재할경우)
    function getMemberTokenList(){
        try{
            $sql = "select  mb_id, mb_name , firebase_token
                    from    g5_member
                    where   firebase_token is not null";
            $result = sql_query($sql);
            return $result;
        }catch (Exception $e){
            return null;
        }
    }

    // 파이어 베이스 토큰 업데이트
    function setFirebaseToken($mb_id,$token){
        if($this->dao->startTransaction()){
            try{
                $this->dao->from("g5_member")
                    ->set("firebase_token=?",$token)
                    ->where('mb_id=?', $mb_id)
                    ->update();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }
    //정상 취소, 정상 하기 flag 업데이트
    function setExpensesCalculate($flag, $od_id){
        if($this->dao->startTransaction()){
            echo $od_id;
            echo $flag;
            try{
                $sql = $this->dao->prepare("UPDATE detox_marketing_expenses SET calculate_FL = ? WHERE od_id = ?");
                $sql->bindParam(1,$flag);
                $sql->bindParam(2,$od_id);
                $sql->execute();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }


    //알람 제거
    function removeAlarm($mb_id, $alarmIndex){
        if($this->dao->startTransaction()){
            try{
                $sql = $this->dao->prepare("DELETE from detox_member_alarm WHERE mb_id = ? and alarmIndex = ?");
                $sql->bindParam(1,$mb_id);
                $sql->bindParam(2,$alarmIndex);
                $sql->execute();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }


    //알람 추가
    function addAlarm($mb_id,$day,$hours,$min,$title){
        if($this->dao->startTransaction()){
            try{
                $use = "Y";
                $sql = $this->dao->prepare("INSERT INTO detox_member_alarm(mb_id, days, hours, min, title, isUse) VALUES (?,?,?,?,?,?);");
                $sql->bindParam(1,$mb_id);
                $sql->bindParam(2,$day);
                $sql->bindParam(3,$hours);
                $sql->bindParam(4,$min);
                $sql->bindParam(5,$title,PDO::PARAM_STR);
                $sql->bindParam(6,$use);
                $sql->execute();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }

    function updateAlarm($mb_id,$alarmIndex,$useYN){
        if($this->dao->startTransaction()){
            try{
                $sql = $this->dao->prepare("UPDATE detox_member_alarm SET isUse = ? WHERE alarmIndex = ? and mb_id = ?");
                $sql->bindParam(1,$useYN);
                $sql->bindParam(2,$alarmIndex);
                $sql->bindParam(3,$mb_id);
                $sql->execute();

                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }

    //마케팅 정보 유저 DB 에 UPDATE
    function setMarketingInfoProcess($it_id,$mb_id){
        try{
            //구매기록 추천인 마케팅 정보 가져오기
            $myTopSellerInfo = $this->getTopSellerInfo($it_id,$mb_id);

            //나의 마케팅 레벨 구하기
            $myMarketingLevel = 0;
            if($myTopSellerInfo['marketing_members'] == 0){
                $myMarketingLevel = 0;
            }else{
                $myMarketingLevel = $myTopSellerInfo['marketing_members'];
            }

//            echo $myMarketingLevel;

            //나의 마케팅 정보 업데이트
            $reuslt = $this->setMyMarketingInfo($myTopSellerInfo['mb_id'] , $myMarketingLevel , $mb_id);
//            var_dump($reuslt);

//            내가 추천한 사람 마케팅 cnt +1 해주기
            $reuslt = $this->setTopSellerMarketingInfo($myTopSellerInfo['mb_id']);
//            var_dump($reuslt);

        }catch (Exception $e){
            return $e->getMessage();

        }
    }

    //나의 마케팅 정보 업데이트
    function setMyMarketingInfo($top_seller_id,$marketing_level,$mb_id){
        if($this->dao->startTransaction()){
            try{
                echo $top_seller_id.$marketing_level.$mb_id;
                $sql = $this->dao->prepare("UPDATE g5_member SET marketing_members_first =? ,marketing_level =? WHERE mb_id = ?");
                $sql->bindParam(1,$top_seller_id,PDO::PARAM_STR);
                $sql->bindParam(2,$marketing_level,PDO::PARAM_INT);
                $sql->bindParam(3,$mb_id,PDO::PARAM_STR);
                $sql->execute();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                var_dump($e->getMessage());
                return false;
            }
        }
    }

    // 추천인 유저정보 가져옴
    function getTopSellerInfo($it_id,$mb_id){
        try{
            $sql = "select  mb_id , marketing_level , marketing_members , marketing_members_first
                    from    g5_member a ,
                            detox_marketing_level b
                    where   a.mb_id = b.top_seller
                    and     b.lower_seller = '{$mb_id}'
                    and     b.it_id = '{$it_id}'
                    and     b.order_state = '4' ";
            $result = sql_fetch($sql);
            return $result;
        }catch (Exception $e){
            var_dump($e->getMessage());
            return null;
        }
    }
    // 추천인 마케팅 cnt +
    function setTopSellerMarketingInfo($top_seller){
        if($this->dao->startTransaction()){
            try{
                $sql = $this->dao->prepare("UPDATE g5_member SET marketing_members = marketing_members + 1 WHERE mb_id = ?");
                $sql->bindParam(1,$top_seller,PDO::PARAM_STR);
                $sql->execute();
                $this->dao->commit();
                return true;
            }catch (Exception $e){
                return false;
            }
        }
    }
}