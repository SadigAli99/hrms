<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\UserInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;

class UserRepository extends GenericRepository implements UserInterface
{

    public function __construct()
    {
        $this->model = User::class;
    }

    public function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $response = parent::create($data);
        if ($response['success']) {
            $user = $response['item'];
            $user->roles()->sync([$data['role_id']]);
        }
        return $response;
    }

    public function update($model, array $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $model->password;
        }
        $response =  parent::update($model, $data);
        if ($response['success']) {
            $model->roles()->sync([$data['role_id']]);
        }
        return $response;
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
                    ->orWhere('email', 'like', "%{$data['search']}%");
            });
        }

        if (isset($data['role']) && !empty($data['role'])) {
            $query->whereHas('roles', function ($q) use ($data) {
                return $q->where('id', $data['role']);
            });
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        $users = isset($data['has_export']) && $data['has_export'] == 1 ?
            $query->orderBy($sortBy, $ascDesc)->get() :
            $query
            ->orderBy($sortBy, $ascDesc)
            ->paginate($limit)
            ->withQueryString();

        return $users;
    }

    public function export(array $data)
    {
        $users = $this->filter($data);

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->setTitle('Users');

        $headers = ['#', 'Ad', 'Email', 'Rol', 'Status', 'Yaranma tarixi'];
        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:f1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FEFEFEFE'],
            ]
        ]);

        $row = 2;
        $i = 1;
        foreach ($users as $user) {
            $sheet->setCellValue("A{$row}", $i++);
            $sheet->setCellValue("B{$row}", $user->name);
            $sheet->setCellValue("C{$row}", $user->email);
            $sheet->setCellValue("D{$row}", $user->getRoleNames()->implode(', '));
            $sheet->setCellValue("E{$row}", $user->status == 1 ? 'Aktiv' : 'Deaktiv');
            $sheet->setCellValue("F{$row}", $user->created_at->format('d.m.Y H:i'));
            $row++;
        }

        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A2');

        $fileName = 'users' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadSheet) {
            $writer = new Xlsx($spreadSheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
