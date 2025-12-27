<?php

namespace App\Http\Controllers\API\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;

class ClientController extends Controller
{
    protected $clientRepo;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepo = $clientRepo;
    }

    public function store(Request $request)
    {

        $client = $this->clientRepo->create($request); // Assuming this method exists

        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }
    
    public function show($id)
    {
        try {
            $client = $this->clientRepo->search($id); // Assuming 'search' gets a single client
    
            if (!$client) {
                return response()->json(['message' => 'Client not found'], 404);
            }
    
            return response()->json([
                'message' => 'Client retrieved successfully',
                'client' => $client
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve client', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching client'], 500);
        }
    }

}
