<?php
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 6:59 PM
 */
  if(file_exists('./../vendor/autoload.php'))
  {
    require './../vendor/autoload.php';
  }
  else
  {
    throw new \Exception('Please run dumpautoload');
  }

  $services = [
    'route'   => 'App\Route',
    'db'      => 'App\DB',
    'config'  => 'App\Config'
  ];

  $dispatcher = App\Dispatcher::getInstance($services);
  $route = $dispatcher->get('route');
  $db = $dispatcher->get('db');

  require './../App/router.php';

  $TERMINAL_VAL = $REQUEST_VAL = null;

  if(isset($_SERVER['TERM']))
  {
    $TERMINAL_VAL = $_SERVER;
  }
  else
  {
    $REQUEST_VAL = $_SERVER;
  }

  if($REQUEST_VAL)
  {
    if(!file_exists('./../App/router.php'))
    {
      throw new \Exception('Routes file not found');
    }

    $log_output = date('Y-m-d H:i:s') . ': ' . $REQUEST_VAL['REQUEST_METHOD'] . ' - ' .$REQUEST_VAL['REQUEST_URI'];
//    echo $log_output;

    $uri = $REQUEST_VAL['REQUEST_URI'] === '/'?'/':$REQUEST_VAL['REQUEST_URI'];

    $route->resolve($uri);
  }