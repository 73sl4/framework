<?php
  namespace App;
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 7:18 PM
 */
  class Route
  {
    private $routes = [];

    public function addRoute($httpMethod, $route = '', $options = null)
    {
      if(isset($this->routes[$httpMethod][$route]))
      {
        throw new \Exception($route . ' route already exists');
      }

      $route = strtolower($route);

      if($options instanceof \Closure)
      {
        $this->routes[$httpMethod][$route] = $options;
        return true;
      }

      if(is_array($options) && $options && !empty($route))
      {
        if(isset($options['uses']))
        {
          $class = $method = null;

          list($class, $method) = explode('@', $options['uses']);

          if($class && $method)
          {
            $this->routes[$httpMethod][$route] = [
              $class,
              $method
            ];
          }
        }
      }

      if(is_string($options))
      {
        $class = $method = null;

        list($class, $method) = explode('@', $options);

        if($class && $method)
        {
          $this->routes[$httpMethod][$route] = [
            $class,
            $method
          ];
        }
      }
    }

    public function __call($httpMethod, $arguments)
    {
      $methods_allowed = ['post', 'get', 'put', 'patch', 'delete'];

      if(in_array(strtolower($httpMethod), $methods_allowed))
      {
        call_user_func([$this, 'addRoute'], $httpMethod, $arguments[0], $arguments[1]);
      }
      else
      {
        throw new \Exception('Invalid method');
      }
    }
    public function resolve($route)
    {
      $method = strtolower($_SERVER['REQUEST_METHOD']);
      $route = parse_url(strtolower($route))['path'];

      if(isset($this->routes[$method][$route]))
      {
        if($this->routes[$method][$route] instanceof \Closure)
        {
          $this->routes[$method][$route](new Request($_SERVER));
        }
        elseif(is_array($this->routes[$method][$route]))
        {
          if(class_exists($this->routes[$method][$route][0]))
          {
            $object = new $this->routes[$method][$route][0];

            if(method_exists($object, $this->routes[$method][$route][1]))
            {
              $func = $this->routes[$method][$route][1];
              $object->$func(new Request($_SERVER));
            }
            else
            {
              throw new \Exception($this->routes[$method][$route][1] . ' method doesn\'t exist');
            }
          }
          else
          {
            throw new \Exception($this->routes[$method][$route][0] . ' class doesn\'t exist');
          }
        }
      }
      else
      {
        throw new \Exception('Route doesn\'t exist');
      }
    }
  }

  class Request
  {
    public function __construct($server = null)
    {
      $this->uri = $server['REQUEST_URI'];
      $this->host = $server['HTTP_HOST'];
      $this->method = $server['REQUEST_METHOD'];

      if(isset($server['AUTH_TYPE']))
      {
        $this->auth = $server['AUTH_TYPE'];
        $this->username = $server['PHP_AUTH_USER'];
        $this->password = $server['PHP_AUTH_PW'];
      }


      $this->query = isset($server['QUERY_STRING'])?$server['QUERY_STRING']:null;

      $this->headers = getallheaders();

      $post = null;
      switch($this->headers['Content-Type'])
      {
        case 'application/json':
        case 'text/x-json':
          $post = json_decode(file_get_contents('php://input'), true);
          break;
        case 'application/x-www-form-urlencoded':
        default:
          $post = $_POST;
          break;
      }

      if($post)
      {
        $this->input = array_merge($post, $_GET);
      }
      else
      {
        $this->input = $_GET;
      }
    }

    public function get($key, $default = null)
    {
      if(isset($this->input[$key]))
      {
        return $this->input[$key];
      }

      return $default;
    }

    public function all()
    {
      return $this->input;
    }

    public function has($key)
    {
      if(isset($this->input[$key]) || array_key_exists($key, $this->input))
      {
        return true;
      }

      return false;
    }

    public function header($key, $default = null)
    {
      if(isset($this->headers[$key]))
      {
        return $this->headers[$key];
      }

      return $default;
    }
  }