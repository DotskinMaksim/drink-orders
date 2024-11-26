<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kogume vormi andmed
    $datetime = $_POST['datetime'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $sugar_level = $_POST['sugar_level'];
    $cup_type = $_POST['cup_type'];
    $method = $_POST['method'];
    $paid = $_POST['paid'];
    $status = $_POST['status'];

    $source = $_POST['source'];

    // Arvutame kogu hinna ja tagasiraha
    $total_price = $price * $amount;
    $change = $paid - $total_price;

    // Koostame uue joogi tellimuse
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

    if ($source === 'json') {
        // JSON-faili laadimine või loomine
        $jsonFile = '../drink_orders.json';
        if (file_exists($jsonFile)) {
            $jsonData = file_get_contents($jsonFile);
            $dataArray = json_decode($jsonData, true);
        } else {
            $dataArray = ["drink_orders" => []];
        }

        // Lisame uue tellimuse
        $dataArray["drink_orders"][] = $newDrink;

        // Salvestame JSON-faili
        file_put_contents($jsonFile, json_encode($dataArray, JSON_PRETTY_PRINT));

    }
    if ($source === 'xml') {
        // XML-faili laadimine või loomine
        $xmlFile = '../drink_orders.xml';
        if (file_exists($xmlFile)) {
            $xml = simplexml_load_file($xmlFile);
        } else {
            $xml = new SimpleXMLElement('<drink_orders></drink_orders>');
        }

        // Lisame uue tellimuse XML-faili
        $drinkOrder = $xml->addChild('drink_order');
        $drinkOrder->addChild('datetime', $datetime);

        $drink = $drinkOrder->addChild('drink');
        $drink->addChild('name', htmlspecialchars($name, ENT_XML1, 'UTF-8'));
        $drink->addChild('price', $price);
        $drink->addChild('amount', $amount);
        $drink->addChild('sugar_level', $sugar_level);
        $drink->addChild('cup_type', $cup_type);

        $payment = $drinkOrder->addChild('payment');
        $payment->addChild('method', $method);
        $payment->addChild('paid', $paid);
        $payment->addChild('change', $change);

        $drinkOrder->addChild('status', $status);

        // Vormindame ja salvestame XML-faili
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($xmlFile);
    }

    // Suuname tagasi põhiindeksisse
    header("Location: index.php");
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
    <link rel="stylesheet" href="../styles.css">

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
        <select name="sugar_level" id="sugar_level">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <label for="cup_type">Cup type:</label>
        <select name="cup_type" id="cup_type">
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
        </select>

        <label for="method">Payment method:</label>
        <select name="method" id="method">
            <option value="Card">Card</option>
            <option value="Cash">Cash</option>
        </select>

        <label for="paid">Paid:</label>
        <input type="number" id="paid" name="paid" step="0.01" required><br><br>

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="Complete">Complete</option>
            <option value="Interrupted">Interrupted</option>
        </select>

        <label><input type="radio" name="source" value="xml" required>XML</label>
        <label><input type="radio" name="source" value="json" required>JSON</label>


        <div class="form-buttons">
            <a href="index.php">Back</a>
            <button type="submit">Insert new</button>
        </div>

    </form>

</div>


</body>
</html>
