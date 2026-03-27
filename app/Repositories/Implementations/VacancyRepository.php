<?php

namespace App\Repositories\Implementations;

use App\Exceptions\CRUD\CreateException;
use App\Models\Vacancy;
use App\Repositories\Interfaces\VacancyInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class VacancyRepository extends GenericRepository implements VacancyInterface
{

    public function __construct()
    {
        $this->model = Vacancy::class;
    }

    public function create(array $data)
    {
        try {
            if (!isset($data['requirements_text']) || empty($data['requirements_text']))
                $data['requirements_text'] = $this->generate_requirement_text($data['vacancy_requirements'] ?? []);

            DB::beginTransaction();
            $response = parent::create($data);
            if ($response['success']) {
                $vacancy = $response['item'];
                $this->add_requirements($vacancy, $data['vacancy_requirements'] ?? []);
            }
            DB::commit();
            return $response;
        } catch (Exception $ex) {
            Log::error(json_encode([$ex->getMessage(), $ex->getLine(), $ex->getFile()]));
            DB::rollBack();
            return $response;
        }
    }

    public function update($model, array $data)
    {
        try {
            if (!isset($data['requirements_text']) || empty($data['requirements_text']))
                $data['requirements_text'] = $this->generate_requirement_text($data['vacancy_requirements'] ?? []);

            DB::beginTransaction();
            $response = parent::update($model, $data);
            if ($response['success']) {
                $vacancy = $response['item'];
                $this->add_requirements($vacancy, $data['vacancy_requirements'] ?? []);
            }
            DB::commit();
            return $response;
        } catch (Exception $ex) {
            Log::error(json_encode([$ex->getMessage(), $ex->getLine(), $ex->getFile()]));
            DB::rollBack();
            return $response;
        }
    }

    public function add_requirements(Vacancy $vacancy, array $requirements)
    {
        $vacancy->requirements()->delete();

        foreach ($requirements as $requirement) {
            $vacancy->requirements()->create([
                'requirement_type' => isset($requirement['type']) ? $requirement['type'] : '',
                'requirement_name' => isset($requirement['label']) ? $requirement['label'] : '',
                'requirement_value' => isset($requirement['value']) ? $requirement['value'] : '',
                'is_required' => isset($requirement['required']) && $requirement['required'],
            ]);
        }
    }

    public function generate_requirement_text(array $requirements): string
    {
        $lines = collect($requirements)
            ->groupBy('type')
            ->map(function ($group, $type) {
                $values = $group->map(function ($item) {
                    return !empty($item['value'])
                        ? $item['label'] . ' : ' . $item['value']
                        : $item['label'];
                })->implode(', ');

                return ucfirst($type) . ' : ' . $values;
            })
            ->values()
            ->implode(PHP_EOL);

        return $lines;
    }

    public function filter(array $data)
    {
        $query = $this->model::query();
        $limit = $data['limit'] ?? 10;
        $sortBy = $data['sort_by'] ?? 'id';
        $ascDesc = $data['asc_desc'] ?? 'desc';

        if (isset($data['search']) && !empty($data['search'])) {
            $query->where('title', 'like', "%{$data['search']}%");
        }

        if (isset($data['department_id']) && !empty($data['department_id'])) {
            $query->where('department_id', $data['department_id']);
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        $vacancies = isset($data['has_export']) && $data['has_export'] == 1
            ? $query->orderBy($sortBy, $ascDesc)->get()
            : $query->orderBy($sortBy, $ascDesc)->paginate($limit)->withQueryString();

        return $vacancies;
    }

    public function export(array $data)
    {
        $vacancies = $this->filter($data);

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->setTitle('Roles');

        $headers = ['#', 'Ad', 'Status', 'Yaranma tarixi'];
        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FEFEFEFE'],
            ]
        ]);

        $row = 2;
        $i = 1;
        foreach ($vacancies as $role) {
            $sheet->setCellValue("A{$row}", $i++);
            $sheet->setCellValue("B{$row}", $role->name);
            $sheet->setCellValue("C{$row}", $role->status == 1 ? 'Aktiv' : 'Deaktiv');
            $sheet->setCellValue("D{$row}", $role->created_at->format('d.m.Y H:i'));
            $row++;
        }

        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        $fileName = 'vacancies' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadSheet) {
            $writer = new Xlsx($spreadSheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
