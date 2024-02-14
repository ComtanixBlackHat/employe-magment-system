<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Services\Auth\RegisterService;
use App\Modules\Services\Institution\InstitutionService;

class InstitutionController extends Controller
{
    public function register(Request $request)
    {

        $registrationResult = RegisterService::register($request, "institution");
            // Register::register()
        return response()->json($registrationResult);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string', // Assuming 'username' is the unique identifier
            'password' => 'required|string|min:8',
        ]);

        $loginResult = RegisterService::login($request);

        return response()->json($loginResult);
    }
    public function getAll()
    {
        try {
            // Call the getAll function from the InstitutionService
            $result = InstitutionService::getAll();

            return response()->json($result);
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve institutions: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            // Call the search function from the InstitutionService
            $result = InstitutionService::search($request);


            return response()->json($result);
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'status' => false,
                'message' => 'Failed to search institutions: ' . $e->getMessage(),
                'status_code' => 500,
            ], 500);
        }
    }
}
