<?php
include 'helpers.php';
auto_return_books();

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Buku tidak ditemukan.";
    exit;
}

$books = load_json('data/books.json');
$book = null;

foreach ($books as &$b) {
    if ($b['id'] == $id) {  
        $book = &$b;  
        break;
    }
}


if (!$book) {
    echo "Buku tidak ditemukan.";
    exit;
}

// Tangani tombol favorit (tambah / hapus)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = load_json('data/users.json');
    foreach ($users as &$u) {
        if ($u['id'] == current_user()['id']) {
            if (isset($_POST['favorite_add'])) {
                if (!in_array($book['id'], $u['favorites'])) {
                    $u['favorites'][] = $book['id'];
                    $_SESSION['user'] = $u;
                    save_json('data/users.json', $users);
                    notify("✅ Buku ditambahkan ke favorit.");
                } else {
                    notify("ℹ️ Buku sudah ada di favorit.");
                }
            }
            if (isset($_POST['favorite_remove'])) {
                $u['favorites'] = array_filter($u['favorites'], fn($fid) => $fid != $book['id']);
                $_SESSION['user'] = $u;
                save_json('data/users.json', $users);
                notify("❌ Buku dihapus dari favorit.");
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        a {
            margin: 0 10px;
            text-decoration: none;
            color: #444;
            font-weight: bold;
        }
        .book-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .book-detail img {
            width: 200px;
            height: auto;
            border-radius: 5px;
        }
        .book-info {
            flex: 1;
        }
        .book-info h2 {
            margin-top: 0;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .btn {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn.secondary {
            background-color: #f39c12;
        }
        .btn.danger {
            background-color: #e74c3c;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #2196F3;
        }
        @media (max-width: 768px) {
            .book-detail {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php" class="back-link">← Kembali ke Daftar Buku</a>

    <div class="book-detail">
        <img src="<?= htmlspecialchars($book['cover']) ?>" alt="Cover Buku">
        <div class="book-info">
            <h2><?= htmlspecialchars($book['title']) ?></h2>
            <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>Penerbit:</strong> <?= htmlspecialchars($book['publisher']) ?></p>
            <p><strong>Tahun Terbit:</strong> <?= htmlspecialchars($book['year']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($book['status']) ?></p>
            <p><strong>Stok Tersedia:</strong> <?= $book['stock'] ?> buah</p>
            <p><?= htmlspecialchars($book['description']) ?></p>

            <?php if (current_user()): ?>
                <?php $isFavorite = in_array($book['id'], current_user()['favorites'] ?? []); ?>
                <form method="post">
                    <?php if ($isFavorite): ?>
                        <input type="hidden" name="favorite_remove" value="1">
                        <button type="submit" class="btn danger">🗑 Hapus dari Favorit</button>
                    <?php else: ?>
                        <input type="hidden" name="favorite_add" value="1">
                        <button type="submit" class="btn secondary">❤️ Tambah ke Favorit</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <hr>

    <?php if (current_user()): ?>
        <?php if ($book['stock'] > 0): ?>
            <form method="post" action="borrow.php">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">

                <label for="borrow_date">Tanggal Pinjam:</label>
                <input type="date" name="borrow_date" id="borrow_date" required>

                <label for="return_date">Tanggal Kembali:</label>
                <input type="date" name="return_date" id="return_date" required>

                <button type="submit" class="btn">📚 Ajukan Peminjaman</button>
            </form>
        <?php else: ?>
            <p><strong>📕 Buku sedang dipinjam atau stok habis.</strong></p>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="login.php" class="btn danger">Login untuk meminjam atau menambahkan ke favorit</a></p>
    <?php endif; ?>
</div>
</body>
</html>
