<?php

namespace App\Http\Controllers\Recruitment;

use App\Enums\Vacancy\EmploymentType;
use App\Enums\Vacancy\Level;
use App\Enums\Vacancy\RequirementType;
use App\Enums\Vacancy\Status;
use App\Enums\Vacancy\WorkMode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\Vacancy\VacancyRequest;
use App\Models\Vacancy;
use App\Repositories\Interfaces\DepartmentInterface;
use App\Repositories\Interfaces\VacancyInterface;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    protected $departmentRepo;
    protected $vacancyRepo;

    public function __construct(
        DepartmentInterface $departmentRepo,
        VacancyInterface $vacancyRepo
    ) {
        $this->departmentRepo = $departmentRepo;
        $this->vacancyRepo = $vacancyRepo;
    }

    public function index(Request $request)
    {
        $departments = $this->departmentRepo->all();
        $statuses = Status::getValues();
        $vacancies = $this->vacancyRepo->filter($request->all());
        return view('pages.recruitment.vacancies.index', compact(
            'departments',
            'statuses',
            'vacancies'
        ));
    }

    public function create()
    {
        $employment_types = EmploymentType::getValues();
        $levels = Level::getValues();
        $requirement_types = RequirementType::getValues();
        $statuses = Status::getValues();
        $work_modes = WorkMode::getValues();
        $departments = $this->departmentRepo->all();
        return view('pages.recruitment.vacancies.create', compact(
            'employment_types',
            'levels',
            'requirement_types',
            'statuses',
            'work_modes',
            'departments',
        ));
    }

    public function store(VacancyRequest $request)
    {
        $data = $request->validated();
        $response = $this->vacancyRepo->create($data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('vacancy.index')->with('success_message', $response['message']);
    }

    public function show(Vacancy $vacancy)
    {
        return view('pages.recruitment.vacancies.show', compact('vacancy'));
    }

    public function edit(Vacancy $vacancy)
    {
        $employment_types = EmploymentType::getValues();
        $levels = Level::getValues();
        $requirement_types = RequirementType::getValues();
        $statuses = Status::getValues();
        $work_modes = WorkMode::getValues();
        $departments = $this->departmentRepo->all();
        return view('pages.recruitment.vacancies.edit', compact([
            'vacancy',
            'employment_types',
            'levels',
            'requirement_types',
            'statuses',
            'work_modes',
            'departments',
        ]));
    }

    public function update(VacancyRequest $request, Vacancy $vacancy)
    {
        $data = $request->validated();
        $response = $this->vacancyRepo->update($vacancy, $data);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('vacancy.index')->with('success_message', $response['message']);
    }

    public function destroy(Vacancy $vacancy)
    {
        $response = $this->vacancyRepo->softDelete($vacancy);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->route('vacancy.index')->with('success_message', $response['message']);
    }

    public function filter(Request $request)
    {
        try {
            $vacancies = $this->vacancyRepo->filter($request->all());
            $view = view('pages.recruitment.vacancies.partials.list', compact('vacancies'))->render();
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
