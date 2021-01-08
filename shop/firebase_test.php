<?php
include_once('./_common.php');
//echo G5_JS_URL;
add_stylesheet('<link rel="manifest" href="'.G5_JS_URL.'/manifest.json">', 0);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Insert title here</title></head>
<body><h1>FCM 메세지</h1>

<script>
    if ("serviceWorker" in navigator) {
        window.addEventListener("load", () => {
            navigator.serviceWorker.register("https://7detox.co.kr/detox/service-worker.js")
                .then(() => console.info('service worker registered'))
                .catch(error => {
                    console.log('ServiceWorker registration failed: mobile ', error)
                });
        });
    }
</script>

<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://www.gstatic.com/firebasejs/7.6.1/firebase.js"></script>
<link rel="manifest" href="https://7detox.co.kr/detox/js/manifest.json"/>
<script> // Initialize Firebase
    var config = {
        apiKey: "AIzaSyA0Les_Nu-_P430nPtskgyYPyRI92VAgmM",
        authDomain: "detox-firedb.firebaseapp.com",
        databaseURL: "https://detox-firedb.firebaseio.com",
        projectId: "detox-firedb",
        storageBucket: "detox-firedb.appspot.com",
        messagingSenderId: "216743546689",
        appId: "1:216743546689:web:3680e08f5ed816e2585bc0"
    };
    firebase.initializeApp(config);
    const messaging = firebase.messaging(); //token 값 알아내기
    messaging.requestPermission().then(function (permission) {
        alert(permission + " : Have permission");
        return messaging.getToken();
    }).then(function (token) {
        console.log('token : ' + token);
        // push test
        //
        /* $.ajax({
             url : "shop/shop_marketing_ajax_common.php",
             data : {"action" : "fb_push",
                 "token" : token},
             async: false,
             success : function (data){
                 //location.href = "javascript:history.go(-1);"
                 console.log(data + ":: fb_push test");
             }
         });*/
        $('#_token').html(token);
    }).catch(function (arr) {
        console.log("Error Occured");
        alert(arr);
    });
</script>
<span id='_token'></span></body>
</html>

