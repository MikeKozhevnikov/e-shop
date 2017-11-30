<?php

/**
 * UserController
 * Controller for work with Users
 */
class UserController
{
    /**
     * Action for Sign in page
     */
    public function actionLogin()
    {
        // login form variables
        $email    = false;
        $password = false;
        
        // processing form
        if (isset($_POST['submit'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            // fields validation
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            // check user avaliability
            $userId = User::checkUserData($email, $password);

            if ($userId == false) {
                // if auth fails - show error
                $errors[] = 'Некорректные данные для входа. Попробуйте снова.';
            } else {
                // if auth success - save in session and redirect
                User::auth($userId);
                header("Location: /cabinet");
            }
        }

        // attach specified view
        require_once(ROOT . '/app/views/user/login.php');
        return true;
    }

    /**
     * Action for Log out
     */
    public function actionLogout()
    {
        session_start();

        unset($_SESSION["user"]);
        
        header("Location: /");
    }

    /**
     * Action for Sign up page
     */
    public function actionRegister()
    {
        // registration form variables
        $name     = false;
        $email    = false;
        $password = false;
        $result   = false;

        // processing form
        if (isset($_POST['submit'])) {
            
            // get fields
            $name     = $_POST['name'];
            $email    = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            // fields validation
            if (!User::checkName($name))
                $errors[] = 'Имя не должно быть короче 2-х символов';

            if (!User::checkEmail($email))
                $errors[] = 'Неправильный email';

            if (!User::checkPassword($password))
                $errors[] = 'Пароль не должен быть короче 6-ти символов';

            if (User::checkEmailExists($email))
                $errors[] = 'Такой email уже используется';

            if ($errors == false)
                $result = User::register($name, $email, $password);

        }

        // attach specified view
        require_once(ROOT . '/app/views/user/register.php');
        return true;
    }
    
}