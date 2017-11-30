<?php

/**
 * AdminController
 */

class AdminController extends AdminBase
{
    /**
     * Action for Admin Panel main page
     */
    public function actionIndex()
    {
        // authorization check
        self::checkAdmin();

        // attach specified view
        require_once(ROOT . '/app/views/admin/index.php');
        return true;
    }

}