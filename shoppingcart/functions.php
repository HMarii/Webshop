<?php
// Tartalmazza az összes funkciót, ami kell ehhez a rendszerhez (template fejléc, template lábléc, db csatlakozás)
include 'dbconfig.php';
//Template fejléc
$useremail = "";
function template_header($title) {

    // Lekérjük a kosárban levő elemek számát, ez lesz a fejlécben
    $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;   
    if(isset($_SESSION['user_email'])) {
        $useremail = $_SESSION['user_email'];
    }
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
            
            
            
            <nav>";
            
            if(isset($useremail)) {
                echo '<a href="#">'.$useremail.'</a>';
            }
            echo "
                <a href='index.php'>Főoldal</a>
                ";
                if(!isset($useremail)) {
                echo "<a href='index.php?page=loginForm'>Bejelentkezés</a>
                <a href='index.php?page=registerForm'>Regisztráció</a>";
                }
                echo "
                <a href='index.php?page=products'>Termékek</a>
                ";
                if(isset($useremail)) {
                echo '<a href="index.php?page=logout">Kijelentkezés</a>';
                }
                echo "
            </nav>
            
            <div class='link-icons'>";
            if(isset($useremail)) {
                echo "
                <a href='index.php?page=cart'>
                    <i class='fas fa-shopping-cart'></i>
                    <span>$num_items_in_cart</span>
                </a>
                ";
            }
            echo "
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