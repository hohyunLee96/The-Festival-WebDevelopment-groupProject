<?php

class ShoppingCartController
{
    public function test(){
        $shoppingCartService = new ShoppingCartService();
        $shoppingCartService->test();
        require __DIR__ . '/../../views/ShoppingCart/ShoppingCart.php';
    }
}