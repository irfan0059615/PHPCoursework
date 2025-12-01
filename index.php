<?php
    session_start();
    include 'php/db.php';
    include 'php/twig.php';
    
    $user = $_SESSION['username'];
    
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM books_table WHERE user_id=? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = $result->fetch_all(MYSQLI_ASSOC);

    echo $twig->render('dashboard.twig', [
        'user'  => $user,
        'books' => $books
    ]);
?>