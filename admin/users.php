<?php
include '../helpers.php';
if (!is_admin()) die("Admin only");

$users = load_json('../data/users.json');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        h2 {
            margin-bottom: 20px;
        }

        .top-nav {
            margin-bottom: 20px;
        }

        .top-nav a {
            margin-right: 15px;
            color: #2196F3;
            text-decoration: none;
            font-weight: bold;
        }

        .top-nav a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 12px 14px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f1f1f1;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
            color: #fff;
        }

        .badge.admin {
            background-color: #4CAF50;
        }

        .badge.user {
            background-color: #2196F3;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ccc;
                padding: 10px;
                border-radius: 6px;
                background: #fff;
            }

            td {
                position: relative;
                padding-left: 50%;
            }

            td::before {
                position: absolute;
                left: 10px;
                top: 12px;
                width: 40%;
                font-weight: bold;
                white-space: nowrap;
            }

            td:nth-child(1)::before { content: "ID"; }
            td:nth-child(2)::before { content: "Username"; }
            td:nth-child(3)::before { content: "Email"; }
            td:nth-child(4)::before { content: "Phone"; }
            td:nth-child(5)::before { content: "Role"; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-nav">
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>

    <h2>👥 Manajemen Pengguna</h2>

    <?php if (empty($users)): ?>
        <p><em>Tidak ada data pengguna.</em></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id']) ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                        <td>
                            <span class="badge <?= $u['role'] === 'admin' ? 'admin' : 'user' ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
