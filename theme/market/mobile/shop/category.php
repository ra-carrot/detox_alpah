<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

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
?>
<style type="text/css">
    .menu_wr_top{
        height: 150px;

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

        font-size: 12px;
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
        cursor: pointer;
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

<div id="category" class="menu">
    <div class="menu_wr">
    	<div class="menu_wr_top" style="background-color: #7DB262;">
    		<?php echo outlogin('theme/gnb'); // 외부 로그인 ?>
			<button type="button" class="menu_close" style="background-color: #7DB262;"><i style="color: white" class="fa fa-times" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button>
		</div>


        <div class="menu_container" style="height: 190px">
            <div class="left_menu_container">
                <ul class="left_menu_list">
                    <li class="detox_color"><a style="color: #7DB262" href="<?php echo G5_SHOP_URL; ?>/">7디톡스</a></li>
                    <li class="detox_color"><a style="color: #7DB262" href="<?php echo get_pretty_url("free")?>">게시판</a></li>
                    <li><a href="<?php echo get_pretty_url("free")?>">자유게시판</a></li>
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
                    <li><a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a></li>
                    <li><a href="<?php echo get_pretty_url('content', 'provision'); ?>">이용약관</a></li>
                    <li>청소년 보호정책</li>
                </ul>
            </div>
            <div class="right_menu_container">
                <ul class="left_menu_list">
                    <li>고객센터</li>
                    <li><a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a></li>
                    <li>제휴문의</li>
                </ul>
            </div>
        </div>
	</div>
</div>
<script>
jQuery(function ($){

    $("button.sub_ct_toggle").on("click", function() {
        var $this = $(this);
        $sub_ul = $(this).closest("li").children("ul.sub_cate");

        if($sub_ul.size() > 0) {
            var txt = $this.text();

            if($sub_ul.is(":visible")) {
                txt = txt.replace(/닫기$/, "열기");
                $this
                    .removeClass("ct_cl")
                    .text(txt);
            } else {
                txt = txt.replace(/열기$/, "닫기");
                $this
                    .addClass("ct_cl")
                    .text(txt);
            }

            $sub_ul.toggle();
        }
    });
});
</script>
