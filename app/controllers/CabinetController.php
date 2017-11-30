<?php

/**
 * CabinetController
 * Controller using for User cabinet at site
 */
class CabinetController
{

    /**
     * Action for main user cabinet page
     */
    public function actionIndex()
    {
        // get user auth from session
        $userId = User::checkLogged();

        // get user info from Db
        $user = User::getUserById($userId);

        // attach specified view
        require_once(ROOT . '/app/views/cabinet/index.php');
        return true;
    }

    /**
     * Action for edit user info page
     */
    public function actionEdit()
    {
        $userId = User::checkLogged();

        $user = User::getUserById($userId);

        // new user info valiables
        $name     = $user['name'];
        $password = $user['password'];

        $result = false;

        // form processing
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $password = $_POST['password'];

            $errors = false;

            // validate fields
            if (!User::checkName($name))
                $errors[] = 'Имя должно быть длиннее 3-х символов';

            if (!User::checkPassword($password))
                $errors[] = 'Пароль должен быть длиннее 5-ти символов';

            // save new data    
            if ($errors == false)

                $result = User::edit($userId, $name, $password);
        }

        // attach specified view
        require_once(ROOT . '/app/views/cabinet/edit.php');
        return true;
    }

}