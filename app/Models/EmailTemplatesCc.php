<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplatesCc extends Model
{
    protected $table = 'email_templates_cc';

    protected $fillable = ['template_id', 'email_cc', 'created_at', 'updated_at'];
}
