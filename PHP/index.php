<?php
$source = $_GET['source'] ?? 'xml'; // Vaikimisi laaditakse XML

$data = [];

if ($source === 'xml') {
    // Laadime XML faili ja töötleme andmed
    $xml = simplexml_load_file('../drink_orders.xml');
    foreach ($xml->drink_order as $orderItem) {
        $data[] = [
            'datetime' => (string)$orderItem->datetime,
            'name' => (string)$orderItem->drink->name,
            'price' => (float)$orderItem->drink->price,
            'amount' => (int)$orderItem->drink->amount,
            'sugar_level' => (string)$orderItem->drink->sugar_level,
            'cup_type' => (string)$orderItem->drink->cup_type,
            'method' => (string)$orderItem->payment->method,
            'paid' => (float)$orderItem->payment->paid,
            'change' => (float)$orderItem->payment->change,
            'status' => (string)$orderItem->status,
        ];
    }
}
elseif ($source === 'json') {
    // Laadime JSON faili ja töötleme andmed
    $json = file_get_contents('../drink_orders.json');
    $orders = json_decode($json, true); // Dekodeerime JSON andmed assotsiatiivseks massiiviks

    foreach ($orders['drink_orders'] as $orderItem) {
        $data[] = [
            'datetime' => (string)$orderItem['datetime'],
            'name' => (string)$orderItem['drink']['name'],
            'price' => (float)$orderItem['drink']['price'],
            'amount' => (int)$orderItem['drink']['amount'],
            'sugar_level' => (string)$orderItem['drink']['sugar_level'],
            'cup_type' => (string)$orderItem['drink']['cup_type'],
            'method' => (string)$orderItem['payment']['method'],
            'paid' => (float)$orderItem['payment']['paid'],
            'change' => (float)$orderItem['payment']['change'],
            'status' => (string)$orderItem['status'],
        ];
    }
}

// Otsingu parameetri töötlemine
$search = $_GET['search'] ?? '';
$field = $_GET['field'] ?? '';
if ($search !== '') {
    $data = array_filter($data, function ($item) use ($search, $field) {
        if ($field === '') {
            return stripos(implode(' ', $item), $search) !== false; // Otsing igal väljal
        }
        return isset($item[$field]) && stripos($item[$field], $search) !== false; // Otsing kindlal väljal
    });
}

