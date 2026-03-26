<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\PermissionInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends GenericRepository implements PermissionInterface
{

    public function __construct()
    {
        $this->model = Permission::class;
    }

    public function getByGroup(): array
    {
        $items = $this->model::query()
            ->select(['id', 'name', 'group_name'])
            ->orderBy('name', 'asc')
            ->get()
            ->groupBy('group_name');

        $result = [];

        foreach ($items as $group => $rows) {
            $result[$group] = $rows->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
            ])->values()->toArray();
        }

        return $result;
    }

    public function filter(array $data)
    {
        $query = $this->model::query();
        $limit = $data['limit'] ?? 10;
        $sortBy = $data['sort_by'] ?? 'id';
        $ascDesc = $data['asc_desc'] ?? 'desc';
        if (isset($data['search']) && !empty($data['search'])) {
            $query->where(function ($q) use ($data) {
                return $q
                    ->where('name', 'like', "%{$data['search']}%")
                    ->orWhere('group_name', 'like', "%{$data['search']}%");
            });
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        $permissions = isset($data['has_export']) && $data['has_export'] == 1 ?
            $query->orderBy($sortBy, $ascDesc)->get() :
            $query
            ->orderBy($sortBy, $ascDesc)
            ->paginate($limit)
            ->withQueryString();

        return $permissions;
    }

    public function export(array $data)
    {
        $permissions = $this->filter($data);

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->setTitle('Permissions');

        $headers = ['#', 'Ad', 'Qrup', 'Status', 'Yaranma tarixi'];
        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FEFEFEFE'],
            ]
        ]);

        $row = 2;
        $i = 1;
        foreach ($permissions as $permission) {
            $sheet->setCellValue("A{$row}", $i++);
            $sheet->setCellValue("B{$row}", $permission->name);
            $sheet->setCellValue("C{$row}", $permission->group_name);
            $sheet->setCellValue("D{$row}", $permission->status == 1 ? 'Aktiv' : 'Deaktiv');
            $sheet->setCellValue("E{$row}", $permission->created_at->format('d.m.Y H:i'));
            $row++;
        }

        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        $fileName = 'permissions' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadSheet) {
            $writer = new Xlsx($spreadSheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
