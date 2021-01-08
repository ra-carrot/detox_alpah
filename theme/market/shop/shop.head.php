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
<!--	        <fieldset id="hd_sch">-->
<!--	            <legend>쇼핑몰 전체검색</legend>-->
<!--	            <form name="frmsearch1" action="--><?php //echo G5_SHOP_URL; ?><!--/search.php" onsubmit="return search_submit(this);">-->
<!--	            <label for="sch_stx" class="sound_only">검색어 필수</label>-->
<!--	                <div class="sch_ipt">-->
<!--	            		<label for="sch_str" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>-->
<!--	            		<input type="text" name="q" class="sch_stx" value="--><?php //echo stripslashes(get_text(get_search_string($q))); ?><!--" id="sch_str" required placeholder="검색어를 입력해주세요">-->
<!--	            		<button type="submit" id="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>-->
<!--	            	</div>-->
<!--	            </form>-->
<!--	        </fieldset>-->
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
<!--            <ul id="hd_qnb">-->
<!--	        	<li>-->
<!--	        		--><?php //if ($is_member) { ?>
<!--	        		<button class="login_btn">나의정보</button>-->
<!--	        		<a href="--><?php //echo G5_BBS_URL ?><!--/logout.php" class="logout_btn">로그아웃</a>-->
<!--	        		--><?php //if ($is_admin) {  ?>
<!--		            <a href="--><?php //echo G5_ADMIN_URL; ?><!--/shop_admin" class="admin_btn">관리자</a>-->
<!--		            --><?php //}  ?>
<!--	        		--><?php //} else { ?>
<!--	        		<button class="login_btn">로그인</button>-->
<!--	        		<a href="--><?php //echo G5_BBS_URL ?><!--/register.php" class="logout_btn join_btn">회원가입-->
<!--	        			</a>-->
<!--	        		--><?php //} ?>
<!--	        		<div id="member_menu">-->
<!--						<div class="member_div">-->
<!--							--><?php //echo outlogin('theme/basic'); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 ?>
<!--							--><?php //echo display_banner('왼쪽'); //쇼핑몰 배너 시작 ?>
<!--						</div>-->
<!--						<div class="bg"></div>-->
<!--					</div>-->
<!--					<script>-->
<!--				    $(function(){-->
<!--				        $(".login_btn").click(function(){-->
<!--				            $("#member_menu").toggle();-->
<!--				        });-->
<!--				        $(".login_cls_btn").click(function(){-->
<!--				            $("#member_menu").hide();-->
<!--				        });-->
<!--				    });-->
<!--					</script>-->
<!---->
<!--	        	</li>-->
<!--	        </ul>-->
        </div>
    </div>
    <div style="width: 100%; background-color: #7DB262">
    <nav id="gnb" class="font">
<!--    	<button type="button" id="menu_open"><i class="fa fa-bars" aria-hidden="true"></i> 전체상품</button>-->
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