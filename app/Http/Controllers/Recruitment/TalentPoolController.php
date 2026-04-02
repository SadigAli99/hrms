<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\TalentPool\AddToVacancyRequest;
use App\Repositories\Interfaces\TalentPoolInterface;
use App\Repositories\Interfaces\VacancyInterface;
use Illuminate\Http\Request;

class TalentPoolController extends Controller
{
    protected $talentPoolRepo;
    protected $vacancyRepo;

    public function __construct(TalentPoolInterface $talentPoolRepo,VacancyInterface $vacancyRepo)
    {
        $this->talentPoolRepo = $talentPoolRepo;
        $this->vacancyRepo = $vacancyRepo;
    }

    public function index(Request $request)
    {
        $talent_pools = $this->talentPoolRepo->filter($request->all());
        $vacancies = $this->vacancyRepo->all();
        return view('pages.recruitment.talent-pools.index', compact('talent_pools','vacancies'));
    }

    public function add_to_vacancy(int $id, AddToVacancyRequest $request)
    {
        try {
            $data = $request->validated();
            $talent_pool = $this->talentPoolRepo->getById($id);
            $this->talentPoolRepo->add_to_vacancy($talent_pool, $data);
            return response()->json([
                'success' => true,
                'message' => 'Namizəd vakansiyaya əlavə olundu'
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function filter(Request $request)
    {
        try {
            $talent_pools = $this->talentPoolRepo->filter($request->all());
            $view = view('pages.recruitment.talent-pools.partials.list', compact('talent_pools'))->render();
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
