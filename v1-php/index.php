<?php 
    require "db.php";

    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task Manager</title>
    </head>
    <body>
        <div style="max-width: 600px; padding: 20px; margin: 20px; border: 1px solid #888; border-radius: 10px; display: flex; flex-direction: column;">
            <h1>Create Task</h1>
            <form action="create.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="title">Title: </label>
                    <input type="text" name="title" id="title" required style="padding: 5px; border-radius: 15px ; border: 1px solid #888;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="description">Description: </label>
                    <textarea name="description" id="description" style="padding: 5px; border-radius: 10px ; border: 1px solid #888;"></textarea>
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label for="due_date">Due Date: </label>
                    <input type="date" name="due_date" id="due_date" style="padding: 5px; border-radius: 15px ; border: 1px solid #888;">
                </div>

                <input type="submit" value="Create Task" style="padding: 10px; border-radius: 15px ; border: 1px solid #888; background-color: #4CAF50; color: white; cursor: pointer;">
            </form>
        </div>
        <div>
            <h1>Task List</h1>
            <?php if (count($tasks) === 0): ?>
                <p>No tasks found.</p>
                <?php else: ?>
                    <ul>
                    <?php foreach($tasks as $task): ?>
                        <li style="border: 1px solid #888; border-radius: 10px; padding: 10px; margin: 5px 0;">
                            <strong><?php echo htmlspecialchars($task['title'])?></strong>
                            <?php if($task['description']): ?>
                                <p><?php echo htmlspecialchars($task['description']) ?></p>
                            <?php endif; ?>
                            <?php if($task['due_date']): ?>
                                <p>Due Date: <?php echo htmlspecialchars($task['due_date']) ?></p>
                            <?php endif; ?>
                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                <form action="delete.php" method="POST" style="display: inline;">
                                   
                                    <button  type="submit" name="id" value="<?= (int) $task['id'] ?>" style="padding: 5px 10px; border-radius: 15px ; border: 1px solid #888; background-color: #f44336; color: white; cursor: pointer; ">Delete</button>
                                </form>
                                <a href="edit.php?id=<?= (int) $task['id']?>" style="padding: 4px 10px; border-radius: 15px ; border: 1px solid #888; background-color: #008CBA; color: white; text-decoration: none;">Edit</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
            <?php endif; ?>
            </div>
    </body>
</html>