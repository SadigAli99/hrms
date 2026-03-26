<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Repositories\Interfaces\PermissionInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $permissionRepo;

    public function __construct(
        PermissionInterface $permissionRepo
    ) {
        $this->permissionRepo = $permissionRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $permissions = $this->permissionRepo->filter($data);
        return view('main.pages.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('main.pages.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $data = $request->validated();
        $response = $this->permissionRepo->create($data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('permission.index')->with('success_message', $response['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = $this->permissionRepo->getById($id);
        return view('main.pages.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = $this->permissionRepo->getById($id);
        return view('main.pages.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, string $id)
    {
        $data = $request->validated();
        $permission = $this->permissionRepo->getById($id);
        $response = $this->permissionRepo->update($permission, $data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('permission.index')->with('success_message', $response['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $response = $this->permissionRepo->softDelete($permission);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('permission.index')->with('success_message', $response['message']);
    }

    /**
     * Filter With Ajax
     */

    public function filter(Request $request)
    {
        try {
            $permissions = $this->permissionRepo->filter($request->all());
            $view = view('main.pages.permissions.partials.list', compact('permissions'))->render();

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
