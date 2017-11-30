<?php

/**
 * User Class - model for work with users (customers)
 */

class User
{
    /**
     * User with specified $email и $password check
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @return mixed : integer user id or false
     */
    public static function checkUserData($email, $password)
    {
        // connect to Db
        $db = Db::createConnection();

        // db query
        $sqlQuery = 'SELECT * 
        		FROM user 
        		WHERE email = :email 
        		AND password = :password';

        // prepare and exec sql query
        $result = $db->prepare($sqlQuery);
        $result->bindParam(':email',    $email,    PDO::PARAM_INT);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        // use sql response
        $user = $result->fetch();

        if ($user) {
            // if user exists - return user id
            return $user['id'];
        }
        return false;
    }


    /**
     * User registration 
     * @param string $name <p>Name</p>
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Password</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function register($name, $email, $password)
    {
        $db = Db::createConnection();

        $sqlQuery = '
        			INSERT INTO user (name, email, password) 
        			VALUES (:name, :email, :password)
                	';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':name',     $name,     PDO::PARAM_STR);
        $result->bindParam(':email',    $email,    PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Edit user data
     * @param integer $id <p>User id</p>
     * @param string $name <p>Name</p>
     * @param string $password <p>Password</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function edit($id, $name, $password)
    {
        $db = Db::createConnection();

        $sqlQuery = "
        			UPDATE user 
            		SET name = :name, password = :password 
            		WHERE id = :id
            		";

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id',       $id,       PDO::PARAM_INT);
        $result->bindParam(':name',     $name,     PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Remember user Id
     * @param integer $userId <p>User id</p>
     */
    public static function auth($userId)
    {
        // Save userId in session
        $_SESSION['user'] = $userId;
    }

    /**
     * Return userID if user is authorized
     * else redirecting to login page
     * @return string <p>User id</p>
     */
    public static function checkLogged()
    {
        // if session exists, return userId
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        header("Location: /user/login");
    }

    /**
     * Guest user check
     * @return boolean <p>Method exec result</p>
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user']))
            return false;
        return true;
    }

    /**
     * Phone lengh check (at leats 10 symbols)
     * @param string $phone <p>Phone</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10)
        	return true;
        return false;
    }

    /**
     * Name lengh check (at least 4 symbols) 
     * @param string $name <p>Имя</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 4)
        	return true;
        return false;
    }

    /**
     * Password lengh check (at least 6 symbols)
     * @param string $password <p>Password</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6)
            return true;
        return false;
    }

    /**
     * Return user with required id
     * @param integer $id <p>User id</p>
     * @return array <p>User info array</p>
     */
    public static function getUserById($id)
    {
        $db = Db::createConnection();

        $sqlQuery = '
        			SELECT * 
        			FROM user 
        			WHERE id = :id
        			';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // show that we want to get data in array
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    /**
     * E-email exiting check
     * @param type $email <p>E-mail</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function checkEmailExists($email)
    {
        $db = Db::createConnection();

        $sqlQuery = '
        			SELECT COUNT(*) 
        			FROM user 
        			WHERE email = :email
        			';

        $result = $db->prepare($sqlQuery);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * E-mail check
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Method exec result</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

}