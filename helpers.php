<?php
session_start();

// Load data dari file JSON
function load_json($file) {
    if (!file_exists($file)) return [];
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

// Simpan data ke file JSON
function save_json($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


// Dapatkan user yang sedang login
function current_user() {
    return $_SESSION['user'] ?? null;
}

// Cek apakah user adalah admin
function is_admin() {
    return current_user() && current_user()['role'] === 'admin';
}

// Tampilkan notifikasi alert
function notify($msg) {
    echo "<script>alert('$msg');</script>";
}

// Fungsi auto return jika lewat tanggal kembali
function auto_return_books() {
    $borrows = load_json('data/borrow_requests.json');
    $books = load_json('data/books.json');
    $today = date('Y-m-d');
    $changed = false;

    foreach ($borrows as &$borrow) {
        if ($borrow['status'] === 'approved' && $borrow['return_date'] < $today) {
            $borrow['status'] = 'returned';

            // Tambahkan stok kembali ke buku
            foreach ($books as &$book) {
                if ($book['id'] == $borrow['book_id']) {
                    $book['stock'] = isset($book['stock']) ? $book['stock'] + 1 : 1;

                    // Ubah status buku jika stok tersedia
                    if ($book['stock'] > 0) {
                        $book['status'] = 'available';
                    }
                    break;
                }
            }
            $changed = true;
        }
    }

    if ($changed) {
        save_json('data/borrow_requests.json', $borrows);
        save_json('data/books.json', $books);
    }
}
?>
