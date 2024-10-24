<?php 
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION['username'] !== 'leo') {
    header("location: index.html");
    exit;
} 
?>
<html>
    <head>
        <title>IP刪除紀錄</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href='./css/datatables.min.css' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
        <script src="./js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="./js/bootstrap.min.js"></script>
        <script src="./js/datatables.min.js"></script>
        <script src="./js/jquery.dataTables-1.11.3.min.js"></script>
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
            .float-button {
                position: fixed;
                right: 1%;
                bottom: 5%;
                z-index: 999;
            }
        </style>
    </head>
    <body>
    <div class='container' style='margin-top:60px;' >
    <h3 style='float:left;'>IP刪除紀錄</h3>
    <input type="button" class='btn btn-sm btn btn-danger float-right' style='margin-bottom:10px;' data-toggle='modal' id='delete_record' value='Delete' >
    <input type="button" value="IP管理" class="btn btn-sm btn btn-outline-success float-right " style='margin-right:10px;' onclick="javascript:location.href='./stream.php'" />
    <!-- Table -->
    <table id='empTable' class='display dataTable'>
        <thead>
        <tr>        
            <th bgcolor="#99BBFF" scope="col">廠商名稱</th>
            <th bgcolor="#99BBFF" scope="col">IP</th>
            <th bgcolor="#99BBFF" scope="col">刪除時間</th>
            <th bgcolor="#99BBFF" scope="col">刪除人員</th>
            <th bgcolor="#99BBFF" scope="col">全選   <input type="checkbox" class='checkall' id='checkall'></th>
        </tr>
        </thead>
    </table>
    </div>

    <script>
    var dataTable;
    $(document).ready(function(){

    // Initialize datatable
    dataTable = $('#empTable').DataTable({
        "lengthMenu": [
                            [10, 30, 50, 100],
                            [10, 30, 50, 100]
                        ],
        language: {
                            'emptyTable': '無資料...',
                            'processing': '處理中...',
                            'loadingRecords': '載入中...',
                            'lengthMenu': '每頁 _MENU_ 筆資料',
                            'zeroRecords': '無搜尋結果',
                            'info': '_START_ 至 _END_ / 共 _TOTAL_ 筆',
                            'infoEmpty': '尚無資料',
                            'infoFiltered': '(從 _MAX_ 筆資料過濾)',
                            'infoPostFix': '',
                            'search': '搜尋字串:',
                            'paginate': {
                                'first': '首頁',
                                'last': '末頁',
                                'next': '下頁',
                                'previous': '前頁'
                            },
                            'aria': {
                                'sortAscending': ': 升冪',
                                'sortDescending': ': 降冪'
                            }
                },
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'deletestreamtack.php',
            'data': function(data){
            
            data.request = 1;

            }
        },
        'columns': [    
            { data: 'name' },
            { data: 'ename' },
            { data: 'deleted_time'},
            { data: 'deleted_id'}, 
            { data: 'action' },    
        ],
        'columnDefs': [ {
        'targets': [4], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
        }]
    });

    // Check all 
    $('#checkall').click(function(){
        if($(this).is(':checked')){
            $('.delete_check').prop('checked', true);
        }else{
            $('.delete_check').prop('checked', false);
        }
    });

    // Delete record
    $('#delete_record').click(function(){

        var deleteids_arr = [];
        // Read all checked checkboxes
        $("input:checkbox[class=delete_check]:checked").each(function () {
            deleteids_arr.push($(this).val());
        });

        // Check checkbox checked or not
        if(deleteids_arr.length > 0){

            // Confirm alert
            var confirmdelete = confirm("Do you really want to Delete records?");
            if (confirmdelete == true) {
                $.ajax({
                url: 'deletestreamtack.php',
                type: 'post',
                data: {request: 3,deleteids_arr: deleteids_arr},
                success: function(response){
                    dataTable.ajax.reload();
                }
                });
            } 
        }
    });

    });

    // Checkbox checked
    function checkcheckbox(){

    // Total checkboxes
    var length = $('.delete_check').length;

    // Total checked checkboxes
    var totalchecked = 0;
    $('.delete_check').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });

    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#checkall").prop('checked', true);
    }else{
        $('#checkall').prop('checked', false);
    }
    }
    </script>
    </body>
</html>
