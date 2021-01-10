<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>


<!--<div class="sct_wrap">-->
<!--    <ul class="sct sct_30">-->
<!---->
<!--    </ul>-->
<!--</div>-->

<div class="sct_wrap">
    <div class="sct sct_30" style="margin-left: 5px; margin-right: 5px; margin-bottom: 20px;">
        <h2>
            <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table ?>" style="float: left; color: #707070"><?php echo $bo_subject ?></a>
            <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=<?php echo $bo_table ?>" class="more_btn" style="float: right; color: #7DB262; "><span class="sound_only"><?php echo $bo_subject ?></span>더보기 ></a>
        </h2>

    </div>


    <ul class="sct sct_30">
    <?php for ($i=0; $i<count($list); $i++) { ?>
        <li class="sct_li  sct_clear sct_li_0" style="width:50%;">
            <div class="li_wr" style="border: 0px;">
                <div class="sct_img">
                    <a>
                        <img src="https://7detox.co.kr/alpha/detox/shop/img/no_image.gif" width="300" height="300" alt="">
                    </a>
                </div>
                <div class="sct_wrap_ct" style="text-align: left; padding: 0px;">
                    <a href="" class="sct_a" style="text-align: left">
                        <?php
                        if ($list[$i]['is_notice'])
                            echo "<span>".$list[$i]['subject']."</span>";
                        else
                            echo $list[$i]['subject'];
                        ?>
                    </a>
                </div>
            </div>
        </li>
    <?php } ?>
    </ul>

</div>
