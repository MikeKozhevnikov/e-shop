<?php

/**
 * Product Class - model for work with goods (products)
 */
class Product
{
    // default products per page
    const SHOW_BY_DEFAULT = 6;

    /**
     * Create new product
     * @param array $options <p>Product info array</p>
     * @return integer <p>Inserted DB record id</p>
     */
    public static function createProduct($options)
    {
        // connect to Db
        $db = Db::createConnection();

        // db query
        $sqlQuery = '
                    INSERT INTO product 
                        (name, code, price, category_id, brand, availability, 
                        description, is_new, is_recommended, status)
                    VALUES 
                        (:name, :code, :price, :category_id, :brand, 
                        :availability, :description, :is_new, :is_recommended, :status)
                    ';

        // prepare and exec sql query
        $result = $db->prepare($sqlQuery);
        
        $result->bindParam(':name',           $options['name'],           PDO::PARAM_STR);
        $result->bindParam(':code',           $options['code'],           PDO::PARAM_STR);
        $result->bindParam(':price',          $options['price'],          PDO::PARAM_STR);
        $result->bindParam(':category_id',    $options['category_id'],    PDO::PARAM_INT);
        $result->bindParam(':brand',          $options['brand'],          PDO::PARAM_STR);
        $result->bindParam(':availability',   $options['availability'],   PDO::PARAM_INT);
        $result->bindParam(':description',    $options['description'],    PDO::PARAM_STR);
        $result->bindParam(':is_new',         $options['is_new'],         PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status',         $options['status'],         PDO::PARAM_INT);

        if ($result->execute())
            // if query is ok - return inserted DB record id
            return $db->lastInsertId();

        return 0;
    }

    /**
     * Delete product with specified id
     * @param integer $id <p>Product id</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function deleteProductById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    DELETE FROM product 
                    WHERE id = :id
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Edit product with specified id
     * @param integer $id <p>Product id</p>
     * @param array $options <p>Product info array</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function updateProductById($id, $options)
    {
        $db = Db::createConnection();

        $sqlQuery = "
                    UPDATE product
                    SET 
                        name           = :name, 
                        code           = :code, 
                        price          = :price, 
                        category_id    = :category_id, 
                        brand          = :brand, 
                        availability   = :availability, 
                        description    = :description, 
                        is_new         = :is_new, 
                        is_recommended = :is_recommended, 
                        status         = :status
                    WHERE id = :id";

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id',             $id,                        PDO::PARAM_INT);
        $result->bindParam(':name',           $options['name'],           PDO::PARAM_STR);
        $result->bindParam(':code',           $options['code'],           PDO::PARAM_STR);
        $result->bindParam(':price',          $options['price'],          PDO::PARAM_STR);
        $result->bindParam(':category_id',    $options['category_id'],    PDO::PARAM_INT);
        $result->bindParam(':brand',          $options['brand'],          PDO::PARAM_STR);
        $result->bindParam(':availability',   $options['availability'],   PDO::PARAM_INT);
        $result->bindParam(':description',    $options['description'],    PDO::PARAM_STR);
        $result->bindParam(':is_new',         $options['is_new'],         PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status',         $options['status'],         PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Returns list of products
     * @return array <p>Products array</p>
     */
    public static function getProductsList()
    {
        $db = Db::createConnection();

        $result = $db->query('
                             SELECT id, name, price, code 
                             FROM product 
                             ORDER BY id ASC
                             ');

        $productsList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id']    = $row['id'];
            $productsList[$i]['name']  = $row['name'];
            $productsList[$i]['code']  = $row['code'];
            $productsList[$i]['price'] = $row['price'];
            $i++;
        }
        return $productsList;
    }

    /**
     * Returns count of products in specified categoryId
     * @param integer $categoryId
     * @return integer
     */
    public static function getTotalProductsInCategory($categoryId)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    SELECT count(id) AS count 
                    FROM product 
                    WHERE status="1" AND category_id = :category_id';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);

        $result->execute();

        $row = $result->fetch();
        return $row['count'];
    }

