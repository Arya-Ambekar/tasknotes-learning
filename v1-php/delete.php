<?php 
    require 'db.php';

    if($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        die("Method not allowed. Please use POST to delete a task.");
    }

    $id = filter_input(
        INPUT_POST,
        'id',
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($id === false || $id === null) {
        http_response_code(400);
        die("Invalid Task Id.");
    }

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: index.php", true, 303);
    exit;

?>