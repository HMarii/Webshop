<?php
// Ha a felhasználó a kosárba gombra kattint, akkor ellenőrízzük a FORM adatokat
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Beállítjuk a POST változókat, muszáj inteknek lenniük
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // ELőkészítjük az SQL utasítást, ezzel azt is megnézzük, hogy benne van-e a termék a DB-ben
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_POST['product_id']]);
    //Fetcheljük a terméket a DB-ből és visszaadjuk tömbként
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Megnézzük, hogy a termék létezik-e (A tömb nem üres)
    if ($product && $quantity > 0) {
        // A termék létezik, létrehozhatjuk/frissíthetjük a SESSION['cart'] változót
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // Termék létezik = Csak frissítjük a mennyiséget
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Termék nincs benne a kosárban, hozzáadjuk
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // Nincs termék a kosárban, az első terméket hozzárendeljük a kosárhoz
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    // Megakadályozzuk a form újraküldését
    header('location: index.php?page=cart');
    exit;
}

// Töröljük a terméket a kosárból, megnézzük, hogy a 'remove' paraméter az URL-ben van, mivel ez a termék ID-je, azt is megnézzük, hogy a termék a kosárban van-e, létezik-e
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // Remove the product from the shopping cart
    unset($_SESSION['cart'][$_GET['remove']]);
}

// Frissítjük a termék mennyiségét a kosárban, ha a frissít gombra kattint, azt is megnézzük, hogy a kosrában van-e a termék
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // Végigmegyünk a POST adatokon, ezzel az összes terméket frissíteni tudjuk, amelyek a kosárban vannak
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Ellenőrzés és validáció (Ezt mindig végezzük el)
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // Frissítjük az új mennyiségre
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    // Form újboli beküldésének a megakadályozása
    header('location: index.php?page=cart');
    exit;
}

// Átirányítjuk a felhasználót a megrendelés oldalra, ha a megrendelés gombra kattint és, ha van-e termék a kosárban
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: index.php?page=placeorder');
    exit;
}

// Megnézzük, hogy van-e termék a kosárban ($_SESSION['cart']-ban)
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
// Ha van termék
if ($products_in_cart) {
    // Le kell kérdeznünk a termékeket a DB-ből
    // A termékeket a kosárban kérdőjeles string tömbbé kell alakítani az SQL-es utasítás miatt 
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $array_to_question_marks . ')');
    // Csak a tömbnek a kulcsai kellenek, mivel ezek a termékeknek az azonosítói
    $stmt->execute(array_keys($products_in_cart));
    // Fetcheljük a termékeket a DB-ből és visszaadjuk ezeket tömbként
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Kiszámítjuk a végösszeget
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
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
                    <td>Összesen</td>
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