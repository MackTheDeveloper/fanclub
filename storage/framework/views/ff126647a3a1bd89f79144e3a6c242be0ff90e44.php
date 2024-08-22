<!DOCTYPE html>
<html>
<?php echo $__env->make('frontend.include.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body id="body">
	<?php ($themeType = Auth::check() ? Auth::user()->theme : (!empty($darkmode)?'dark':'')); ?>
	<script type="text/javascript">
		var thhemeType = "<?php echo e($themeType); ?>";
		if (!thhemeType) {
			thhemeType = localStorage.getItem('fanclubtheme');
		}
		if (thhemeType == 'dark') {
			var element = document.getElementById("body");
			element.classList.add("dark-theme");
		}
	</script>
	<!--------------------------
	    HEADER START
	--------------------------->
	<?php echo $__env->make('frontend.include.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	<!--------------------------
	    HEADER END
	--------------------------->

	<div class="ajax-alert">

	</div>


	<!--------------------------
	    	CONTENT START
	--------------------------->
	<?php echo $__env->yieldContent('content'); ?>
	<!--------------------------
	    	CONTENT END
	--------------------------->

	<!--------------------------
	    	FOOTER START
	--------------------------->
	<?php if(Request::route()->getName() != 'myMusicPlayer'): ?>
	<?php echo $__env->make('frontend.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php endif; ?>
	<!--------------------------
	    	FOOTER END
	--------------------------->
	<div class="cookie-alert" id="cookie-alert">
		<img class="close-cookie-alert" src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" alt="" />
		<p>We and our partners use cookies to personalise your experience, for measurement and analytics purposes. By using our website and services, you agree to our use of cookies as described in our <a href="<?php echo e(route('cookiePolicy')); ?>"> Cookie Policy.</a></p>
	</div>
</body>
<?php echo $__env->make('frontend.include.bottom', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if(Session::has('message')): ?>
<script>
	var type = "<?php echo e(Session::get('alert-type', 'info')); ?>";
	switch (type) {
		case 'info':
			toastr.info("<?php echo e(Session::get('message')); ?>");
			break;

		case 'warning':
			toastr.warning("<?php echo e(Session::get('message')); ?>");
			break;

		case 'success':
			toastr.success("<?php echo e(Session::get('message')); ?>");
			break;

		case 'error':
			toastr.error("<?php echo e(Session::get('message')); ?>");
			break;
	}
</script>
<?php endif; ?>

</html><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/layouts/master.blade.php ENDPATH**/ ?>