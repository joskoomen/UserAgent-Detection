<?php

/*
|--------------------------------------------------------------------------
| JosKoomen Detect
|--------------------------------------------------------------------------
|
| The following options should be equal in all applications.
|
*/

return [


    /*
    |--------------------------------------------------------------------------
    | Base Url
    |--------------------------------------------------------------------------
    |
    | The API base url without slash at the end
    |
    */

    'api_url' => env('JOSKOOMEN_DETECT_BASE_API_URL', 'https://useragentdetect.nl'),

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | Set the API version
    |
    */
    'api_version' => env('JOSKOOMEN_DETECT_API_VERSION', '1'),

    /*
    |--------------------------------------------------------------------------
    | Print classes
    |--------------------------------------------------------------------------
    |
    | There is a possibility to get a list of classesfor your html tag.
    | Set this value as 1,2,3,4
    |
    | 0 = none
    | 1 = only layoutengine
    | 2 = layoutengine + browser
    | 3 = layoutengine + browser + os
    | 4 = layoutengine + browser + os + hardware
    |
    */
    'class_level' => env('JOSKOOMEN_DETECT_CLASS_LEVEL', 4),

    /*
    |--------------------------------------------------------------------------
    | Hash key
    |--------------------------------------------------------------------------
    |
    | Secret hash for your server to connect
    |
    */
    'hashsecret' => env('JOSKOOMEN_DETECT_HASH_SECRET', env('APP_KEY')),

];
