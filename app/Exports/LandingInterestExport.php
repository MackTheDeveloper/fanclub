<?php

namespace App\Exports;

use App\Models\ComingInterest;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Request;
use App\Models\ArtistDetail;
use App\Models\Interest;

class LandingInterestExport implements FromArray, WithHeadings, WithStyles
{
    use Exportable;


    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Role',
            'Created At',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
   // public function collection()
    public function array(): array
    {

        $request = Request::all();
        // dd($request);
        $search = isset($request['search'])?$request['search']:"";
        $startDate = isset($request['startDate'])?$request['startDate']:"";
        $endDate = isset($request['endDate'])?$request['endDate']:"";
        $type = isset($request['role'])?$request['role']:"";

        $query = ComingInterest::whereNull('deleted_at');

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $startDate = date($startDate);
            $endDate = date($endDate);
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }

        if ($type && $type!='all') {
            $query = $query->where('role', $type);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date($startDate);
            $endDate = date($endDate);
            $query = $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [$value->name, $value->email,ucfirst($value->role),getFormatedDate($value->created_at)];
        }
        return $data;
    }
}
