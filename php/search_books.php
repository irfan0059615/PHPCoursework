<?php
    session_start();
    include 'db.php';

    $search = $_POST['search'] ?? '';
    $user_id = $_SESSION['user_id'] ?? '';
    $books = [];

    if ($search) {
        $stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ? AND (title LIKE ? OR author LIKE ? OR genre LIKE ?) ORDER BY id DESC");
        $like = "%$search%";
        $stmt->bind_param("ssss", $user_id, $like, $like, $like);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['updated_on_short'] = date("d M, h:i A", strtotime($row['updated_on']));
            $books[] = $row;
        }

    } else {
        $stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['updated_on_short'] = date("d M, h:i A", strtotime($row['updated_on']));
            $books[] = $row;
        }
    }

    echo json_encode(['books' => $books]);
?>