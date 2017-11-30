<?php

/**
 * AdminCategoryController
 * Controller using for category management in AdminPanel
 */
class AdminCategoryController extends AdminBase
{

    /**
     * Action for category management main page in AdminPanel
     */
    public function actionIndex()
    {
        // authorization check
        self::checkAdmin();

        $categoriesList = Category::getCategoriesListAdmin();

        // attach specified view
        require_once(ROOT . '/app/views/admin_category/index.php');
        return true;
    }

    /**
     * Action for add category page
     */
    public function actionCreate()
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];

            $errors = false;

            // validating fields
            if (!isset($name) || empty($name)) {
                $errors[] = 'Введите название категории.';
            }

            if ($errors == false) {
                Category::createCategory($name, $sortOrder, $status);
                header("Location: /admin/category");
            }
        }

        require_once(ROOT . '/app/views/admin_category/create.php');
        return true;
    }

    /**
     * Action for edit category page
     */
    public function actionUpdate($id)
    {
        self::checkAdmin();

        $category = Category::getCategoryById($id);

        if (isset($_POST['submit'])) {

            $name = $_POST['name'];
            $sortOrder = $_POST['sort_order'];
            $status = $_POST['status'];

            Category::updateCategoryById($id, $name, $sortOrder, $status);

            header("Location: /admin/category");
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_category/update.php');
        return true;
    }

    /**
     * Action for delete category page
     */
    public function actionDelete($id)
    {
        self::checkAdmin();

        if (isset($_POST['submit'])) {
            Category::deleteCategoryById($id);
            header("Location: /admin/category");
        }

        // attach specified view
        require_once(ROOT . '/app/views/admin_category/delete.php');
        return true;
    }

}