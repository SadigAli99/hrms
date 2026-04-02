<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\Department\DepartmentRequest;
use App\Models\Department;
use App\Repositories\Interfaces\DepartmentInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentRepo;
    protected $userRepo;

    public function __construct(
        DepartmentInterface $departmentRepo,
        UserInterface $userRepo
    ) {
        $this->departmentRepo = $departmentRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $departments = $this->departmentRepo->filter($request->all());
        return view('pages.recruitment.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = $this->departmentRepo->all();
        $users = $this->userRepo->all();
        return view('pages.recruitment.departments.create', compact('users', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        $data = $request->validated();
        $response = $this->departmentRepo->create($data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('department.index')->with('success_message', $response['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return view('pages.recruitment.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $departments = $this->departmentRepo->all();
        $users = $this->userRepo->all();
        return view('pages.recruitment.departments.edit', compact('department', 'departments', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, string $id)
    {
        $data = $request->validated();
        $department = $this->departmentRepo->getById($id);
        $response = $this->departmentRepo->update($department, $data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('department.index')->with('success_message', $response['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $response = $this->departmentRepo->softDelete($department);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('department.index')->with('success_message', $response['message']);
    }

    /**
     * Filter with Ajax
     */

    public function filter(Request $request)
    {
        try {
            $departments = $this->departmentRepo->filter($request->all());
            $view = view('pages.recruitment.departments.partials.list', compact('departments'))->render();

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
