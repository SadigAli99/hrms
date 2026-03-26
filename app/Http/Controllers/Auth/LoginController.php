<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
        $this->middleware('guest')->only(['login']);
        $this->middleware('auth')->only(['update_profile', 'logout']);
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $this->authService->login($data);
            return redirect()->route('dashboard');
        } catch (InvalidCredentialsException $ex) {
            Log::error(json_encode([$ex->getFile(), $ex->getLine(), $ex->getMessage()]));
            return redirect()->back()->with('error_message', $ex->getMessage());
        } catch (\Exception $ex) {
            Log::error(json_encode([$ex->getFile(), $ex->getLine(), $ex->getMessage()]));
            return redirect()->back()->with('error_message', $ex->getMessage());
        }
    }

    public function update_profile(ProfileRequest $request)
    {
        try {
            $this->authService->update_profile($request);
            return redirect()->back()->with('success_message', 'Profil uğurla yeniləndi');
        } catch (\Exception $ex) {
            Log::error(json_encode($ex->getFile(), $ex->getLine(), $ex->getMessage()));
            return redirect()->back()->with('error_message', $ex->getMessage());
        }
    }

    public function delete_image(){
        try {
            $this->authService->delete_image();
            return redirect()->back()->with('success_message', 'Profil uğurla yeniləndi');
        } catch (\Exception $ex) {
            Log::error(json_encode($ex->getFile(), $ex->getLine(), $ex->getMessage()));
            return redirect()->back()->with('error_message', $ex->getMessage());
        }
    }
}
