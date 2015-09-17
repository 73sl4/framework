<?php
  namespace App;
/**
 * Created by IntelliJ IDEA.
 * User: D1
 * Date: 11/09/15
 * Time: 7:49 PM
 */

  $route->get('/rankwiki', 'App\Controllers\GeneralController@rank');

  $route->get('/search', 'App\Controllers\GeneralController@search');

  $route->get('/test', function($req)
  	{
  		var_dump($req);
  	});

