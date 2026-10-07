<?php
session_start();
include '../connection.php';

if (isset($_GET['id']) && isset($_GET['action'])) {
    $request_id = $_GET['id'];
    $action = $_GET['action'];

    // Check if the request already has a decision
    $check_sql = "SELECT status FROM doctor_request WHERE request_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $request_id);
    $check_stmt->execute();
    $check_stmt->bind_result($current_status);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($current_status !== 'Pending') {
        echo "<script>alert('Request already processed!'); window.history.back();</script>";
        exit;
    }

    // Update status based on action
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';

    $update_sql = "UPDATE doctor_request SET status = ? WHERE request_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $request_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Doctor request $status successfully!'); window.location.href='admin-manageDocReq.php';</script>";
    } else {
        echo "<script>alert('Error updating request status!'); window.history.back();</script>";
    }

    $update_stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.history.back();</script>";
}
?>
