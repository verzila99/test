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
    public function countProducts(string $date, array $response): array|bool
    {

        $sth = $this->dbh->prepare("SELECT `product_id`,products.name as name , SUM(`quantity`) as quantity ,SUM(`sum`) as sum FROM `deliveries` LEFT JOIN `products` ON deliveries.product_id = products.id WHERE `date` < :date GROUP BY `product_id`;");

        $sth->execute(array('date' => $date));

        $array = $sth->fetchAll(\PDO::FETCH_ASSOC);

        if (!$array) {

            $response['errors'][] = 'Не верная дата';

            return $response;
        }

        foreach ($array as $key => $value) {

            $stmt = $this->dbh->prepare("UPDATE `stock` SET `quantity` = :quantity ,`sum` = :sum  WHERE `product_id` = :product_id");

            $stmt->execute(array('quantity' => $value['quantity'], 'sum' => $value['sum'], 'product_id' => $value['product_id']));
        }

        return $array;

    }
    protected function countSendings(string $date, array $productsSummary, array $response)
    {

        $begin = new \DateTime($this->startDateOfSending);
        $end = new \DateTime($date);

        $diff = $begin->diff($end)->days;

        $sendingQuantityArray = self::fibonacci($diff);

        $response['date'] = $date;

        if (array_sum($sendingQuantityArray) > $productsSummary[2]['quantity']) {

            $response['errors'][] = "Не хватает товара на складе для выбранной даты";

            return $response;
        }

        $response['name'] = $productsSummary[2]['name'];
        $response['quantity'] = $sendingQuantityArray[$diff - 1];
        $response['price'] = round(($productsSummary[2]['sum'] / $productsSummary[2]['quantity'])
            + ($productsSummary[2]['sum'] / $productsSummary[2]['quantity'] * 0.3), 2);
        $response['sum'] = $response['price'] * $response['quantity'];
        $response['remaining'] = $productsSummary[2]['quantity'] - array_sum($sendingQuantityArray);
        return $response;

    }
    public function countPriceOfProductForSending(string $date, array $response)
    {

        if (!strtotime($date)) {

            $response['errors'][] = 'Не валидная дата';

            return $response;
        }

        $productsSummary = $this->countProducts($date, $response);

        $response = $this->countSendings($date, $productsSummary, $response);

        return $response;

    }

}