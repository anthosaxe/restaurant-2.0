<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    rm_db($id);
}

function rm_db($id) {
    $host = 'db';
    $user = 'user';
    $pass = 'pass';
    $dbn = 'mydb';

    try {
        $dsn = "mysql:host=$host;dbname=$dbn";
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM `client` WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Record with ID $id has been successfully deleted.";
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
}
?>
