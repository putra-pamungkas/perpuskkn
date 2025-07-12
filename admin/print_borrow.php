<?php
include '../helpers.php';
if (!is_admin()) die("Akses hanya untuk admin");

$id = $_POST['id'] ?? '';
$borrows = load_json('../data/borrow_requests.json');
$books = load_json('../data/books.json');
$users = load_json('../data/users.json');

$borrow = array_values(array_filter($borrows, fn($b) => $b['id'] == $id))[0] ?? null;
if (!$borrow) die("Peminjaman tidak ditemukan.");

$book = array_values(array_filter($books, fn($b) => $b['id'] == $borrow['book_id']))[0] ?? ['title' => '[Buku]'];
$user = array_values(array_filter($users, fn($u) => $u['id'] == $borrow['user_id']))[0] ?? ['username' => '[User]'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bukti Peminjaman</title>
    <style>
        body { font-family: Arial; padding: 30px; }
        .bukti { border: 1px solid #aaa; padding: 20px; width: 400px; margin: auto; }
        h2 { text-align: center; }
        .info p { margin: 8px 0; }
    </style>
</head>
<body onload="window.print()">
    <div class="bukti">
        <h2>Bukti Peminjaman</h2>
        <div class="info">
            <p><strong>Peminjam:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Judul Buku:</strong> <?= htmlspecialchars($book['title']) ?></p>
            <p><strong>Tanggal Pinjam:</strong> <?= $borrow['borrow_date'] ?></p>
            <p><strong>Tanggal Kembali:</strong> <?= $borrow['return_date'] ?></p>
            <p><strong>Status:</strong> <?= ucfirst($borrow['status']) ?></p>
        </div>
        <p style="text-align:right; margin-top:30px;">Tanda tangan: ____________</p>
    </div>
</body>
</html>
