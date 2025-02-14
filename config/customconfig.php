<?php
return [
    // Production: 8082
    // Development: 8084
    // API Credentials
    'UserName' => 'api_admin',
    'Password' => 'Pass@1234',

    // Login API
    'ACCESS_TOKEN' => 'http://43.205.45.246:8084/agriMarket/createToken',
    'GENERATE_OTP' => 'http://43.205.45.246:8084/agriMarket/generateOTP',
    'VERIFY_OTP' => 'http://43.205.45.246:8084/agriMarket/verifyOTP',

    // User API
    'USER_ADDRESS' => 'http://43.205.45.246:8084/agriMarket/getUsersAddresses',

    // Item API
    'MASTER_ITEM_IMAGE' => 'http://43.205.45.246:8084/agriMarket/getItemImagesURL',
    'MASTER_ITEM' => 'http://43.205.45.246:8084/agriMarket/getListItems_n_image_urls',
    'ITEM_PRICE_ZONE_WISE_CUSTOMER' => 'http://43.205.45.246:8084/agriMarket/getItemPricesZoneWiseForCustomer',
    'PLACE_ORDER' => 'http://43.205.45.246:8084/agriMarket/bookCustomersOrder',
];
