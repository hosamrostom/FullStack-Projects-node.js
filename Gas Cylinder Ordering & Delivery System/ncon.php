<?php
include_once('includes/db_con.php');

if (
    isset($_POST['conid']) && isset($_POST['cmno']) && isset($_POST['pass']) && isset($_POST['cpass']) &&
    isset($_POST['name']) && isset($_POST['address']) && isset($_POST['city']) && isset($_POST['pin']) &&
    isset($_POST['eid'])
) {
    $cid = $_POST['conid'];
    $m_no = $_POST['cmno'];
    $pwd = $_POST['pass'];
    $cpass = $_POST['cpass'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $pin = $_POST['pin'];
    $eid = $_POST['eid'];
    $status = 'Active';
    $reg_date = date('Y-m-d');
    $did = 1;

    // Check password match
    if ($pwd != $cpass) {
        echo "Passwords do not match!";
        exit;
    }

    // Check if consumer ID already registered
    $checkQuery = "SELECT status FROM consumer_detail WHERE cid = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "i", $cid);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        mysqli_stmt_bind_result($checkStmt, $existingStatus);
        mysqli_stmt_fetch($checkStmt);
        if ($existingStatus == 'Active' || $existingStatus == 'Deactive') {
            echo "Your account/mobile no. is already registered.";
            exit;
        } elseif ($existingStatus == 'Not Registered') {
            $update = "UPDATE consumer_detail SET pwd=?, reg_date=?, status='Active' WHERE cid=?";
            $updateStmt = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($updateStmt, "ssi", $pwd, $reg_date, $cid);
            mysqli_stmt_execute($updateStmt);
            echo "True";
            exit;
        }
    }

    // Insert new consumer
    $query = "INSERT INTO consumer_detail (cid, did, pwd, name, address, city, pin, m_no, e_id, reg_date, status)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iisssssssss", $cid, $did, $pwd, $name, $address, $city, $pin, $m_no, $eid, $reg_date, $status);

    if (mysqli_stmt_execute($stmt)) {
        echo "True";
    } else {
        echo "DB Insert Failed: " . mysqli_error($conn);
    }

} else {
    echo "Missing fields";
}
?>
