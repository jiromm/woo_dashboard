<?php

use Automattic\WooCommerce\HttpClient\HttpClientException;

try {
    $woocommerce = include __DIR__ . '/connection.php';
    $result = $woocommerce->get('orders', [
        'status' => 'any',
        'per_page' => 20,
        'dp' => 0,
    ]);
} catch (HttpClientException $e) {
    echo $e->getMessage();
    exit;
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
        return 'badge-' . $statusMap[$status];
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

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?= $v ?>">

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js?v=<?= $v ?>"></script>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="lead text-primary text-center m-2">Premium Dashboard</div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12 text-center">
            <a href="#" class="btn btn-sm btn-outline-primary">All</a>
            <a href="#" class="btn btn-sm btn-outline-warning">Processing</a>
            <a href="#" class="btn btn-sm btn-outline-success">Completed</a>
            <a href="#" class="btn btn-sm btn-outline-danger">Cancelled</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="list-group">
                <?php

                foreach ($result as $i => $order) {
//                    echo var_dump($order['line_items']);exit;
                ?>
                <div class="list-group-item list-group-item-action pointer order" data-order-id="<?= $order['id'] ?>">
                    <div class="d-flex w-100 justify-content-between primary-record">
                        <strong>
                            #<?= $order['number'] ?>
                            <span class="badge badge-pill align-middle <?= getStatusClass($order['status']) ?>"><?= $order['status'] ?></span>
                        </strong>
                        <small class="muted lh-100">
                            <?= date('Y-m-d', strtotime($order['date_created'])) ?><br>
                            <?= count($order['line_items']) ?> item(s)
                        </small>
                        <strong><?= $order['total'] . ' ' . $order['currency'] ?></strong>
                    </div>
                    <div class="mt-2 w-100 hidden-xs-up secondary-record non-pointer flex-columns">
                        <?php foreach ($order['line_items'] as $item) { ?>
                        <div class="d-flex w-100 justify-content-between">
                            <small class="ellipsis">
                                <?= $item['name'] ?>
                            </small>
                            <small>x <?= $item['quantity'] ?></small>
                            <small><strong><?= $item['total'] . ' ' . $order['currency'] ?></strong></small>
                        </div>
                        <?php } ?>

                        <div class="d-flex w-100 justify-content-between text-primary">
                            <small class="ellipsis">
                                Առաքում
                            </small>
                            <small><strong><?= $order['shipping_total'] . ' ' . $order['currency'] ?></strong></small>
                        </div>

                        <?php if ($order['discount_total']) { ?>
                        <div class="d-flex w-100 justify-content-between text-info">
                            <small class="ellipsis">
                                Զեղչ
                            </small>
                            <small><strong>-<?= abs($order['discount_total']) . ' ' . $order['currency'] ?></strong></small>
                        </div>
                        <?php } ?>

                        <?php if ($order['status'] == 'processing') { ?>
                        <div class="actions mt-2">
                            <button class="btn btn-sm btn-success order-action pointer" data-action="completed">Complete</button>
                            <button class="btn btn-sm btn-danger order-action pointer" data-action="cancelled">Cancel</button>
                        </div>
                        <div class="alert alert-success hidden-xs-up mt-2" role="alert">
                            <button type="button" class="close pointer" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <p>You are about to <span class="action-name">Cancel</span> an order.<strong> Are you sure?</strong></p>
                            <button class="btn btn-sm action-name-btn pointer apply-action" data-status=""></button>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
