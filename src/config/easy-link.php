<?php

return [
    'url' => env('EASY_LINK_URL', 'http://sandbox.easylink.id:9080'),
    'company_key' => env('EASY_LINK_CK'),
    'app_id' => env('EASY_LINK_APP_ID'),
    'app_secret' => env('EASY_LINK_APP_SECRET'),
    'private_key' => storage_path('app/private/keys/easylink_key.pem')
];
