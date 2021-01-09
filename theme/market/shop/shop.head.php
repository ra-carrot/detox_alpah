<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if(G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
    return;
}

function get_mshop_category($ca_id, $len)
{
    global $g5;

    $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']}
                where ca_use = '1' ";
    if($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}

$mshop_categories = get_shop_category_array(true);

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');

add_javascript('<script src="'.G5_JS_URL.'/jquery.bxslider.js"></script>', 10);
add_javascript('<script src="'.G5_THEME_JS_URL.'/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_JS_URL.'/owl.carousel.css">', 0);
add_stylesheet('<link rel="apple-touch-icon" href="'.G5_THEME_IMG_URL.'/logo.png?v=5">', 0);
add_stylesheet('<link rel="apple-touch-icon" sizes="152x152" href="'.G5_THEME_IMG_URL.'/logo.png?v=5">', 0);
add_stylesheet('<link rel="apple-touch-icon" sizes="167x167" href="'.G5_THEME_IMG_URL.'/logo.png?v=5">', 0);
add_stylesheet('<link rel="apple-touch-icon" sizes="180x180" href="'.G5_IMG_URL.'/pwa-splash.png">', 0);
//add_stylesheet('<link rel="manifest" href="../../../manifest.json">', 0);

?>
<script>
    /*if ("serviceWorker" in navigator) {
        window.addEventListener("load", () => {
            navigator.serviceWorker.register("https://7detox.co.kr/detox/firebase-messaging-sw2.js")
                .then(() => console.info('service worker registered'))
                .catch(error => {
                    console.log('ServiceWorker registration failed: ', error)
                });
        });
    }*/

    // if ("serviceWorker" in navigator) {
    //     window.addEventListener("load", () => {
    //         navigator.serviceWorker.register("https://7detox.co.kr/detox/firebase-messaging-sw2.js",{scope:"firebase-cloud-messaging-push-scope"})
    //             .then(() => console.info('service worker registered'))
    //             .catch(error => {
    //                 console.log('ServiceWorker registration failed: ', error)
    //             });
    //     });
    // }
    // caches.keys().then(function(names) {
    //     for (let name of names)
    //         caches.delete(name);
    // });

</script>
<style>
    .gnb_shortcut > li > a{
        color: white;
    }

    .menu-trigger,
    .menu-trigger span {
        display: inline-block;
        transition: all .4s;
        box-sizing: border-box;
    }

    .menu-trigger {
        left: 0px;
        position: absolute;
        width: 30px;
        height: 20px;
        margin-top: 14px;
    }

    .menu-trigger span {
        position: absolute;
        left: 0;
        width: 100%;
        height: 4px;
        background-color: #fff;
        border-radius: 4px;
    }

    .menu-trigger span:nth-of-type(1) {
        top: 0;
    }

    .menu-trigger span:nth-of-type(2) {
        top: 8px;
    }

    .menu-trigger span:nth-of-type(3) {
        bottom: 0;
    }

    .main_tree_menu{
        width: 360px;
        height: 100%;
        background-color: yellow;
        z-index: 10000;
        position: relative;
        top: 50px;
        display: inline-block;
        left: -210px;
    }

    .login_container{
        width: 100%;
        height: 130px;
        background-color: #7DB262;
    }
    .tree_exit_container{
        width: 100%;
        height: 30px;
        background-color: #7DB262;
    }

    .menu_container{
        /*height: 355px;*/
        background-color: white;
        opacity: 0.8;
    }

    .left_menu_container{
        width: 50%;
        /*background-color: yellow;*/
        height: 100%;
        display: inline-block;
        float: left;
    }
    .right_menu_container{
        width: 50%;
        /*background-color: #0f75ac;*/
        height: 100%;
        display: inline-block;
        float: right;
    }

    .left_menu_list{
        padding-top: 15px;
        text-align: left;
        padding-left: 30px;
        /*margin-left: 30px;*/
        /*border-bottom: 1px solid #7DB262;*/

        line-height: 31px;

        font-size: 15px;
        font-weight: bold;
    }
    .detox_color{
        color: #7DB262;
    }

    .menu_line{
        background-color: #7DB262;
        height: 2px;
        margin-left: 30px;
        margin-right: 30px;
    }

    .login_text{
        line-height: 110px;
        color: white;
        font-size: 17px;
        font-weight: bold;
    }
    .exit_btn{
        float: right;
        color: white;
        font-size: 20px;
        padding-right: 20px;
        padding-top: 10px;
    }

    .inline_block{
        display: inline-block;
    }

    .user_info_container{
        width: 100%;
        position: relative;
        top: 18px;
        float: left;
        padding-left: 20px;
        padding-right: 20px
    }
    .box {
        width: 64px;
        height: 64px;
        border-radius: 70%;
        overflow: hidden;
        display: inline-block;
    }
    .profile {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background-color: white;
    }

    .user_info_ul{
        display: inline-block;
        margin-left: 15px;
        color: white;
        font-size: 15px;
        font-weight: bold;
        overflow: hidden;
        line-height: 25px;
    }

</style>
<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
	} ?>
    
    <div id="hd_wrapper" style="height: 55px">
    	<div id="hd_wr" style="text-align: center">
	        <div id="logo" style="display: inline-block; float: initial">
                <a href="<?php echo G5_SHOP_URL; ?>/"><img src="<?php echo G5_THEME_IMG_URL ?>/logo.png?v=5" alt="<?php echo $config['cf_title']; ?>"></a></div>
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
        </div>
    </div>
    <div style="width: 100%; background-color: #7DB262">
    <nav id="gnb" class="font">
<!--    	<button type="button" id="menu_open"><i class="fa fa-bars" aria-hidden="true"></i> 전체상품</button>-->
        <a class="menu-trigger" href="#">
            <span></span>
            <span></span>
            <span></span>
        </a>

        <div class="main_tree_menu">
            <div class="tree_exit_container">
                <i class="fa fa-times exit_btn" aria-hidden="true"></i>
            </div>
            <div class="login_container">
                <?php if ($is_member) {?>
                    <div class="user_info_container">
                        <div class="box" style="background: #BDBDBD;">
                            <img class="profile">
                        </div>
                        <ul class="user_info_ul" style="text-align: left; width: 160px">
                            <li><?=$member["mb_name"]?></li>
                            <li><?=$member["mb_email"]?></li>
                        </ul>
                        <ul class="user_info_ul">
                            <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php" style="color: white">회원정보</a></li>
                            <li><a href="<?php echo G5_BBS_URL ?>/logout.php" style="color: white">로그아웃</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a href="<?php echo G5_BBS_URL ?>/login.php"><span class="login_text">로그인 해주세요.</span></a>
                <?php } ?>
            </div>
            <div class="menu_container" style="height: 190px">
                <div class="left_menu_container">
                    <ul class="left_menu_list">
                        <li class="detox_color">7디톡스</li>
                        <li class="detox_color">게시판</li>
                        <li>자유게시판</li>
                        <li>후기</li>
                        <li>노하우</li>
                    </ul>
                </div>
                <div class="right_menu_container">
                    <ul class="left_menu_list">
                        <li class="detox_color">제품소개</li>
                        <li class="detox_color">참여하기</li>
                        <li>알람설정</li>
                        <li>홈트레이닝</li>
                        <li>해독일지</li>
                    </ul>
                </div>
            </div>
            <div class="menu_container" style="height: 5px;">
                <div class="menu_line" ></div>
            </div>
            <div class="menu_container" style="height: 155px">
                <div class="left_menu_container">
                    <ul class="left_menu_list">
                        <li>회사소개</li>
                        <li>이용약관</li>
                        <li>청소년 보호정책</li>
                    </ul>
                </div>
                <div class="right_menu_container">
                    <ul class="left_menu_list">
                        <li>고객센터</li>
                        <li>개인정보 처리방침</li>
                        <li>제휴문의</li>
                    </ul>
                </div>
            </div>
        </div>

    	<ul class="gnb_shortcut" style="float: right">
    		<li><a href="<?php echo G5_SHOP_URL; ?>/">7디톡스</a></li>
            <li><a href="#">참여하기</a></li>
            <li><a href="#">제품소개</a></li>
            <li><a href="#">게시판</a></li>
<!--            <li><a href="--><?php //echo shop_type_url(5); ?><!--">상품</a></li>-->
            <?php
            $i = 0;
            foreach($mshop_categories as $cate1){
                if( empty($cate1) ) continue;

                $mshop_ca_row1 = $cate1['text'];
                ?>
                <li>
                    <a href="<?php echo $mshop_ca_row1['url']; ?>" class="cate_li_1_a"><?php echo "상품" ?></a>
                </li>
                <?php
                $i++;
            }   // end for
            ?>

            <li>
            <?php if ($is_member) { ?>
                <a href="<?php echo G5_BBS_URL ?>/logout.php" class="logout_btn">로그아웃</a>
            <?php } else { ?>
                <a href="<?php echo G5_BBS_URL ?>/login.php" class="logout_btn">로그인</a>
            <?php } ?>

            </li>

        </ul>
<!--    	<ul class="tnb_right">-->
<!--    		<li><a href="--><?php //echo G5_BBS_URL; ?><!--/faq.php">FAQ</a></li>-->
<!--			<li><a href="--><?php //echo G5_BBS_URL; ?><!--/qalist.php">1:1문의</a></li>-->
<!--    		<li><a href="--><?php //echo G5_SHOP_URL; ?><!--/personalpay.php">개인결제</a></li>-->
<!--    		<li><a href="--><?php //echo G5_SHOP_URL; ?><!--/itemuselist.php">사용후기</a></li>-->
<!--    		<li><a href="--><?php //echo G5_SHOP_URL; ?><!--/couponzone.php">쿠폰존</a></li>-->
<!--		</ul>-->
<!--    	--><?php //include_once(G5_THEME_SHOP_PATH.'/category.php'); // 분류 ?>
	</nav>
    </div>
</div>

<!-- 전체 콘텐츠 시작 { -->
<div id="wrapper">
    <!-- 콘텐츠 시작 { -->
    <div id="container">
		<?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><div id="wrapper_title"><span class="wt"><?php echo $g5['title'] ?></span></div><?php } ?>
        <!-- 글자크기 조정 display:none 되어 있음 시작 { -->
        <div id="text_size">
            <button class="no_text_resize" onclick="font_resize('container', 'decrease');">작게</button>
            <button class="no_text_resize" onclick="font_default('container');">기본</button>
            <button class="no_text_resize" onclick="font_resize('container', 'increase');">크게</button>
        </div>
        <!-- } 글자크기 조정 display:none 되어 있음 끝 -->
        
		<div id="container_inner" class="container">