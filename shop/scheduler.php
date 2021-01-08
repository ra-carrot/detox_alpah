<?php
/*
 * 스케줄러 파일

    알람 설정을 해뒀을 경우, 현재시간 과 동일한 알람 설정한 회원에게 알람전송

    Date date = new Date();
    String $hours = date.getHours();
    SELECT * FROM detox_member_alarm WHERE alarm_time = '$hours'

    SELECT firebase_token FROM g5_member ME
    JOIN detox_member_alarm MA ON ME.mb_id = MA.mb_id AND MA.alarm_time = '$hours'

    $date = new Date();
    echo date('H', $date);
*/

$connect_db = mysqli_connect('localhost', 'root', 'detox1!', 'detox');
mysqli_set_charset( $connect_db,'utf8');

//$sql = "select firebase_token from g5_member where firebase_token is not null";
$sql ="select g5m.firebase_token,ma.title,ma.description,g5m.mb_id,g5m.mb_name from detox_member_alarm ma join g5_member g5m on g5m.mb_id = ma.mb_id and g5m.firebase_token is not null and g5m.mb_leave_date = '' where (days='all' or days =? or days = ?) and hours = ? and min = ? and isUse ='Y'";

//$stmt = $sql->getQueryPrepare("select g5m.firebase_token,ma.title,ma.desc from detox_member_alarm ma join g5_member g5m on g5m.mb_id = ma.mb_id and g5m.firebase_token is not null where (day='all' or day =? or day = ?) and hours = '17' ");

$day = date("Y-m-d", time());
$week = array("sun" , "mon"  , "tue" , "wed" , "thu" , "fri" ,"sat") ;
$weekday = $week[ date('w'  , strtotime($day)  ) ] ;

if($weekday == "sun" or $weekday == "sat") {
    $isWeekend = "weekend";
} else {
    $isWeekend = "weekday";
}
$hours = date("H", time());

$min = date("i", time());

$params = array($weekday,$isWeekend,$hours,$min);

//mysqli_prepared_query($connect_db,$query,"s",$params);
$stmt = $connect_db->prepare($sql);
$stmt->bind_param("ssss", $weekday,$isWeekend,$hours,$min);
$stmt->execute();
$tokens = $stmt->get_result();

//echo "#################".$isWeekend.".".$weekday.".".$hours.".".$min."\n";
//echo $tokens;
//var_dump($tokens);
while ($row = mysqli_fetch_row($tokens)) {
    echo "########################################################";
    echo $isWeekend.".".$weekday.".".$hours.".".$min.PHP_EOL;
    echo $row[0].".".$row[1].".".$row[2].".".$row[3].".".$row[4].PHP_EOL;
    echo "########################################################";
    $boby = $row[1];

    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array('to' =>  $row[0], 'notification' => array('title' => '7Detox', 'body' => $boby, 'click_action' => 'https://7detox.co.kr/detox/shop/'));
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

}
mysqli_close($connect_db);

?>