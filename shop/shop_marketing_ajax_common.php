<?php
//error_reporting(E_ALL);
//ini_set("display_errors",1);
include_once('./_common.php');

$action = isset($_REQUEST['action']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['action']) : '';
$dao = new DetoxDao();

switch ($action){
    case "order_cancle" :
        $it_id = $_REQUEST["it_id"];
        echo $dao->setMarketingStatus($it_id,$member["mb_id"],"1","2");
        break;
//    case "expenses_input" :
//        $it_id = $_REQUEST["it_id"];
//        echo $dao->setMarketingExpenses($it_id);
//        break;
    case "fb_push" :
        $token = $_REQUEST["token"];
        $body = $_REQUEST["body"];
        fcm_push($token , $body);
        break;
    case "update_firebase_token" :
        $mb_id = $_REQUEST["mb_id"];
        $token = $_REQUEST["token"];
        echo $dao->setFirebaseToken($mb_id,$token);
        break;
    case "update_expenses_calculate" :
        $flag = $_REQUEST["flag"];
        $od_id = $_REQUEST["od_id"];
        echo $dao->setExpensesCalculate($flag,$od_id);
        break;
    case "add_alarm" :
        $mb_id = $member["mb_id"];
        $day = $_REQUEST["day"];
        $hour = $_REQUEST["hour"];
        $min = $_REQUEST["min"];
        $title = $_REQUEST["title"];
        echo  $dao->addAlarm($member["mb_id"],$day,$hour,$min,$title);
        break;
    case "remove_alarm" :
        $mb_id = $member["mb_id"];
        $alarmIndex = $_REQUEST["alarmIndex"];
        echo  $dao->removeAlarm($member["mb_id"],$alarmIndex);
        break;
    case "update_alarm" :
        echo $alarmIndex = $_REQUEST['alarmIndex'];
        echo $useYN = $_REQUEST['use'];
        echo $dao->updateAlarm($member["mb_id"],$alarmIndex,$useYN);
        break;
    case "fb_push_all" :
        $token = '/topics/7detox';
        $body = $_REQUEST["body"];
        fcm_push($token , $body);
        break;
}

function fcm_push($token , $boby){
    try{
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array('to' => $token, 'notification' => array('title' => '7Detox', 'body' => $boby, 'click_action' => 'https://7detox.co.kr/detox/shop/'));
        $fcm_sever_key = 'AAAAizwEJnA:APA91bE6bVl5OW9-Y9nmFbvDqF8oi5eryWav6A0NvPgOv5qMxMkPzHM97AzlWGBTz5lLKEjMolXQMJiHbMvX1WJjD0hDpKtXpWziKcquvDJd4VdnzSumeY3CURT32VzBASy8ARxk_tQd';
        $headers = array('Authorization:key =' . $fcm_sever_key, 'Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl Failed: ' . curl_error($ch));
        }
        curl_close($ch);
        echo "firebase push";
    } catch(Exception $exceptione) {
        echo $exceptione;
    }
}