<script>
    import { onMount } from 'svelte';
    // states for listing task
    let tasks = $state([]);

    // states for creating task
    let title = $state('');
    let description = $state('');
    let dueDate = $state('');

    // states for editing task
    let editingId = $state(null);
    let editTitle = $state('');
    let editDescription = $state('');
    let editDueDate = $state('');

    function loadTask() {
        return fetch('http://127.0.0.1:8000/api/tasks')
        .then((res) => 
            {
                if(!res.ok) {
                throw new Error('Failed to fetch tasks');
                }
                return res.json()
            }
        )
        .then((data) => tasks = data)
        .catch((error) => console.error(error));
    }
    
    onMount(() => {
        loadTask();
    })

    async function handleSubmit (event) {
        event.preventDefault();

        if(title.trim() === "") {
            alert('Title is required');
            return;
        }

        const res = await fetch('http://127.0.0.1:8000/api/tasks', {
            method: "POST",
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            },
            body: JSON.stringify({
                title: title.trim(),
                description,
                due_date: dueDate || null
            })
        })

        if (!res.ok) {
            throw new Error('Failed to create task');
        }

        title = '';
        description = '';
        dueDate = '';

        await loadTask();
    }

    async function deleteTask (id) {
        if(!confirm('Delete the task?')) {
            return;
        }
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/tasks/${id}`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json'
                }
            });

            if (!res.ok) {
                throw new Error('Failed to delete task');
                return;
            }

            await loadTask();
        } catch(error) {
            console.error(error);
            alert('Could not delete the task');
        }
    }

    function startEdit(task) {
        editingId = task.id;
        editTitle = task.title;
        editDescription = task.description ?? '';
        editDueDate = task.due_date ?? '';
    }

    async function updateTask(event) {
        event.preventDefault();

        if(editTitle.trim() === '') {
            alert('Title is required');
            return;
        }

        try {
            const res = await fetch(`http://127.0.0.1:8000/api/tasks/${editingId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type' : 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: editTitle.trim(),
                    description: editDescription,
                    due_date: editDueDate || null
                })
            })

            if (!res.ok) {
                throw new Error('Failed to update task');
            }

            editingId = null;
            await loadTask();

        } catch(error) {
            console.error(error);
            alert('Could not update the task');
        }
    }

    async function toggleCompleted(task) {
        try {
            const res = await fetch(
                `http://127.0.0.1:8000/api/tasks/${task.id}`,
                {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: task.title,
                        description: task.description,
                        due_date: task.due_date,
                        completed: !Boolean(task.completed)
                    })
                }
            );

             if (!res.ok) {
            throw new Error('Failed to update task status');
        }

        await loadTask();
        } catch (error) {
            console.error(error);
            alert('Could not update task status');
        }
    }
</script>

<h2>Create Task</h2>

<form onsubmit={handleSubmit}>
    <input 
        type='text'
        placeholder="Task title"
        bind:value={title}
        required
    />

    <textarea
        placeholder="Description"
        bind:value={description}
    ></textarea>

    <input 
        type="date"
        bind:value={dueDate} 
    />

    <button type="submit">Create Task</button>
</form>


{#each tasks as task (task.id)}
    <div>
        {#if editingId === task.id}
            <form onsubmit={updateTask}>
                <input type='text' bind:value={editTitle} required/>

                <textarea bind:value={editDescription}></textarea>

                <input type="date" bind:value={editDueDate} />

                <button type="submit">Save</button>

                <button type='button' onclick={() => editingId = null}>Cancel</button>
            </form>
        {:else}
            <p>{task.title}</p>
            <p>{task.description}</p>
            <p>Due: {task.due_date ?? 'No due date'}</p>

            <p>Status: {task.completed ? 'Completed' : 'Pending'}</p>

            <button onclick={() => toggleCompleted(task)}>
                {task.completed ? 'Mark Pending' : 'Mark Completed'}
            </button>

            <button onclick={() => startEdit(task)}>
                Edit
            </button>

            <button onclick={() => deleteTask(task.id)}>
                Delete
            </button>
        {/if}
    </div>
{/each}
