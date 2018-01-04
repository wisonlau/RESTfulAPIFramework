<?php
namespace App\Services;

use Pheanstalk\Pheanstalk;

class Beanstalkds
{
    public function __construct()
    {
        new Pheanstalk(env('BEANSTALKD_HOST', '127.0.0.1'), env('BEANSTALKD_PORT', 11300));
    }

}