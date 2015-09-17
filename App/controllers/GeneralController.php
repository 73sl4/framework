<?php
  namespace App\Controllers;
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 8:22 PM
 */
  use App\Helper;
  use App\Dispatcher;

  class GeneralController
  {
    use Helper;

    public function rank($request)
    {
      
    }

    public function search($request)
    {
      $DB = Dispatcher::getInstance()->get('db');

      $q = $request->get('q');

      if(!empty($q))
      {
        $search = $DB->rawQuery('SELECT * FROM `search` WHERE q = "' . $q . '"')->toAssocArray();

        if(!empty($search[0]['id']))
        {
          $qid = $search[0]['id'];
        }
        else
        {
          echo 'esle';
          echo $qid = $DB->rawQuery('INSERT INTO `search` (`q`) VALUES("' . $q . '")')->lastInsertId();
        }
        echo $qid;
        // $URL = 'https://en.wikipedia.org/w/api.php?action=opensearch&format=json&search='.$q.'&namespace=0&limit=10&suggest=';

        // $response = $this->extendedHttpRequest($URL, ['method' => 'GET']);

        // if($response->code === 200)
        // {
        //   $responseArray = json_decode($response->data);

        //   $resultArray = $DB->rawQuery('SELECT * FROM `search` WHERE q = "' . $q . '"')->toJson();

        // }

        

        // echo $resultArray;
      }
      
    }
  }