<?php

Route::start();

class Route {
  static function start() {
    $base_url = 'localhost/cform2';

    $controller_name = 'ContactFormController'; // Hardcoded. There is no controllers
    // Default action.
    $default_action = 'index';
    $routes = explode('/', $_SERVER['REQUEST_URI']);
    // Get action from url like 'local/cform/index'.
    $action_name = !empty($routes[2]) ? $routes[2] : '';

    // Add model, controller,  hardcoded.
    require_once 'app/model/ContactFormModel.php';
    require_once 'app/model/Database.php';
    require_once 'app/controller/ContactFormController.php';
    require_once 'app/view/View.php';

    // Create Controller.
    $controller = new $controller_name;
    $action = method_exists($controller, $action_name) ? $action_name : $default_action;

    // Call action.
    $controller->$action();
  }

  function ErrorPage404() {
    $host = 'http://'.$_SERVER['HTTP_HOST'].'cform2/';
    header('HTTP/1.1 404 Not Found');
    header("Status: 404 Not Found");
    header('Location:'.$host.'404');
  }

}
