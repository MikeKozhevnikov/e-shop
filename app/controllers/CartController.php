<?php

/**
 * CartController
 */

class CartController
{
    /**
     * Action for Cart page
     */
    public function actionIndex()
    {
        // get category list for menu
        $categories = Category::getCategoriesList();

        // get product in cart ids and count
        $productsInCart = Cart::getProducts();

        if ($productsInCart) {
            // get products in cart ids array
            $productsIds = array_keys($productsInCart);

            // get products in cart info array
            $products = Product::getProdustsByIds($productsIds);

            // get total cost
            $totalCost = Cart::getTotalCost($products);
        }

        // attach specified view
        require_once(ROOT . '/app/views/cart/index.php');
        return true;
    }

    /**
     * Action for Make Order page
     */
    public function actionCheckout()
    {
        // get products from cart      
        $productsInCart = Cart::getProducts();

        // if cart is empty - redirect to index
        if ($productsInCart == false) {
            header("Location: /");
        }

        $categories = Category::getCategoriesList();

        // get total cost
        $productsIds = array_keys($productsInCart);
        $products = Product::getProdustsByIds($productsIds);
        $totalCost = Cart::getTotalCost($products);

        $totalQuantity = Cart::countItems();

        // make order form fields
        $userName    = false;
        $userPhone   = false;
        $userComment = false;

        // order success status
        $result = false;

        if (!User::isGuest()) {
            // if user is authenificated, get user info
            $userId   = User::checkLogged();
            $user     = User::getUserById($userId);
            $userName = $user['name'];
        } else {
            $userId = false;
        }

        if (isset($_POST['submit'])) {
            $userName    = $_POST['userName'];
            $userPhone   = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            $errors = false;

            if (!User::checkName($userName)) {
                $errors[] = 'Некорректное имя, используйте более 4-х символов.';
            }
            if (!User::checkPhone($userPhone)) {
                $errors[] = 'Некорректный телефон.';
            }


            if ($errors == false) {
                // save order in Db
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                if ($result) {
                    // if order is saved - send email to admin
                    $adminEmail = 'admin@localhost';
                    $message = 'Поступил заказ от ' . $userName . ', на общую сумму: ' 
                    			. $totalCost . 'BYN. Перейти к списку заказов: <a href="http://site.com/admin/orders">Link</a>';
                    $subject = 'New order arrived!';
                    mail($adminEmail, $subject, $message);

                    // clear cart
                    Cart::clear();
                }
            }
        }

        // attach specified view
        require_once(ROOT . '/app/views/cart/checkout.php');
        return true;
    }

    /**
     * Action for add product to cart
     * @param integer $id <p>Product id</p>
     */
    public function actionAdd($id)
    {
        Cart::addProduct($id);

        // redirect to referrer page
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }

    /**
     * Action for add product to cart with AJAX
     * @param integer $id <p>Product id</p>
     */
    public function actionAddAjax($id)
    {
        // add product to cart and print products in cart count
        echo Cart::addProduct($id);
        return true;
    }
    
    /**
     * Action for delete product to cart
     * @param integer $id <p>Product id</p>
     */
    public function actionDelete($id)
    {
        Cart::deleteProduct($id);
        header("Location: /cart");
    }

}