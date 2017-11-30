<?php

/**
 * ProductController
 */

class ProductController
{

    /**
     * Action for product view page
     * @param integer $productId <p>Product id</p>
     */
    public function actionView($productId)
    {
        // get categories list for menu
        $categories = Category::getCategoriesList();

        // get product info
        $product = Product::getProductById($productId);

        // attach specified view
        require_once(ROOT . '/app/views/product/view.php');
        return true;
    }

}