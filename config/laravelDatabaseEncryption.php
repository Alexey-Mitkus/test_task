<?php

return [
    'enable_encryption' => true,
    'encrypt_method' => 'AES-256-CBC',
    'encrypt_key' => env('APP_KEY', null)
];