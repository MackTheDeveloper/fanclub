<div id="attr_type_block_{{ $id }}">
    <div class="row">
        <div class="col-md-4 form-group text-right">
            <label for="display_name">Display Name
                <span class="required">*</span>
            </label>
        </div>

        <div class="col-md-6 form-group">
            <input type="text" class="form-control dispName" id="display_name_{{$id}}" name="display_name[]">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn att_type_{{$id}} " id="add_more_type_{{$id}}" onclick="add_attr_type_block({{$id}})">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn att_type_{{$id}}" id="remove_more_type_{{$id}}" onclick="remove_attr_type_block({{$id}})">
                <i class="fa fa-minus"></i>
            </button>
        </div>

        <div class="col-md-4 form-group text-right">
            <label for="title">Title</label>
        </div>
        <div class="col-md-6 form-group">
            <input type="text" class="form-control" id="title" name="title[]" />
        </div>

        <div class="col-md-4 form-group text-right multi">
            <label for="multicolor">Multicolor
            </label>
        </div>
        <div class="col-md-6 form-group multi">
            <input type="hidden"   name="multicolor[{{$id}}]" value="0" /> 
            <input type="checkbox" id="multicolor"  name="multicolor[{{$id}}]" value="1">

        </div>
    </div>
</div>