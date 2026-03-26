<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RolePermissionRequest;
use App\Http\Requests\Admin\RoleRequest;
use App\Repositories\Interfaces\PermissionInterface;
use App\Repositories\Interfaces\RoleInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    protected $roleRepo;
    protected $permissionRepo;

    public function __construct(
        RoleInterface $roleRepo,
        PermissionInterface $permissionRepo,
    ) {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $roles = $this->roleRepo->filter($data);
        return view('main.pages.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('main.pages.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $response = $this->roleRepo->create($data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('role.index')->with('success_message', $response['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->roleRepo->getById($id);
        return view('main.pages.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = $this->roleRepo->getById($id);
        return view('main.pages.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $data = $request->validated();
        $role = $this->roleRepo->getById($id);
        $response = $this->roleRepo->update($role, $data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('role.index')->with('success_message', $response['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $response = $this->roleRepo->softDelete($role);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('role.index')->with('success_message', $response['message']);
    }

    /**
     * Filter With Ajax
     */

    public function filter(Request $request)
    {
        try {
            $roles = $this->roleRepo->filter($request->all());
            $view = view('main.pages.roles.partials.list', compact('roles'))->render();

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

    public function get_permission(Role $role)
    {
        $roles = $this->roleRepo->all();
        $rolePermissions = $this->roleRepo->getPermissions($role->id);
        $permissionGroups = $this->permissionRepo->getByGroup();
        return view('main.pages.roles.assign-permission', compact('role', 'roles', 'rolePermissions', 'permissionGroups'));
    }

    public function assign_permission(Role $role, RolePermissionRequest $request)
    {
        $response = $this->roleRepo->assignPermissions($role->id, $request->validated());
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('role.index')->with('success_message', $response['message']);
    }
}
