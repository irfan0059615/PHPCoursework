<?php
    session_start();
    include "php/db.php";

    $error = "";
    $success = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $confirm  = trim($_POST["confirm"]);

        if (!$username || !$password || !$confirm) {
            $error = "All fields are required.";
        } elseif ($password !== $confirm) {
            $error = "Passwords do not match.";
        } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
            $error = "Password must be at least 8 chars, with uppercase, lowercase, number, and special char.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Username already taken.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $insert->bind_param("ss", $username, $hashed);

                if ($insert->execute()) {
                    $success = "Account created successfully! You can now log in.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }

            $stmt->close();
            if (isset($insert)) $insert->close();
        }

        if ($error) {
            echo "<script>alert('" . addslashes($error) . "');</script>";
        }
        if ($success) {
            echo "<script>alert('" . addslashes($success) . "'); window.location.href = 'login.php'; </script>";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Register - BookVerse</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark custom-bg border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">BookVerse</a>
        </div>
    </nav>

    <div class="d-flex align-items-center justify-content-center py-5">
        <div class="card card-custom p-3" style="width: 360px;">
            <h3 class="text-center mb-2 text-light fw-bold">Create an Account</h3>

            <form method="POST" onsubmit="return validateForm();">
                <div class="mb-3 position-relative">
                    <label class="form-label text-light">Username</label>
                    <input type="text" name="username" id="username" class="form-control form-control-dark" onkeyup="checkUsername()" required>
                    <small id="username-feedback" class="custom-text-info"></small>
                </div>

                <div class="mb-3 position-relative">
                    <label class="form-label text-light d-flex align-items-center">Password <i class="bi bi-info-circle ms-2 custom-text-info" data-bs-toggle="tooltip" title="At least 8 chars, uppercase, lowercase, number, special char"></i></label>
                    <input type="password" name="password" id="password" class="form-control form-control-dark" onkeyup="checkPasswordStrength()" required>
                    <small id="password-feedback" class="custom-text-info"></small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-light">Confirm Password</label>
                    <input type="password" name="confirm" id="confirm" class="form-control form-control-dark" required>
                </div>

                <button class="btn btn-primary w-100">Create Account</button>

                <p class="text-center mt-2">
                    <span class="text-muted">Already have an account?</span>
                    <a href="login.php" class="text-decoration-none custom-text-info">Login</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        function validateForm() {
            var password = document.getElementById("password").value;
            var confirm = document.getElementById("confirm").value;

            if (password !== confirm) {
                alert("Passwords do not match.");
                return false;
            }
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!regex.test(password)) {
                alert("Password is not strong enough.");
                return false;
            }
            return true;
        }

        function checkUsername() {
            var username = document.getElementById("username").value;
            if (username.length < 3) {
                document.getElementById("username-feedback").innerHTML = "";
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "php/check_username.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                document.getElementById("username-feedback").innerHTML = this.responseText;
            }
            xhr.send("username=" + encodeURIComponent(username));
        }

        function checkPasswordStrength() {
            var password = document.getElementById("password").value;
            var feedback = document.getElementById("password-feedback");
            var strength = "";

            if (password.length === 0) {
                strength = "";
            } else if (password.length < 8) {
                strength = "Password must be at least 8 characters";
            } else if (!/[A-Z]/.test(password)) {
                strength = "Add uppercase";
            } else if (!/[a-z]/.test(password)) {
                strength = "Add lowercase";
            } else if (!/\d/.test(password)) {
                strength = "Add number";
            } else if (!/[\W_]/.test(password)) {
                strength = "Add special character";
            } else {
                strength = "Strong password ✅";
            }

            feedback.innerText = strength;
        }
    </script>
</body>
</html>