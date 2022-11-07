<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Models\Task;
use App\Src\Response;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createTask(TaskCreateRequest $request)
    {
        $user = $request->user();
        $task = Task::create([
            'user_id' => $user->id,
            'task' => $request->task,
            'description' => $request->description,
            'is_done' => false
        ]);
        return Response::success('successful created task', payload: [
            'id' => $task->id,
            'task' => $task->task,
            'description' => $task->description,
            'is_done' => $task->is_done
        ]);
    }

    public function taskDone(Request $request, Task $task)
    {
        $user = $request->user();
        if ($task->user_id != $user->id) {
            return Response::error('The task does not belong to you', 400);
        }
        if ($task->is_done === $request->is_done) {
            return Response::error('is_done does not accept such a value.', 400);
        }
        $task->is_done = $request->is_done;
        $task->save();
        return Response::success('task done');
    }

    public function AllTasks(Request $request)
    {
        $tasks = Task::where('user_id', $request->user()->id)->get(['id', 'task', 'description', 'is_done']);
        return Response::success(payload: $tasks);
    }

    public function updateTask(TaskUpdateRequest $request, Task $task)
    {
        $user = $request->user();
        if ($task->user_id != $user->id) {
            return Response::error('The task does not belong to you', 400);
        }
        $task->update([
            'task' => $request->task,
            'description' => $request->description,
        ]);
        return Response::success();
    }

    public function deleteTask(Request $request, Task $task)
    {
        $user = $request->user();
        if ($task->user_id != $user->id) {
            return Response::error('The task does not belong to you', 400);
        }
        $task->delete();
        return Response::success();
    }
}
