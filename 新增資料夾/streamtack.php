<?php
$server = "192.168.60.134"; 
$db_username = "root"; // 你的資料庫使用者名稱
$db_password = "123456"; // 你的資料庫密碼
$db_name = "player"; // 資料庫名稱

// 建立連接
$con = new mysqli($server, $db_username, $db_password, $db_name);

// 檢查連接
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error); // 如果連結失敗輸出錯誤
}

$con->set_charset("utf8"); // 以 UTF-8 讀取資料，讓資料可以讀取中文

$request = 1;
if (isset($_POST['request'])) {
    $request = intval($_POST['request']); // 將請求轉換為整數
}

// DataTable data
if ($request == 1) {
    // Read value
    $draw = intval($_POST['draw']);
    $row = intval($_POST['start']);
    $rowperpage = intval($_POST['length']); // Rows display per page
    $columnIndex = intval($_POST['order'][0]['column']); // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

    $searchValue = $con->real_escape_string(trim($_POST['search']['value'])); // Search value

    // Search 
    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = " AND (name LIKE '%" . $searchValue . "%' 
        OR ename LIKE '%" . $searchValue . "%') ";
    }

    // Total number of records without filtering
    $result = $con->query("SELECT COUNT(*) AS allcount FROM strname WHERE deleted_time IS NULL");
    if ($result) {
        $records = $result->fetch_assoc();
        $totalRecords = $records['allcount'];
    } else {
        die("Query failed: " . $con->error);
    }

    // Total number of records with filtering
    $result = $con->query("SELECT COUNT(*) AS allcount FROM strname WHERE 1 " . $searchQuery . " AND deleted_time IS NULL");
    if ($result) {
        $records = $result->fetch_assoc();
        $totalRecordwithFilter = $records['allcount'];
    } else {
        die("Query failed: " . $con->error);
    }

    // Fetch records
    $empRecords = $con->query("SELECT * FROM strname WHERE 1 " . $searchQuery . " AND deleted_time IS NULL LIMIT " . $row . ", " . $rowperpage);
    $data = array();

    while ($row = $empRecords->fetch_assoc()) {
        // Update Button
        $updateButton = "<button class='btn btn-sm btn-info updateUser' data-id='" . $row['id'] . "' data-toggle='modal' data-target='#updateModal'>Update</button>";

        // Delete Button
        $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row['id'] . "'>Delete</button>";

        $data[] = array(
            "name" => $row['name'],
            "ename" => $row['ename'],
            "update_time" => $row['update_time'],
            "update_id" => $row['update_id'],
            "Update" => $updateButton,
            "Delete" => $deleteButton
        );
    }

    // Response
    $response = array(
        "draw" => $draw,
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);
    exit;
}

// Fetch user details
if ($request == 2) {
    $id = 0;

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
    }

    $record = $con->query("SELECT * FROM strname WHERE id=" . $id);
    $response = array();
    if ($record && $record->num_rows > 0) {
        $row = $record->fetch_assoc();
        $response = array(
            "name" => $row['name'],
            "ename" => $row['ename'],
        );

        echo json_encode(array("status" => 1, "data" => $response));
        exit;
    } else {
        echo json_encode(array("status" => 0));
        exit;
    }
}

// Update user
if ($request == 3) {
    $id = 0;

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
    }

    // Check id
    $record = $con->query("SELECT id FROM strname WHERE id=" . $id);
    if ($record && $record->num_rows > 0) {
        $name = $con->real_escape_string(trim($_POST['name']));
        $ename = $con->real_escape_string(trim($_POST['ename']));
        $username = $_SESSION['username'];

        if ($name != '' && $ename != '') {
            if ($con->query("UPDATE strname SET name='" . $name . "', ename='" . $ename . "', update_id='" . $username . "', update_time=NOW() WHERE id=" . $id)) {
                echo json_encode(array("status" => 1, "message" => "Record updated."));
                exit;
            } else {
                echo json_encode(array("status" => 0, "message" => "Update failed: " . $con->error));
                exit;
            }
        } else {
            echo json_encode(array("status" => 0, "message" => "Please fill all fields."));
            exit;
        }
    } else {
        echo json_encode(array("status" => 0, "message" => "Invalid ID."));
        exit;
    }
}

// Delete User
if ($request == 4) {
    $id = 0;

    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
    }

    // Check id
    $username = $_SESSION['username'];
    $record = $con->query("SELECT id FROM strname WHERE id=" . $id);
    if ($record && $record->num_rows > 0) {
        $con->query("UPDATE strname SET deleted_id='" . $username . "', deleted_time=NOW() WHERE id=" . $id);
        echo 1;
        exit;
    } else {
        echo 0;
        exit;
    }
}

// Add user
if ($request == 6) {
    $name = $con->real_escape_string(trim($_POST['name']));
    $ename = $con->real_escape_string(trim($_POST['ename']));
    $username = $_SESSION['username'];

    if ($name != '' && $ename != '') {
        if ($con->query("INSERT INTO strname (name, ename, update_id, update_time) VALUES ('" . $name . "', '" . $ename . "', '" . $username . "', NOW())")) {
            echo json_encode(array("status" => 1, "message" => "Record added."));
            exit;
        } else {
            echo json_encode(array("status" => 0, "message" => "Insert failed: " . $con->error));
            exit;
        }
    } else {
        echo json_encode(array("status" => 0, "message" => "Please fill all fields."));
        exit;
    }
}

// 關閉數據庫連接
$con->close();
?>
