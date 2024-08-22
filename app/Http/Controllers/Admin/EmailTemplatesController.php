<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EmailTemplates;
use App\Models\EmailTemplatesCc;
use App\Models\GlobalLanguage;
use Auth;
use Validator;
use Carbon\Carbon;
use DataTables;
use Response;
use DB;

class EmailTemplatesController extends Controller
{
    public function index()
    {
        $model = DB::table('email_templates')->where('deleted', '0')->orderBy('id', 'desc')->get();
        return view("admin.email_templates.index", ['model' => $model]);
    }

    public function list(Request $request)
    {
        $req = $request->all();
        $start = $req['start'];
        $length = $req['length'];
        $search = $req['search']['value'];
        $order = $req['order'][0]['dir'];
        $column = $req['order'][0]['column'];
        $orderby = ['id', 'title', 'slug', 'subject', 'is_active'];


        $total = EmailTemplates::selectRaw('count(*) as total')->where('deleted', '0')->first();
        $query = EmailTemplates::where('deleted', '0');
        $filteredq = EmailTemplates::where('deleted', '0');
        $totalfiltered = $total->total;

        if ($search != '') {
            $query->where(function ($query2) use ($search) {
                $query2->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%');
            });
            $filteredq->where(function ($query2) use ($search) {
                $query2->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%');
            });
            $filteredq = $filteredq->selectRaw('count(*) as total')->first();
            $totalfiltered = $filteredq->total;
        }
        $query = $query->orderBy($orderby[$column], $order)->offset($start)->limit($length)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $isActive = '';
            $action = '';
            $editUrl = route('editEmailTemplate', $value->id);
            if ($value->is_active == 1) {
                $isActive .= '<button type="button" class="btn btn-sm  btn-toggle active toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="true" autocomplete="off"><div class="handle"></div></button>';
            } else {
                $isActive .= '<button type="button" class="btn btn-sm  btn-toggle toggle-is-active-switch" data-id="' . $value->id . '" data-toggle="button" aria-pressed="false" autocomplete="off"><div class="handle"></div></button>';
            }
            // $action .= '<a class="template_delete text-danger" data-id="'.$value->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            $action .= '<div class="d-inline-block dropdown">'
                                .'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                                    .'<span class="btn-icon-wrapper pr-2 opacity-7">'
                                        .'<i class="fa fa-cog fa-w-20"></i>'
                                    .'</span>'
                                .'</button>'
                                .'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                                    .'<ul class="nav flex-column">'
                                        .'<li class="nav-item">'
                                            .'<a class="nav-link active-inactive-link" >Mark as '.(( $value->is_active == 1)?'Inactive':'Active').'</a>'
                                        .'</li>'
                                        .'<li class="nav-item">'
                                            .'<a class="nav-link" href="' . $editUrl . '">Edit</a>'
                                        .'</li>'
                                        .'<li class="nav-item">'
                                            .'<a class="nav-link template_delete" data-id="' . $value->id . '">Delete</a>'
                                        .'</li>'
                                    .'</ul>'
                                .'</div>'
                            .'</div>';

            $classRow = $value->is_active?"":"row_inactive";
            $data[] = [$classRow,$action, $value->title, $value->slug, $value->subject, $isActive];
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
        $model = new EmailTemplates;
        return view('admin.email_templates.form', compact('model'));
    }

    public function store(Request $request)
    {
        $errors = $this->checkAvailableTemplateCode($request);
        if (count($errors) > 0) {
            return redirect()->back()
                ->withErrors(array('message' => $errors))->withInput();
        }
        try {
            $input = $request->all();
            $model = EmailTemplates::create($input);

            $input['emailCc']['email_cc'] = array_values($input['emailCc']['email_cc']);

            $countCc = count($input['emailCc']['email_cc']);
            if ($countCc > 0) {
                $ccData = $input['emailCc'];
                for ($i = 0; $i < $countCc; $i++) {
                    $modelEmailCc = new EmailTemplatesCc();
                    $modelEmailCc->template_id = $model->id;
                    $modelEmailCc->email_cc = $ccData['email_cc'][$i];
                    $modelEmailCc->save();
                }
            }

            $notification = array(
                'message' => 'Template added successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/email-templates/index')->with($notification);
        } catch (\Exception $e) {
            return redirect(config('app.adminPrefix').'/email-templates/index');
        }
    }

    public function edit($id)
    {
        $model = EmailTemplates::findOrFail($id);
        $modelEmailCc = DB::table('email_templates_cc')->where('template_id', $id)->get();
        $dataEmailCc = json_decode(json_encode($modelEmailCc));
        return view('admin.email_templates.form', compact('model', 'dataEmailCc'));
    }

    public function update(Request $request,$id)
    {
        $errors = $this->checkAvailableTemplateCode($request);
        if (count($errors) > 0) {
            return redirect()->back()
                ->withErrors(array('message' => $errors))->withInput();
        }
        try {
            $model = EmailTemplates::findOrFail($id);
            $input = $request->all();
            $model->update($input);
            $input['emailCc']['email_cc'] = array_values($input['emailCc']['email_cc']);
            EmailTemplatesCc::where('template_id', $id)->delete();
            $countCc = count($input['emailCc']['email_cc']);
            if ($countCc > 0) {
                $ccData = $input['emailCc'];
                for ($i = 0; $i < $countCc; $i++) {
                    $modelEmailCc = new EmailTemplatesCc();
                    $modelEmailCc->template_id = $model->id;
                    $modelEmailCc->email_cc = $ccData['email_cc'][$i];
                    $modelEmailCc->save();
                }
            }
            $notification = array(
                'message' => 'Template updated successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/email-templates/index')->with($notification);
        } catch (\Exception $e) {
            return redirect(config('app.adminPrefix').'/email-templates/index');
        }
    }

    protected function checkAvailableTemplateCode($request)
    {
        $data = $request->all();
        if(isset($data['id']))
            $templates = EmailTemplates::whereNull('deleted_at')->where('id', '<>', $data['id'])->get()->toArray();
        else
            $templates = EmailTemplates::whereNull('deleted_at')->get()->toArray();

        $errors = array();
        if (count($templates) > 0) {
            $i = 1;
            foreach ($templates as $template) {
                if (strtolower($template['slug']) == strtolower($data['slug'])) {
                    $errors[] = 'The slug has already been taken.';
                }
            }
        }

        return $errors;
    }

    public function activeInactive(Request $request)
    {
        try {
            $template = EmailTemplates::where('id', $request->template_id)->first();
            if ($request->is_active == 1) {
                $template->is_active = $request->is_active;
                $msg = "Template Activated Successfully!";
            } else {
                $template->is_active = $request->is_active;
                $msg = "Template Deactivated Successfully!";
            }
            $template->save();
            $result['status'] = 'true';
            $result['msg'] = $msg;
            return $result;
        } catch (\Exception $ex) {
            return view('errors.500');
        }
    }

    public function delete(Request $request)
    {
        $template = EmailTemplates::where('id', $request->template_id)->first();
        if (!empty($template)) {
            $template->deleted_at = Carbon::now();
            $template->deleted = '1';
            $template->save();
            $result['status'] = 'true';
            $result['msg'] = "Template Deleted Successfully!";
            return $result;
        } else {
            $result['status'] = 'false';
            $result['msg'] = "Something went wrong!!";
            return $result;
        }
    }

    public function uploadEmailImage(Request $request){
        EmailTemplates::uploadCKeditorImage($request);
    }
}
