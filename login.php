<?php
    session_start();
    include 'php/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (!$username || !$password) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } else {
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: index.php");
                    exit;
                } else {
                    echo "<script>alert('Invalid username or password.');</script>";
                }
            } else {
                echo "<script>alert('Invalid username or password.');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Login - BookVerse</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark custom-bg border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">BookVerse</a>
        </div>
    </nav>

    <div class="d-flex align-items-center justify-content-center py-5">
        <div class="card card-custom p-4" style="width: 370px;">
            <h3 class="text-center mb-3 text-light fw-bold">Login</h3>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-light">Username</label>
                    <input type="text" name="username" class="form-control form-control-dark" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-light">Password</label>
                    <input type="password" name="password" class="form-control form-control-dark" required>
                </div>

                <button class="btn btn-primary w-100">Login</button>

                <p class="text-center mt-2">
                    <span class="text-muted">Don't have an account?</span>
                    <a href="register.php" class="text-decoration-none custom-text-info">Register</a>
                </p>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>