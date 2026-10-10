<?php 

    require 'db.php';

    if($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        die("Method not allowed. Please use POST to update a task.");
    }

    // read the task id from the POST data
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

    // read the task data from the POST data
    $title = trim($_POST['title'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $due_date = trim($_POST['due_date'] ?? "");

    // validate the title
    if($title === '') {
        http_response_code(400);
        die("Title is required.");
    }

    if (mb_strlen($title) > 255) {
        http_response_code(400);
        die("Title must not exceed 255 characters.");
    }

    // validate the due_date
    if($due_date !== "") {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $due_date);

        if(
            $date === false ||
            $date->format("Y-m-d") !== $due_date
        ) {
            http_response_code(400);
            die("Invalid due date format. Please use YYYY-MM-DD");
        }

        if ($date < new DateTimeImmutable('today')) {
            http_response_code(400);
            die("Due date cannot be in the past.");
        }
    }

    $due_date = $due_date === "" ? null : $due_date;

    $stmt = $pdo->prepare("UPDATE tasks SET title= :title, description= :description, due_date= :due_date WHERE id= :id");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'due_date' => $due_date,
        'id' => $id
    ]);

    header('Location: index.php');
    exit;

?>