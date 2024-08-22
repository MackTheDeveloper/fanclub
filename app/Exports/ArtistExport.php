<?php

namespace App\Exports;

use App\Models\Artist;
use App\Models\ArtistSocialMedia;
use App\Models\Subscription;
use App\Models\UserProfilePhoto;
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

class ArtistExport implements FromArray, WithHeadings, WithStyles
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
            'Phone',
            'Address',
            'Country',
            'Status',
            'Approved',
            'Bio',
            'Event',
            'News Detail',
            'Interest',
            'Social Link',
            '# Fans Subscribed',
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
        $country = isset($request['country'])?$request['country']:"";
        $approval = isset($request['approval'])?$request['approval']:"";

        $query = Artist::whereNull('deleted_at')->where('role_id', '2');
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%');
            });
        }
        if (isset($status) && $status!='') {
            $query->where('is_active', $status);
        }
        if ($country) {
            $query->where('country', $country);
        }
        if (isset($approval) && $approval!='') {
            $query->where('is_verify', $approval);
        }
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = date($startDate);
            $endDate = date($endDate);
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween(DB::raw("date_format(created_at,'%Y-%m-%d')"), [$startDate, $endDate]);
            });
        }
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = ($value->is_active)?'Active':'Inactive';
            $isApprove = ($value->is_verify)?'Approved':'Not Approved';
            $artistDetails = ArtistDetail::getArtistDetail($value->id);
            $bio = (isset($artistDetails->bio)) ? $artistDetails->bio : '';
            $event = (isset($artistDetails->event)) ? $artistDetails->event : '';
            $news_detail = (isset($artistDetails->news_detail)) ? $artistDetails->news_detail : '';
            $interest = (isset($artistDetails->interest)) ? $artistDetails->interest : '';
            $no_subscription = (isset($artistDetails->no_subscription)) ? $artistDetails->no_subscription : '0';
            $interest =  Interest::getInteres($interest);
            $artistSocialMedia = ArtistSocialMedia::getSocialMedia($value->id);
            $data[] = [$value->firstname . ' ' . $value->lastname, $value->email, $value->phone, $value->address,
                        $value->country,$isActive,$isApprove,$bio,$event,$news_detail,$interest
                        ,$artistSocialMedia,$no_subscription,getFormatedDate($value->created_at)];
        }
        // dd($data);
        return $data;
    }
}
