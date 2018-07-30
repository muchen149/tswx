<?php

namespace App\lib;


interface YnApiInterface
{
    public function responseMessage($code = 0, $data = null, $message = '');

    public function getCodeDescription($code);
   
    public function getIp();
   
    public function getUrlParameter($request);
}