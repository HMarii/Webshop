<?php

// Ez fogja megjeleníteni a terméket (GET request-től függően), és tartalmaz egy form-ot, amely segítségével a felhasználó megváltoztathatja a mennyiséget és hozzáadhatja ezt a kosárba
// Ellenőrízzük, hogy az id paraméter szerepel-e az URL-ben
if(isset($_GET['id'])) {
    // Preparel-jük a kérést és lefuttatjuk, SQL Injekció megakadályozása (1. Prepare 2.Execute)
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id=?');
    $stmt->execute([$_GET['id']]);
    // Fetcheljük a terméket db-ből és ezt egy tömbbe eltároljuk
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    //Ellenőrízzük, hogy a termék létezik-e (A tömb nem üres)
    if(!$product) {
        // Egyszerű hibaüzenet, ha a terméknek nincs id-je
        exit('A termék nem létezik');
    }
 } else {
        // Ha az id nem volt megadva
        exit('A termék nem létezik');
    }
?>

<?=template_header("Product")?>

<div class="product content-wrapper">
    <img src="imgs/<?=$product['img']?>" width="500" height="500" alt="<?=$product['name']?>"/>
    <div>
        <h1 class="name"><?=$product['name']?></h1>
            <span class="price">
                <?=$product['price']?> HUF
                <?php if ($product['retail_price'] > 0) : ?>
                    <span class="rrp"><?=$product['retail_price']?> HUF</span>
                <?php endif; ?>
            </span>
            <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" placeholder="Mennyiség" required>
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Kosárba">
        </form>
        <div class="description">
            <?=$product['description']?>
        </div>

    </div>

</div>
<?=template_footer()?>
