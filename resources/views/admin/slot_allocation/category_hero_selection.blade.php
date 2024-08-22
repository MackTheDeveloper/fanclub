<?php
use App\Models\Professionals; 
?>
@foreach($categories as $key => $cat)
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?php echo Form::label('category_hero', $cat->name, ['class' => 'font-weight-bold']); ?>
            <div>
                <?php
                $professionals = Professionals::byCategory($cat->id);
                if($professionals->count()){
                    $professionalsArray = $professionals->pluck('company_name', 'user_id');
                }else{
                    $professionalsArray = [];
                }
                echo Form::select("category_hero[$key][user_id]", $professionalsArray,$cat->selected_user_id?:'', ['class' => 'form-control multiselect-dropdown','placeholder' => 'Select ...']); ?>
                {{ Form::hidden("category_hero[$key][category_id]", $cat->id) }}
            </div>
        </div>
    </div>
</div>

@endforeach