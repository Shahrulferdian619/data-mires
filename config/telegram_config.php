<?php

return [
    'chat_id' => env('CHAT_ID', 'chat-id-here'), // grup id mires
    'telegram_api_url' => env('TELEGRAM_API_URL', 'telegram-api-url'), // api url
    'telegram_api_token' => env('TELEGRAM_API_TOKEN', 'telegram-api-token'), // api token utk akses ke sistem bot
    'telegram_group_id_sales' => env('TELEGRAM_GROUP_ID_SALES', 'telegram-group-id-sales'),
    'telegram_group_id_purchasing' => env('TELEGRAM_GROUP_ID_PURCHASING', 'telegram-group-id-sales'),

    // versi 2
    'v2_telegram_url' => env('TELEGRAM_URL', 'telegram-url'),
    'v2_telegram_auth_token' => env('TELEGRAM_AUTH_TOKEN', 'telegram-auth-token'),
    'v2_telegram_sales_id' => env('TELEGRAM_SALES_ID', 'telegram-sales-id'),
    'v2_telegram_purchase_id' => env('TELEGRAM_PURCHASE_ID', 'telegram-purchase-id'),
];