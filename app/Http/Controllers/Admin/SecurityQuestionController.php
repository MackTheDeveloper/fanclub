<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HowItWorks;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;
use Image;

class SecurityQuestionController extends Controller
{
    public function index()
    {
        return view("admin.security_questions.index");
    }

    public function list(Request $request)
    {
        $isEditable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_security_question_edit');
        $isDeletable = whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_app_delete');
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        //$orderby = ['id','title','type','description',''];


        $total = SecurityQuestions::selectRaw('count(*) as total')->whereNull('deleted_at')->first();
        $query = SecurityQuestions::whereNull('deleted_at');
        $filteredq = SecurityQuestions::whereNull('deleted_at');
        $totalfiltered = $total->total;
        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('question', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('question', 'like', '%' . $search . '%');
            });
        }

        if (isset($request->type) && $request->type!='all') {
            $filteredq = $filteredq->where('question', $request->type);
            $query = $query->where('question', $request->type);
        }

        $filteredq = $filteredq->selectRaw('count(*) as total')->first();
        $totalfiltered = $filteredq->total;
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value) {
            $action = '';
            $isActive = '';
            if ($value->status == 1) {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            $editUrl = route('editSecurity', $value->id);
            $subaction = ($isEditable)?'<li class="nav-item">'
                        .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
                    .'</li>':'';
            $subaction .= ($isEditable)?'<li class="nav-item">'
                .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->status == '1')?'Inactive':'Active').'</a>'
            .'</li>':'';
            if ($subaction ){
                $action .= '<div class="d-inline-block dropdown">'
                    .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                        .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                            .'<i class="fa fa-cog fa-w-20"></i>'
                        .'</span>'
                    .'</button>'
                    .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                        .'<ul class="nav flex-column">'
                        .$subaction
                        .'</ul>'
                    .'</div>'
                .'</div>';
            }
            $image = '<img width="50" height="50" src=' . $value->image . '/>';
            $classRow = $value->status?"":"row_inactive";
            $data[] = [$classRow,$action,$value->question,ucfirst($value->type),$value->description,$image,$isActive];
        }
        $json_data = array(
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($total->total),
            "recordsFiltered" => intval($totalfiltered),
            "data" => $data,
        );
        return Response::json($json_data);
    }


    public function add()
    {
        $model = new SecurityQuestions;
        return view('admin.security_questions.form', compact('model'));
    }

    public function store(Request $request)
    {
        $input = $request->all();

            $questions = new SecurityQuestions();
            $questions->question = $request->question;
            $questions->status = $request->is_active;
            $questions->save();

            $notification = array(
                'message' => 'Security Question Added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/security-questions/index')->with($notification);
}

    public function edit($id)
    {
        $model = SecurityQuestions::findOrFail($id);
        return view('admin.security_questions.form', compact('model'));
    }

    public function update(Request $request, $id)
    {
            $questions = SecurityQuestions::findOrFail($id);
            if (!empty($questions)) {
                $questions->question = $request->question;
                $questions->status = $request->is_active;
                $questions->update();

                $notification = array(
                    'message' => 'Security Question Updated successfully!',
                    'alert-type' => 'success'
                );

                return redirect(config('app.adminPrefix').'/security-questions/index')->with($notification);
            }

    }

    public function activeInactive(Request $request)
    {
        try {
            $model = SecurityQuestions::where('id', $request->how_it_works_id)->first();
            if ($request->status == 1) {
                $model->status = $request->status;
                $msg = "Security Question Activated Successfully!";
            } else {
                $model->status = $request->status;
                $msg = "Security Question Deactivated Successfully!";
            }
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function delete(Request $request)
    {
        $model = HowItWorksApp::where('id', $request->id)->first();
        if (!empty($model)) {
            $model->deleted_at = Carbon::now();
            $model->save();
            $result['status'] = 'true';
            $result['msg'] = "How It Works Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }
}
