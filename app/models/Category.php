<?php

/**
 * Category Class - model for work with categories
 */
class Category
{

    /**
     * Add new category
     * @param string $name <p>Category name</p>
     * @param integer $sortOrder <p>Sort number</p>
     * @param integer $status <p>Status</p> (0 - inactive, 1 - active)
     * @return boolean <p>Method exec result</p>
     */
    public static function createCategory($name, $sortOrder, $status)
    {
        // connect to Db
        $db = Db::createConnection();

        // db query
        $sqlQuery = '
                    INSERT INTO category 
                        (name, sort_order, status) 
                    VALUES (:name, :sort_order, :status)
                    ';

        // prepare and exec sql query
        $result = $db->prepare($sqlQuery);

        $result->bindParam(':name',       $name,      PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
        $result->bindParam(':status',     $status,    PDO::PARAM_INT);

        return $result->execute();
    }

    /**
     * Edit category with specified id
     * @param integer $id <p>Categort id</p>
     * @param string $name <p>Category name</p>
     * @param integer $sortOrder <p>Sort number</p>
     * @param integer $status <p>Status</p> (0 - inactive, 1 - active)
     * @return boolean <p>Method exec result</p>
     */
    public static function updateCategoryById($id, $name, $sortOrder, $status)
    {
        $db = Db::createConnection();

        $sqlQuery = "
                    UPDATE category
                    SET 
                        name       = :name, 
                        sort_order = :sort_order, 
                        status     = :status
                    WHERE id = :id";

        $result = $db->prepare($sqlQuery);

        $result->bindParam(':id',         $id,        PDO::PARAM_INT);
        $result->bindParam(':name',       $name,      PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sortOrder, PDO::PARAM_INT);
        $result->bindParam(':status',     $status,    PDO::PARAM_INT);

        return $result->execute();
    }

    /**
     * Delete category with specified id
     * @param integer $id
     * @return boolean <p>Method exec result</p>
     */
    public static function deleteCategoryById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    DELETE FROM category 
                    WHERE id = :id
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }    

    /**
     * Returns categories array for main site
     * @return array <p>Categories info array</p>
     */
    public static function getCategoriesList()
    {
        $db = Db::createConnection();

        $result = $db->query('
                            SELECT id, name 
                            FROM category 
                            WHERE status = "1" 
                            ORDER BY sort_order, name ASC
                            ');

        $i = 0;
        $categoryList = array();
        while ($row = $result->fetch()) {
            $categoryList[$i]['id']   = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Returns category array in adminpanel (active and inactive categories all included)
     * @return array <p>Categories info array</p>
     */
    public static function getCategoriesListAdmin()
    {
        $db = Db::createConnection();

        $result = $db->query('
                            SELECT id, name, sort_order, status 
                            FROM category 
                            ORDER BY sort_order ASC
                            ');

        $categoryList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id']         = $row['id'];
            $categoryList[$i]['name']       = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status']     = $row['status'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Returns visibility status text message for category, 0 - hidden, 1 - visible
     * @param integer $status <p>Status</p>
     * @return string <p>Category visibility status text message</p>
     */
    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
                return 'Visible';
                break;
            case '0':
                return 'Hidden';
                break;
        }
    }

    /**
     * Returns category with specified id
     * @param integer $id <p>Category id</p>
     * @return array <p>Categories info array</p>
     */
    public static function getCategoryById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
                    SELECT * 
                    FROM category 
                    WHERE id = :id
                    ';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $result->execute();

        return $result->fetch();
    }

}