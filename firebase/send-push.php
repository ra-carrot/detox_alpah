<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_push extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $tokens = 'index.html 에서 얻은 Token 값';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array('to' => $tokens, 'notification' => array('title' => 'Web PUSH Test - Title', 'body' => 'Web Push Message', 'click_action' => 'https://yultory.com/'));
        $fcm_sever_key = 'AAAAMnbsD0E:APA91bEtzvFeo-IOjbHbYR0uw3ylyNo24t4LyhsMhyy3pLqYw7KpQvrahpvM_x5APDh-hxg_zbKcqv9LAWNBG8x3H6W5A58J8ElMgpX4VYe7NWT_6ttqSV5DdzhYZvum7KBMr1mH6nMV';
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
}

?>