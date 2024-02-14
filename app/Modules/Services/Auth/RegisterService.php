<?php
namespace App\Modules\Services\Auth;


use Illuminate\Http\Request;
use App\Models\User;
use App\Modules\Services\Admin\AdminService;
use App\Modules\Services\Institution\InstitutionService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class RegisterService
{

    public static function admin_validator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|string',
            'username' => 'required|string', // Add your validation rules for 'username'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public static function institution_validator(array $data, $isupdate = false)
    {
        $rules = [
            'name' => 'required|string',
            'password' => 'required|min:8',
            'type' => 'required|string',
            'service' => 'required|string',

        ];

        // $table->string('name'); // Add missing column definition
        // $table->string('type'); // Add missing column definition
        // $table->string('service'); // Add missing column definition

        $update_rules = [
            'name' => 'required|string',
            'username' => 'required|email|unique:users,email',
            'password' => 'required|min:8',

        ];

        $validator = Validator::make($data, $isupdate ? $update_rules : $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public static function employee_validator(array $data, $isupdate = false)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone' => 'required|string|unique:users,phone',
        ];

        $update_rules = [
            'name' => 'required|string',
            'username' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($data, $isupdate ? $update_rules : $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public static function register(Request $request, $role)
    {
        try {
            switch ($role) {
                case 'admin':
                    self::admin_validator($request);
                    break;
                case 'institution':
                    self::institution_validator($request->all());
                    break;
                case 'employee':
                    self::employee_validator($request->all());
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid role: $role");
            }
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->role = $role;
            $user->password = Hash::make($request->input('password'));
            $user->save();
            // Call registerRole method to handle role-specific logic
            self::registerRole($request, $user, $role);

            DB::commit();

            return [
                'status' => true,
                'message' => 'Registration successful',
                'user' => $user,
                'status_code' => 200
            ];
            } catch (ValidationException $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()->toArray(),
                'status_code' => 400,
            ];
            } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'status_code' => 500,
            ];
            }
    }
    public static function login(Request $request)
    {
        try {
            $loginUserData = $request->validate([
                'username' => 'required|string', // Change 'email' to 'username'
                'password' => 'required|min:8'
            ]);

            $user = User::where('username', $loginUserData['username'])->first(); // Change 'email' to 'username'

            if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
            $rememberToken = Str::random(1000);
            $user->remember_token = $rememberToken;

            // Save the user to persist the token
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Login successful re',
                'access_token' => $rememberToken
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public static function registerRole(Request $request, $user, $role)
    {
        switch ($role) {
            case 'admin':
                AdminService::addAdmin($user->id);
                break;
            case 'institution':
                InstitutionService::add($user->id , $request);
                // Handle institution-specific logic here
                break;
            case 'employee':
                AdminService::addAdmin($user->id);
                // Handle employee-specific logic here
                break;
            default:
                throw new \InvalidArgumentException("Invalid role: $role");
        }
    }
}
