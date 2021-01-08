<?php
include_once('./_common.php');
//error_reporting(E_ALL);
//ini_set("display_errors",1);
if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/calculate_history.php"));

//if (G5_IS_MOBILE) {
//    include_once(G5_MSHOP_PATH.'/mypage.php');
//    return;
//}

$member_level = $member["mb_level"];

$g5['title'] = '정산내역';
include_once('./_head.php');
?>
    <style type="text/css">

        <?php if($member_level == 10){ ?>
        #container_inner.container {margin:0 auto;width: 1750px;padding:0 0 30px 60px}
        #wrapper_title .wt {
            width: 1200px;
            margin: 0 auto;
            display: block;
            width: 1750px;
            padding-left: 60px;
        }

        <?php }else{ ?>
        #container_inner.container {margin:0 auto;width: 1000px;padding:0 0 30px 60px}
        #wrapper_title .wt {
            width: 1200px;
            margin: 0 auto;
            display: block;
            width: 1000px;
            padding-left: 60px;
        }

        <?php } ?>

        tbody > th , td{
            text-align: right;
        }

        td.point_target{
            background-color: #7DB262;
            color: white;
            font-weight: bolder;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css"/>
    <div id="smb_my">
        <div>
            <table class="display cell-border compact" id="dataTable">
                <?php if($member_level == 10){ ?>
                    <thead>
                    <tr>
                        <th scope="cols"></th>
                        <th>상품명</th>
                        <th>결제방식</th>
                        <th>주문자</th>
                        <th>정산여부</th>
                        <th>상품가격</th>
                        <th>상품마케팅비</th>
                        <th>1단계 추천인</th>
                        <th>1단계 마케팅비</th>
                        <th>2단계 추천인</th>
                        <th>2단계 마케팅비</th>
                        <th>3단계 추천인</th>
                        <th>3단계 마케팅비</th>
                        <th>4단계 추천인</th>
                        <th>4단계 마케팅비</th>
                        <th>총합계</th>
                        <th>정산</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th colspan="8" class="text_right">합계 :</th>
                        <th class="text_right">asdfsadf</th>
                        <th class="text_right">합계 :</th>
                        <th class="text_right point_target"></th>
                        <th class="text_right">합계 :</th>
                        <th class="text_right point_target"></th>
                        <th class="text_right">합계 :</th>
                        <th class="text_right point_target"></th>
                        <th class="text_right point_target"></th>
                        <th class="text_right point_target"></th>
                    </tr>
                    </tfoot>
                <?php }else {?>
                    <thead>
                    <tr>
                        <th scope="cols"></th>
                        <th>상품명</th>
                        <th>1단계 마케팅비</th>
                        <th>2단계 마케팅비</th>
                        <th>3단계 마케팅비</th>
                        <th>4단계 마케팅비</th>
                        <th>합계 마케팅비</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th colspan="2" class="text_right">합계 :</th>
                        <th class="text_right"></th>
                        <th class="text_right"></th>
                        <th class="text_right"></th>
                        <th class="text_right"></th>
                        <th class="text_right"></th>
                    </tr>

                    </tfoot>
                <?php } ?>
            </table>
        </div>
    </div>
    <!--<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

    <script>
        // Korean
        var lang_kor = {
            "decimal" : "",
            "emptyTable" : "데이터가 없습니다.",
            "info" : "_START_ - _END_ (총 _TOTAL_ 명)",
            "infoEmpty" : "0명",
            "infoFiltered" : "(전체 _MAX_ 명 중 검색결과)",
            "infoPostFix" : "",
            "thousands" : ",",
            "lengthMenu" : "_MENU_ 개씩 보기",
            "loadingRecords" : "로딩중...",
            "processing" : "처리중...",
            "search" : "검색 : ",
            "zeroRecords" : "검색된 데이터가 없습니다.",
            "paginate" : {
                "first" : "첫 페이지",
                "last" : "마지막 페이지",
                "next" : "다음",
                "previous" : "이전"
            },
            "aria" : {
                "sortAscending" : " :  오름차순 정렬",
                "sortDescending" : " :  내림차순 정렬"
            }
        };

        $(document).ready(function() {

            var mb_level = <?=$member_level?>;
            var action = "";
            if(mb_level == 10){
                action = "admin_list";
                var columnDefs =[
                        {
                            'targets': [ 5,6,8,10,12,14,15],
                            'render': function (data, type, full, meta){
                                return data.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }
                        }
                        ,
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        }
                        ,
                        {
                            'targets': [8,10,12,14,15],
                            'class' : "point_target"
                        }
                        ,
                        {
                            'targets': [16],
                            'render': function (data, type, full, meta){
                                var cal_chk = full[4];
                                var od_id = full[17];
                                if(cal_chk == "N"){
                                    return '<button class="calculator_ajax" data1="'+od_id+'" data2="Y">정산 하기</button>';
                                }else{
                                    return '<button class="calculator_ajax" data1="'+od_id+'" data2="N">정산 취소</button>';
                                }
                            }
                        }
                    ]
                ;
            }else{
                action = "user_list";
                var columnDefs =[
                        {
                            'targets': [ 2,3,4,5,6],
                            'render': function (data, type, full, meta){
                                return data.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }
                        }
                        ,
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        }
                    ]
                ;
            }

            if(mb_level == 10){
                var admin = [8,10,12,14,15];
            }else{
                var admin = [2,3,4,5,6];
            }

            var table = $('#dataTable').DataTable({
                "ajax": "./shop_marketing_dataTable.php?action=" + action
                ,"paging " : false
                , "info" : false
                , "orderable" : false
                ,"bPaginate" : false
                ,"columnDefs": columnDefs
                ,"order": [[ 1, 'asc' ]]
                ,"footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };
                    for(var z=0; z<admin.length; z++){
                        // Total over all pages
                        total = api
                            .column( admin[z] )
                            .data()
                            .reduce( function (a, b) {
                                var sum_val =intVal(a) + intVal(b);
                                sum_val = sum_val + "";
                                return sum_val.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                            }, 0 );

                        // Update footer
                        $( api.column( admin[z] ).footer() ).html(total);
                    }
                    // Total over this page
                    // pageTotal = api
                    //     .column( 5, { page: 'current'} )
                    //     .data()
                    //     .reduce( function (a, b) {
                    //         var sum_val =intVal(a) + intVal(b);
                    //         sum_val = sum_val + "";
                    //         return sum_val.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    //     }, 0 );
                }

                ,language : lang_kor //or lang_eng
                // ,dom: 'Bfrtip'
                // ,buttons: [
                //     {
                //         extend: 'excel'
                //         ,text: '엑셀출력'
                //     }
                // ]
            });
            table.on( 'order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();
        });

        $(document).on('click','.calculator_ajax',function(){
            var od_id = $(this).attr("data1");
            var cal_chk = $(this).attr("data2");
            $.ajax({
                url : g5_shop_url+"/shop_marketing_ajax_common.php",
                data : {
                    "action" : "update_expenses_calculate"
                    , "flag" : cal_chk
                    , "od_id" : od_id
                },
                async: false,
                success : function (data){
                    $('#dataTable').DataTable().ajax.reload();
                }
            });
        });

    </script>

<?php
include_once("./_tail.php");
?>