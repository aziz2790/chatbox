<?php
date_default_timezone_set('Asia/Jakarta');
$file = 'users.json';

function getUserIP() {
    return $_SERVER['HTTP_CLIENT_IP'] ?? 
           $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
           $_SERVER['REMOTE_ADDR'];
}

$ip = getUserIP();

// Ambil dua bagian terakhir dari IP
$parts = explode('.', $ip);
$bagianTerakhir = array_slice($parts, -2); // ambil 2 terakhir
$user = "User-" . implode('.', $bagianTerakhir);

// Ambil data user yang sudah ada
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

// Simpan atau update waktu aktif user ini
$data[$ip] = [
    'name' => $user,
    'time' => time()
];

// Hapus user yang tidak aktif > 15 detik
foreach ($data as $key => $u) {
    if (time() - $u['time'] > 15) {
        unset($data[$key]);
    }
}

// Simpan ulang
file_put_contents($file, json_encode($data));

// Kirim daftar user ke front-end
echo json_encode(array_values($data));