// Sortimise parameetri töötlemine
$sort = $_GET['sort'] ?? '';
$order = $_GET['order'] ?? 'asc';
if ($sort !== '') {
    usort($data, function ($a, $b) use ($sort, $order) {
        if ($order === 'asc') {
            return $a[$sort] <=> $b[$sort]; // Sorteerimine kasvavas järjekorras
        }
        return $b[$sort] <=> $a[$sort]; // Sorteerimine kahanevas järjekorras
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Drink Orders View</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../styles.css">
    <script src="script.js"></script>
</head>
<body onload="document.getElementById('hidden-columns-header').style.display = 'none';">
<h1>Drink orders view</h1>
<h3>Search</h3>

<!-- Otsingu vorm -->
<form method="GET" action="index.php">
    <select name="field">
        <option value="" <?php echo $field === '' ? 'selected' : ''; ?>>Any Field</option>
        <option value="name" <?php echo $field === 'name' ? 'selected' : ''; ?>>Drink Name</option>
        <option value="price" <?php echo $field === 'price' ? 'selected' : ''; ?>>Drink Price</option>
        <option value="amount" <?php echo $field === 'amount' ? 'selected' : ''; ?>>Drink Amount</option>
        <option value="sugar_level" <?php echo $field === 'sugar_level' ? 'selected' : ''; ?>>Drink Sugar Level</option>
        <option value="cup_type" <?php echo $field === 'cup_type' ? 'selected' : ''; ?>>Drink Cup Type</option>
        <option value="method" <?php echo $field === 'method' ? 'selected' : ''; ?>>Payment Method</option>
        <option value="paid" <?php echo $field === 'paid' ? 'selected' : ''; ?>>Paid</option>
        <option value="change" <?php echo $field === 'change' ? 'selected' : ''; ?>>Change</option>
        <option value="status" <?php echo $field === 'status' ? 'selected' : ''; ?>>Status</option>
        <option value="datetime" <?php echo $field === 'datetime' ? 'selected' : ''; ?>>Datetime</option>
    </select>

    <input type="text" name="search" placeholder="Enter search value..." value="<?php echo htmlspecialchars($search); ?>">

    <!-- Peidetud väli source parameetri saatmiseks -->
    <input type="hidden" name="source" value="<?php echo htmlspecialchars($source); ?>">

    <button type="submit">Search</button>
    <a href="index.php?source=<?php echo htmlspecialchars($source); ?>&field=<?php echo htmlspecialchars($field); ?>&sort=<?php echo htmlspecialchars($sort); ?>&order=<?php echo htmlspecialchars($order); ?>"><button type="button">Show All</button></a>
</form>

<div id="hidden-columns-controls" style="margin-bottom: 20px;">
    <h4 id="hidden-columns-header">Hidden columns:</h4>
    <div id="hidden-columns"></div>
</div>

<h3>Table</h3>

<!-- Andmeallika valiku vorm -->
<form method="GET" action="index.php" id="source-switch-form">
    <label><input onchange="submitForm('source-switch-form')" type="radio" name="source" value="xml" <?php echo $source === 'xml' ? 'checked' : ''; ?>> XML</label>
    <label><input onchange="submitForm('source-switch-form')" type="radio" name="source" value="json" <?php echo $source === 'json' ? 'checked' : ''; ?>> JSON</label>

    <!-- Peidetud väljad olemasolevate parameetrite hoidmiseks -->
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
    <input type="hidden" name="field" value="<?php echo htmlspecialchars($field); ?>">
    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
    <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">

</form>

<!-- Tabeli kuvamine -->
<table>
    <thead>
    <tr>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=datetime&order=<?php echo $sort === 'datetime' && $order === 'asc' ? 'desc' : 'asc'; ?>">DateTime</a>
            <input type="checkbox" onchange="toggleColumnVisibility(1, 'DateTime'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=name&order=<?php echo $sort === 'name' && $order === 'asc' ? 'desc' : 'asc'; ?>">Drink Name</a>
            <input type="checkbox" onchange="toggleColumnVisibility(2, 'Drink Name'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=price&order=<?php echo $sort === 'price' && $order === 'asc' ? 'desc' : 'asc'; ?>">Drink Price (€)</a>
            <input type="checkbox" onchange="toggleColumnVisibility(3, 'Drink Price (€)'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=amount&order=<?php echo $sort === 'amount' && $order === 'asc' ? 'desc' : 'asc'; ?>">Drink Amount</a>
            <input type="checkbox" onchange="toggleColumnVisibility(4, 'Drink Amount'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=sugar_level&order=<?php echo $sort === 'sugar_level' && $order === 'asc' ? 'desc' : 'asc'; ?>">Drink Sugar Level</a>
            <input type="checkbox" onchange="toggleColumnVisibility(5, 'Drink Sugar Level'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=cup_type&order=<?php echo $sort === 'cup_type' && $order === 'asc' ? 'desc' : 'asc'; ?>">Drink Cup Type</a>
            <input type="checkbox" onchange="toggleColumnVisibility(6, 'Drink Cup Type'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=method&order=<?php echo $sort === 'method' && $order === 'asc' ? 'desc' : 'asc'; ?>">Payment Method</a>
            <input type="checkbox" onchange="toggleColumnVisibility(7, 'Payment Method'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=paid&order=<?php echo $sort === 'paid' && $order === 'asc' ? 'desc' : 'asc'; ?>">Paid (€)</a>
            <input type="checkbox" onchange="toggleColumnVisibility(8, 'Paid (€)'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=change&order=<?php echo $sort === 'change' && $order === 'asc' ? 'desc' : 'asc'; ?>">Change (€)</a>
            <input type="checkbox" onchange="toggleColumnVisibility(9, 'Change (€)'); keepChecked(this);" checked>
        </th>
        <th>
            <a href="?field=<?php echo htmlspecialchars($field); ?>&search=<?php echo htmlspecialchars($search); ?>&source=<?php echo htmlspecialchars($source); ?>&sort=status&order=<?php echo $sort === 'status' && $order === 'asc' ? 'desc' : 'asc'; ?>">Status</a>
            <input type="checkbox" onchange="toggleColumnVisibility(10, 'Status'); keepChecked(this);" checked>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $orderItem): ?>
        <tr>
            <td><?php echo htmlspecialchars($orderItem['datetime']); ?></td>
            <td><?php echo htmlspecialchars($orderItem['name']); ?></td>
            <td><?php echo number_format($orderItem['price'], 2); ?></td>
            <td><?php echo htmlspecialchars($orderItem['amount']); ?></td>
            <td><?php echo htmlspecialchars($orderItem['sugar_level']); ?></td>
            <td><?php echo htmlspecialchars($orderItem['cup_type']); ?></td>
            <td><?php echo htmlspecialchars($orderItem['method']); ?></td>
            <td><?php echo number_format($orderItem['paid'], 2); ?></td>
            <td><?php echo number_format($orderItem['change'], 2); ?></td>
            <td><?php echo htmlspecialchars($orderItem['status']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div>
    <a href="insert.php"><button type="button">Insert new</button></a>
</div>
</body>
</html>
