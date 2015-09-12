<?php
  namespace App;
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 8:22 PM
 */
  class GeneralController
  {
    use Helper;

    public function rank($request)
    {
      $q = $request->get('q');

//      $URL = 'https://en.wikipedia.org/w/api.php?action=opensearch&format=json&search='.$q.'&namespace=0&limit=10&suggest=';
//
//      $response = $this->extendedHttpRequest($URL, ['method' => 'GET']);
//
//      if($response)
//      {
//
//      }
      var_dump($DB);
    }

    public function search($request)
    {
      $DB = Dispatcher::getInstance()->get('db');

      $resultArray = $DB->rawQuery('SELECT * FROM search')->toJson();

      echo $resultArray;
    }
  }