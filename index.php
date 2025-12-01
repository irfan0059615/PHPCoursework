<?php
    session_start();
    include 'php/db.php';

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM books WHERE user_id=? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard - BookVerse</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-bg border-bottom border-secondary">
        <div class="container">

            <a class="navbar-brand fw-bold" href="index.php">BookVerse</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <?php if ($user): ?>
                        <li class="nav-item d-flex align-items-center me-2">
                            <span class="text-light">Hello, <strong><?= htmlspecialchars($user) ?></strong></span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-danger btn-sm" href="php/logout.php">Logout</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>

        </div>
    </nav>

    <div class="container py-4">
        <div class="card custom-bg border-secondary mx-auto center-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <form method="get" class="flex-grow-1 me-2">
                        <div class="input-group input-group-sm" style="max-width:260px;">
                            <input id="searchInput" name="search" type="text" class="form-control custom-search-input" placeholder="Search title, author or genre">
                        </div>
                    </form>

                    <div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bookModal">+ Add Book</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0 text-wrap">
                        <thead class="table-secondary text-dark">
                            <tr>
                                <th style="width:60px">SN</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th style="width:100px">Year</th>
                                <th style="width:140px">Updated</th>
                                <th style="width:170px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No books found</td>
                            </tr>
                        <?php else:
                            $sn = 1;
                        ?>
                            <?php foreach($books as $book): ?>
                            <tr>
                                <td><?= $sn++; ?></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td><?= htmlspecialchars($book['genre']) ?></td>
                                <td><?= htmlspecialchars($book['published_year']) ?></td>
                                <td>
                                    <?php 
                                        if (!empty($book['updated_on'])) {
                                            echo date("d M h:i A", strtotime($book['updated_on']));
                                        } else {
                                            echo '-';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-light me-1" onclick="editBook(<?= $book['id'] ?>)" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBook(<?= $book['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-bg text-light border-secondary">
            <div class="modal-header">
                <h5 class="modal-title">Add New Book</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bookForm" onsubmit="event.preventDefault(); addBook();">
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" class="form-control form-control-dark" type="text" name="title" placeholder="Title" required>
                    </div>
                    <div class="mb-2">
                        <label for="author" class="form-label">Author</label>
                        <input id="author" class="form-control form-control-dark" type="text" name="author" placeholder="Author">
                    </div>
                    <div class="mb-2">
                        <label for="genre" class="form-label">Genre</label>
                        <input id="genre" class="form-control form-control-dark" type="text" name="genre" placeholder="Genre">
                    </div>
                    <div class="mb-2">
                        <label for="published_year" class="form-label">Year</label>
                        <input id="published_year" class="form-control form-control-dark" type="number" name="published_year" placeholder="Year">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Add Book</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-bg text-light border-secondary">
            <div class="modal-header">
                <h5 class="modal-title">Edit Book</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" onsubmit="event.preventDefault(); updateBook();">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-2">
                        <label for="editTitle" class="form-label">Title</label>
                        <input id="editTitle" class="form-control form-control-dark" type="text" name="title" placeholder="Title" required>
                    </div>
                    <div class="mb-2">
                        <label for="editAuthor" class="form-label">Author</label>
                        <input id="editAuthor" class="form-control form-control-dark" type="text" name="author" placeholder="Author">
                    </div>
                    <div class="mb-2">
                        <label for="editGenre" class="form-label">Genre</label>
                        <input id="editGenre" class="form-control form-control-dark" type="text" name="genre" placeholder="Genre">
                    </div>
                    <div class="mb-2">
                        <label for="editYear" class="form-label">Year</label>
                        <input id="editYear" class="form-control form-control-dark" type="number" name="published_year" placeholder="Year">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Update Book</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>