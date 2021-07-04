<?php
// Tartalmazza az összes funkciót, ami kell ehhez a rendszerhez (template fejléc, template lábléc, db csatlakozás)
function pdo_connect_mysql() {
    // MySQL-es adatok felvétele
    $DATABASE_HOST = "localhost";
    $DATABASE_NAME = "webshop";
    $DATABASE_USER = "root";
    $DATABASE_PASS = "root";

    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch(PDOException $exception) {
        // Ha hiba lép fel, akkor leállítjuk a scriptet és kiírjuk a hibaüzenetet
        exit("Hiba történt az adatbázishoz való csatlakozás során!");
    }

}
//Template fejléc

function template_header($title) {
    echo <<<EOT
    <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf8">
                <title>$title</title>
                <link href="style.css" rel="stylesheet" type="text/css">
		        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	        </head>
	    <body>
        <header>
        <div class="content-wrapper">
            <h1>Webshop</h1>
            <nav>
                <a href="index.php">Főoldal</a>
                <a href="index.php?page=products">Termékek</a>
            </nav>
            <div class="link-icons">
                <a href="index.php?page=cart">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
    </header>
    <main>
EOT;
}

function template_footer() {
    $year = date('Y');
    echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Webshop</p>
            </div>
        </footer>
        <script src="script.js"></script>
    </body>
</html>
EOT;
}

?>