<?php

namespace App\Http\Controllers\API\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    protected $taskRepo;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_projectid'      => 'required|integer|exists:projects,project_id',
            'task_title'          => 'required|string|max:255',
            'description'    => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {

            $task = $this->taskRepo->create();
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $request,
            ], 422);
            //if($project) {
            //    $assigned = $this->projectAssignedRepo->add($project);
            //    $managers = $this->projectManagersRepo->add($project);
            //}

            return response()->json([
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);

        } catch (\Exception $e) {
            Log::error('Task creation failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Task creation failed'.$e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $task = $this->taskRepo->search($id); // assuming search() returns a single task
    
            if (!$task) {
                return response()->json(['message' => 'Task not found'], 404);
            }
    
            return response()->json([
                'message' => 'Task retrieved successfully',
                'task' => $task
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Task fetch failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error retrieving task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
