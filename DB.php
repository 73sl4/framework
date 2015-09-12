<?php
  namespace App;
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 11:29 PM
 */
  class DB
  {
    private $adapter = [];

    private $driver = null;

    private $result = null;


    public function __construct()
    {
      $config = Dispatcher::getInstance()->get('config');
      if(isset($this->adapter[$config->get('driver')]))
      {
        return $this->adapter[$config->get('driver')];
      }

      switch($config->get('driver'))
      {
        case 'mysql':
        default:
          $this->adapter[$config->get('driver')] = new \mysqli($config->get('host'), $config->get('user'), $config->get('password'), $config->get('db'));
          $this->driver = 'mysql';
          break;
      }

      return $this->adapter[$this->driver];
    }

    public function rawQuery($sql)
    {
      if($this->driver)
      {
        $this->result = $this->adapter[$this->driver]->query($sql);
      }

      return $this;
    }

    public function toAssocArray($json = false)
    {
      if($this->result)
      {
        return $this->result->fetch_all(MYSQLI_ASSOC);
      }

      return [];
    }

    public function toJson($json = false)
    {
      return json_encode($this->toAssocArray());
    }

    public function toArray($json = false)
    {
      if($this->result)
      {
        return $this->result->fetch_all(MYSQLI_NUM);
      }

      return [];
    }

    public function totalResults()
    {
      if($this->result)
      {
        return $this->result->num_rows;
      }

      return 0;
    }

    public function lastInsertId()
    {
      if($this->adapter[$this->driver])
      {
        return $this->adapter->insert_id;
      }

      return 0;
    }
  }