<?php
include_once('./_common.php');

define("_INDEX_", TRUE);

include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
?>

<style type="text/css">
    .m_main_bar{
        width: 100%;
        background-color: #7DB262;
        height: 50px;
        text-align: center;
        line-height: 50px;
        color: white;
    }

</style>

<script src="<?php echo G5_JS_URL; ?>/swipe.js"></script>
<script src="<?php echo G5_JS_URL; ?>/shop.mobile.main.js"></script>

<?php echo display_banner('메인', 'mainbanner.10.skin.php'); ?>

<?php echo display_banner('왼쪽', 'boxbanner.skin.php'); ?>


<div class="idx_visual">
	<?php include_once(G5_MSHOP_SKIN_PATH.'/main.event.skin.php'); // 이벤트 ?>
</div>

<div class="m_main_bar" style="margin-top: -3px">
    <h1>독소 지수 & 비만도 측정 ></h1>
</div>

<?php
// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
echo latest('theme/main_img', 'review', 4, 23);
?>

<?php if($default['de_mobile_type4_list_use']) { ?>
    <div class="sct_wrap" style="margin-left: 20px">
        <h2 style="text-align: left"><a href="<?php echo shop_type_url('4'); ?>" style="color: #707070">제품 소개</a></h2>
        <div class="sct_slider">
            <?php
            $list = new item_list();
            $list->set_mobile(true);
            $list->set_type(4);
            $list->set_view('it_id', false);
            $list->set_view('it_name', true);
            $list->set_view('it_cust_price', false);
            $list->set_view('it_price', true);
            $list->set_view('it_icon', false);
            $list->set_view('sns', false);
            echo $list->run();
            ?>
        </div>
    </div>
<?php } ?>

<?php include_once(G5_THEME_MSHOP_PATH.'/shop.tail.php'); ?>