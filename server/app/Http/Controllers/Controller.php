<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function randomString($length = 20)
    {
        $string = bin2hex(random_bytes(64));
        return substr($string, 0, $length);
    }

    public function uploadBase64($value, $path)
    {
        $data = explode(';base64,', $value)[1];
        file_put_contents($path, base64_decode($data));
        return true;
    }
}
