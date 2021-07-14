<?php
// Tartalmazza az összes funkciót, ami kell ehhez a rendszerhez (template fejléc, template lábléc, db csatlakozás)
include 'dbconfig.php';
//Template fejléc

function template_header($title) {

    // Lekérjük a kosárban levő elemek számát, ez lesz a fejlécben
    $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;   

    echo "
    <!DOCTYPE html>
        <html>
            <head>
                <meta charset='utf8'>
                <title>$title</title>
                <link href='style.css' rel='stylesheet' type='text/css'>
		        <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.1/css/all.css'>
	        </head>
	    <body>
        <header>
        <div class='content-wrapper'>
            <h1>StoneNailShop</h1>
            
            
            <nav>
                <a href='index.php'>Főoldal</a>
                <a href='index.php?page=loginForm'>Bejelentkezés</a>
                <a href='index.php?page=registerForm'>Regisztráció</a>
                <a href='index.php?page=products'>Termékek</a>
            </nav>
            <div class='link-icons'>
                <a href='index.php?page=cart'>
                    <i class='fas fa-shopping-cart'></i>
                    <span>$num_items_in_cart</span>
                </a>
            </div>
        </div>
    </header>
    <main>
";
}

function template_footer() {
    $year = date('Y');
    echo <<<EOT
        </main>
        <footer>
            <div class='content-wrapper'>
                <p>&copy; $year, StoneNailShop</p>
            </div>
        </footer>
        <script src='script.js'></script>
    </body>
</html>
EOT;
}

?>