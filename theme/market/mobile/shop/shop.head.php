<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
add_stylesheet('<link rel="apple-touch-icon" href="'.G5_THEME_IMG_URL.'/logo.png?v=5">', 0);
add_stylesheet('<link rel="apple-touch-icon" sizes="152x152" href="'.G5_THEME_IMG_URL.'/logo.png?v=5">', 0);
add_stylesheet('<link rel="apple-touch-icon" sizes="167x167" href="'.G5_THEME_IMG_URL.'/logo.png?v=5">', 0);
add_stylesheet('<link rel="apple-touch-icon" sizes="180x180" href="'.G5_IMG_URL.'/pwa-splash.png">', 0);
add_stylesheet('<link rel="manifest" href="../../../manifest.json">', 0);
?>
<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->

<script src="https://www.gstatic.com/firebasejs/8.1.1/firebase-analytics.js"></script>

<script src="<?=G5_URL?>/firebase-messaging.js?ver=44"></script>
<script>
    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    var firebaseConfig = {
        apiKey: "AIzaSyDBTUYqATItl-WCXi-uoX8Z5ExxB7kcGPM",
        authDomain: "detox-53dda.firebaseapp.com",
        databaseURL: "https://detox-53dda.firebaseio.com",
        projectId: "detox-53dda",
        storageBucket: "detox-53dda.appspot.com",
        messagingSenderId: "598007359088",
        appId: "1:598007359088:web:655e032d7b0d44ad6676b5",
        measurementId: "G-6J12JK6393"
    };
    // Initialize Firebase

    if ("serviceWorker" in navigator) {
        window.addEventListener("load", () => {
            navigator.serviceWorker.register("/service-worker16.js");
        });
    }
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
    // [START get_messaging_object]
    // Retrieve Firebase Messaging object.
    var messaging = firebase.messaging();
    // [END get_messaging_object]
    messaging.getToken({vapidKey: 'BAC7b0MnvSUqtfEPDUWJGZX07QbNOG6B4EXMax1F4CExETitaZP-B7Xnet5Ru1S4Mnxoju9T_OYE9I_XI3ogeow'}).then((currentToken) => {
        if (currentToken) {
            var g5_shop_url = "<?php echo G5_SHOP_URL; ?>";
            <?php if ($_SESSION['ss_mb_id']) { ?>
            $.ajax({
                url : g5_shop_url+"/shop_marketing_ajax_common.php",
                data : {
                    "action" : "update_firebase_token"
                    , "token" : currentToken
                    , "mb_id" : "<?=$member['mb_id']?>"
                },
                async: false,
                success : function (data){
                    //alert('data : ' + data +  ' / firebase token update : ' + currentToken + ' / mb_id : ' + "<?//=$member['mb_id']?>//");
                }
            });
            <?php } ?>
            Topic(currentToken);
            console.log(currentToken);
        } else {
            // Show permission request.
            console.log('No registration token available. Request permission to generate one.');
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
    });

    var fcm_server_key = 'AAAAizwEJnA:APA91bE6bVl5OW9-Y9nmFbvDqF8oi5eryWav6A0NvPgOv5qMxMkPzHM97AzlWGBTz5lLKEjMolXQMJiHbMvX1WJjD0hDpKtXpWziKcquvDJd4VdnzSumeY3CURT32VzBASy8ARxk_tQd';
    function subscribeTokenToTopic(token, topic) {
        fetch('https://iid.googleapis.com/iid/v1/'+token+'/rel/topics/'+topic, {
            method: 'POST',
            headers: new Headers({
                'Authorization': 'key='+fcm_server_key
            })
        }).then(response => {
            if (response.status < 200 || response.status >= 400) {
                throw 'Error subscribing to topic: '+response.status + ' - ' + response.text();
            }
            console.log('Subscribed to "'+topic+'"');
        }).catch(error => {
            console.error(error);
        })
    }

    function Topic(Token){
        subscribeTokenToTopic(Token, "7detox");
    }

    // messaging.onMessage((payload) => {
    //
    //     if (navigator.serviceWorker) {
    //         console.log("dfd");
    //         navigator.serviceWorker.register("/firebase-messaging-sw.js?ver=41",{scope:"/firebase-cloud-messaging-push-scope"});
    //
    //         // navigator.serviceWorker.addEventListener('message', event => {
    //         //     // event is a MessageEvent object
    //         //     console.log(`The service worker sent me a message: ${event.data}`);
    //         // });
    //
    //         navigator.serviceWorker.ready.then( registration => {
    //             console.log("tete");
    //             registration.active.postMessage("Hi service worker");
    //         });
    //
    //     }
    // });


    function isNewNotificationSupported() {
        if (!window.Notification || !Notification.requestPermission)
            return false;
        if (Notification.permission == 'granted')
            throw new Error('You must only call this *before* calling Notification.requestPermission(), otherwise this feature detect would bug the user with an actual notification!');
        try {
            new Notification('');
        } catch (e) {
            if (e.name == 'TypeError')
                return false;
        }
        return true;
    }

    function appendMessage(payload) {
        alert("msg 도착");
    }


</script>
<header id="hd">
    <?php if ((!$bo_table || $w == 's' ) && defined('_INDEX_')) { ?><h1><?php echo $config['cf_title'] ?></h1><?php } ?>

    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
    } ?>

    <section id="hd_sec">
        <div id="messages"></div>
    	<div id="hd_wr">
        	<div id="logo"><a href="<?php echo G5_SHOP_URL; ?>/"><img src="<?php echo G5_THEME_IMG_URL ?>/logo.png?v=1" alt="<?php echo $config['cf_title']; ?> 메인"></a></div>
	        <div id="hd_btn">
	            <button type="button" id="btn_hdcate"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">분류</span></button>
	            <button type="button" id="user_btn"><i class="fas fa-user"></i><span class="sound_only">사용자메뉴</span></button>
		        <button type="button" id="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>

			    <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">
				    <aside id="hd_sch">
				        <div class="sch_inner">
				            <h2>상품 검색</h2>
				            <label for="sch_str" class="sound_only">상품명<strong class="sound_only"> 필수</strong></label>
				            <input type="text" name="q" value="<?php echo stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required placeholder="검색어를 입력해주세요">
				            <button type="submit" value="검색" class="sch_submit"><i class="fa fa-search" aria-hidden="true"></i></button>
				        </div>
				        <button type="button" class="sch_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">닫기</span></button>
				    </aside>
			    </form>
			    <script>
			    function search_submit(f) {
			        if (f.q.value.length < 2) {
			            alert("검색어는 두글자 이상 입력하십시오.");
			            f.q.select();
			            f.q.focus();
			            return false;
			        }
			
			        return true;
			    }
			    </script>
			    <div class="hd_div" id="user_menu">
		            <?php echo outlogin('theme/shop_basic'); // 외부 로그인 ?>
		        </div>
		        
		        <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="sp_cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="sound_only">장바구니</span><span class="cart-count"><?php echo get_boxcart_datas_count(); ?></span></a>
			</div>
	    </div>
    </section>

    <?php include_once(G5_THEME_MSHOP_PATH.'/category.php'); // 분류 ?>

    <script>
    $( document ).ready( function() {
        var jbOffset = $( '#hd_sec' ).offset();
        $( window ).scroll( function() {
            if ( $( document ).scrollTop() > jbOffset.top ) {
                $( '#hd_sec' ).addClass( 'fixed' );
            }
            else {
                $( '#hd_sec' ).removeClass( 'fixed' );
            }
        });
    });
	
	$("#user_btn").on("click", function() {
        $("#user_menu").show();
    });
    
    $("#btn_hdcate").on("click", function() {
        $("#category").show();
    });

    $("#sch_btn").on("click", function() {
        $("#hd_sch").show();
    });
    
    $(".sch_close").on("click", function() {
        $("#hd_sch").hide();
    });
    
    $(".cate_bg").on("click", function() {
        $(".menu").hide();
    });
    
    $(".menu_close").on("click", function() {
        $(".menu").hide();
    });
    
    $(".user_close").on("click", function() {
        $("#user_menu").hide();
    });
    
   </script>
</header>

<div id="container">
    <?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><h1 id="container_title"><?php echo $g5['title'] ?></h1><?php } ?>
