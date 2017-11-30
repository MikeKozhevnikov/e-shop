<?php

/**
 * AdminBase Class is abstract class that contains general logic of controllers for AdminPanel
 */
abstract class AdminBase
{

    /**
     * Administrator authorization method
     * @return boolean
     */
    public static function checkAdmin()
    {
        // authenification check
        $userId = User::checkLogged();

        // get current user info
        $user = User::getUserById($userId);

        // compare roles
        if ($user['role'] == 'admin') {
            return true;
        }

        // if isn't good - die
        die('Access denied');
    }

}