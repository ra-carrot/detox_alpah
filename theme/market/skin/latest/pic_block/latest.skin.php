<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 210;
$thumb_height = 150;
?>

<style type="text/css">
    .pic_lt .lat_title{
        text-align: center;
        color: #7DB262;
        font-size: 33px;
    }
    .pic_lt .lat_title a{
        color: #7DB262;
    }
    .img_top_box{
        background-color: #7DB262;
        height: 100px;
        color: white;
        margin-bottom: -5px;
        overflow: hidden;
    }
    .img_top_box_title{
        padding-left: 27px;
        padding-top: 27px;
        padding-right: 27px;
        font-weight: bold;
        white-space: nowrap
    }
    .img_top_box_notice{
        padding-top: 5px;
        padding-left: 27px;
        padding-right: 27px;
    }
</style>

<div class="pic_lt" style="background-color: #F4F4F4; padding-bottom: 50px;">
    <h2 class="lat_title" style="margin-bottom: 100px; margin-top: 100px;">
        <a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>
    <ul style="margin-left: 15%; margin-right: 15%;">
    <?php
    for ($i=0; $i<count($list); $i++) {
    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);

    if($thumb['src']) {
        $img = $thumb['src'];
    } else {
        $img = G5_IMG_URL.'/no_img.png';
        $thumb['alt'] = '이미지가 없습니다.';
    }
    $img_content = '<img src="'.$img.'" alt="'.$thumb['alt'].'" style="height : 360px;">';
    ?>
        <li>
            <div class="img_top_box">
                <h2 class="img_top_box_title"><?=$list[$i]['subject']?></h2>
                <h2 class="img_top_box_notice"><?=$list[$i]['wr_content']?></h2>
            </div>
            <a href="<?php echo $list[$i]['href'] ?>" class="lt_img"><?php echo $img_content; ?></a>
            <?php
            if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i><span class=\"sound_only\">비밀글</span> ";

            echo "<a href=\"".$list[$i]['href']."\"> ";
            echo "</a>";

            ?>

            <div class="lt_info">
<!--				<span class="lt_nick">--><?php //echo $list[$i]['name'] ?><!--</span>-->
<!--            	<span class="lt_date">--><?php //echo $list[$i]['datetime2'] ?><!--</span>              -->
            </div>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
    <div style="text-align: center; margin-top: 30px;">
        <div style="height: 50px; width: 210px; display: inline-block; border: 1px solid #7DB262; background-color: white">
            <a href="<?php echo get_pretty_url($bo_table); ?>"><h2 style="height: 50px; line-height: 50px; color: #7DB262">더보기</h2></a>
        </div>
    </div>
</div>
