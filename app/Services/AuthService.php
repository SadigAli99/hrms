<?php

namespace App\Services;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Traits\FileUploadTrait;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    use FileUploadTrait;

    protected $userRepo;

    public function __construct(
        UserInterface $userRepo
    ) {
        $this->userRepo = $userRepo;
    }

    public function login(array $data, $guard = 'web')
    {
        $credentials = ['email' => $data['email'], 'password' => $data['password']];
        $remember_me = isset($data['remember_me']) ? true : false;
        $authAttempt = Auth::guard($guard)->attempt($credentials, $remember_me);
        if (!$authAttempt) throw new InvalidCredentialsException();
    }

    public function update_profile(ProfileRequest $request)
    {
        $data = $request->validated();
        $user = $this->userRepo->getById(auth()->id());
        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUpload($request->file('image'), 'users');
            $this->fileDelete($user->image ?? '');
        }
        $data['password'] = isset($data['new_password']) ? bcrypt($data['new_password']) : $user->password;
        $user->update($data);
    }

    public function delete_image()
    {
        $user = $this->userRepo->getById(auth()->id());
        $this->fileDelete($user->image);
        $this->userRepo->update($user, ['image' => null]);
    }

    public function logout($guard = 'web')
    {
        Auth::guard($guard)->logout();
        request()->session()->regenerateToken();
    }
}
