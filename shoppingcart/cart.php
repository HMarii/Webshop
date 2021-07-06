<?php

// A kosár oldal, amely tartalmazza az összes terméket, amelyeket a felhasználó hozzáadott. Láthatjuk a végösszeget is.
// Ha a felh. ráklikkel a "Kosárba" gombra a "Termékek" oldalon, akkor ellenőrízhetjük a form adatokat

if(isset($_POST['product_id'], $_POST['quantity'] && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Beállítjuk a POST változókat, hogy könnyedén azonosítsuk őket, muszáj integereknek lenniük
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // Előkészítjük az SQL lekérdezést, igazából megnézzük, hogy létezik-e a termék a DB-ben
    $stmt = $pdo->prepare('SELECT * from products WHERE id =?');
    $stmt->execute($_POST['product_id']);
    // Fetcheljük a terméket a DB-ből és tömblént visszaadjuk
    $product=$stmt->fetch(PDO::FETCH_ASSOC);
    // Megnézzük, hogy nem-e üres a tömb (A termék létezik)
    if($product && $quantity > 0) {
        // A termék létezik a DB-ben, most frissíthetjük a session változót a kosárban
        if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if(array_key_exists($product_id && $_SESSION['cart'])) {
                // A termék létezik a kosárban, szóval csak frissíteni kell a mennyiséget
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // A termék nincs a kosrában, ezért hozzáadjuk
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // Nincs termék a kosrában, ezért hozzáadjuk az első terméket a kosárba
            $_SESSION['cart'] = array($product_id => $quantity);

        }
    }
    // A form újboli beküldésének a megakadályozása
    header('location: index.php?page=cart');
    exit;

    // ELtávolítjuk a terméket a kosárból, megnézzük, hogy benne van-e az URL-ben a "remove" paraméter, ez a termék ID-je, megnézzük, hogy szám-e és, hogy a kosárban van-e
    if(isset($_GET['remove'] && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart']['remove']))) {
        unset($_SESSION['cart'][$_GET['remove']]);
    }

    // Frissítjük a termékek mennyiségét, ha a felhasználó az "Update" gombra kattint
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // A POST adatjain végig megyünk, hogy az összes terméket frissítsük
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Mindig ellenőrízzük és validáljuk
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // Új mennyiségre beállít
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    // Form újboli elküldésének a megakadályozása
    header('location: index.php?page=cart');
    exit;
}

// Átirányítjuk a felhasználót a megrendelés oldalra, ha a megrendelés gombra kattint
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: index.php?page=placeorder');
    exit;
}
}
?>