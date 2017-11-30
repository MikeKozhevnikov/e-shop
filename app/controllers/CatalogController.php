<?php

/**
 * CatalogController
 * Controller using for work with products catalog
 */
class CatalogController
{

    /**
     * Action for catalog page
     */
    public function actionIndex()
    {
        $categories = Category::getCategoriesList();

        $latestProducts = Product::getLatestProducts(12);

        // attach specified view
        require_once(ROOT . '/app/views/catalog/index.php');
        return true;
    }

    /**
     * Action for products categories page
     */
    public function actionCategory($categoryId, $page = 1)
    {
        // get categories list for menu
        $categories = Category::getCategoriesList();

        // get products list  in specified category
        $categoryProducts = Product::getProductsListByCategory($categoryId, $page);

        // get total count of products 
        $total = Product::getTotalProductsInCategory($categoryId);

        // create padignation
        $pagination = new Pagination($total, $page, Product::SHOW_BY_DEFAULT, 'page-');

        // attach specified view
        require_once(ROOT . '/app/views/catalog/category.php');
        return true;
    }

}