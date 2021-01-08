<?php
include_once('./_common.php');
//error_reporting(E_ALL);
//ini_set("display_errors",1);

$g5['title'] = '알림 전송';
include_once('./_head.php');
?>
    <style type="text/css">
        #container_inner.container {margin:0 auto;width: 500px;padding:0 0 30px 60px}
        #wrapper_title .wt {
            width: 1200px;
            margin: 0 auto;
            display: block;
            width: 500px;
            padding-left: 60px;
        }
        tbody > th , td{
            text-align: center;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css"/>
    <div id="smb_my">
        <div>
            <button onclick="push_all();">전체 알림 전송</button>
        </div>
        <div>
            <table class="display cell-border compact" id="dataTable">
                <thead>
                <tr>
                    <th scope="cols"></th>
                    <th>ID</th>
                    <th>이름</th>
                    <th>알림전송</th>
                </tr>
                </thead>
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
            var action = "firebase";
            var table = $('#dataTable').DataTable({
                "ajax": "./shop_marketing_dataTable.php?action=" + action
                ,"paging " : false
                , "info" : false
                , "orderable" : false
                ,"bPaginate" : false
                ,"columnDefs": [
                    {
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }
                    ,
                    {
                        'targets': [ 3],
                        'render': function (data, type, full, meta){
                            var token = full[4];
                            return '<button class="push_send" data1="'+token+'">전송</button>';
                        }
                    }
                ]
                ,"order": [[ 1, 'asc' ]]
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

            $(document).on("click",".push_send",function(){
                var token = $(this).attr("data1");
                var body_text = prompt("보낼 내용을 적어주세요");
                $.ajax({
                    url : g5_shop_url+"/shop_marketing_ajax_common.php",
                    data : {
                        "action" : "fb_push"
                        , "token" : token
                        , "body" : body_text
                    },
                    async: false,
                    success : function (data){
                        console.log('firebase token update');
                    }
                });

            })
        });

        function push_all(){
            var body_text = prompt("보낼 내용을 적어주세요");
            if(body_text) {
            $.ajax({
                url : g5_shop_url+"/shop_marketing_ajax_common.php",
                data : {
                    "action" : "fb_push_all"
                    , "body" : body_text
                },
                async: false,
                success : function (data){
                    console.log('firebase token update');
                }
            });
            }
        }
    </script>

<?php
include_once("./_tail.php");
?>