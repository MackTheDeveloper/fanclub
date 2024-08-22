<?php $__env->startSection('title','404'); ?>

<?php $__env->startSection('content'); ?>
<div class="error-404">
    <div class="container">
      	<div class="width-539">
        	<img src="<?php echo e(asset('public/assets/frontend/img/error404.png')); ?>">
        	<h6>The page you're trying to reach cannot be found.</h6>
        	<a href="<?php echo e(url('/')); ?>" class="fill-btn">Return to Homepage</a>
      	</div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/php/fanclub/resources/views/errors/404.blade.php ENDPATH**/ ?>