<?php
// Ez a fő template, alap routing is van benne
session_start();
// Funkciók igénybe vétele és a db-hez csatlakozás PDO MySQL-el
require_once 'functions.php';


// Az oldal alapból a home-ra (home.php) van beállítva, szóval ezt az oldalt fogják először látni

$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';

// Include-oljuk és megjelenítjük az oldalt

require_once $page . '.php';

?>