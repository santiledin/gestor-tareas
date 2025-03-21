<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return response()->json($tasks);
    }

    // Crear una nueva tarea
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = Task::with('comments')->findOrFail($id);
        return response()->json($task);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validated();

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $task->delete();

        return response()->json(['message' => 'Tarea eliminada con éxito']);
    }

    public function addComment(Request $request, $taskId)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $task = Task::findOrFail($taskId);

        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $comment = Comment::create([
            'task_id' => $taskId,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return response()->json($comment, 201);
    }

    public function toggleStatus($id)
    {
        $task = Task::findOrFail($id);

        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $task->status = $task->status === 'pending' ? 'completed' : 'pending';
        $task->save();

        return response()->json($task);
    }
}
