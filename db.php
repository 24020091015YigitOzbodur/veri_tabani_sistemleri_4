<?php
$host = 'localhost';
$dbname = 'odev4_db';
$kullanici = 'root';
$sifre = '2331548y';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $kullanici, $sifre);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Veritabanına bağlanılamadı kanka, hata: " . $e->getMessage();
}
?>