<?php
// Főoldal, amely tartalmazza a megjelenítendő 4 terméket és a hozzá tartozó képet
require_once 'index.php';
$stmt = $pdo->prepare('SELECT * from products ORDER BY created_at LIMIT 4');
$stmt->execute();

$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Főoldal')?>

<div class="featured">
    <h2>StoneNailShop</h2>
    <p>Kőszegi minőségi termékek olcsón.</p>
</div>
<div class="recentlyadded content-wrapper">
    <h2>Kiemelt termékek</h2>
    <div class="products">
        <?php foreach ($recently_added_products as $product): ?>
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
</div>

<?=template_footer()?>