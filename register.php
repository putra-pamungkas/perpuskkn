<?php
include 'helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = load_json('data/users.json');

    foreach ($users as $u) {
        if ($u['email'] === $_POST['email']) {
            notify("Email sudah digunakan!");
            exit;
        }
        if ($u['username'] === $_POST['username']) {
            notify("Username sudah digunakan!");
            exit;
        }
    }

    $newUser = [
        "id" => count($users) + 1,
        "username" => $_POST['username'],
        "email" => $_POST['email'],
        "password" => $_POST['password'], // Bisa diganti jadi password_hash jika ingin aman
        "role" => "user",
        "favorites" => []
    ];

    $users[] = $newUser;
    save_json('data/users.json', $users);

    notify("Registrasi berhasil! Silakan login.");
    header("Refresh: 1; url=login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f2f4f8;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            background: #fff;
            padding: 30px;
            margin: 60px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.06);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        form label {
            display: block;
            margin: 12px 0 5px;
        }
        form input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #388E3C;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #2196F3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📝 Registrasi</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="phone">Phone:</label>
        <input type="phone" name="phone" id="phone" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

</body>
</html>
