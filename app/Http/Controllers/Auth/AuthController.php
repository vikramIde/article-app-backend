<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHandler;
use App\Helpers\ValidationHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','un']]);
    }

    /**
     * Get a JWT via given request signup.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validation_rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6']
        ];

        $validation_inputs = $request->all();

        $validator_result = ValidationHelper::validator($validation_inputs, $validation_rules);

        if($validator_result['code'] !== 200)
        {
            return ResponseHandler::buildUnsuccessfulValidationResponse($validator_result);
        }

        return User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validation_rules = [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6']
        ];

        $validation_inputs = $request->all();

        $validator_result = ValidationHelper::validator($validation_inputs, $validation_rules);

        if($validator_result['code'] !== 200)
        {
            return ResponseHandler::buildUnsuccessfulValidationResponse($validator_result);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return ResponseHandler::buildUnsuccessfulResponse('login_failed', ['Unauthorized'],'Invalid Username/password', 401);
        }

        return ResponseHandler::buildSuccessfulResponse([$this->respondWithToken($token)],'Login successfull');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return ResponseHandler::buildSuccessfulResponse([response()->json(auth()->user())], 'You are authorised User');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return ResponseHandler::buildSuccessfulResponse([], 'Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function un()
    {
        return ResponseHandler::buildUnsuccessfulResponse('invalid_token',['Token expired or Invalid'],'Token is expired or invalid, please try again later',401);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
