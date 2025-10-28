<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Part 3 - Interactive Calculator</title>
    <script>

    function calcClient() {
        var a = parseFloat(document.getElementById('a').value) || 0;
        var b = parseFloat(document.getElementById('b').value) || 0;
        var op = document.getElementById('op').value;
        var res = 0;
        switch (op) {
            case '+': res = a + b; break;
            case '-': res = a - b; break;
            case '*': res = a * b; break;
            case '/': res = b !== 0 ? a / b : 'Error (div by 0)'; break;
        }
        document.getElementById('client_result').textContent = res;
    }
    </script>
</head>
<body>
    <h1>Interactive Calculator (Part 3)</h1>

    <?php
    $server_result = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $a = isset($_POST['a']) ? floatval($_POST['a']) : 0;
        $b = isset($_POST['b']) ? floatval($_POST['b']) : 0;
        $op = $_POST['op'] ?? '+';
        switch ($op) {
            case '+': $server_result = $a + $b; break;
            case '-': $server_result = $a - $b; break;
            case '*': $server_result = $a * $b; break;
            case '/': $server_result = ($b != 0) ? $a / $b : 'Error (div by 0)'; break;
            default: $server_result = 'Unknown op';
        }
    }
    ?>

    <h2>Client-side (instant)</h2>
    <input id="a" type="number" step="any" value="0" oninput="calcClient()"> 
    <select id="op" onchange="calcClient()">
        <option>+</option>
        <option>-</option>
        <option>*</option>
        <option>/</option>
    </select>
    <input id="b" type="number" step="any" value="0" oninput="calcClient()">
    = <span id="client_result">0</span>

    <h2>Server-side (submit to PHP)</h2>
    <form method="post" action="interactive.php">
        <input name="a" type="number" step="any" value="0">
        <select name="op">
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>
        <input name="b" type="number" step="any" value="0">
        <input type="submit" value="Calculate">
    </form>

    <?php if ($server_result !== null): ?>
        <p>Server result: <strong><?php echo htmlspecialchars((string)$server_result); ?></strong></p>
    <?php endif; ?>

    <p><a href="index.php">Back to Challenge Index</a></p>
</body>
</html>
