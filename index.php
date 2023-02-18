<?php
require_once(__DIR__ . '/post.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .table {
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #dddddd;
        border-collapse: collapse;
    }

    .table th {
        font-weight: bold;
        padding: 5px;
        background: #efefef;
        border: 1px solid #dddddd;
    }

    .table td {
        border: 1px solid #dddddd;
        padding: 5px;
    }
</style>

<body>


    <form action="index.php" method="post">
        <input name="date" type="date" value="<?php echo $response[count($response) - 1]['date'] ?? '' ?>"
            min="2021-01-14" max="2021-03-01" />
        <button type="submit">Отправить</button>
        <?php if (isset($response['errors']) && is_array($response['errors'])): ?>
            <?php foreach ($response['errors'] as $key => $value): ?>
                <p style="color:red"><?php echo $value ?></p>
            <?php endforeach; ?>
        <?php endif; ?>

    </form>
    <?php if (!isset($response['errors'])): ?>

        <table class="table">
            <tr>
                <td>Дата</td>
                <td>Товар</td>
                <td>Цена</td>
                <td>Количество</td>
                <td>Сумма</td>
                <td>Остаток</td>
            </tr>
            <?php foreach ($response as $item): ?>
                <tr>
                    <td><?php echo $item['date'] ?></td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo $item['price'] ?></td>
                    <td><?php echo $item['quantity'] ?></td>
                    <td><?php echo $item['sum'] ?></td>
                    <td><?php echo $item['remaining'] ?></td>

                </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>


</body>

</html>