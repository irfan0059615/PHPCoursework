<?php
    session_start();

    include 'php/db.php';
    include 'php/twig.php';

    $error = null;
    $success = null;

    if (isset($_SESSION['username'])) {
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $recaptcha = $_POST['g-recaptcha-response'] ?? '';

        if (!$recaptcha) {
            $error = 'Please verify the CAPTCHA.';
        } else {
            $secretKey = '6LcJ9xwsAAAAALWyqpJvQ7FryTqrHCpJejr0f7h6';

            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha"
            );
            $responseData = json_decode($response);

            if (!$responseData->success) {
                $error = 'CAPTCHA verification failed.';
            }
        }

        if (!$error) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $confirm  = trim($_POST['confirm']);

            if (!$username || !$password || !$confirm) {
                $error = 'All fields are required.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } elseif (!preg_match(
                '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/',
                $password
            )) {
                $error = 'Password must be at least 8 chars, include uppercase, lowercase, number and special char.';
            } else {
                $stmt = $conn->prepare(
                    'SELECT id FROM users WHERE username = ? LIMIT 1'
                );
                $stmt->bind_param('s', $username);
                $stmt->execute();

                if ($stmt->get_result()->num_rows > 0) {
                    $error = 'Username already taken.';
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);

                    $insert = $conn->prepare(
                        'INSERT INTO users (username, password) VALUES (?, ?)'
                    );
                    $insert->bind_param('ss', $username, $hashed);

                    if ($insert->execute()) {
                        $success = 'Account created successfully. You can now log in.';
                    } else {
                        $error = 'Something went wrong. Please try again.';
                    }
                }
            }
        }
    }

    echo $twig->render('register.twig', [
        'error'   => $error,
        'success' => $success
    ]);
?>