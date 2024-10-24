<?php
session_start();

require_once 'config.php';

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
        $searchQuery = " AND (username LIKE '%" . $searchValue . "%' 
        OR password LIKE '%" . $searchValue . "%') ";
    }

    // Total number of records without filtering
    $result = $con->query("SELECT COUNT(*) AS allcount FROM users");
    if ($result) {
        $records = $result->fetch_assoc();
        $totalRecords = $records['allcount'];
    } else {
        die("Query failed: " . $con->error);
    }

    // Total number of records with filtering
    $result = $con->query("SELECT COUNT(*) AS allcount FROM users WHERE 1 " . $searchQuery);
    if ($result) {
        $records = $result->fetch_assoc();
        $totalRecordwithFilter = $records['allcount'];
    } else {
        die("Query failed: " . $con->error);
    }

    // Fetch records
    $empRecords = $con->query("SELECT * FROM users WHERE 1 " . $searchQuery . "LIMIT " . $row . ", " . $rowperpage);
    $data = array();

    while ($row = $empRecords->fetch_assoc()) {
        // Update Button
        $updateButton = "<button class='btn btn-sm btn-info updateUser' data-id='" . $row['id'] . "' data-toggle='modal' data-target='#updateModal'>Update</button>";

        // Delete Button
        $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='" . $row['id'] . "'>Delete</button>";

        $data[] = array(
            "username" => $row['username'],
            "password" => $row['password'],
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

    $record = $con->query("SELECT * FROM users WHERE id=" . $id);
    $response = array();
    if ($record && $record->num_rows > 0) {
        $row = $record->fetch_assoc();
        $response = array(
            "username" => $row['username'],
            "password" => $row['password'],
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
    $record = $con->query("SELECT id FROM users WHERE id=" . $id);
    if ($record && $record->num_rows > 0) {
        $username = $con->real_escape_string(trim($_POST['username']));
        $password = $con->real_escape_string(trim($_POST['password']));

        if ($username != '' && $password != '') {
            if ($con->query("UPDATE users SET username='" . $username . "', password='" . $password . "' WHERE id=" . $id)) {
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
    $record = $con->query("SELECT id FROM users WHERE id=" . $id);
    if ($record && $record->num_rows > 0) {
        $con->query("DELETE FROM users WHERE id=" . $id);
        echo 1;
        exit;
    } else {
        echo 0;
        exit;
    }
}

// Add user
if ($request == 6) {
    $username = $con->real_escape_string(trim($_POST['username']));
    $password = $con->real_escape_string(trim($_POST['password']));

    if ($username != '' && $password != '') {
        if ($con->query("INSERT INTO users (username, password) VALUES ('" . $username . "', '" . $password . "')")) {
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
