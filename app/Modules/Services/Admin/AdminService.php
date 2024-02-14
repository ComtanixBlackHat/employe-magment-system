<?php
namespace App\Modules\Services\Admin;


use App\Models\Admin\Admin;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
class AdminService
{

    public static function addAdmin(int $userId): ?Admin
    {
        try {
            // Check if the user exists
            $user = User::find($userId);

            if (!$user) {
                return null; // User not found, return null
            }

            // Create a new admin record
            $admin = new Admin();
            $admin->user_id = $userId;
            $admin->save();

            return $admin;
        } catch (QueryException $e) {
            // Log the database error or handle it accordingly
            throw new Exception('Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            // Log or handle any other unexpected exceptions
            throw $e;
        }
    }
}
