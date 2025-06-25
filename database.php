<?php
$host = 'localhost';
$dbname = 'urls';
$user = 'postgres';
$password = '********';

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}

