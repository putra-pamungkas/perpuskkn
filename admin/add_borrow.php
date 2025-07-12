<?php
include '../helpers.php';
if (!is_admin()) die("Akses hanya untuk admin");

$books = load_json('../data/books.json');
$users = load_json('../data/users.json');
$borrows = load_json('../data/borrow_requests.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = array_values(array_filter($users, fn($u) => strtolower($u['username']) == strtolower($_POST['username'])));
    $book = array_values(array_filter($books, fn($b) => strtolower($b['title']) == strtolower($_POST['book_title'])));

    if (!$user || !$book) {
        notify("❌ User atau buku tidak ditemukan.");
    } else if ($book[0]['stock'] <= 0) {
        notify("⚠️ Stok buku tidak tersedia.");
    } else {
        $uid = $user[0]['id'];
        $bid = $book[0]['id'];

        $borrows[] = [
            'id' => uniqid(),
            'user_id' => $uid,
            'book_id' => $bid,
            'borrow_date' => $_POST['borrow_date'],
            'return_date' => $_POST['return_date'],
            'status' => 'approved'
        ];

        foreach ($books as &$b) {
            if ($b['id'] == $bid) {
                $b['stock']--;
                if ($b['stock'] <= 0) $b['status'] = 'borrowed';
                break;
            }
        }

        save_json('../data/borrow_requests.json', $borrows);
        save_json('../data/books.json', $books);

        notify("✅ Peminjaman berhasil ditambahkan.");
        header("Location: borrow_table.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Peminjaman</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f7fb;
            padding: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.05);
        }
        h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #1976D2;
        }
        .top-nav {
            max-width: 600px;
            margin: auto;
            margin-bottom: 20px;
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
    </style>
</head>
<body>

<div class="top-nav">
    <a href="borrow_table.php">← Kembali ke Tabel Peminjaman</a>
</div>

<div class="form-container">
    <h2>➕ Tambah Peminjaman Manual</h2>

    <form method="post">
        <label for="username">Nama Peminjam:</label> 
        <input list="user_list" name="username" id="username" required placeholder="Ketik atau pilih username">
        <datalist id="user_list">
            <?php foreach ($users as $u): ?>
                <option value="<?= htmlspecialchars($u['username']) ?>">
            <?php endforeach; ?>
        </datalist>

        <label for="book_title">Judul Buku (tersedia):</label>
        <input list="book_list" name="book_title" id="book_title" required placeholder="Ketik atau pilih judul buku">
        <datalist id="book_list">
            <?php foreach ($books as $b): ?>
                <?php if ($b['stock'] > 0): ?>
                    <option value="<?= htmlspecialchars($b['title']) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
        </datalist>

        <label for="borrow_date">Tanggal Pinjam:</label>
        <input type="date" name="borrow_date" id="borrow_date" required>

        <label for="return_date">Tanggal Kembali:</label>
        <input type="date" name="return_date" id="return_date" required>

        <button type="submit" class="btn">💾 Simpan</button>
    </form>
</div>

</body>
</html>
