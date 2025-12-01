<?php
    include 'db.php';

    if(isset($_POST['username'])) {
        $username = trim($_POST['username']);
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            echo '<span class="text-danger"><i class="bi bi-x-circle"></i> Username is already taken</span>';
        } else {
            echo '<span class="text-success"><i class="bi bi-check-circle"></i> Username is available</span>';
        }
    }
?>