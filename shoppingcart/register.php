<?php
        session_start();

    require 'dbconfig.php';
    // Ha a "Regisztráció" gombra kattintunk
    if(isset($_POST['register'])) {
        // Lekérjük a regisztrációs form adatokat (email, jelszó, teljes név, lakcím);

        $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
        $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $fullname = !empty($_POST['fullname']) ? trim($_POST['fullname']) : null;
        $address = !empty($_POST['address']) ? trim($_POST['address']) : null;

        // Check, hogy az email létezik-e

        $sql = "SELECT count(email) as num FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        // Bindeljük a megadott email címet a lekérdezéshez

        $stmt->bindValue(':email', $email);

        // Végrehajt
        $stmt->execute();

        // Fetch

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Email foglalt, a regisztráció sikertelen.
        if($row['num'] > 0) {
            echo "Ez az email cím már létezik! Kérlek egy másikat adj meg!";
            header("refresh:5;url=register.php");
            exit();
        }

        // Jelszó hashelése
        // BCRYPT, mert biztonságosabb, mint az md5, sha1
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));
        // MD5 elég, mert a megerősítésnél lényegtelen a biztonság és gyorsabb is
        $confirmHash = md5( rand(0,1000) );
        // Beszúró lekérdezés előkészítése
        $sql = "INSERT INTO users (email, password, fullname, address, hash) VALUES(:email, :password, :fullname, :address, :hash)";
        $stmt = $pdo->prepare($sql);

        //Bindelés

        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", $passwordHash);
        $stmt->bindValue(":fullname", $fullname);
        $stmt->bindValue(":address", $address);
        $stmt->bindValue(":hash", $confirmHash);

        //Lekérdezés lefuttatása 

        $result = $stmt->execute();

        if($result) {
            echo "Sikeres regisztráció! Kérlek erősítsd meg a fiókodat az aktivációs linkre kattintva, amit az email fiókodban találsz meg!.";

            // Elküldjük az aktivációs linket a megadott email címre
            $to = $email;
            $subject = 'Regisztrálás';

            header('Content-Type: text/html; charset=utf-8'); 

            $message = '
                Köszönjük, hogy regisztráltál!
                A fiókodat létrehoztuk, a link kattintása után be is tudsz jelentkezni!
                -----------------------------------------------------------------------
                Bejelentkezési adatok:
                Email cím: '.$email.'
                -----------------------------------------------------------------------
                  
                Kérlek kattints az alábbi linkre a fiókod aktiválásához!
                http:////localhost/shop/Webshop/shoppingcart/verify.php?email='.$email.'&hash='.$confirmHash.'
  
                ';

                $headers = 'Küldte: StoneNailShop@gmail.com' . "\r\n";
                mail(utf8_decode($to), utf8_decode($subject), utf8_decode($message), utf8_decode($headers)); // Email elküldése
            

            header("refresh:5;url=home.php");
        }

    }

?>