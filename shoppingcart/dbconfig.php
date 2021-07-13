<?php
// DB-hez csatlakozás PDO-s megoldással

    // MySQL-es adatok felvétele
    // 1. Megoldás
    /* $DATABASE_HOST = "localhost";
    $DATABASE_NAME = "webshop";
    $DATABASE_USER = "root";
    $DATABASE_PASS = "root";
    try {
         $pdo = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $exception) {
        // Ha hiba lép fel, akkor leállítjuk a scriptet és kiírjuk a hibaüzenetet
        exit("Hiba történt az adatbázishoz való csatlakozás során!");
    } */

    // 2. Megoldás

    // Kell: MySQL felh. fiók, jelszó, hosztállomás neve, DB neve

    // Felh. név
    define('MYSQL_USER', 'root');
    // Jelszó
    define('MYSQL_PASSWORD', 'root');
    // Szerver, amin a MySQL hosztolva van
    define('MYSQL_HOST', 'localhost');
    // Adatbázis neve
    define('MYSQL_DATABASE', 'webshop');

    // PDO beállítások / konfig

    $pdoOptions = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Hibák lekezelése
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci' ", // UTF8

    );

    // Csatlakozás a MySQL-hez és a PDO objektum inicializálása
    // Ez kelleni fog majd a lekérdezésekhez
    $pdo = new PDO(
        "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE, MYSQL_USER, MYSQL_PASSWORD, $pdoOptions
    );

?>