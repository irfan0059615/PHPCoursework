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

            <form method="POST">
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
</body>
</html>