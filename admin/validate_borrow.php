<?php 
include '../helpers.php';

if (!is_admin()) die("Akses ditolak");

$borrows = load_json('../data/borrow_requests.json');
$books = load_json('../data/books.json');
$users = load_json('../data/users.json');

// Proses persetujuan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve'])) {
    $id = $_POST['approve'];

    foreach ($borrows as &$borrow) {
        if ($borrow['id'] == $id) {
            $borrow['status'] = 'approved';
            break;
        }
    }

    foreach ($books as &$book) {
        if ($book['id'] == $_POST['book_id']) {
            $book['status'] = 'borrowed';
            break;
        }
    }

    save_json('../data/borrow_requests.json', $borrows);
    save_json('../data/books.json', $books);
    notify("✅ Peminjaman disetujui.");
    header("Refresh: 1; url=validate_borrow.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Validasi Peminjaman</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }

        .top-nav {
        display: flex; 
        margin: auto;
        justify-content: space-between;
        align-items: center; 
        }
        .top-nav a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .top-nav a:hover {
            color: #2196F3; 
            text-decoration: underline;
        }
        
        h2 {
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .request-card {
            background: #fafafa;
            border-left: 5px solid #3498db;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.05);
        }
        .request-card p {
            margin: 8px 0;
        }
        .request-card strong {
            color: #2c3e50;
            min-width: 140px;
            display: inline-block;
        }
        .btn {
            margin-top: 15px;
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #219150;
        }
        .empty-message {
            text-align: center;
            color: #888;
            margin-top: 40px;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-nav">
        <a href="dashboard.php">← Kembali ke Dashboard</a>  
    </div>

    <h2>📋 Validasi Peminjaman Buku</h2>

    <?php 
    $hasPending = false;
    foreach ($borrows as $b): 
        if ($b['status'] !== 'pending') continue;
        $hasPending = true;

        $book = array_filter($books, fn($bk) => $bk['id'] == $b['book_id']);
        $book = reset($book);

        $user = array_filter($users, fn($u) => $u['id'] == $b['user_id']);
        $user = reset($user);
    ?>
        <div class="request-card">
            <p><strong>📖 Judul Buku:</strong> <?= htmlspecialchars($book['title'] ?? '❓Tidak ditemukan') ?></p>
            <p><strong>👤 Peminjam:</strong> <?= htmlspecialchars($user['username'] ?? '❓Tidak ditemukan') ?></p>
            <p><strong>📅 Tanggal Pinjam:</strong> <?= htmlspecialchars($b['borrow_date']) ?></p>
            <p><strong>📅 Tanggal Kembali:</strong> <?= htmlspecialchars($b['return_date']) ?></p>

            <form method="post">
                <input type="hidden" name="approve" value="<?= $b['id'] ?>">
                <input type="hidden" name="book_id" value="<?= $b['book_id'] ?>">
                <button type="submit" class="btn">✅ Setujui Peminjaman</button>
            </form>
        </div>
    <?php endforeach; ?>

    <?php if (!$hasPending): ?>
        <p class="empty-message">Tidak ada permintaan peminjaman yang menunggu.</p>
    <?php endif; ?>
</div>
</body>
</html>