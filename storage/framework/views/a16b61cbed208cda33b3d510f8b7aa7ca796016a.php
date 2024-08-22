<?php if(Auth::check()): ?>
<?php ($authenticateClass = ''); ?>
<?php else: ?>
<?php ($authenticateClass = 'loginBeforeGo'); ?>
<?php endif; ?>
<?php ($authRole = getAuthProps()); ?>
<!--------------------------
      FOOTER END
--------------------------->
<?php if(empty($mobile)): ?>
<footer class="<?php echo e(($authRole=='2')?'footer-artist':''); ?>">
    <div class="container-fluid">
        <?php if(!Auth::check() || $authRole=='3'): ?>
        <div class="footer-wrapper">
            <?php $__currentLoopData = $frontendFooter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="footer-block">
                <p class="s2"><?php echo e($item['footerDetails']['footerName']); ?></p>
                <ul>
                    <?php $__currentLoopData = $item['footerMenuData']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e($item1); ?>" class=""><?php echo e($key1); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            <div class="footer-block follow-block">
                <p class="s2">Follow Us</p>
                <div class="social-icon">
                    <a target="_blank" href="<?php echo e(app('App\Models\GlobalSettings')::getSingleSettingVal('fb_link')); ?>"><img src="<?php echo e(asset('public/assets/frontend/img/fb.svg')); ?>"></a>
                    
                    <a target="_blank" href="<?php echo e(app('App\Models\GlobalSettings')::getSingleSettingVal('insta_link')); ?>"><img src="<?php echo e(asset('public/assets/frontend/img/insta.svg')); ?>"></a>
                    
                </div>
                <p class="s2">Also Available On</p>
                <div class="app-store">
                    <a target="_blank" href="<?php echo e(app('App\Models\GlobalSettings')::getSingleSettingVal('andriod_link')); ?>"><img src="<?php echo e(asset('public/assets/frontend/img/app_store_icon.svg')); ?>"></a>
                    <a target="_blank" href="<?php echo e(app('App\Models\GlobalSettings')::getSingleSettingVal('ios_link')); ?>"><img src="<?php echo e(asset('public/assets/frontend/img/play_store_icon.svg')); ?>"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="copy-right">
            <p class="caption">Copyright © <?php echo e(date('Y')); ?> fanclub Ltd.</p>
            <a href="https://magnetoitsolutions.com/" target="_blank" class="caption">Made by Magneto IT Solutions</a>
        </div>
    </div>
</footer>

<!------   FOOTER END
--------------------------->

