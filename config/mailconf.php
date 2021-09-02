<?php
return [
    'driver'     => env('MAIL_DRIVER', 'smtp'),
    'host'       => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port'       => env('MAIL_PORT', 587),
    'from'       => array('address' => 'noreply@apsoft.com.ph', 'name' => 'support'),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username'   => env('MAIL_USERNAME'),
    'password'   => env('MAIL_PASSWORD')
];