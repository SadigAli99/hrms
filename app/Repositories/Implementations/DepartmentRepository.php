<?php

namespace App\Repositories\Implementations;

use App\Models\Department;
use App\Repositories\Interfaces\DepartmentInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DepartmentRepository extends GenericRepository implements DepartmentInterface
{
    public function __construct()
    {
        $this->model = Department::class;
    }

    public function filter(array $data = [])
    {
        $query = $this->model::query();
        $limit = $data['limit'] ?? 10;
        $sortBy = $data['sort_by'] ?? 'id';
        $ascDesc = $data['asc_desc'] ?? 'desc';
        if (isset($data['search']) && !empty($data['search'])) {
            $query->where('name', 'like', "%{$data['search']}%");
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        $departments = isset($data['has_export']) && $data['has_export'] == 1 ?
            $query->orderBy($sortBy, $ascDesc)->get() :
            $query
            ->orderBy($sortBy, $ascDesc)
            ->paginate($limit)
            ->withQueryString();

        return $departments;
    }

    public function export(array $data)
    {
        $departments = $this->filter($data);

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
        foreach ($departments as $role) {
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

        $fileName = 'departments' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadSheet) {
            $writer = new Xlsx($spreadSheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
