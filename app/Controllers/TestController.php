<?php

namespace App\Controllers;

use App\Services\DB;
use App\Services\File;
use App\Services\WRedis;
use App\Services\Logger;
use Slim\Http\Request;
use Wisonlau\Validation\Factory as ValidatorFactory;

class TestController extends Controller
{
	public function test(Request $request)
	{
	    // DB Service Example
		// $db = DB::getInstance();
		// $arrUser = $db->select('users', ['username']);

        // Redis Service Example
        $redis = WRedis::getInstance();
        $lock = $redis->lock('hello', 5);

        // Logger Service Example
		// Logger::add('name', [$request->getUri(), $request->getMethod()]);

        // file
        $file = (new File())->lock('wisonlau');

		return $this->outPut(200, 'success', ['project' => 'slim-framework', 'lock' => $lock]);
	}

	public function lock(Request $request)
    {
        echo '<pre>';
        // file
        // $file = (new File())->lock('wisonlau');

        // dd(array(1, 2, 3));

        // redis
        // $redis = WRedis::getInstance();
        // $lock = $redis->lock('hello', 5);
        // var_dump($lock);

        // validation
        $input = $request->getParams();
        $rules = [
            'password' => 'max:10',
        ];
        $factory = new ValidatorFactory($input, $rules);
        // $rules = [
        //     'email' => ['between:5,9', '邮箱']
        // ];
        // $factory = new ValidatorFactory($input, $rules, '', 'validate');
        if ( ! $factory->success) {
            print_r($factory->errors);
        }
    }
}