<?php

    $host = "127.0.0.1";
    $dbname = "tasknotes";
    $username = "your_db_username";
    $password = "your_db_password";

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(500);
        exit("Database connection failed.");
    }

?>