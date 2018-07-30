<?php

return [

    'STATUSMSGURL' => env('STATUSMSGURL', 'https://sms-api.luosimao.com/v1/status.json'),

    'SENDMSGURL' => env('SENDMSGURL', 'https://sms-api.luosimao.com/v1/send.json'),

    'APIKEY' => env('APIKEY', 'api:key-8d99e9d6cbb8786cbaea2ab0cc8810fa'),

    'MSGHEADCONTENT' => env('MSGHEADCONTENT', '你的6位手机验证码是:'),

    'MSGFOOTCONTENT' => env('MSGFOOTCONTENT', '【水丁网】'),

];
