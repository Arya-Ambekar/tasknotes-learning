<?php 
    require "db.php";

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        http_response_code(405);
        die('Method not allowed. Please use POST to create a task.');
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $due_date = trim($_POST['due_date'] ?? '');

        if($title === '') {
            http_response_code(400);
            die("Title is required.");
        }

        if(mb_strlen($title) > 255) {
            http_response_code(400);
            die("Title must not exceed 255 characters.");
        }

        if($due_date !== "") {
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $due_date);
            if(
                $date === false ||
                $date->format("Y-m-d") !== $due_date
            ) {
                http_response_code(400);
                die("Invalid due date format. Please use YYYY-MM-DD.");
            }

            if($date < new DateTimeImmutable('today')) {
                http_response_code(400);
                die("Due date cannot be in the past.");
            }
        }


        $due_date = $due_date === "" ? null : $due_date;

        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, due_date) VALUES (:title, :description, :due_date)");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'due_date' => $due_date
        ]);
        header("Location: index.php");
        exit;
    }
?>  
