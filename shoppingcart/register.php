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

        // Beszúró lekérdezés előkészítése
        $sql = "INSERT INTO users (email, password, fullname, address) VALUES(:email, :password, :fullname, :address)";
        $stmt = $pdo->prepare($sql);

        //Bindelés

        $stmt->bindValue(":email", $email);
        $stmt->bindValue(":password", $passwordHash);
        $stmt->bindValue(":fullname", $fullname);
        $stmt->bindValue(":address", $address);

        //Lekérdezés lefuttatása 

        $result = $stmt->execute();

        if($result) {
            echo "Sikeres regisztráció!";
            header("refresh:5;url=home.php");
        }

    }

?>