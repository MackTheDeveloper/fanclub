<?php $__env->startSection('title',$cms->name); ?>
<?php $__env->startSection('metaTitle',$cms->seo_title); ?>
<?php $__env->startSection('metaKeywords',$cms->seo_meta_keyword); ?>
<?php $__env->startSection('metaDescription',$cms->seo_description); ?>
<?php $__env->startSection('content'); ?>
<!--------------------------
        ABOUT US START
--------------------------->

<div class="about-us">
    <?php if(empty($mobile)): ?>
    <div class="container">
        <div class="breadCrums">
            <ul>
                <li><a href="<?php echo e(url('/')); ?>">fanclub</a></li>
                <li>About fanclub</li>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <div class="container">
        <div class="about-banner">
            <img src="<?php echo e(asset('public/assets/frontend/img/about-banner.png')); ?>" alt="" />
        </div>
    </div>
    <?php echo $cms->content; ?>


</div>

<!--------------------------
        ABOUT US END
--------------------------->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footscript'); ?>
<script type="text/javascript">
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/pages/about-us.blade.php ENDPATH**/ ?>