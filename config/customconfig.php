<?php
return [
    // Production: 8084
    // Development: 8082
    // API Credentials
    'UserName' => 'api_admin',
    'Password' => 'Pass@1234',

    // Login API
    'ACCESS_TOKEN' => 'http://43.205.45.246:8082/agriMarket/createToken',
    'GENERATE_OTP' => 'http://43.205.45.246:8082/agriMarket/generateOTP',
    'VERIFY_OTP' => 'http://43.205.45.246:8082/agriMarket/verifyOTP',

    // User API
    'USER_ADDRESS' => 'http://43.205.45.246:8082/agriMarket/getUsersAddresses',

    // Item API
    'MASTER_ITEM_IMAGE' => 'http://43.205.45.246:8082/agriMarket/getItemImagesURL',
    'MASTER_ITEM' => 'http://43.205.45.246:8082/agriMarket/getListItems_n_image_urls',
    'ITEM_PRICE_ZONE_WISE_CUSTOMER' => 'http://43.205.45.246:8082/agriMarket/getItemPricesZoneWiseForCustomer',
    'PLACE_ORDER' => 'http://43.205.45.246:8082/agriMarket/bookCustomersOrder',
];
