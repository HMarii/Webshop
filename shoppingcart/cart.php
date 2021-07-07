<?php

// A kosár oldal, amely tartalmazza az összes terméket, amelyeket a felhasználó hozzáadott. Láthatjuk a végösszeget is.
// Ha a felh. ráklikkel a "Kosárba" gombra a "Termékek" oldalon, akkor ellenőrízhetjük a form adatokat

if(isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
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
}
    // ELtávolítjuk a terméket a kosárból, megnézzük, hogy benne van-e az URL-ben a "remove" paraméter, ez a termék ID-je, megnézzük, hogy szám-e és, hogy a kosárban van-e
    if(isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart']['remove'])) {
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

//Megnézzük a SESSION változót, hogy vannak-e a kosárban termékek
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0;

// Ha vannak termékek a kosárban
if($products_in_cart) {
    // Vannak termékek a kosárban, szóval le kell kérnünk őket a DB-ből
    // A termékeket a kosárban kérdőjeles string tömbbé kell alakítani az SQL utasítás miatt (?, ?)
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE ID in (' . $array_to_question_marks . ')');
    // Csak a tömbnek a kulcsai kellenek, nem az értékei, mivel a kulcsok a termékeknek az azonosítója
    $stmt->execute(array_keys($products_in_cart));
    // Fetcheljük a termékeket a DB-ből és visszaadjuk őket egy tömbben
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Kiszámítjuk a végösszeget
    foreach($products as $product) {
        $subtotal += $product['price'] * (int)$products_in_cart[$product['id']];
    }
}
?>

<?=template_header('Cart')?>

<div class="cart content-wrapper">
    <h1>Kosár</h1>
    <form action="index.php?page=cart" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Termék</td>
                    <td>Ár</td>
                    <td>Mennyiség</td>
                    <td>Végösszeg</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Nincs termék a kosaradban!</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <a href="index.php?page=product&id=<?=$product['id']?>">
                            <img src="imgs/<?=$product['img']?>" width="50" height="50" alt="<?=$product['name']?>">
                        </a>
                    </td>
                    <td>
                        <a href="index.php?page=product&id=<?=$product['id']?>"><?=$product['name']?></a>
                        <br>
                        <a href="index.php?page=cart&remove=<?=$product['id']?>" class="remove">Törlés</a>
                    </td>
                    <td class="price"><?=$product['price']?> HUF</td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$products_in_cart[$product['id']]?>" min="1" max="<?=$product['quantity']?>" placeholder="Mennyiség" required>
                    </td>
                    <td class="price"><?=$product['price'] * $products_in_cart[$product['id']]?> HUF</td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Végösszeg</span>
            <span class="price"><?=$subtotal?> HUF</span>
        </div>
        <div class="buttons">
            <input type="submit" value="Frissítés" name="update">
            <input type="submit" value="Rendelés" name="placeorder">
        </div>
    </form>
</div>

<?=template_footer()?>