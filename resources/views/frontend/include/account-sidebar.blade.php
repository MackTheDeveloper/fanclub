<?php 
use App\Models\Professionals;

$isProfesional = Professionals::getProfessionalStatus(Auth::user()->id);
?>

<div class="col-sm-12 col-md-5 col-lg-4">
    <div class="sidebar">
        <div class="left-sidebar">
            <div class="dropdowns" id="">
                My Account
            </div>
            <ul class="dropdowns-toggles account-sidebar">
                <li class="my-profile-li {{(in_array(Route::current()->getName(), ['getMyProfile','editMyProfile','viewChangePassword']))?'active':''}} {{Request::is('account/edit-profile')?'active':''}}">
                    <a href="{{ url('account/profile') }}">
                        My Profile
                    </a>
                </li>
                <li style="display: none" class="my-subscription {{Request::is('account/subscriptions')?'active':''}}">
                    <a href="{{ url('account/profile') }}">
                        My Subscriptions
                    </a>
                </li>
                <li class="my-review {{(Route::current()->getName()=='getMyReviews')?'active':''}}">
                    <a href="{{ route('getMyReviews') }}">
                        My Reviews
                    </a>
                </li>
                <li class="my-enquiries {{(Route::current()->getName()=='getMyInquiries')?'active':''}}">
                    <a href="{{ route('getMyInquiries') }}">
                        My Enquiries
                    </a>
                </li>
                <li class="liked-items {{(Route::current()->getName()=='likedItems')?'active':''}}">
                    <a href="{{ route('likedItems') }}">
                        Liked Items
                    </a>
                </li>
                @if(Auth::user()->is_professional=='0')
                <li class="register-professional {{Request::is('account/professional-register')?'active':''}}">
                    <a href="{{ route('ProfessionalRegister') }}">
                        Register as a Professional
                    </a>
                </li>
                @else
                    @if(!$isProfesional)
                    <li class="register-professional {{Request::is('account/professional-register-approval')?'active':''}}">
                        <a href="{{ route('ApprovalProfessionalRegister') }}">
                            Register as a Professional
                        </a>
                    </li>
                    @endif
                @endif
                <li class="logout">
                    <a href="{{ url('logout') }}">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>