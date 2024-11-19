<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datetime = $_POST['datetime'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $sugar_level = $_POST['sugar_level'];
    $cup_type = $_POST['cup_type'];
    $method = $_POST['method'];
    $paid = $_POST['paid'];
    $change = $_POST['change'];
    $status = $_POST['status'];

    $newDrink = [
        "datetime" => $datetime,
        "drink" => [
            "name" => $name,
            "price" => $price,
            "amount" => $amount,
            "sugar_level" => $sugar_level,
            "cup_type" => $cup_type
        ],
        "payment" => [
            "method" => $method,
            "paid" => $paid,
            "change" => $change
        ],
        "status" => $status
    ];

    $jsonFile = '../drink_orders.json';
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $dataArray = json_decode($jsonData, true);
    } else {
        $dataArray = ["drink_orders" => []];
    }

    // Добавление нового напитка в массив
    $dataArray["drink_orders"][] = $newDrink;

    // Запись обновленных данных обратно в файл
    file_put_contents($jsonFile, json_encode($dataArray, JSON_PRETTY_PRINT));

    // Можно добавить редирект или сообщение для подтверждения
    header("Location: index.php"); // Перенаправление на тот же скрипт для очистки формы
    exit;
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert new</title>
    <link rel="stylesheet" href="insert-style.css">
</head>
<body>
<div class="form-container">
    <h1>Insert new drink order</h1>

    <form action="insert.php" method="POST">
        <label for="datetime">DateTime:</label>
        <input type="datetime-local" id="datetime" name="datetime" required><br><br>

        <label for="name">Drink name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="price">Drink price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <label for="amount">Drink amount:</label>
        <input type="number" id="amount" name="amount" required><br><br>

        <label for="sugar_level">Drink sugar level:</label>
        <input type="text" id="sugar_level" name="sugar_level" required><br><br>

        <label for="cup_type">Cup type:</label>
        <input type="text" id="cup_type" name="cup_type" required><br><br>

        <label for="method">Payment method:</label>
        <input type="text" id="method" name="method" required><br><br>

        <label for="paid">Paid:</label>
        <input type="number" id="paid" name="paid" step="0.01" required><br><br>

        <label for="change">Change:</label>
        <input type="number" id="change" name="change" step="0.01" required><br><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br><br>

        <button type="submit">Insert new</button>
    </form>
</div>

</body>
</html>