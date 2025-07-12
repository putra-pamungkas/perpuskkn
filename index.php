<?php
include 'helpers.php';
auto_return_books();

$books = load_json('data/books.json');
$search = strtolower($_GET['search'] ?? '');

if ($search) {
    $books = array_filter($books, fn($b) => str_contains(strtolower($b['title']), $search));
}

$page = $_GET['page'] ?? 1;
$perPage = 5;
$total = count($books);
$books = array_slice($books, ($page - 1) * $perPage, $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-nav {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #444;
            font-weight: bold;
        }

        .top-nav a:hover {
            color: #2196F3;
        }

        .layout {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .sidebar {
            flex: 1;
            min-width: 250px;
            max-width: 300px;
            background-color: #fff;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .content {
            flex: 3;
            min-width: 0;
        }

        .search-form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-form button {
            margin-top: 10px;
            padding: 8px 12px;
            border: none;
            background-color: #2196F3;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .book-card {
            display: flex;
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }

        .book-card img {
            width: 100px;
            height: auto;
            margin-right: 15px;
            border-radius: 4px;
        }

        .book-info h3 {
            margin-top: 0;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            padding: 6px 12px;
            margin: 2px;
            border: 1px solid #aaa;
            text-decoration: none;
            border-radius: 4px;
            color: #333;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        @media (max-width: 768px) {
            .layout {
                flex-direction: column;
            }
            .sidebar {
                max-width: 100%; 
            }
            .top-nav {
                flex-direction: row;
                gap: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>📚 Sistem Informasi Perpustakaan</h1>

    <div class="top-nav">
        <?php if (current_user()): ?>
            <a href="profile.php">Profil</a>
            <?php if (is_admin()): ?>
            <a href="admin/dashboard.php">Dashboard Admin</a>
            <?php endif; ?> 
        <?php else: ?>
            <a href="login.php">Login</a> | <a href="register.php">Daftar</a>
        <?php endif; ?>
    </div>

    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>🔍 Cari Buku</h3>
            <form class="search-form" method="get">
                <input type="text" name="search" placeholder="Masukkan judul..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Konten -->
        <div class="content">
            <?php if (empty($books)): ?>
                <p><i>Tidak ada buku ditemukan.</i></p>
            <?php else: ?>
                <?php foreach ($books as $b): ?>
                    <div class="book-card">
                        <img src="<?= htmlspecialchars($b['cover']) ?>" alt="Cover">
                        <div class="book-info">
                            <h3><?= htmlspecialchars($b['title']) ?></h3>
                            <p><strong>Penulis:</strong> <?= htmlspecialchars($b['author']) ?><br>
                               <strong>Tahun:</strong> <?= htmlspecialchars($b['year']) ?><br>
                               <strong>Status:</strong> <?= htmlspecialchars($b['status']) ?></p>
                            <a href="book.php?id=<?= $b['id'] ?>">📖 Lihat Detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="pagination">
                <?php for ($i = 1; $i <= ceil($total / $perPage); $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
