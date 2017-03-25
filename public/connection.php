<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

return new \Automattic\WooCommerce\Client(
    WOO_HOST,
    WOO_KEY,
    WOO_SECRET,
    [
        'wp_api' => true,
        'version' => 'wc/v1',
        'verify_ssl' => false,
    ]
);
