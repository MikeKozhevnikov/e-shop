<?php

/**
 * Router Class using for work with site routes
 */

class Router
{

    /**
     * Routes array
     * @var array 
     */
    private $routes;

    public function __construct()
    {
        // site routes filepath
        $routesPath = ROOT . '/app/config/SiteRoutes.php';

        // include routes
        $this->routes = include($routesPath);
    }

    /**
     * Returns requested URI
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']))
        	return trim($_SERVER['REQUEST_URI'], '/');
    }

    /**
     * Request processing method
     */
    public function run()
    {
        // returns requested URI
        $uri = $this->getURI();

        // checking route availability in routes array
        foreach ($this->routes as $uriPattern => $path) {

            // compare $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {

                // get internal path
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // define controller, action and parameters
                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;

                // include specified controller
                $controllerFile = ROOT . '/app/controllers/' .
                        $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                // create object, call controller action
                $controllerObject = new $controllerName;

                // calling specified action in controller with parameters
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                // if controller method was successfully called - finish router work
                if ($result != null) {
                    break;
                }
            }
        }
    }

}