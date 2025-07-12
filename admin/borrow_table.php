<?php
include '../helpers.php';
if (!is_admin()) die("Akses hanya untuk admin");

$borrows = load_json('../data/borrow_requests.json');
$books = load_json('../data/books.json');
$users = load_json('../data/users.json');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Peminjaman</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f7fb;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            margin-bottom: 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f0f0f0;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn.print {
            background-color: #2196F3;
            color: white;
        }

        .btn.print:hover {
            background-color: #1976D2;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            tr {
                margin-bottom: 15px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.05);
                padding: 10px;
                border-radius: 5px;
                background: #fff;
            }
            th {
                display: none;
            }
            td {
                position: relative;
                padding-left: 50%;
            }
            td::before {
                position: absolute;
                top: 12px;
                left: 15px;
                font-weight: bold;
                color: #555;
            }

            td:nth-of-type(1)::before { content: "Nama Peminjam"; }
            td:nth-of-type(2)::before { content: "Judul Buku"; }
            td:nth-of-type(3)::before { content: "Tanggal Pinjam"; }
            td:nth-of-type(4)::before { content: "Tanggal Kembali"; }
            td:nth-of-type(5)::before { content: "Status"; }
            td:nth-of-type(6)::before { content: "Aksi"; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-nav">
        <a href="dashboard.php">← Dashboard</a>
        <a href="add_borrow.php">➕ Tambah Peminjaman</a> 
    </div>

    <h1>📋 Tabel Peminjaman Buku</h1>

    <?php if (empty($borrows)): ?>
        <p><em>Belum ada data peminjaman.</em></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrows as $b):
                    $book = array_values(array_filter($books, fn($bk) => $bk['id'] == $b['book_id']))[0] ?? ['title' => '[Buku]'];
                    $user = array_values(array_filter($users, fn($u) => $u['id'] == $b['user_id']))[0] ?? ['username' => '[User]'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($b['borrow_date']) ?></td>
                    <td><?= htmlspecialchars($b['return_date']) ?></td>
                    <td><?= ucfirst($b['status']) ?></td>
                    <td>
                        <form action="print_borrow.php" method="post" target="_blank">
                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                            <button type="submit" class="btn print">🖨️ Cetak</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
