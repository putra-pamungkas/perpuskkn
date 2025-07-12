<?php
include '../helpers.php';
if (!is_admin()) die("Akses hanya untuk admin");

$books = load_json('../data/books.json');
$users = load_json('../data/users.json');
$borrows = load_json('../data/borrow_requests.json');
$pendingBorrows = array_filter($borrows, fn($b) => $b['status'] === 'pending');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .top-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .top-nav a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .top-nav a:hover {
            color: #2196F3;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            min-width: 200px;
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }

        .card h2 {
            font-size: 36px;
            margin: 0;
            color: #2196F3;
        }

        .card p {
            margin-top: 8px;
            font-size: 16px;
            color: #555;
        }

        .menu {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .menu h3 {
            margin-bottom: 15px;
        }

        .menu ul {
            list-style: none;
            padding-left: 0;
        }

        .menu li {
            margin-bottom: 12px;
        }

        .menu a {
            text-decoration: none;
            color: #2196F3;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .menu a:hover {
            transform: translateX(5px);
            color: #0d8ae4;
        }

        @media (max-width: 768px) {
            .stats {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="top-nav">
        <div><a href="../index.php">← Kembali ke Homepage</a></div>
        <div><a href="../logout.php">🔒 Logout</a></div>
    </div>

    <h1>📊 Dashboard Admin</h1>

    <div class="stats">
        <div class="card">
            <h2><?= count($books) ?></h2>
            <p>📚 Total Buku</p>
        </div>
        <div class="card">
            <h2><?= count($users) ?></h2>
            <p>👥 Total Pengguna</p>
        </div>
        <div class="card">
            <h2><?= count($pendingBorrows) ?></h2>
            <p>⏳ Pending Peminjaman</p>
        </div>
    </div>

    <div class="menu">
        <h3>📁 Menu Admin</h3>
        <ul>
            <li><a href="add_book.php">➕ Tambah Buku</a></li>
            <li><a href="borrow_table.php">📋 Riwayat Peminjaman</a></li>
            <li><a href="validate_borrow.php">✅ Validasi Peminjaman</a></li>
            <li><a href="users.php">👥 Manajemen Pengguna</a></li>
        </ul>
    </div>

</div>
</body>
</html>
