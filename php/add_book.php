<?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $published_year = trim($_POST['published_year'] ?? '');

    if (!$title) {
        echo json_encode(['status' => 'error', 'message' => 'Title is required']);
        exit;
    }

    if ($published_year !== '') {
        $year = (int)$published_year;
        if ($year < 1901 || $year > 2155) {
            echo json_encode(['status' => 'error', 'message' => 'Year must be between 1901 and 2155']);
            exit;
        }
    } else {
        $year = null;
    }

    $stmt = $conn->prepare("INSERT INTO books_table (user_id, title, author, genre, published_year, updated_on) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssi", $user_id, $title, $author, $genre, $year);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
?>