<?php

/**
 * SiteController 
 * Controller for main pages
 */
class SiteController
{

    /**
     * Action for Index page
     */
    public function actionIndex()
    {
        // category list for menu
        $categories = Category::getCategoriesList();

        // list of last products
        $latestProducts = Product::getLatestProducts(9);

        // list of recommend products
        $sliderProducts = Product::getRecommendedProducts();

        // attach specified view
        require_once(ROOT . '/app/views/site/index.php');
        return true;
    }

    /**
     * Action for Contact page
     */
    public function actionContact()
    {

        // contact form variables
        $userEmail        = false;
        $userMessageText  = false;
        $result           = false;

        // form processing
        if (isset($_POST['submit'])) {
            // if form was sent, get data from it
            $userEmail = $_POST['userEmail'];
            $userMessageText = $_POST['userText'];

            $errors = false;

            // field validation
            if (!User::checkEmail($userEmail))
                $errors[] = 'Некорректный Email';

            if ($errors == false) {
                $adminEmail = 'admin@localhost';
                $message = "Text: {$userMessageText}. By {$userEmail}";
                $subject = 'Message from site';
                $result = mail($adminEmail, $subject, $message);
                $result = true;
            }
        }

        // attach specified view
        require_once(ROOT . '/app/views/site/contact.php');
        return true;
    }

    /**
     * Action for About page
     */
    public function actionAbout()
    {
        // attach specified view
        require_once(ROOT . '/app/views/site/about.php');
        return true;
    }

    /**
     * Action for Payment page
     */
    public function actionPayment()
    {
        // attach specified view
        require_once(ROOT . '/app/views/site/payment.php');
        return true;
    }

    /**
     * Action for Delivery page
     */
    public function actionDelivery()
    {
        // attach specified view
        require_once(ROOT . '/app/views/site/delivery.php');
        return true;
    }        

}
