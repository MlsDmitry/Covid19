<?php

namespace App\Http\Controllers;

use App\Mail\CriticalNews;
use App\Mail\Personal;
use App\Mail\Stats;
use App\Preferences;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials, ['exp' => Carbon::now()->addHour()])) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'surname' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'phone' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'passport' => 'required|unique:users'
            //regex:/(?:[IVX]{2}[0-9]{2}\-[А-Я]{2}|[0-9]{4}\s*[0-9]{3}\s*[0-9]{3})\/
//            |regex:/(+7)[0-9]{9}/
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
//'name', 'surname', 'city', 'passport', 'street', 'phone', 'email', 'password',
        $user = User::create([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'city' => $request->get('city'),
            'passport' => $request->get('passport'),
            'street' => $request->get('passport'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        Preferences::create([
            'user_id' => $user->id
        ]);
        $custom_claims = [];
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
    public function sendMail(Request $request) {
        switch ($request->get('id')){
            case 'critical_news':
                $mail = new CriticalNews([]);
                break;
            case 'stats':
                $mail = new Stats([]);
                break;
            case 'personal':
                $mail = new Personal(['user' => Auth::user()]);
                break;
        }
        try {
            Mail::to(Auth::user()->email)->send($mail);
        } catch (\Exception $e) {
            echo 'Error -> '. $e;
        }
    }
}