    /**
     * Returns last produtcs array
     * @param type $count [optional] <p>Count</p>
     * @param type $page [optional] <p>Current page number</p>
     * @return array <p>Array with products</p>
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    SELECT id, name, price, is_new 
                    FROM product
                    WHERE status = "1" 
                    ORDER BY category_id, id DESC
                    LIMIT :count
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':count', $count, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        $result->execute();

        $i = 0;
        $productsList = array();
        while ($row = $result->fetch()) {
            $productsList[$i]['id']     = $row['id'];
            $productsList[$i]['name']   = $row['name'];
            $productsList[$i]['price']  = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productsList;
    }

    /**
     * Returns list of products in specified categoryId wirh page offset
     * @param type $categoryId <p>Category id</p>
     * @param type $page [optional] <p>Page number</p>
     * @return type <p>Products info array</p>
     */
    public static function getProductsListByCategory($categoryId, $page = 1)
    {
        $limit = Product::SHOW_BY_DEFAULT;
        // page offset for sqlQUery
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $db = Db::createConnection();

        $sqlQuery = '
                    SELECT id, name, price, is_new 
                    FROM product 
                    WHERE status = 1 AND category_id = :category_id 
                    ORDER BY id ASC 
                    LIMIT :limit OFFSET :offset
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam(':limit',       $limit,      PDO::PARAM_INT);
        $result->bindParam(':offset',      $offset,     PDO::PARAM_INT);

        $result->execute();

        $i = 0;
        $products = array();
        while ($row = $result->fetch()) {
            $products[$i]['id']     = $row['id'];
            $products[$i]['name']   = $row['name'];
            $products[$i]['price']  = $row['price'];
            $products[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $products;
    }

    /**
     * Returns product info with specified id
     * @param integer $id <p>Product id</p>
     * @return array <p>Product info array</p>
     */
    public static function getProductById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    SELECT * 
                    FROM product 
                    WHERE id = :id
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $result->execute();

        return $result->fetch();
    }

    
    /**
     * Returns list of products with specified id's
     * @param array $idsArray <p>Id's array</p>
     * @return array <p>Product info array</p>
     */
    public static function getProdustsByIds($idsArray)
    {

        $db = Db::createConnection();

        $idsString = implode(',', $idsArray);

        $sqlQuery = "
                    SELECT * 
                    FROM product 
                    WHERE status='1' AND id IN ($idsString)";

        $result = $db->query($sqlQuery);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        $products = array();
        while ($row = $result->fetch()) {
            $products[$i]['id']    = $row['id'];
            $products[$i]['code']  = $row['code'];
            $products[$i]['name']  = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }
        return $products;
    }

    /**
     * Returns list of recommend products
     * @return array <p>Products info array</p>
     */
    public static function getRecommendedProducts()
    {
        $db = Db::createConnection();

        $result = $db->query('
                            SELECT id, name, price, is_new 
                            FROM product 
                            WHERE status = "1" AND is_recommended = "1" 
                            ORDER BY id DESC
                            ');
        $i = 0;
        $productsList = array();
        while ($row = $result->fetch()) {
            $productsList[$i]['id']     = $row['id'];
            $productsList[$i]['name']   = $row['name'];
            $productsList[$i]['price']  = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $productsList;
    }


    /**
     * Product availability check, 1 - is available, 0 -is'nt
     * @param integer $availability <p>Product status</p>
     * @return string <p>Availability text message</p>
     */
    public static function getAvailabilityText($availability)
    {
        switch ($availability) {
            case '1':
                return 'В наличии';
                break;
            case '0':
                return 'Под заказ';
                break;
        }
    }

    /**
     * Returns file path for product small image
     * @param integer $id
     * @return string <p>Image path</p>
     */
    public static function getImage($id)
    {
        // if there's no image for product
        $noImage = 'no-image.jpg';

        // images path
        $path = '/images/products/';

        // build path to product image
        $pathToProductImage = $path . $id . '.jpg';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].$pathToProductImage))
            // if image file exists - return filepath
            return $pathToProductImage;

        // if image file not exists - return no-image image filepath
        return $path . $noImage;
    }

    /**
     * Returns file path for product big image
     * @param integer $id
     * @return string <p>Image path</p>
     */
    public static function getImageBig($id)
    {
        $noImage = 'no-image.jpg';

        $path = '/images/products/';

        $pathToProductImage = $path . $id . '_big.jpg';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].$pathToProductImage))
            return $pathToProductImage;

        return $path . $noImage;
    }    


    /**
     * Returns file path for product medium image
     * @param integer $id
     * @return string <p>Image path</p>
     */
    public static function getImageMedium($id)
    {

        $noImage = 'no-image.jpg';

        $path = '/images/products/';

        $pathToProductImage = $path . $id . '_medium.jpg';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].$pathToProductImage))
            return $pathToProductImage;


        return $path . $noImage;
    }      

}