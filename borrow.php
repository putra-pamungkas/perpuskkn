<?php include 'helpers.php'; 

if (!current_user()) die("Login dahulu.");
 
auto_return_books();

$books = load_json('data/books.json');
$borrows = load_json('data/borrow_requests.json');
$users = load_json('data/users.json');

if (!is_array($books)) die("Gagal membaca data buku.");
if (!is_array($borrows)) $borrows = [];
if (!is_array($users)) $users = [];

$user = current_user();
if (!$user) {
    notify("Login diperlukan.");
    header("Location: login.php");
    exit;
}

$borrows = load_json('data/borrow_requests.json');
$borrows[] = [
    "id" => count($borrows) + 1,
    "user_id" => current_user()['id'],
    "book_id" => $_POST['book_id'],
    "borrow_date" => $_POST['borrow_date'],
    "return_date" => $_POST['return_date'],
    "status" => "pending"
];
foreach ($books as &$book) {
    if ($book['id'] == $_POST['book_id']) {
        $book['stock']--;

        // Jika stok habis, set status borrowed
        if ($book['stock'] <= 0) {
            $book['status'] = 'borrowed';
        }
        break;
    }
}

save_json('data/books.json', $books);
save_json('data/borrow_requests.json', $borrows);
notify("Permintaan peminjaman dikirim");
header("Refresh: 1; url=index.php");
