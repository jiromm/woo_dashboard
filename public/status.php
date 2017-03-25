<?php

$result = [
    'status' => 'error',
    'message' => 'Error! Something went wrong.',
];

try {
    if (!count($_POST)) {
        throw new \Exception('Bad request');
    }

    if (empty($_POST['order_id']) || !ctype_digit($_POST['order_id'])) {
        throw new \Exception('Bad order id');
    }

    if (empty($_POST['status']) || !in_array($_POST['status'], ['complete', 'cancel'])) {
        throw new \Exception('Bad status');
    }

    /**
     * @var Automattic\WooCommerce\Client $woocommerce
     */
    $woocommerce = include __DIR__ . '/connection.php';
//    $result = $woocommerce->put('orders/' . $_POST['status'], [
//        'status' => 'any',
//    ]);

    $result = [
        'status' => 'success',
        'message' => 'Status successfully updated',
    ];
} catch (\Exception $e) {
    $result['message'] = $e->getMessage();
}

header('Content-type: application/json');
echo json_encode($result);
