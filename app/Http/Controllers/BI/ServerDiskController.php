<?php

namespace App\Http\Controllers\BI;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class ServerDiskController extends Controller
{

  /**
   * The associated views
   * @var [type]
   */
  public $views = [
    'index' => 'bi.servers.disks'
  ];

  /**
   * The class of the model
   * @var string
   */
  public $model_class = 'App\ServerDisk';

  /**
   * What relationships to grab with the model
   * @var [type]
   */
  public $with = [
    'server'
  ];

}