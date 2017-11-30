<?php

/**
 * Order Class - model for work with orders
 */
class Order
{

    /**
     * Edit order with specified id
     * @param integer $id <p>Order id</p>
     * @param string $userName <p>Customer name</p>
     * @param string $userPhone <p>Customer phone</p>
     * @param string $userComment <p>Customer comment</p>
     * @param string $date <p>Order date</p>
     * @param integer $status <p>Order status<i>(1 - is active, 0 - is inactive</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function updateOrderById($id, $userName, $userPhone, $userComment, $date, $status)
    {
        // connect to Db
        $db = Db::createConnection();

        // db query
        $sqlQuery = "
                    UPDATE product_order
                    SET 
                        user_name = :user_name, 
                        user_phone = :user_phone, 
                        user_comment = :user_comment, 
                        date = :date, 
                        status = :status 
                    WHERE id = :id
                    ";

        // prepare and exec sql query
        $result = $db->prepare($sqlQuery);

        $result->bindParam(':id',           $id,          PDO::PARAM_INT);
        $result->bindParam(':user_name',    $userName,    PDO::PARAM_STR);
        $result->bindParam(':user_phone',   $userPhone,   PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':date',         $date,        PDO::PARAM_STR);
        $result->bindParam(':status',       $status,      PDO::PARAM_INT);

        return $result->execute();
    }


    /**
     * Create new order 
     * @param string $userName <p>Name</p>
     * @param string $userPhone <p>Phone</p>
     * @param string $userComment <p>User comment</p>
     * @param integer $userId <p>User id</p>
     * @param array $products <p>Products array</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function save($userName, $userPhone, $userComment, $userId, $products)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    INSERT INTO product_order 
                        (user_name, user_phone, user_comment, user_id, products)
                    VALUES 
                        (:user_name, :user_phone, :user_comment, :user_id, :products)
                    ';

        $products = json_encode($products);

        $result = $db->prepare($sqlQuery);

        $result->bindParam(':user_name',    $userName,    PDO::PARAM_STR);
        $result->bindParam(':user_phone',   $userPhone,   PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':user_id',      $userId,      PDO::PARAM_STR);
        $result->bindParam(':products',     $products,    PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Delete order with specified id
     * @param integer $id <p>Order id</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function deleteOrderById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    DELETE FROM product_order 
                    WHERE id = :id
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Returns order status. 1 - new order, 2 - order in proccess, 3 - send, 4 - complete
     * @param integer $status <p>Order status</p>
     * @return string <p>Order status text message</p>
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Новый заказ';
                break;
            case '2':
                return 'Обрабатывается';
                break;
            case '3':
                return 'Отправлен';
                break;
            case '4':
                return 'Выполнен';
                break;
        }
    }

    /**
     * Returns order list
     * @return array <p>Order array</p>
     */
    public static function getOrdersList()
    {
        $db = Db::createConnection();

        $result = $db->query('
                            SELECT id, user_name, user_phone, date, status 
                            FROM product_order 
                            ORDER BY id DESC
                            ');

        $ordersList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $ordersList[$i]['id']         = $row['id'];
            $ordersList[$i]['user_name']  = $row['user_name'];
            $ordersList[$i]['user_phone'] = $row['user_phone'];
            $ordersList[$i]['date']       = $row['date'];
            $ordersList[$i]['status']     = $row['status'];
            $i++;
        }
        return $ordersList;
    }

    /**
     * Returns order with specified id 
     * @param integer $id <p>Order id</p>
     * @return array <p>Order info array</p>
     */
    public static function getOrderById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    SELECT * 
                    FROM product_order 
                    WHERE id = :id
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $result->execute();

        return $result->fetch();
    }

}