<?php

/**
 * AdminOrderController
 * Controller for orders management in AdminPanel
 */
class AdminOrderController extends AdminBase
{

    /**
     * Action for orders management main page in AdminPanel
     */
    public function actionIndex()
    {
        // authorization check
        self::checkAdmin();

        // get orders list
        $ordersList = Order::getOrdersList();

        // attach specified view
        require_once(ROOT . '/app/views/admin_order/index.php');
        return true;
    }

    /**
     * Action for edit order page
     */
    public function actionUpdate($id)
    {
        self::checkAdmin();

        $order = Order::getOrderById($id);

        if (isset($_POST['submit'])) {

            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date = $_POST['date'];
            $status = $_POST['status'];

            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);

            header("Location: /admin/order/view/$id");
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_order/update.php');
        return true;
    }

    /**
     * Action for view order page in AdminPanel
     */
    public function actionView($id)
    {
        self::checkAdmin();

        $order = Order::getOrderById($id);

        $productsQuantity = json_decode($order['products'], true);

        $productsIds = array_keys($productsQuantity);

        $products = Product::getProdustsByIds($productsIds);

        // attach specified view
        require_once(ROOT . '/app/views/admin_order/view.php');
        return true;
    }

    /**
     * Action for delete order page in AdminPanel
     */
    public function actionDelete($id)
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {
            Order::deleteOrderById($id);
            header("Location: /admin/order");
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_order/delete.php');
        return true;
    }

}