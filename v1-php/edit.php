<?php 
    require "db.php";

    // read the task id from the query string
    $id = filter_input(
        INPUT_GET,
        'id',
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );
 
    if ($id === false || $id === null) {
        http_response_code(400);
        die("Invalid Task Id.");
    }

    // fetch task from the database
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id= :id");
    $stmt->execute(['id' => $id]);

    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        http_response_code(404);
        die('Task not found.');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit task</title>
    </head>
    <body>
       <div style="max-width: 600px; padding: 20px; margin: 20px; border: 1px solid #888; border-radius: 10px; display: flex; flex-direction: column;">
            <h1>Edit Task</h1>
            <form action="update.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="title">Title: </label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>" required style="padding: 5px; border-radius: 15px ; border: 1px solid #888;">
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="description">Description: </label>
                    <textarea id="description" name="description" style="padding: 5px; border-radius: 15px ; border: 1px solid #888;"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="due_date">Due Date: </label>
                    <input type="date" id="due_date" name="due_date" value="<?= htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8') ?>" style="padding: 5px; border-radius: 15px ; border: 1px solid #888;">
                </div>

                <button type="submit" style="padding: 10px; border-radius: 15px ; border: 1px solid #888; background-color: #008CBA; color: white; cursor: pointer;">Update Task</button>
            </form>
       </div>
    </body>
</html>