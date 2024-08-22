<?php $__env->startSection('title','Sign In'); ?>

<?php $__env->startSection('content'); ?>
<!--------------------------
        SIGN IN START
--------------------------->

<div class="signin-page">
    <h4>Sign In</h4>
    <p>Already have an account? Welcome back!</p>
    <form id="loginForm" method="POST" action="<?php echo e(url('/login')); ?>">
        <?php echo csrf_field(); ?>
        <div>
            <div class="inputs-group">
                <input type="text" class="email" name="email" id="email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" >
                <span>Email Address*</span>
                <?php if($errors->has('email')): ?>
                        <div class="error"><?php echo e($errors->first('email')); ?></div>
                <?php endif; ?>
                <!-- <label class="error">Error</label> -->
            </div>
            <div class="inputs-group">
                <input type="password" name="password" id="password">
                <span>Password*</span>
                <!-- <label class="error">Error</label> -->
            </div>
        </div>
        <div class="forgot-psw">
            <a href="<?php echo e(route('showForgotPassword')); ?>"><span>Forgot Password?</span></a>
        </div>
        <div class="otp">
            <span>Or Log In with<a href="<?php echo e(url('login-using-otp')); ?>">One Time Password (OTP)</a></span>
        </div>
        <button type="submit" class="fill-btn">Log In</button>
        <div class="create-link">
            <span>New to fanclub?<a href="<?php echo e(url('signup')); ?>"> Create Account</a></span>
        </div>
    </form>
</div>

<!--------------------------
        SIGN IN END
--------------------------->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footscript'); ?>
<script type="text/javascript">
    $("#loginForm").validate({
        ignore: [],
        rules: {
            email: {
                    required: true,
                    email: true,
                },
            password: "required",
        },
        messages: {
            email: "Please insert a valid email address.",
            password: "Password is required"
        },
        errorPlacement: function(error, element) {
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
    });

    $(document).on('change keyup keydown', 'input[name="email"]', function() {
        $('input[name="input"]').val($(this).val())
        var valueInt = $.isNumeric($(this).val());
        if (valueInt) {
            if (!$('.hasEmailPhone').hasClass('hasNumber')) {
                $('.hasEmailPhone').addClass('hasNumber');
            }
        } else {
            $('.hasEmailPhone').removeClass('hasNumber');
        }
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/auth/login.blade.php ENDPATH**/ ?>