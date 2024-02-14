<?php
namespace App\Modules\Services\Institution;

use App\Models\Institution\Institution;
use Illuminate\Http\Request;
use App\Models\User;

use Exception;
use Illuminate\Database\QueryException;
use App\Models\Employee\Employee;

class EmployeeService


// // Create a new employee
// $employee = new Employee();

// // Fill the employee data
// $employee->user_id = 1; // Replace 1 with the actual user ID
// $employee->institution_id = 1; // Replace 1 with the actual institution ID
// $employee->name = 'John Doe';
// $employee->address = '123 Main St, City, Country';
// $employee->date_of_birth = '1990-01-01';
// $employee->place_of_birth = 'City, Country';
// $employee->certification = 'Certification Name';
// $employee->government_id = '123456789';
// $employee->picture = 'path/to/picture.jpg';

// // Save the employee
// $employee->save();

{

    public static function add(int $userId, Request $request): ?Institution
    {
        try {
            // Check if the user exists
            $user = User::find($userId);

            if (!$user) {
                return null; // User not found, return null
            }


            // Create a new institution

// Create a new employee
$employee = new Employee();

// Fill the employee data
$employee->user_id = 1; // Replace 1 with the actual user ID
$employee->institution_id = 1; // Replace 1 with the actual institution ID
$employee->name = 'John Doe';
$employee->address = '123 Main St, City, Country';
$employee->date_of_birth = '1990-01-01';
$employee->place_of_birth = 'City, Country';
$employee->certification = 'Certification Name';
$employee->government_id = '123456789';
$employee->picture = 'path/to/picture.jpg';

// Save the employee
$employee->save();


            return $institution;
        } catch (QueryException $e) {
            // Log the database error or handle it accordingly
            throw new Exception('Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            // Log or handle any other unexpected exceptions
            throw $e;
        }
    }
    public static function getAll()
    {
        try {
            // Retrieve all institutions from the database
            $institutions = Institution::with('user')->get();

            return [
                'status' => true,
                'message' => 'Institutions retrieved successfully',
                'data' => $institutions,
                'status_code' => 200,
            ];
        } catch (Exception $e) {
            // Return error details in case of an exception
            return [
                'status' => false,
                'message' => 'Failed to retrieve institutions: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }
    public static function search(Request $request)
    {
        try {
            $query = Institution::query();

            // Eager load the 'users' relationship
            $query->with('user');
            // Search by id
            if ($request->has('id')) {
                $query->where('id', $request->id);
            }

            // Search by name
            if ($request->has('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // Search by type
            if ($request->has('type')) {
                $query->where('type', 'like', '%' . $request->type . '%');
            }

            // Search by services
            if ($request->has('services')) {
                $query->where('services', 'like', '%' . $request->services . '%');
            }

            $institutions = $query->get();

            return [
                'status' => true,
                'message' => 'Institutions retrieved successfully',
                'data' => $institutions,
                'status_code' => 200,
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to retrieve institutions: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }

}
