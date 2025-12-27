<?php

namespace App\Http\Controllers\API\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProjectRepository;
use App\Repositories\ProjectAssignedRepository;
use App\Repositories\ProjectManagerRepository;

class ProjectController extends Controller
{
    protected $projectRepo;
    protected $projectAssignedRepo;
    protected $projectManagersRepo;

    public function __construct(ProjectRepository $projectRepo, ProjectAssignedRepository $projectAssignedRepo, ProjectManagerRepository $projectManagersRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->projectAssignedRepo = $projectAssignedRepo;
        $this->projectManagersRepo = $projectManagersRepo;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_clientid'      => 'required|integer|exists:clients,client_id',
            'project_title'          => 'required|string|max:255',
            'project_date_start'     => 'required|date_format:m-d-Y',
            'description'    => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {

            $project = $this->projectRepo->create([
                'return' => 'object'
            ]);
            
            if($project) {
                $assigned = $this->projectAssignedRepo->add($project);
                $managers = $this->projectManagersRepo->add($project);
            }

            return response()->json([
                'message' => 'Project created successfully',
                'project' => $project
            ], 201);

        } catch (\Exception $e) {
            Log::error('Project creation failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Project creation failed'.$e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $project = $this->projectRepo->search($id); // assuming search() returns a single project
    
            if (!$project) {
                return response()->json(['message' => 'Project not found'], 404);
            }
    
            return response()->json([
                'message' => 'Project retrieved successfully',
                'project' => $project
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Project fetch failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error retrieving project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
