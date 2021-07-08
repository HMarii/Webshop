<?php

// Segítségével lekérjük az összes terméket paginációval (pagination)

// Mennyi termék jelenjen meg egy oldalon
$num_products_on_each_page = 4;

// A jelenlegi oldal, amely az URL-ben így jelenik meg: index.php?page=products&p=1, index.php?page=products&p=2, stb...
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

// Kiválasszuk a termékeket a dátum alapján
$stmt = $pdo->prepare('SELECT * from products ORDER BY created_at LIMIT ?,?');
// bindValue-val használhatunk inteket SQL lekérdezésekben
$stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
$stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch-eljük a termékeket a DB-ben és egy tömbben visszaadjuk az eredményt
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lekérjük az összes létező terméket
$total_products = $pdo->query('SELECT * from products')->rowCount();
?>

<?=template_header('Products')?>

<div class="products content-wrapper">
    <h1>Termékek</h1>
    <p><?=$total_products?> Termék</p>
    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="product">
            <img src="imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['name']?>">
            <span class="name"><?=$product['name']?></span>
            <span class="price">
                <?=$product['price']?> HUF
                <?php if ($product['retail_price'] > 0): ?>
                <span class="rrp"><?=$product['retail_price']?> HUF</span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="index.php?page=products&p=<?=$current_page-1?>">Előző</a>
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
        <a href="index.php?page=products&p=<?=$current_page+1?>">Következő</a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>