<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use CodeBase\ErrorHandler as EX;
use CodeBase\SessionManager as S;

class ShoppingCart
{
    /**
     * @return void
     */
    public static function emptyCart()
    {
        S::removeItem('codebasexcart');
    }

    /**
     * @return void
     */
    public static function startCart()
    {
        S::setItem('codebasexcart', []);
    }

    /**
     * @param array $itemDetails
     * @return void
     */
    public static function addItemToCart(array $itemDetails)
    {
        $cart = self::getCart();
        for ($i = 0; $i < count($cart); $i++) {
            if ($cart[$i]['itemId'] == $itemDetails['id']) {
                $_SESSION['codebasexcart'][$i]['qty'] += 1;
                return;
            }
        }
        $_SESSION['codebasexcart'][] = $itemDetails;
    }

    /**
     * @return void
     */
    public static function getCart()
    {
        return S::getItem('codebasexcart');
    }

    /**
     * @param int $itemId
     * @param int $qty
     * @return void
     */
    public static function removeItemFromCart(int $itemId, int $qty = 0)
    {
        $cart = self::getCart();
    }

    /**
     * @return float
     */
    public static function calculateTotal() : float
    {
        $cart = self::getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['qty'] * $item['price'];
        }
        return $total;
    }

    /**
     * @param array $itemDetails
     * @return void
     */
    private static function validateItemDetails(array $itemDetails)
    {
    }
}
