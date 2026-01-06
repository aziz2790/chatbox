<?php
date_default_timezone_set('Asia/Jakarta');

$file = 'chat.json';

if (!isset($_POST['message'])) exit;

function getUserIP() {
    return $_SERVER['HTTP_CLIENT_IP'] ?? 
           $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
           $_SERVER['REMOTE_ADDR'];
}

$ip = getUserIP();

// Ambil angka terakhir dari IP
$parts = explode('.', $ip);
$last = array_slice($parts, -2); // ambil 2 terakhir
$user = "User-" . implode('.', $last);

$message = strip_tags($_POST['message']);
$time = date("d/m H:i:s");

$newMessage = [
    'user' => $user,
    'message' => $message,
    'time' => $time
];

if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
} else {
    $data = [];
}

$data[] = $newMessage;
$data = array_slice($data, -100);
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
?>
