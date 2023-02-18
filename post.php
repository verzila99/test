<?php
require_once(__DIR__ . '/db.php');
use app\DB;

$response['product_id'] = 3;

if (!isset($_REQUEST['date'])) {
    $response['errors'][] = 'Поле дата не должно быть пустым';
    return $response;
} else {
    $db = new DB;
    $response = $db->countPriceOfProductForSending($_REQUEST['date'], $response);
}