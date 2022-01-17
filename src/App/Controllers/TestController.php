<?php

namespace App\Controllers;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Controller;
use Core\Bootstrap\DiContainer;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;
use Core\Libs\Csrf;

class TestController extends Controller
{
    public function __construct()
    {
        parent::__construct();


    }

    public function test(Request $request, Validator $validator)
    {
        $init = [
            'max_files'=>1,
            'directory'=>'uploads2/',
            'max_size'=>8,
            //  'file_name'=>'random',
            //  'filename_length'=>8

        ];

        $request->file()->init($init);

        $request->file()->upload('files');
    }

    public function upform()
    {
        return view('test1');
    }

}