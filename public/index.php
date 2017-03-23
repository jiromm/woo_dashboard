<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

try {
    $woocommerce = new Client(
        WOO_HOST,
        WOO_KEY,
        WOO_SECRET,
        [
            'wp_api' => true,
            'version' => 'wc/v1',
            'verify_ssl' => false,
        ]
    );
    $result = $woocommerce->get('orders', [
        'status' => 'any',
        'per_page' => 20,
        'dp' => 0,
    ]);
} catch (HttpClientException $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getRequest() . PHP_EOL;
    echo $e->getResponse() . PHP_EOL;
    $result = [];
}

function getStatusClass($status) {
    $statusMap = [
        'pending' => '',
        'processing' => 'warning',
        'on-hold' => 'muted',
        'completed' => 'success',
        'cancelled' => 'danger',
        'refunded' => 'info',
        'failed' => 'danger',
    ];

    if (array_key_exists($status, $statusMap) && !empty($statusMap[$status])) {
        return 'list-group-item-' . $statusMap[$status];
    }

    return '';
}

$v = uniqid();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Premium Dashboard</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0">

    <link rel="stylesheet" href="css/style.css?v=<?= $v ?>">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha256-/SIrNqv8h6QGKDuNoLGA4iret+kyesCkHGzVUUV0shc=" crossorigin="anonymous"></script>
    <script src="js/main.js?v=<?= $v ?>"></script>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="lead text-primary text-center m-2">Premium Dashboard</div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="list-group">
                <?php

                foreach ($result as $i => $order) {
//                    echo var_dump($order['line_items']);exit;
                ?>
                <a href="#" class="list-group-item list-group-item-action <?= getStatusClass($order['status']) ?>">
                    <div class="d-flex w-100 justify-content-between primary-record">
                        <strong>#<?= $order['number'] ?></strong>
                        <small class="muted lh-100">
                            <?= date('Y-m-d', strtotime($order['date_created'])) ?><br>
                            <?= count($order['line_items']) ?> item(s)
                        </small>
                        <strong><?= $order['total'] . ' ' . $order['currency'] ?></strong>
                    </div>
                    <div class="mt-2 w-100 hidden-xs-down secondary-record flex-columns">
                        <?php foreach ($order['line_items'] as $item) { ?>
                        <div class="d-flex w-100 justify-content-between">
                            <small class="ellipsis">
                                <?= $item['name'] ?>
                            </small>
                            <small>x <?= $item['quantity'] ?></small>
                            <small><strong><?= $item['total'] . ' ' . $order['currency'] ?></strong></small>
                        </div>
                        <?php } ?>
                        <div class="actions mt-2">
                            <button class="btn btn-sm btn-success complete" data-action="complete">Complete</button>
                            <button class="btn btn-sm btn-danger cancel" data-action="cancel">Cancel</button>
                        </div>
                        <div class="alert alert-dismissible alert-success mt-2" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <p><strong>You are about to <span class="action-name">Cancel</span> an order</strong></p>
                            <p>Are you sure? <button class="btn btn-sm btn-primary">Yes</button></p>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
