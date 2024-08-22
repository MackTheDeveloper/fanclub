<input type="hidden" name="group_id" value="{{$model->id}}">
@if($model->type=='1' || $model->type=='2')
<div class="main-card mb-3 card expand_filter" >
    <div class="card-body">
        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i> Filter</h5>
        <div>
            <form method="post" class="" id="GroupItemSearch">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Criteria </label>
                              <select name="criteria" id="criteria" class="multiselect-dropdown form-control" multiple>
                                  <option value="">Select Criteria</option>
                                  @if(isset($searchCriteria))
                                  @foreach($searchCriteria as $key=>$val)
                                  <option value="{{$key}}">{{$val}}</option>
                                  @endforeach
                                  @endif
                              </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="daterange" class="font-weight-bold">Date Range </label>
                            <input type="text" class="form-control" name="daterange" id="daterange" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                            <label class="font-weight-bold">Likes </label>
                            <div class="row">
                              <div class="col-md-3" >
                                <div class="form-group">
                                  <input type="text" class="form-control" name="likeMin" id="likeMin" placeholder="Min" />
                                </div>
                              </div>
                              <div class="col-md-3" >
                                <div class="form-group">
                                  <input type="text" class="form-control" name="likeMax" id="likeMax" placeholder="Max" />
                                </div>
                              </div>
                           </div>
                    </div>
                    <div class="col-md-6">
                            <label class="font-weight-bold">Views </label>
                            <div class="row">
                              <div class="col-md-3" >
                                <div class="form-group">
                                  <input type="text" class="form-control" name="viewMin" id="viewMin" placeholder="Min" />
                                </div>
                              </div>
                              <div class="col-md-3" >
                                <div class="form-group">
                                  <input type="text" class="form-control" name="viewMax" id="viewMax" placeholder="Max" />
                                </div>
                              </div>
                           </div>
                    </div>
                </div>
                @if($model->type=='2')
                <div class="row">
                  <div class="col-md-6">
                          <label class="font-weight-bold">Downloads </label>
                          <div class="row">
                            <div class="col-md-3" >
                              <div class="form-group">
                                <input type="text" class="form-control" name="downloadMin" id="downloadMin" placeholder="Min" />
                              </div>
                            </div>
                            <div class="col-md-3" >
                              <div class="form-group">
                                <input type="text" class="form-control" name="downloadMax" id="downloadMax" placeholder="Max" />
                              </div>
                            </div>
                         </div>
                  </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <button type="button" id="btnFilterData" class="btn btn-primary">Search</button>
                                    <button type="button" id="resetFilter" class="btn btn-light">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<div class="main-card mb-3 card">
    <div class="card-body">
      <div class="row">
          <div class="col-md-12 ">
              <div class="form-group">
                  <div class="row">
                      <div class="col-md-4 offset-md-8 text-right">
                          <button type="button" id="btnAddData" class="btn btn-primary">Add</button>
                          <button type="button" id="btnRemoveData" class="btn btn-light">Remove</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <table style="width:100%;" id="GroupDataList" class="display nowrap table table-hover table-striped table-bordered" >
          <thead>
              <tr class="text-center">
                  <th>
                    <label class="ck only-ck">
                        <input type="checkbox" value="" id="selectAll" name="selectAll">
                        <span class="ck-mark"></span>
                    </label>
                  </th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Created At</th>
                  @if($model->type=='1' || $model->type=='2')
                  <th>Likes</th>
                  <th>Views</th>
                  @if($model->type=='2')
                  <th>Downloads</th>
                  @endif
                  @endif
                  <th>Action</th>
              </tr>
          </thead>
      </table>
    </div>
</div>
