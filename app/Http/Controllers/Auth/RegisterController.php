<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Modules\Services\Auth\RegisterService;
use Illuminate\Http\Request;
// use Monolog\Registry;

class RegisterController extends Controller
{
    public function registerAdmin(Request $request)
    {

        $registrationResult = RegisterService::register($request, "admin");
            // Register::register()
        return response()->json($registrationResult);
    }

    public function loginAdmin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string', // Assuming 'username' is the unique identifier
            'password' => 'required|string|min:8',
        ]);

        $loginResult = RegisterService::login($request);

        return response()->json($loginResult);
    }
}
