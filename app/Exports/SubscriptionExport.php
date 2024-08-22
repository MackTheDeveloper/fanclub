<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Subscription;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\Fan;
use Auth;
use Request;

class SubscriptionExport implements FromArray, WithHeadings, WithStyles
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
            'Name',
            'Email',
            'Phone',
            'Subscription Plan',
            'Amount',
            'Status',
            'Payment ID',
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
        $search = $request['search'];
        $startDate = $request['startDate'];
        $endDate = $request['endDate'];
        $status = $request['status'];
        $subscription = $request['subscription'];
        $query = Subscription::getSubscriptionDetails();

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.email', 'like', '%' . $search . '%')
                    ->orWhere('subscriptions.created_at', 'like', '%' . $search . '%');
            });

        }
        if ($status) {
            $query = $query->where('status', $status);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date($startDate);
            $endDate = date($endDate);
            $query = $query->where(function($q) use ($startDate,$endDate){
                $q->whereBetween('subscriptions.created_at', [$startDate, $endDate]);
            });
        }
        if ($subscription && $subscription!='all') {
            $query = $query->where('subscription__plans.id', $subscription);
        }
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value)
        {
            $isActive = ($value->status)?'Paid':'Unpaid';
            $data[] = [$value->firstname, $value->email, $value->phone,$value->duration , $value->amount, $isActive ,$value->payment_id ,getFormatedDate($value->created_at)];
        }
        return $data;
    }
}
