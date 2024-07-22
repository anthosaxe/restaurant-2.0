<?php
$host = 'db';
$user = 'user';
$pass = 'pass';
$dbn = 'mydb';

$dsn = 'mysql:host=' . $host . ';dbname=' . $dbn;

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $exception) {
    echo 'Error :' . $exception->getMessage();
    die;
}

function create_select($name)
{
    $devises = ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'RUB', 'MXN'];
    $select = '<select name="' . $name . '" id="devise">';
    foreach ($devises as $devise) {
        $select .= '<option value="' . $devise . '">' . $devise . '</
      option>';
    }
    $select .= '</select>';
    return $select;
}

function addtodb($usd1, $usd2, $exchange) {
    $host = 'db';
    $user = 'user';
    $pass = 'pass';
    $dbn = 'mydb';

    try {
        // Create a new PDO instance
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbn . ';charset=utf8';
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement
        $sql = "INSERT INTO exchange_rate (usd1, usd2, rate) VALUES (:usd1, :usd2, :rate)";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':usd1', $usd1);
        $stmt->bindParam(':usd2', $usd2);
        $stmt->bindParam(':rate', $exchange);

        // Execute the statement
        $stmt->execute();

        echo "New record created successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
