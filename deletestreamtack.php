<?php
session_start();

require_once 'config.php';

$request = 1;
if (isset($_POST['request'])) {
    $request = $_POST['request'];
}

// DataTable data
if ($request == 1) {
    ## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

    $searchValue = $con->real_escape_string($_POST['search']['value']); // Search value

    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name like '%" . $searchValue . "%' 
        or ename like '%" . $searchValue . "%') ";
    }

    ## Total number of records without filtering
    $sel = $con->query("select count(*) as allcount from strname WHERE deleted_time IS NOT NULL");
    $records = $sel->fetch_assoc();
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sel = $con->query("select count(*) as allcount from strname WHERE 1 " . $searchQuery . "and deleted_time IS NOT NULL");
    $records = $sel->fetch_assoc();
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records
    $empRecords = $con->query("select * from strname WHERE 1 " . $searchQuery . "and deleted_time IS NOT NULL order by id desc limit " . $row . "," . $rowperpage);
    $data = array();

    while ($row = $empRecords->fetch_assoc()) {
        $data[] = array(
            "name" => $row['name'],
            "ename" => $row['ename'],
            "deleted_time" => $row['deleted_time'],
            "deleted_id" => $row['deleted_id'],
            "action" => "<input type='checkbox' class='delete_check' id='delcheck_" . $row['id'] . "' onclick='checkcheckbox();' value='" . $row['id'] . "'>",
        );
    }

    ## Response
    $response = array(
        "draw" => intval($draw),
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
        $id = $con->real_escape_string($_POST['id']);
    }

    $record = $con->query("SELECT * FROM strname WHERE id=" . $id);
    $response = array();
    if ($record->num_rows > 0) {
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

// Delete User
if ($request == 4) {
    $id = 0;

    if (isset($_POST['id'])) {
        $id = $con->real_escape_string($_POST['id']);
    }

    // Check id
    $record = $con->query("SELECT id FROM strname WHERE id=" . $id);
    if ($record->num_rows > 0) {
        $con->query("DELETE FROM strname WHERE id=" . $id);
        echo 1;
        exit;
    } else {
        echo 0;
        exit;
    }
}

// Delete multiple records
if ($request == 3) {
    $deleteids_arr = array();

    if (isset($_POST['deleteids_arr'])) {
        $deleteids_arr = $_POST['deleteids_arr'];
    }
    foreach ($deleteids_arr as $deleteid) {
        $deleteid = $con->real_escape_string($deleteid);
        $con->query("DELETE FROM strname WHERE id=" . $deleteid);
    }

    echo 1;
    exit;
}
