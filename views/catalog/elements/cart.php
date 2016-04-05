<?php

use yii\helpers\Url;

$cart_products = Yii::$app->cart->getPositions();


?>
<div class="cart-wrap">
    <p class="cart-title">Корзина</p>
    <?php if (!empty($cart_products)): ?>
        <?php foreach ($cart_products as $pos): ?>
            <div class="cart-item">
                <span class="product-name"><?= $pos->name ?></span>
                <span data-id="<?= $pos->id ?>" class="product-delete"><i class="fa fa-times"></i></span>
                <span class="product-price"><?= $pos->getCost() ?> руб</span>
                <span class="product-ammount">х<?= $pos->getQuantity() ?></span>
            </div>
        <?php endforeach; ?>

        <a href="" class="btn btn-cart">Оформить заказ</a>
        <div class="clearfix"></div>

    <?php else: ?>
        <div class="cart-item">
            Ваша корзина пуста
        </div>
    <?php endif; ?>
</div>


<div class="cart-wrap">
    <p class="description">
        Заказ по телефону
        <br>
        +7 495 995 00000
        <br>
        Адрес магазина:
        <br>
        г. Москва, Добролюбова проезд д.3
    </p>

</div>
<script>
    $('.product-delete').on('click', function () {
        var self = $(this);
        $.ajax({
            url: "/catalog/remove-from-cart",
            data: {id: self.data('id')},
            dataType: "json",
            success: function (response) {
                self.closest('.cart-item').hide();
            }
        });

    });
</script>
