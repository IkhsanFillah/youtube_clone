<?php
session_start();
require_once '../config/database.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    echo 'error';
    exit();
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($user_id > 0 && in_array($action, ['subscribe', 'unsubscribe'])) {
    if ($action == 'subscribe') {
        $sql = "INSERT INTO subscriptions (user_id, subscribed_to) VALUES (?, ?)";
    } elseif ($action == 'unsubscribe') {
        $sql = "DELETE FROM subscriptions WHERE user_id = ? AND subscribed_to = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $_SESSION['user_id'], $user_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
} else {
    echo 'error';
}

$conn->close();
?>