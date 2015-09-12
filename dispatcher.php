<?php
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 9:51 PM
 */

namespace App;


class Dispatcher
{
  private $provider = [];

  private $services = [];

  private static $instance = null;

  public function __construct($services = [])
  {
    $this->services = $services;
  }

  public static function getInstance($services = [])
  {
    if(null === self::$instance)
    {
      self::$instance = (new Dispatcher($services));
    }

    return self::$instance;
  }

  public function get($provider = null)
  {
    if($provider)
    {
      if(isset($this->provider[$provider]))
      {
        return $this->provider[$provider];
      }

      return $this->provider[$provider] = $this->resolve($provider);
    }
  }

  public function resolve($provider)
  {
    if(isset($this->services[$provider]))
    {
      return (new $this->services[$provider]);
    }

    return null;
  }


}