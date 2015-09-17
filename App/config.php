<?php
  namespace App;
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 7:18 PM
 */
  class Config
  {
    private $config = [];

    public function __construct()
    {
      if(file_exists('./../App/configuration.php'))
      {
      	$this->config = require './../App/configuration.php';
      }


    }

    public function get($name, $default = null)
    {
      if(isset($this->config[$name]))
      {
      	return $this->config[$name];
      }

      return $default;
    }
  }