<div class="modal signUpModal fade" id="signUpModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="sign-in-wrapper">
                    <div class="sign-in-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" alt="" class="modal-close close-black" data-bs-dismiss="modal" />
                        <div class="with-password">
                            <h4>Sign In</h4>
                            <p>Already have an account? Welcome back!</p>
                            <form id="loginFormFromPopup" method="POST" action="<?php echo e(url('/login-from-popup')); ?>">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <div class="inputs-group">
                                        <input type="email" class="email" name="email" id="email">
                                        <span>Email Address*</span>
                                        <!-- <label class="error">Error</label> -->
                                    </div>
                                    <div class="inputs-group">
                                        <input type="password" name="password" id="password">
                                        <span>Password*</span>
                                        <!-- <label class="error">Error</label> -->
                                    </div>
                                </div>
                                <div class="forgot-psw">
                                    <a data-bs-target="#forgotPasswordModal" data-bs-toggle="modal" data-bs-dismiss="modal"><span>Forgot Password?</span></a>
                                </div>
                                <div class="otp">
                                    <span>Or Log In with<a data-bs-target="#loginWithOtpModal" data-bs-toggle="modal" data-bs-dismiss="modal">One Time Password (OTP)</a></span>
                                </div>
                                <button type="submit" class="fill-btn">Log In</button>
                                <!-- <button class="fill-btn">Log In</button> -->
                                <div class="create-link">
                                    <span>New to fanclub?<a href="<?php echo e(route('showSignup')); ?>"> Create Account</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="signin-img-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/white-close.png')); ?>" class="white-close" alt="" data-bs-dismiss="modal" />
                        <h6>An exciting exclusive music collection to complement your lifestyle. Created by music fans for music fans</h6>
                        <div class="img-wrapper">
                            <img src="<?php echo e(asset('public/assets/frontend/img/modal-img.png')); ?>" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal signUpModal loginWithOtpModal fade" id="loginWithOtpModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="sign-in-wrapper">
                    <div class="sign-in-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" alt="" class="modal-close close-black" data-bs-dismiss="modal" />
                        <div class="with-password add-padding-otp-email">
                            <h4>Sign In</h4>
                            <p>Already have an account? Welcome back!</p>
                            <form id="loginWithOtpFormFromPopup" method="POST" action="<?php echo e(url('/login-using-otp-from-popup')); ?>">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <div class="inputs-group">
                                        <input type="email" class="email" name="input" id="email">
                                        <span>Email Address*</span>
                                        <!-- <label class="error">Error</label> -->
                                    </div>
                                </div>
                                <div class="login-with-psw">
                                    <span>Or Log In with<a data-bs-target="#signUpModal" data-bs-toggle="modal" data-bs-dismiss="modal">Password</a></span>
                                </div>
                                <button type="submit" class="fill-btn">Request OTP</button>
                                <!-- <button class="fill-btn">Log In</button> -->
                                <div class="create-link">
                                    <span>New to fanclub?<a href="<?php echo e(route('showSignup')); ?>"> Create Account</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="signin-img-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/white-close.png')); ?>" class="white-close" alt="" data-bs-dismiss="modal" />
                        <h6>An exciting exclusive music collection to complement your lifestyle. Created by music fans for music fans</h6>
                        <div class="img-wrapper">
                            <img src="<?php echo e(asset('public/assets/frontend/img/modal-img.png')); ?>" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal signUpModal loginWithOtpVerificationModal fade" id="loginWithOtpVerificationModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="sign-in-wrapper">
                    <div class="sign-in-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" alt="" class="modal-close close-black" data-bs-dismiss="modal" />
                        <div class="with-password otp-page add-padding-otp">
                            <h4>OTP Verification</h4>
                            <p>We have sent the OTP to your email address</p>
                            <div class="email-edit">
                                <p class="s1 opt-email-popup-text"></p>
                                <a data-bs-target="#loginWithOtpModal" data-bs-toggle="modal" data-bs-dismiss="modal"><img src="<?php echo e(url('public/assets/img/edit.svg')); ?>" alt=""></a>
                            </div>
                            <form id="loginWithOtpVerificationFormFromPopup" method="POST" action="<?php echo e(url('/otp-verification-from-popup')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="otp-input">
                                    <input type="text" id="digit-1" name="digit-1" data-next="digit-2" oninput='digitValidate(this)' />
                                    <input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" oninput='digitValidate(this)' />
                                    <input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" oninput='digitValidate(this)' />
                                    <input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" oninput='digitValidate(this)' />
                                </div>
                                <label id="" class="otp-error"></label>
                                <input type="hidden" name="otp" id="otp-input" value="" />
                                <input type="hidden" class="opt-email-popup-value" name="input" id="email" value="" />

                                <div class="login-with-psw">
                                    <span>Or Log In with<a data-bs-target="#signUpModal" data-bs-toggle="modal" data-bs-dismiss="modal">Password</a></span>
                                </div>
                                <button type="submit" class="fill-btn">Submit</button>
                            </form>
                            <div class="resend-otp">
                                <form action="<?php echo e(url('/login-using-otp-from-popup')); ?>" id="resendOtpFormFromPopup" method="post">
                                    <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
                                    <input type="hidden" name="input" class="opt-email-popup-value" id="email" value="<?php echo e(Session::get('opt-email')); ?>" />
                                    <span>
                                        <button class="resend-button">Resend OTP</button>
                                        
                                    </span>
                                </form>
                            </div>
                        </div>
                        
                </div>
                <div class="signin-img-side">
                    <img src="<?php echo e(asset('public/assets/frontend/img/white-close.png')); ?>" class="white-close" alt="" data-bs-dismiss="modal" />
                    <h6>An exciting exclusive music collection to complement your lifestyle. Created by music fans for music fans</h6>
                    <div class="img-wrapper">
                        <img src="<?php echo e(asset('public/assets/frontend/img/modal-img.png')); ?>" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal signUpModal forgotPasswordModal fade" id="forgotPasswordModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="sign-in-wrapper">
                    <div class="sign-in-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" alt="" class="modal-close close-black" data-bs-dismiss="modal" />
                        <div class="with-password add-padding-forgot">
                            <h4>Forgot Password</h4>
                            <p>Keep calm, we’ve got you covered. Just enter your email address below to reset your
                                password</p>
                            <form method="POST" id="forgotPasswordFormFromPopup" action="<?php echo e(url('/forgot-password-from-popup')); ?>">
                                <?php echo csrf_field(); ?>
                                <div>
                                    <div class=" inputs-group">
                                        <input type="email" class="email" name="email" id="email">
                                        <span>Email Address*</span>
                                        <!-- <label class="error">Error</label> -->
                                    </div>
                                </div>
                                <div class="still-need-help">
                                    <a href="<?php echo e(route('showSecurityQuestionCheck')); ?>"><span style="float:right; color:#ed4247">Still Need Help?</span></a>
                                </div>
                                <div class="login-with-psw">
                                    <span>Or Log In with<a data-bs-target="#signUpModal" data-bs-toggle="modal" data-bs-dismiss="modal">Password</a></span>
                                </div>

                                <button type="submit" class="fill-btn">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="signin-img-side">
                        <img src="<?php echo e(asset('public/assets/frontend/img/white-close.png')); ?>" class="white-close" alt="" data-bs-dismiss="modal" />
                        <h6>An exciting exclusive music collection to complement your lifestyle. Created by music fans for music fans</h6>
                        <div class="img-wrapper">
                            <img src="<?php echo e(asset('public/assets/frontend/img/modal-img.png')); ?>" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/include/footer.blade.php ENDPATH**/ ?>