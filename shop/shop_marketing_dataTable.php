<?php
//error_reporting(E_ALL);
//ini_set("display_errors",1);
include_once('./_common.php');
include_once('../dbcontroll.php');
header('Content-Type: application/json');

$action = isset($_REQUEST['action']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['action']) : '';
//$member_level = $_REQUEST['mb_level'];
$dao = new DetoxDao();

$getDataArr = array();

switch ($action){
    case "admin_list" :
        $getData = $dao->getCalculate_history();
        for ($i=0; $val=sql_fetch_array($getData); $i++) {
            $empty_arr = array();
            array_push($empty_arr,"",
                $val["it_name"] ,
                $val["od_settle_case"] ,
                $val["od_name"] ,
                $val["calculate_FL"] ,
                $val["od_cart_price"] ,
                $val["it_marketing_expenses"] ,
                $val["seller1"] ,
                $val["top_seller1_expenses"] ,
                $val["seller2"] ,
                $val["top_seller2_expenses"] ,
                $val["seller3"] ,
                $val["top_seller3_expenses"] ,
                $val["seller4"] ,
                $val["top_seller4_expenses"] ,
                $val["total"],
                $val[""],
                $val["od_id"]
            );
            array_push($getDataArr,$empty_arr);
        }
        break;
    case "user_list" :
        $mb_id = $member["mb_id"];
        $getData = $dao->getMembercalculate_history($mb_id);
        for ($i=0; $val=sql_fetch_array($getData); $i++) {
            $empty_arr = array();
            array_push($empty_arr,"",
                $val["it_name"] ,
                $val["A1"] ,
                $val["A2"] ,
                $val["A3"] ,
                $val["A4"] ,
                $val["total"]
            );
            array_push($getDataArr,$empty_arr);

        }
        break;
    case "firebase":
        $getData = $dao->getMemberTokenList();
        for ($i=0; $val=sql_fetch_array($getData); $i++) {
            $empty_arr = array();
            array_push($empty_arr,"",
                $val["mb_id"] ,
                $val["mb_name"] ,
                "",
                $val["firebase_token"]
            );
            array_push($getDataArr,$empty_arr);
        }
        break;
}
$dataArr = array();
$dataArr["data"] = $getDataArr;
$output = json_encode($dataArr,JSON_UNESCAPED_UNICODE);
echo $output;