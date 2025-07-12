<?php
include 'helpers.php';
auto_return_books();

$user = current_user();
if (!$user) {
    header("Location: login.php");
    exit;
}

$books = load_json('data/books.json');
$borrows = load_json('data/borrow_requests.json');

// Ambil daftar buku favorit
$favBooks = array_filter($books, fn($b) => in_array($b['id'], $user['favorites']));

// Ambil riwayat peminjaman user
$history = array_filter($borrows, fn($b) => $b['user_id'] == $user['id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f4f6f9;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #2196F3;
            font-weight: bold;
        }
        .profile-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.08);
        }
        h2 {
            margin-top: 0;
        }
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        .book-card {
            background: #fafafa;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            text-align: center;
            transition: 0.2s ease;
        }
        .book-card img {
            width: 80px;
            height: auto;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        .book-card:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .top-nav {
            margin: auto; 
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
        @media (max-width: 600px) {
            .book-list {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-nav">
        <a class="back-link" href="index.php">← Kembali ke Beranda</a>
    </div>

    <div class="profile-card">
        <h2>👤 Profil Pengguna</h2>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Nomor HP:</strong> <?= htmlspecialchars($user['phone'] ?? '-') ?></p> 
        <a href="logout.php" style="color: red;">🔒 Logout</a>
    </div>

    <div class="profile-card">
        <h2>❤️ Buku Favorit</h2>
        <?php if (empty($favBooks)): ?>
            <p><i>Belum ada buku favorit.</i></p>
        <?php else: ?>
            <div class="book-list">
                <?php foreach ($favBooks as $book): ?>
                    <a href="book.php?id=<?= $book['id'] ?>" class="book-card" style="text-decoration: none; color: inherit;">
                        <img src="<?= htmlspecialchars($book['cover']) ?>" alt="Cover">
                        <div><strong><?= htmlspecialchars($book['title']) ?></strong></div>
                        <small><?= htmlspecialchars($book['author']) ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="profile-card">
        <h2>📖 Riwayat Peminjaman</h2>
        <?php if (empty($history)): ?>
            <p><i>Belum ada riwayat peminjaman.</i></p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($history as $h): ?>
                    <?php
                        $buku = array_filter($books, fn($b) => $b['id'] == $h['book_id']);
                        $judul = $buku ? reset($buku)['title'] : '❌ Tidak ditemukan';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($judul) ?></td>
                        <td><?= htmlspecialchars($h['borrow_date']) ?></td>
                        <td><?= htmlspecialchars($h['return_date']) ?></td>
                        <td><?= ucfirst($h['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
