<?php
    session_start();
    include 'db.php';
    $response = ['status' => 'error'];

    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'You must be logged in to perform this action';
        echo json_encode($response);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("DELETE FROM books_table WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        if ($stmt->execute()) $response['status'] = 'success';
        echo json_encode($response);
    }
?>