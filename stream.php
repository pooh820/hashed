<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.html");
    exit;
}
?>
<!doctype html>
<html>

<head>
    <title>IP管理</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' href='./css/datatables.min.css'>
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
    <div class='container' style='margin-top:60px;'>
        <!-- Modal -->
        <div id="updateModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">標籤</label>
                            <input type="text" class="form-control" id="name" placeholder="輸入標籤" required>
                        </div>
                        <div class="form-group">
                            <label for="ename">IP</label>
                            <input type="text" class="form-control" id="ename" placeholder="輸入IP" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="txt_userid" value="0">
                        <button type="button" class="btn btn-success btn-sm" id="btn_save">Save</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="addModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">廠商名稱</label>
                            <input type="text" class="form-control" id="name2" placeholder="輸入標籤" required>
                        </div>
                        <div class="form-group">
                            <label for="ename">IP</label>
                            <input type="text" class="form-control" id="ename2" placeholder="輸入IP" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" id="btn_save2">Save</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="changeModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">修改密碼</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">帳號 : <?php echo $_SESSION['username']; ?></label>
                        </div>
                        <div class="form-group">
                            <label for="password">密碼 : </label>
                            <input type="password" class="form-control" id="password" placeholder="修改密碼" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" id="btn_save3">Save</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <h3 style='float:left;'>IP管理</h3>
        <button class='btn btn-sm btn btn-outline-warning float-right addUser ' style='margin-bottom:10px;' data-toggle='modal' data-target='#addModal'>Add</button>
        <button class='btn btn-sm btn btn-outline-secondary changeUser float-right' style='margin-right:10px;' data-toggle='modal' data-target='#changeModal'>修改密碼</button>
        <?php if ($_SESSION['username'] == 'leo') { ?>
            <input type="button" value="刪除紀錄" class="btn btn-sm btn btn-outline-danger float-right " style='margin-right:10px;' onclick="javascript:location.href='./deletestream.php'" />
            <input type="button" value="使用者管理" class="btn btn-sm btn btn-outline-success float-right " style='margin-right:10px;' onclick="javascript:location.href='./user.php'" />
        <?php  } else {
        } ?>
        <!-- Table -->
        <table id='userTable' class='display dataTable' width='100%'>
            <thead>
                <tr>
                    <th bgcolor="#99BBFF" scope="col">廠商名稱</th>
                    <th bgcolor="#99BBFF" scope="col">IP</th>
                    <th bgcolor="#99BBFF" scope="col">新增/更新時間</th>
                    <th bgcolor="#99BBFF" scope="col">新增/更新人員</th>
                    <th bgcolor="#99BBFF" scope="col">更新</th>
                    <th bgcolor="#99BBFF" scope="col">刪除</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- Script -->
    <script>
        $(document).ready(function() {
            // DataTable
            var userDataTable = $('#userTable').DataTable({
                "scrollX": true,
                "lengthMenu": [
                    [10, 30, 50, 100],
                    [10, 30, 50, 100]
                ],
                language: {
                    "emptyTable": "無資料...",
                    "processing": "處理中...",
                    "loadingRecords": "載入中...",
                    "lengthMenu": "每頁 _MENU_ 筆資料",
                    "zeroRecords": "無搜尋結果",
                    "info": "_START_ 至 _END_ / 共 _TOTAL_ 筆",
                    "infoEmpty": "尚無資料",
                    "infoFiltered": "(從 _MAX_ 筆資料過濾)",
                    "infoPostFix": "",
                    "search": "搜尋字串:",
                    "paginate": {
                        "first": "首頁",
                        "last": "末頁",
                        "next": "下頁",
                        "previous": "前頁"
                    },
                    "aria": {
                        "sortAscending": ": 升冪",
                        "sortDescending": ": 降冪"
                    }
                },
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'streamtack.php'
                },
                'columns': [{
                        data: 'name'
                    },
                    {
                        data: 'ename'
                    },
                    {
                        data: 'update_time'
                    },
                    {
                        data: 'update_id'
                    },
                    {
                        data: 'Update'
                    },
                    {
                        data: 'Delete'
                    },
                ]
            });

            // Update record
            $('#userTable').on('click', '.updateUser', function() {
                var id = $(this).data('id');
                $('#txt_userid').val(id);
                // AJAX request
                $.ajax({
                    url: 'streamtack.php',
                    type: 'post',
                    data: {
                        request: 2,
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 1) {
                            $('#name').val(response.data.name);
                            $('#ename').val(response.data.ename);
                        } else {
                            alert("Invalid ID.");
                        }
                    }
                });
            });

            // Save user 
            $('#btn_save').click(function() {
                var id = $('#txt_userid').val();
                var name = $('#name').val().trim();
                var ename = $('#ename').val().trim();
                if (name != '' && ename != '') {
                    // AJAX request
                    $.ajax({
                        url: 'streamtack.php',
                        type: 'post',
                        data: {
                            request: 3,
                            id: id,
                            name: name,
                            ename: ename,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 1) {
                                alert(response.message);
                                // Empty the fields
                                $('#name', '#ename').val('');
                                $('#txt_userid').val(0);
                                // Reload DataTable
                                userDataTable.ajax.reload();
                                // Close modal
                                $('#updateModal').modal('toggle');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('Please fill all fields.');
                }
            });

            // Delete record
            $('#userTable').on('click', '.deleteUser', function() {
                var id = $(this).data('id');
                var deleteConfirm = confirm("Are you sure?");
                if (deleteConfirm == true) {
                    // AJAX request
                    $.ajax({
                        url: 'streamtack.php',
                        type: 'post',
                        data: {
                            request: 4,
                            id: id
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert("Record deleted.");
                                // Reload DataTable
                                userDataTable.ajax.reload();
                            } else {
                                alert("Invalid ID.");
                            }
                        }
                    });
                }
            });

            // Add record && Save user 
            $('#btn_save2').click(function() {
                var name = $('#name2').val().trim();
                var ename = $('#ename2').val().trim();
                if (name != '' && ename != '') {
                    // AJAX request
                    $.ajax({
                        url: 'streamtack.php',
                        type: 'post',
                        data: {
                            request: 6,
                            name: name,
                            ename: ename,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 1) {
                                alert(response.message);
                                // Empty the fields
                                $('#name2', '#ename2').val('');
                                // Reload DataTable
                                userDataTable.ajax.reload();
                                // Close modal
                                $('#addModal').modal('toggle');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('Please fill all fields.');
                }
            });

            // Change password && Save user 
            $('#btn_save3').click(function() {
                var password = $('#password').val().trim();
                if (password != '') {
                    // AJAX request
                    $.ajax({
                        url: 'streamtack.php',
                        type: 'post',
                        data: {
                            request: 7,
                            password: password
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 1) {
				alert(response.message);
				window.location.href = 'index.html'; // 跳轉到登入頁面    
                                // Empty the fields
                                $('#password').val('');
                                // Reload DataTable
                                userDataTable.ajax.reload();
                                // Close modal
                                $('#changeModal').modal('toggle');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                } else {
                    alert('Please fill all fields.');
                }
            });
        });
    </script>
</body>

</html>
