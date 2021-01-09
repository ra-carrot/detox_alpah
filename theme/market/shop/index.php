<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

define("_INDEX_", TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>

<section id="idx_banner" style="max-height: 700px;">
	<?php echo display_banner('메인', 'mainbanner.10.skin.php'); // 큰 배너 1 ?>
</section>
<section class="main2" style="background: url('<?=G5_THEME_IMG_URL."/main2_bg_img.png?v=25"?>'); background-size: 1920px 150px; height: 150px;" >
<!--    // 큰 배너 아래 이미지-->
</section>

<h2 class="sound_only">최신글</h2>

<div class="latest_wr" >
    <!-- 사진 최신글2 { -->
    <?php
    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
    echo latest('theme/pic_block', 'review', 4, 23);		// 최소설치시 자동생성되는 갤러리게시판
    ?>
    <!-- } 사진 최신글2 끝 -->
</div>

<section class="main4" style="background-color: white">
	<?php if($default['de_type1_list_use']) { ?>
	<!-- 히트상품 시작 { -->
	<h2><a href="<?php echo shop_type_url('4'); ?>" class="main_tit" style="color:#7DB262; padding-bottom: 60px;">제품소개</a></h2>
    <?php
    $list = new item_list();
    $list->set_type(1);
    $list->set_view('it_img', true);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_basic', true);
    $list->set_view('it_cust_price', false);
    $list->set_view('it_price', false);
    $list->set_view('it_icon', false);
    $list->set_view('sns', false);
    echo $list->run();
    ?>
	<!-- } 히트상품 끝 -->
	<?php } ?>
</section>

<script>
$("#container_inner").removeClass("container").addClass("idx-container");
</script>

<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
?>