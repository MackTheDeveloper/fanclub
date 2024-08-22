<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Events;
use App\Models\Package;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use DB;
use Session;
use App\Traits\ExportTrait;

class EventsController extends Controller
{
    use ExportTrait;

    public function getListOfEvents(Request $request)
    {
        if($request->ajax())
        {
            try
            {
                $id = Auth::guard('admin')->user()->id;

                DB::statement(DB::raw('set @rownum=0'));

                $events = Events::select('id', 'event_name', 'event_image', 'event_desc','is_active',
                                DB::raw('@rownum  := @rownum  + 1 AS rownum'),DB::raw("date_format(created_at,'%Y-%m-%d %h:%i:%s') as e_created_at"))
                                ->whereNull('deleted_at')
                                ->orderBy('updated_at','desc')
                                ->get();
                // dd($events);
                return Datatables::of($events)->rawColumns(['event_desc'])->make(true);
            }
            catch (\Exception $e)
            {
                return view('errors.500');
            }
        }
        return view('admin.events.event.index');
    }

    public function eventsAddView()
    {
        return view('admin.events.event.add');
    }

    public function addEvent(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'event_image' => 'mimes:jpeg,jpg,png,gif',
                'event_name' => 'required',
                'event_desc' => 'required',
                'is_active' => 'required',
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $event = new Events;
            $event->event_name = $request->event_name;
            $event->event_desc = $request->event_desc;
            $event->is_active = $request->is_active;

            if($request->hasFile('event_image'))
            {
                $image = $request->file('event_image');
                $filename = $image->getClientOriginalName();
                $image->move(public_path('assets/images/events/'), $filename);
                $event->event_image = $request->file('event_image')->getClientOriginalName();
            }
            $event->save();
            $notification = array(
                'message' => 'Event added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/event/list')->with($notification);
        }
        catch (\Exception $e)
        {
            Session::flash('error', $e->getMessage());
            return redirect(config('app.adminPrefix').'/event/list');
        }
    }

    public function eventEditView($id)
    {
        $event = Events::findOrFail($id);
        if(!empty($event))
        {
            return view('admin.events.event.edit',compact('event'));
        }
    }

    public function updateEvent(Request $request)
    {
        $event = Events::findOrFail($request->id);
        if(!empty($event))
        {
            $validator = Validator::make($request->all(), [
                'event_image' => 'mimes:jpeg,jpg,png,gif',
                'event_name' => 'required',
                'event_desc' => 'required',
                'is_active' => 'required',
            ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $event->event_name = $request->event_name;
            $event->event_desc = $request->event_desc;
            $event->is_active = $request->is_active;

            if($request->hasFile('event_image'))
            {
                $image = $request->file('event_image');
                $filename = $image->getClientOriginalName();
                $image->move(public_path('assets/images/events/'), $filename);
                $event->event_image = $request->file('event_image')->getClientOriginalName();
            }
            if($event->save())
            {
                $notification = array(
                    'message' => 'Event updated successfully!',
                    'alert-type' => 'success'
                );
                return redirect(config('app.adminPrefix').'/event/list')->with($notification);
            }
        }
    }

    public function eventActiveInactive(Request $request)
    {
        try
        {
            $event = Events::where('id',$request->event_id)->first();
            if($request->is_active == 1)
            {
                $event->is_active = $request->is_active;
                $msg = "Event Activated Successfully!";
            }
            else
            {
                $event->is_active = $request->is_active;
                $msg = "Event Deactivated Successfully!";
            }
            $event->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        }
        catch(\Exception $ex)
        {
            return view('errors.500');
        }
    }

    public function deleteEvent(Request $request)
    {
        $event = Events::where('id', $request->event_id)->first();
        if($event)
        {
            $event->deleted_at = Carbon::now();
            $event->save();
            $packages = Package::where('event_id',$request->event_id)->get();

            foreach ($packages as $package)
            {
                $package->deleted_at = Carbon::now();
                $package->save();
            }
            $result['status'] = 'true';
            $result['msg'] = "Event Deleted Successfully!";
            return $result;
        }
        else
        {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }

    public function getExportEvents(Request $request)
    {
        try
        {
            $events = Events::select('id as Id', 'event_name as Event Name', 'event_desc as Event Description')
                ->orderBy('id','desc')
                ->get()
                ->toArray();
            $sheetTitle = 'Events';
            return $this->exportEvents($events, $sheetTitle);
        }
        catch(\Exception $ex)
        {
            return redirect($request->segment(1).'/event/list');
        }
    }
}

