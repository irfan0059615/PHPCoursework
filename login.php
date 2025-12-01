<?php
    session_start();

    include 'php/db.php';
    include 'php/twig.php';

    $error = null;

    if (isset($_SESSION['username'])) {
        header('Location: index.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (empty($_POST['g-recaptcha-response'])) {
            $error = 'Please verify that you are not a robot.';
        } else {
            $secretKey = '6LcJ9xwsAAAAALWyqpJvQ7FryTqrHCpJejr0f7h6';
            $recaptcha = $_POST['g-recaptcha-response'];

            $response = file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptcha"
            );

            $responseData = json_decode($response);

            if (!$responseData->success) {
                $error = 'reCAPTCHA verification failed.';
            } else {
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);

                if (!$username || !$password) {
                    $error = 'Please fill in all fields.';
                } else {
                    $stmt = $conn->prepare(
                        'SELECT id, username, password FROM users WHERE username = ? LIMIT 1'
                    );
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();

                    if ($user && password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        header('Location: index.php');
                        exit;
                    } else {
                        $error = 'Invalid username or password.';
                    }
                }
            }
        }
    }

    echo $twig->render('login.twig', [
        'error' => $error
    ]);
?>