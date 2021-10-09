<?php
return [
    'driver'     => env('MAIL_MAILER', 'smtp'),
    'host'       => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port'       => env('MAIL_PORT', 587),
    'from'       => env('MAIL_FROM_ADDRESS'),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username'   => env('MAIL_USERNAME'),
    'password'   => env('MAIL_PASSWORD')
];