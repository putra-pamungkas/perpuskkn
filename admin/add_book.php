<?php
include '../helpers.php';
if (!is_admin()) die("Admin only");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $books = load_json('../data/books.json');

    $file = $_FILES['cover'] ?? null;
    if ($file && $file['tmp_name']) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $path = '../uploads/' . $filename;
        move_uploaded_file($file['tmp_name'], $path);
        $coverPath = substr($path, 3); // relative path
    } else {
        $coverPath = 'default.jpg';
    }

    $books[] = [
        "id" => uniqid(),
        "title" => $_POST['title'],
        "author" => $_POST['author'],
        "publisher" => $_POST['publisher'],
        "year" => $_POST['year'],
        "description" => $_POST['description'],
        "cover" => $coverPath,
        "status" => "available",
        "stock" => (int)$_POST['stock']
    ];

    save_json('../data/books.json', $books);
    notify("✅ Buku berhasil ditambahkan!");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7fb;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        h2 {
            margin-bottom: 25px;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #2196F3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .btn {
            padding: 10px 16px;
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php">← Kembali ke Dashboard</a>
    <h2>➕ Tambah Buku Baru</h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul Buku:</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Pengarang:</label>
            <input type="text" name="author" required>
        </div>

        <div class="form-group">
            <label>Penerbit:</label>
            <input type="text" name="publisher" required>
        </div>

        <div class="form-group">
            <label>Tahun Terbit:</label>
            <input type="number" name="year" min="1000" max="<?= date('Y') ?>" required>
        </div>

        <div class="form-group">
            <label>Deskripsi Buku:</label>
            <textarea name="description" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label>Jumlah Stok:</label>
            <input type="number" name="stock" min="1" required>
        </div>

        <div class="form-group">
            <label>Cover Buku (gambar):</label>
            <input type="file" name="cover" accept="image/*" required>
        </div>

        <button type="submit" class="btn">📚 Tambah Buku</button>
    </form>
</div>

</body>
</html>
