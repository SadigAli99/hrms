<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateRequest;
use App\Http\Requests\Admin\User\EditRequest;
use App\Models\User;
use App\Repositories\Interfaces\RoleInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $roleRepo;
    protected $userRepo;

    public function __construct(
        RoleInterface $roleRepo,
        UserInterface $userRepo
    ) {
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->roleRepo->all();
        $users = $this->userRepo->filter($request->all());
        return view('main.pages.users.index', compact('roles', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleRepo->all();
        return view('main.pages.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $data = $request->validated();
        $response = $this->userRepo->create($data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('user.index')->with('success_message', $response['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('main.pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = $this->roleRepo->all();
        return view('main.pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditRequest $request, string $id)
    {
        $data = $request->validated();
        $user = $this->userRepo->getById($id);
        $response = $this->userRepo->update($user, $data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('user.index')->with('success_message', $response['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $response = $this->userRepo->softDelete($user);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('user.index')->with('success_message', $response['message']);
    }

    /**
     * Filter With Ajax
     */

    public function filter(Request $request)
    {
        try {
            $users = $this->userRepo->filter($request->all());
            $view = view('main.pages.users.partials.list', compact('users'))->render();

            return response()->json([
                'success' => true,
                'view' => $view,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }
}
