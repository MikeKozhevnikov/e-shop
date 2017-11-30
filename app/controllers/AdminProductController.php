<?php

/**
 * AdminProductController
 * Using for products management in AdminPanel
 */
class AdminProductController extends AdminBase
{

    /**
     * Action for products management main page in AdminPanel
     */
    public function actionIndex()
    {
        // authorization check
        self::checkAdmin();

        // get products list
        $productsList = Product::getProductsList();

        // attach specified view
        require_once(ROOT . '/app/views/admin_product/index.php');
        return true;
    }

    /**
     * Action for add product page in AdminPanel
     */
    public function actionCreate()
    {

        self::checkAdmin();

        // get categories list
        $categoriesList = Category::getCategoriesListAdmin();

        // new product form processing
        if (isset($_POST['submit'])) {

            $options['name'] 		   = $_POST['name'];
            $options['code'] 		   = $_POST['code'];
            $options['price'] 		   = $_POST['price'];
            $options['category_id']    = $_POST['category_id'];
            $options['brand'] 		   = $_POST['brand'];
            $options['availability']   = $_POST['availability'];
            $options['description']    = $_POST['description'];
            $options['is_new'] 		   = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] 		   = $_POST['status'];

            $errors = false;

            // form main fields validating
            if (!isset($options['name']) || empty($options['name']))
                $errors[] = 'Введите название товара';

            if (!isset($options['code']) || empty($options['code']))
                $errors[] = 'Введите код товара';

            if (!isset($options['price']) || empty($options['price']))
                $errors[] = 'Введите цену товара';

            if (!isset($options['category_id']) || empty($options['category_id']))
                $errors[] = 'Выберете категорию товара';

            if ($errors == false) {

                $id = Product::createProduct($options);

                // product is saved - check uploaded images
                if ($id) {
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        // rename image and move to img folder
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                };

                header("Location: /admin/product");
            }
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_product/create.php');
        return true;
    }

    /**
     * Action for edit product page in AdminPanel
     */
    public function actionUpdate($id)
    {
        self::checkAdmin();

        $categoriesList = Category::getCategoriesListAdmin();

        // get product info
        $product = Product::getProductById($id);

        // form processing
        if (isset($_POST['submit'])) {
            $options['name'] 		   = $_POST['name'];
            $options['code'] 		   = $_POST['code'];
            $options['price'] 		   = $_POST['price'];
            $options['category_id']    = $_POST['category_id'];
            $options['brand'] 		   = $_POST['brand'];
            $options['availability']   = $_POST['availability'];
            $options['description']    = $_POST['description'];
            $options['is_new'] 		   = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] 		   = $_POST['status'];

            if (Product::updateProductById($id, $options)) {

                // check for uploaded image
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                   move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            }

            header("Location: /admin/product");
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_product/update.php');
        return true;
    }

    /**
     * Action for delete product page in AdminPanel
     */
    public function actionDelete($id)
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {

            Product::deleteProductById($id);

            header("Location: /admin/product");
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_product/delete.php');
        return true;
    }

}
