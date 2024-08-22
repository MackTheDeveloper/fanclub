<?php $__env->startSection('title','Sign In'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('public/assets/frontend/css/jquery.ccpicker.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!--------------------------
        SIGN UP START
--------------------------->
<div class="subscription-yer-no">
    <h4>Now Let's Get Started</h4>
    <p>Please give us some more info</p>
    <form id="subscriptionForm" method="POST" action="<?php echo e(route('secondSignup')); ?>">
        <?php echo csrf_field(); ?>
        <div class="introduce">
            <span>Did a fanclub artist introduce you?</span>
            <div class="radio-group">
                <label class="rd">Yes
                    <input type="radio" <?php echo e($content['artist']->selected?"disabled":""); ?> checked="checked" class="introduce-radio-btn" value="1" name="artist_introduce">
                    <span class="rd-checkmark"></span>
                </label>
                <label class="rd">No
                    <input type="radio" <?php echo e($content['artist']->selected?"disabled":""); ?> class="introduce-radio-btn" value="0" name="artist_introduce">
                    <span class="rd-checkmark"></span>
                </label>
            </div>
        </div>
        
        <div class="yes-select show">
            <div class="label-select">
                <span class="yesLabel">Select Artist</span>
                <span class="noLabel d-none">Discovered fanclub through</span>
                <select name="artist_id" <?php echo e($content['artist']->selected?"disabled":""); ?>>
                    <option value="">Select Artist</option>
                    <?php $__currentLoopData = $content['artist']->artistData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php echo e(($content['artist']->selected==$row->id)?"selected":""); ?> value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        
        <?php if($content['artist']->selected): ?>
            <input type="hidden" name="artist_introduce" value="1">
            <input type="hidden" name="artist_id" value="<?php echo e($content['artist']->selected); ?>">
        <?php endif; ?>
        

        <div class="choose-plan-wrapper">
            <h6><?php echo e($content['subscription']->subscriptionData->title); ?></h6>
            <?php $__currentLoopData = $content['subscription']->subscriptionData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="rd-plan">
                <input type="radio" value="<?php echo e($row->id); ?>" <?php if(!$key): ?> checked="checked" <?php endif; ?> name="subscription_id">
                <span class="rd-plan-checkmark">
                    <div class="paln-data">
                        <div class="ck-value"></div>
                        <div class="plan-inner-data">
                            <p class="s1"><?php echo e($row->title); ?></p>
                            <span><?php echo e($row->description); ?></span>
                            
                        </div>
                    </div>
                </span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
        </div>
        
        <button class="fill-btn" type="submit">Continue</button>
    </form>
</div>

<!--------------------------
        SIGN UP END
--------------------------->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footscript'); ?>
<script src="<?php echo e(asset('public/assets/frontend/js/jquery.ccpicker.js')); ?>"></script>
<script type="text/javascript">
    $(document).on('change','.introduce-radio-btn',function(){
        var value = $(this).val();
        if (value=="1") {
            $('.yesLabel').removeClass('d-none');
            $('.noLabel').addClass('d-none');
            $('.yes-select').addClass('show').find('select').prop('disabled',false);
        }else {
            $('.yesLabel').addClass('d-none');
            $('.noLabel').removeClass('d-none');
            $('.yes-select').removeClass('show').find('select').prop('disabled',true);
        }
    });

    $("#subscriptionForm").validate({
        ignore: [],
        rules: {
            artist_introduce: "required",
            artist_id: "required",
            subscription_id: "required",
        },
        messages: {
            artist_introduce: "artist introduction is required",
            artist_id: "Please select artist",
            subscription_id: "Please select subscription",
        },
        errorPlacement: function(error, element) {
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
    });
    
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/auth/subscription.blade.php ENDPATH**/ ?>