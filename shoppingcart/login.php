<?php 
    session_start();

    require_once 'dbconfig.php';
    // Ha a "Bejelentkezés" gombra kattint
    if(isset($_POST['login'])) {
        $emailAttempt = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

        // Lekérjük a felhasználó adatait a megadott email cím alapján
        
        $sql = "SELECT email, password, fullname, address FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        // Bindelés

        $stmt->bindValue(":email", $emailAttempt);

        // Végrehajt

        $stmt->execute();

        // Sor fetchelése

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ha a $row false (Nem létezik)

        if(!$row) {
            echo "Helytelen email cím vagy jelszó!";
        } else {
            // A fiók létezik, megnézzük, hogy a jelszó valid
            // Jelszó összehasonlítása

            $validPassword = password_verify($passwordAttempt, $row['password']);

            if($validPassword) {
                // A jelszó helyes, a belépés sikeres
                // Bejelentkezési munkamenete (login session) létrehozása

                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_time'] = time();
                header('location:home.php');
                exit();
            } else {
                // A jelszó helytelen, a belépés sikertelen

                echo "Helytelen jelszó!";
                exit();
            }
        }
    }



?>