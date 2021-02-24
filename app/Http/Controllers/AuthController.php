<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Middleware\JwtMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;


class AuthController extends Controller
{
     /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email'    => 'required|email',
                'password' => 'required|string|min:6',
            ]
        );
        
        if ($validator->fails()) {
              return response()->json($validator->errors(), 400);
        }

        $token_validity = (24 * 60);

        $this->guard()->factory()->setTTL($token_validity);

        try {
            if (!$token = $this->guard()->attempt($validator->validated())) {
                return response()->json(['error' => 'Invalid Credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return $this->respondWithToken($token);
    }

    public function studentregister(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstname'     => 'required|string|between:2,100',
                'lastname'     => 'required|string|between:2,100',
                'email'    => 'required|email|unique:users',
                'studentId'    => 'required|string|unique:users',
                'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        $role=Role::findByName('Student');
        $user->assignRole($role);
        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }
    
    public function universityregister(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstname'     => 'required|string|between:2,100',
                'lastname'     => 'required|string|between:2,100',
                'nameuniversity'     => 'required|string|between:2,100',
                'email'    => 'required|email|unique:users',
                'phonenumber'   => 'required|numeric|unique:users|min:11',
                'password' => 'required|confirmed|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        $role=Role::findByName('University');
        $user->assignRole($role);
        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    

    public function entrepreneurregister(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstname'     => 'required|string|between:2,100',
                'lastname'     => 'required|string|between:2,100',
                'email'    => 'required|email|unique:users',
                'companyname'    => 'required|string|unique:users',
                'phonenumber'   => 'required|numeric|unique:users|min:11',
                'password' => 'required|confirmed|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        $role=Role::findByName('Entrepreneur');
        $user->assignRole($role);
        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    }

    public function mentorregister(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstname'     => 'required|string|between:2,100',
                'lastname'     => 'required|string|between:2,100',
                'email'    => 'required|email|unique:users',
                'interestedindustry'    => 'required|string',
                'phonenumber'   => 'required|numeric|unique:users|min:11',
                'password' => 'required|confirmed|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );
        $role=Role::findByName('Mentor');
        $user->assignRole($role);
        return response()->json(['message' => 'User created successfully', 'user' => $user]);

    }
    
    public function user()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
                }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                 return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

             return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    public function getAllUser()
    {
        $users = User::where('id', '!=', $this->guard()->id())->get();
        return $users;
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
            'expires_in' => $this->guard()->factory()->getTTL() * 0.1
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
