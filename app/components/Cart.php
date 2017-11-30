<?php

/**
 * Cart Class - using for work with cart on site
 */
class Cart
{
    /**
     * Add product to cart
     * @param int $id <p>Product id</p>
     * @return integer <p>Product in cart count</p>
     */
    public static function addProduct($id)
    {
        $id = intval($id);

        $productsInCart = array();

        // if cart isn't empty, fill cart array with data from session
        if (isset($_SESSION['products']))
            $productsInCart = $_SESSION['products'];

        // product in cart availability check 
        if (array_key_exists($id, $productsInCart)) {
            // if product is already in cart - count++
            $productsInCart[$id] ++;
        } else {
            // if product isn't in cart - add product with count =1
            $productsInCart[$id] = 1;
        }

        // save products array in session
        $_SESSION['products'] = $productsInCart;

        // return products in cart count
        return self::countItems();
    }

    /**
     * Get total products count in cart
     * @return int <p>Products in cart count</p>
     */
    public static function countItems()
    {
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            return 0;
        }
    }

    /**
     * Returns array of products id's and product count in cart, if array in NULL - return false
     * @return mixed: boolean or array
     */
    public static function getProducts()
    {
        if (isset($_SESSION['products']))
            return $_SESSION['products'];

        return false;
    }

    /**
     * Get total cost of products in cart
     * @param array $products <p>Products array</p>
     * @return integer <p>Total cost</p>
     */
    public static function getTotalCost($products)
    {
        // get array of product id's and cout
        $productsInCart = self::getProducts();

        // compute total cost
        $total = 0;
        if ($productsInCart) {
            foreach ($products as $item)
                $total += $item['price'] * $productsInCart[$item['id']];
        }

        return $total;
    }

    /**
     * Clear cart
     */
    public static function clear()
    {
        if (isset($_SESSION['products']))
            unset($_SESSION['products']);
    }

    /**
     * Delete product from cart with specified id
     * @param integer $id <p>Product id</p>
     */
    public static function deleteProduct($id)
    {
        // get product in cart array
        $productsInCart = self::getProducts();

        // delete specified id from cart
        unset($productsInCart[$id]);

        // save product array in session
        $_SESSION['products'] = $productsInCart;
    }

}