<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\Fan;
use Auth;
use Request;

class FansExport implements FromArray, WithHeadings, WithStyles
// class FansExport implements FromCollection, WithHeadings, WithStyles
{
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
            'ID',
            'Name',
            'Email',
            'Phone',
            'Address',
            'Country',
            'Subscription',
            'Status',
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
        $search = isset($request['search'])?$request['search']:"";
        $startDate = isset($request['startDate'])?$request['startDate']:"";
        $endDate = isset($request['endDate'])?$request['endDate']:"";
        $status = isset($request['status'])?$request['status']:"";
        $subscription = isset($request['subscription'])?$request['subscription']:"";
        $country = isset($request['country'])?$request['country']:"";
        // dd($request);
        $query = Fan::with('subscriptionPlan')->whereNull('deleted_at')->where('role_id','3');
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%');
            });
        }
        if (isset($status) && $status!='') {
            $query = $query->where('is_active', $status);
        }
        if ($subscription) {
            $query = $query->where('current_subscription', $subscription);
        }
        if ($country) {
            $query = $query->where('country', $country);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $query = $query->where(function($q) use ($startDate,$endDate){
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = ($value->is_active)?'Active':'Inactive';
            $mySubscriptions = $value->subscriptionPlan?$value->subscriptionPlan->subscription_name:'-';
            $data[] = [$value->id, $value->firstname.' '.$value->lastname,$value->email, $value->phone, $value->address, $value->country,$mySubscriptions , $isActive,getFormatedDate($value->created_at)];
        }
        return $data;
    }
}
