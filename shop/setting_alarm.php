<?php
include_once('./_common.php');
//error_reporting(E_ALL);
//ini_set("display_errors",1);
if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/setting_alarm.php"));

//if (G5_IS_MOBILE) {
//    include_once(G5_MSHOP_PATH.'/mypage.php');
//    return;
//}

$member_level = $member["mb_level"];

$g5['title'] = '알림 설정';
include_once('./_head.php');
?>
<style type="text/css">

    #container_inner.container {margin:0 auto;width: 800px;padding:0 0 30px 60px}
    #wrapper_title .wt {
        width: 1200px;
        margin: 0 auto;
        display: block;
        width: 800px;
        padding-left: 60px;
    }

    tbody > th , td{
        text-align: right;
    }

    td.point_target{
        background-color: #7DB262;
        color: white;
        font-weight: bolder;
    }
    .sit_opt_del {
        border: 0;
        font-size: 15px;
    }
    #dataForm button {
        width: 30px;
        background: #fff;
        color: #666;
        font-size: 0.92em;
    }

    select option{
        padding:3px;
    }
    .sit_option{
        border: 1px solid #eee;
        padding: 10px;
        margin-bottom: 15px;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css"/>
<div id="smb_my">
    <div>
        <div class="sit_option" >
            <p style="padding-bottom: 10px;text-decoration: underline;">알람 추가</p>
        <select name="alarmDay" id="alarmDay" style="width:200px; display: inline-block;">
            <option value="">알람 일을 선택해주세요.</option>
            <option value="all">매일</option>
            <option value="weekday">월 ~ 금</option>
            <option value="mon">월요일</option>
            <option value="tue">화요일</option>
            <option value="wed">수요일</option>
            <option value="thu">목요일</option>
            <option value="fri">금요일</option>
            <option value="sat">토요일</option>
            <option value="sun">일요일</option>
            <option value="weekend">토 ~ 일</option>
        </select>
        <select name="alarmHour" id="alarmHour" style="width:200px; display: inline-block;">
            <option value="">시간을 선택해주세요.</option>
            <?php
                for($i=0;$i<25;$i++) {
                    if($i<10){
                        $str = "0".$i;
                    } else {
                        $str = $i;
                    }
            ?>
            <option value="<?=$str?>"><?=$str?></option>
            <?php }?>
        </select>
        <select name="alarmMin" id="alarmMin" style="width:200px; display: inline-block;">
            <option value="">분을 선택해주세요.</option>
            <option value="00">00</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
        </select>
        <input type="text" id="alarmTitle" class="frm_input" name="alarmTitle" placeholder="알람 제목을 입력해주세요." style="width:250px;"/>
        <button id="addAlarmBtn" onclick="addAlarm();">알람추가</button>
        </div>
        <form id="dataForm">
        <table class="display cell-border compact " id="dataTable">
            <colgroup>
                <col width="5%;">
                <col width="10%;">
                <col width="10%;">
                <col width="10%;">
                <col width="">
                <col width="10%;">
                <col width="8%">
            </colgroup>
            <thead>
                <tr>
                    <th scope="cols">No</th>
                    <th>알람일</th>
                    <th>시간</th>
                    <th>분</th>
                    <th>제목</th>
                    <th>On/Off</th>
                    <th>삭제</th>
                </tr>
            </thead>
            <tbody>
            <style>

            </style>
            <?php
            $sql = "select *
                        from    detox_member_alarm
                        where   mb_id = '{$member['mb_id']}' order by hours,min";
            $result = sql_query($sql);
            for ($i=0; $row=sql_fetch_array($result); $i++) {
                ?>
                <tr>
                    <td style="text-align: center;"><?php echo $i+1?>
                        <input type="hidden" name="alarmIndex" value="<?php echo $row['alarmIndex']?>">
                    </td>
                    <td><?php
                        $daysStr = '';
                        if($row['days'] == 'all') {
                            $daysStr = '매일';
                        } else if ($row['days'] == 'weekday'){
                            $daysStr = '월 ~ 금';
                        } else if ($row['days'] == 'weekend'){
                            $daysStr = '토 ~ 일';
                        } else if ($row['days'] == 'mon'){
                            $daysStr = '월요일';
                        } else if ($row['days'] == 'tue'){
                            $daysStr = '화요일';
                        } else if ($row['days'] == 'wed'){
                            $daysStr = '수요일';
                        } else if ($row['days'] == 'thu'){
                            $daysStr = '목요일';
                        } else if ($row['days'] == 'fri'){
                            $daysStr = '금요일';
                        } else if ($row['days'] == 'sat'){
                            $daysStr = '토요일';
                        } else if ($row['days'] == 'sun'){
                            $daysStr = '일요일';
                        }
                        echo $daysStr
                        ?>
                    </td>
                    <td><?php echo $row['hours']?></td>
                    <td><?php echo $row['min']?></td>
                    <td style="text-align: left;"><?php echo $row['title']?></td>
                    <td style="text-align: center;"><input type="checkbox" name="useYN" value="Y" <?php echo ($row['isUse'] == 'Y'?'checked':'')?> onclick="updateAlarm(this);"></td>
                    <td style="text-align: center;">
                        <?php /*if($row['isDefault'] == 'N') { */?>
                        <button type="button" class="sit_opt_del" onclick="delAlarm('<?php echo $row["alarmIndex"]?>');"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">삭제</span></button>
                        <?php /*} */?>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        </form>
    </div>


</div>



<!--<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script>
    // Korean

    $(document).ready(function() {

        var table = $('#dataTable').DataTable({
            "paging " : false
            , "info" : false
            , "orderable" : false
            ,"bPaginate" : false
            ,"searching":false
            ,"ordering": false

        });

    });

    function addAlarm(){
        var day = $("#alarmDay option:selected").val();
        var hour = $("#alarmHour option:selected").val();
        var min = $("#alarmMin option:selected").val();
        var title = $("#alarmTitle").val();

        if(day == '' || hour== '' || min == '') {
            alert('알람일, 시간, 분을 모두 선택해주세요.');
            return ;
        }
        var data = {
            "action":"add_alarm",
            "day": day,
            "hour":hour,
            "min":min,
            "title":title
        };

        $.ajax({
            url : g5_shop_url+"/shop_marketing_ajax_common.php",
            data : data,
            async: false,
            success : function (data){
                console.log(data);
                location.reload();
                //$('#dataTable').DataTable().ajax.reload();
            }
        });
    };

    function delAlarm(index){


        var rs = confirm("알람을 삭제하시겠습니까?");
        if(rs) {
        var data = {
            "action":"remove_alarm",
            "alarmIndex": index
        };

        $.ajax({
            url : g5_shop_url+"/shop_marketing_ajax_common.php",
            data : data,
            async: false,
            success : function (data){
                console.log(data);
                location.reload();
                //$('#dataTable').DataTable().ajax.reload();
            }
        });
        }
    };



    function updateAlarm(th){
        console.log($(th).parent().parent().find("[name=alarmIndex]").val());
        console.log($(th).prop('checked')?"Y":"N");
        var index = $(th).parent().parent().find("[name=alarmIndex]").val();
        var isUse = $(th).prop('checked')?"Y":"N";
        var data = {
            "alarmIndex" : index,
            "use" : isUse,
            "action":"update_alarm"
        }


        $.ajax({
            url : g5_shop_url+"/shop_marketing_ajax_common.php",
            data : data,
            async: false,
            success : function (data){
                console.log(data);
                //$('#dataTable').DataTable().ajax.reload();
            }
        });
    };

</script>

<?php
include_once("./_tail.php");
?>