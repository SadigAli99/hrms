<?php

namespace App\Repositories\Implementations;

use App\Enums\Application\Source;
use App\Enums\Application\Status;
use App\Models\TalentPool;
use App\Repositories\Interfaces\ApplicationInterface;
use App\Repositories\Interfaces\TalentPoolInterface;

class TalentPoolRepository extends GenericRepository implements TalentPoolInterface
{

    protected $applicationRepo;

    public function __construct(ApplicationInterface $applicationRepo)
    {
        $this->model = TalentPool::class;
        $this->applicationRepo = $applicationRepo;
    }

    public function getByCandidateAndApplication(int $candidateId, int $sourceApplicationId)
    {
        return $this->model::query()
            ->where('candidate_id', $candidateId)
            ->where('source_application_id', $sourceApplicationId)
            ->first();
    }

    public function create(array $data)
    {
        $data['added_by'] = auth()->id();
        $talent_pool = $this->getByCandidateAndApplication($data['candidate_id'], $data['source_application_id']);
        if ($talent_pool) {
            return $this->update($talent_pool, $data);
        }
        return parent::create($data);
    }

    public function add_to_vacancy(TalentPool $talentPool, array $data)
    {
        if ($data['vacancy_id'] == $talentPool->source_vacancy_id) {
            throw new \RuntimeException('Eyni vakansiyaya yenidən əlavə etmək olmaz');
        }

        $existingApplication = $this->applicationRepo->getByCandidate(
            $data['vacancy_id'],
            $talentPool->candidate_id
        );

        if ($existingApplication) {
            throw new \RuntimeException('Bu namizəd artıq seçilmiş vakansiyaya əlavə olunub');
        }

        $this->applicationRepo->create([
            'vacancy_id' => $data['vacancy_id'],
            'candidate_id' => $talentPool->candidate_id,
            'owner_user_id' => auth()->id(),
            'application_source' => Source::TALENT_POOL,
            'status' => Status::NEW,
            'applied_at' => now(),
            'notes' => $data['note'] ?? null,
        ]);
    }

    public function filter(array $data = [])
    {
        $query = $this->model::query()
            ->with('candidate.profiles', 'vacancy');
        $limit = $data['limit'] ?? 10;
        $sortBy = $data['sort_by'] ?? 'id';
        $ascDesc = $data['asc_desc'] ?? 'desc';

        if (isset($data['search']) && !empty($data['search'])) {
            $query->where(function ($q) use ($data) {
                $q
                    ->whereHas('candidate', function ($qq) use ($data) {
                        $qq->where('full_name', 'like', "%{$data['search']}%");
                    })
                    ->orWhereHas('vacancy', function ($qq) use ($data) {
                        $qq->where('title', 'like', "%{$data['search']}%");
                    });
            });
        }

        if (isset($data['vacancy_id']) && !empty($data['vacancy_id'])) {
            $query->where('source_vacancy_id', $data['vacancy_id']);
        }

        if (isset($data['category']) && !empty($data['category'])) {
            $query->where('category', $data['category']);
        }

        $talent_pools = isset($data['has_export']) && $data['has_export'] == 1
            ? $query->orderBy($sortBy, $ascDesc)->get()
            : $query
            ->orderBy($sortBy, $ascDesc)
            ->paginate($limit)
            ->withQueryString();

        return $talent_pools;
    }
}
