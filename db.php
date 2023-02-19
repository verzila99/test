<?php
namespace app;

class DB
{

    public \PDO $dbh;
    protected $startDateOfSending = "2021-01-13";
    function __construct()
    {
        try {
            $this->dbh = new \PDO('mysql:dbname=test;host=localhost', 'root', '');
        } catch (\PDOException $e) {
            die($e->getMessage());
        }

    }
    protected static function fibonacci(int $maxLevel, int $currentLevel = 1, $result = [1]): array
    {

        if ($currentLevel === 2) {

            $result = [1, 1];

        } elseif ($currentLevel > 2) {

            array_push($result, $result[count($result) - 2] + $result[count($result) - 1]);
        }

        if ($currentLevel >= $maxLevel) {

            return $result;
        }
        $currentLevel++;

        return self::fibonacci($maxLevel, $currentLevel, $result);

    }
    public function updatingStockFromDeliveries(string $date, array $response): array
    {

        $sth = $this->dbh->prepare("SELECT * FROM `deliveries` WHERE `date` = :date AND `product_id`=:product_id;");

        $sth->execute(['date' => $date, 'product_id' => $response['product_id']]);

        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {

            return $response;
        }

        $stmt = $this->dbh->prepare("INSERT INTO `stock` SET `product_id` = :product_id,`quantity` =:quantity  ,`sum` = :sum    ON DUPLICATE KEY UPDATE
            `product_id` = :product_id,`quantity` =`quantity`+ :quantity ,`sum` = (`sum` + :sum) / 2");

        $stmt->execute(
            [
                'quantity' => $result['quantity'],
                'sum' => $result['sum'],
                'product_id' => $result['product_id']
            ]
        );

        return $result;

    }
    protected function countSendings(string $date, int $sendingQuantity, array $response): array
    {

        $sth = $this->dbh->prepare("SELECT sum,quantity,name FROM `stock` LEFT JOIN `products` ON stock.product_id = products.id WHERE `product_id` = :product_id;");
        $sth->execute(['product_id' => $response['product_id']]);
        $stock = $sth->fetch(\PDO::FETCH_ASSOC);

        $price = round($stock['sum'] / $stock['quantity']
            + $stock['sum'] / $stock['quantity'] * 0.3, 2) * 100;

        if ($sendingQuantity > $stock['quantity']) {
            $date = new \DateTime($date);
            $date = $date->modify('+1 day')->format('Y-m-d');
            $response['name'] = 'Не хватает товара на складе для выбранной даты для оправки в магазин';
            $response['price'] = '';
            $response['quantity'] = '';
            $response['date'] = $date;
            $response['sum'] = '';
            $response['remaining'] = '';
            $response['status'] = false;
            return $response;
        }

        $stmt = $this->dbh->prepare("INSERT INTO `sendings` SET `quantity` = :quantity ,`price`=:price,`sum` = :sum ,`date` = :date,`product_id`= :product_id;");

        $stmt->execute(
            array(
                'quantity' => $sendingQuantity,
                'sum' => $sendingQuantity * $price,
                'date' => $date,
                'price' => $price,
                'product_id' => $response['product_id']
            )
        );
        $date = new \DateTime($date);
        $date = $date->modify('+1 day')->format('Y-m-d');

        $response['price'] = $price / 100;
        $response['name'] = $stock['name'];
        $response['quantity'] = $sendingQuantity;
        $response['date'] = $date;
        $response['sum'] = $sendingQuantity * $price / 100;
        $response['remaining'] = $stock['quantity'] - $sendingQuantity;
        $response['status'] = true;
        return $response;

    }
    public function countPriceOfProductForSending(string $date, array $response): array
    {

        if (!strtotime($date)) {

            $response['errors'][] = 'Не валидная дата';

            return $response;
        }
        $this->dbh->exec("DELETE FROM `stock` WHERE `product_id`=3");
        $this->dbh->exec("TRUNCATE `sendings`");

        $beginDate = new \DateTime($this->startDateOfSending);

        $endDate = new \DateTime($date);

        $dayDiff = $beginDate->diff($endDate)->days;

        if ($dayDiff < 1) {

            $response['errors'][] = 'Не верная дата';

            return $response;
        }

        $sendingQuantityArray = self::fibonacci($dayDiff);

        $result = [];

        $sendingsOrder = 0;
        for ($i = 0; $i < $dayDiff; $i++) {
            $response = $this->updatingStockFromDeliveries($beginDate->format('Y-m-d'), $response);
            if (isset($response['errors'])) {
                return $response;
            }

            $response = $this->countSendings(
                $beginDate->format('Y-m-d'),
                $sendingQuantityArray[$sendingsOrder],
                $response
            );
            if (isset($response['errors'])) {
                return $response;
            }
            if ($response['status']) {
                $sendingsOrder++;
            }

            $beginDate = $beginDate->modify('+1 day');

            $result[] = $response;
        }

        return $result;

    }

}