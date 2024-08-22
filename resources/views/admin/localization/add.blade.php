@extends('admin.layouts.master')
<title>Add Locale | fanclub</title>

@section('content')
    @include('admin.include.header')
    <div class="app-main">
        @include('admin.include.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title app-page-title-simple">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class="lnr-cog opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Localization</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a>
                                                    <i aria-hidden="true" class="fa fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/locale')}}">Localization</a>
                                            </li>
                                            <!-- <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/language/list')}}">Language</a>
                                            </li> -->
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Add Locale
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Locales Information</h5>
                        @if(Session::has('msg'))
                        <div class="alert {{((Session::get('alert-class') == true) ? 'alert-success' : 'alert-danger')}} alert-dismissible fade show"
                            role="alert">
                            {{ Session::get('msg') }}
                            <button type="button" class="close session_error" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <form id="localeForm" class="col-md-12" method="POST" action="{{url(config('app.adminPrefix').'/locale/add')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="code" class="col-sm-2 col-form-label">Code<span
                                        style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="code" id="code" placeholder="Code">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Title<span
                                        style="color:red">*</span></label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-sm-6 col-form-label">Locales<span
                                        style="color:red">*</span>
                                    <div style="color:red;">Note: All tabs are mandatory. Please check before submit.
                                    </div>
                                </label>
                                <div class="col-md-12">
                                    <div class="mb-3 card">
                                        <div class="card-header card-header-tab-animation">
                                            <ul class="nav nav-justified">
                                                <?php $counter = 0; ?>
                                                @foreach($languages as $language)
                                                <?php $counter++; ?>
                                                <?php if($counter == 1){ ?>
                                                <li class="nav-item"><a data-toggle="tab"
                                                        href="#tab-eg115-<?php echo $counter; ?>"
                                                        class="active nav-link"><?php echo $language->langEN."(".$language->alpha2.")";?></a>
                                                </li>
                                                <?php }else{ ?>
                                                <li class="nav-item"><a data-toggle="tab"
                                                        href="#tab-eg115-<?php echo $counter; ?>"
                                                        class="nav-link"><?php echo $language->langEN."(".$language->alpha2.")";?></a>
                                                </li>
                                                <?php } ?>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <?php $counter = 0; ?>
                                                @foreach($languages as $language)
                                                <?php $counter++; ?>
                                                <?php if($counter == 1){ ?>
                                                <div class="tab-pane active" id="tab-eg115-<?php echo $counter; ?>"
                                                    role="tabpanel">
                                                    <textarea type="text" dir="ltr" required id="locale_textarea"
                                                        name="test[{{$language->alpha2}}]" cols="100" rows="5"
                                                        class="form-control"></textarea>
                                                </div>
                                                <?php }else{ ?>
                                                <div class="tab-pane" id="tab-eg115-<?php echo $counter; ?>"
                                                    role="tabpanel">
                                                    <textarea type="text" dir="ltr" required id="locale_textarea"
                                                        name="test[{{$language->alpha2}}]" cols="100" rows="5"
                                                        class="form-control"></textarea>
                                                </div>
                                                <?php } ?>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <!-- <input type="submit" class="btn btn-primary" name="add_locale" value="Submit"> -->
                                        <button type="submit" class="btn btn-primary" name="add_locale"
                                            id="add_locale">Add Locale</button>
                                        <a href="{{url(config('app.adminPrefix').'/locale')}}"><button type="button" class="btn btn-light"
                                                name="cancel" value="Cancel">Cancel</button></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
@endsection
<div class="app-drawer-overlay d-none animated fadeIn"></div>

</html>
