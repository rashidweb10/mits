<?php

return [
    'backend_access_domain' => env('BACKEND_ACCESS_DOMAIN'),
    'school_id' => env('SCHOOL_ID'),
    'assets_url' => env('ASSETS_URL'),
    'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY'),
    'recaptcha_secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'author' => env('AUTHOR'),
    'author_url' => env('AUTHOR_URL'),
    'app_name' => env('APP_NAME'),
    'cache_minutes' => env('CACHE_MINUTES', 120),
    'from_email' => env('MAIL_FROM_ADDRESS'),
    'tinymce_api' => env('TINYMCE_API_KEY'),
    'pagination_per_page' => env('PAGINATION_PER_PAGE', 10),
    'pagination_per_media_page' => env('PAGINATION_PER_MEDIA_PAGE', 24),
];