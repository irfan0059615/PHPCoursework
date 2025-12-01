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
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $genre = $_POST['genre'] ?? '';
        $year = $_POST['published_year'] ?? null;

        $stmt = $conn->prepare("UPDATE books_table SET title=?, author=?, genre=?, published_year=?, updated_on=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("sssiii", $title, $author, $genre, $year, $id, $_SESSION['user_id']);
        if ($stmt->execute()) $response['status'] = 'success';
        echo json_encode($response);
    }
?>