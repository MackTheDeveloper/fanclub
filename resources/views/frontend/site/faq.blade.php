@section('title', $seo_title)
@section('metaTitle', $seo_title)
@section('metaKeywords', $seo_meta_keyword)
@section('metaDescription', $seo_description)
@extends('frontend.layouts.master')
@section('content')
<!-- My Reviews Songs Page starts here -->
<div class="faqs-page">
    <div class="container">
        <!-- breadcrumb Component is here    -->
        <div class="breadcrumb-section">
            <div class="breadCrums">
                <ul>
                    <li><a href="{{url('/')}}">fanclub</a></li>
                    <li>Frequently Asked Questions</li>
                </ul>
            </div>
        </div>

        <h5>How can we help you?</h5>
        <div class="row">
            <div class="col-md-12">
                <!-- Nav pills -->
                <ul class="nav nav-pills faq-main-pills">
                    <li class="faq-main-btns">
                        <a class="faqmain-link active" data-toggle="pill" href="#fan">Fan</a>
                        <a class="faqmain-link" data-toggle="pill" href="#artist">Artist</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Fan Tab Panel Content Here -->
                    <ul class="faq-sec-pill">
                        {{-- <li class="faqsec-btn"><a data-tag="0" class="active" href="javascript:void(0)">All</a></li> --}}
                        @foreach($content['faqCategory']->faqCategoryData->list as $key=>$row)
                        <li class="faqsec-btn"><a data-tag="{{$row->Id}}" class="{{($key==0)?'active':''}}" href="javascript:void(0)">{{$row->name}}</a></li>
                        @endforeach
                        {{-- <li class="faqsec-btn"><a href="#payment">Payment</a></li>
                        <li class="faqsec-btn"><a href="#">Subscription</a></li> --}}
                    </ul>
                    <div class="tab-pane active" id="fan">
                        <div class="faqs-accordians">
                            <div id="accordion">
                                @foreach($content['faqList']->faqListData->list as $key=>$row)
                                    @if ($row->userType=="fan")
                                    <div class="accordian-item" tags={{$row->tags}}>
                                        <button class="accordian-head" data-toggle="collapse" data-target="#collapseF{{$key}}"
                                            aria-expanded="true" aria-controls="collapseF{{$key}}">
                                            <h6>{{$row->question}}</h6>
                                        </button>
                                        <div id="collapseF{{$key}}" class="collapse {{$key?'':'show'}}" aria-labelledby="headingF{{$key}}"
                                            data-parent="#accordion">
                                            <div class="card-body">
                                                <p>{{$row->answer}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- Artist Tab Panel Content Here -->
                    <div class="tab-pane fade" id="artist">
                        <div class="faqs-accordians">
                            <div id="accordion2">
                                @foreach($content['faqList']->faqListData->list as $key=>$row)
                                    @if ($row->userType=="artist")
                                    <div class="accordian-item" tags={{$row->tags}}>
                                        <button class="accordian-head" data-toggle="collapse" data-target="#collapseA{{$key}}"
                                            aria-expanded="true" aria-controls="collapseA{{$key}}">
                                            <h6>{{$row->question}}.</h6>
                                        </button>
                                        <div id="collapseA{{$key}}" class="collapse {{$key?'':'show'}}" aria-labelledby="headingA{{$key}}"
                                            data-parent="#accordion2">
                                            <div class="card-body">
                                                <p>{{$row->answer}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footscript')
<script type="text/javascript">
    $(document).on('click','ul.faq-sec-pill li.faqsec-btn a',function(){
        $(this).addClass('active').parent().siblings().find('a').removeClass('active');
        var value = $(this).data('tag');
        // alert(value);
        if(value){
            $('.accordian-item').each(function(){
                var tags = $(this).attr('tags')
                var tags = tags.split(',');
                if(tags.indexOf(value.toString()) !== -1){
                    $(this).show()
                }else{
                    $(this).hide();
                }
            }) 
        }else{
           $('.accordian-item').show();
        }
    });
</script>
@endsection