<!DOCTYPE html>
<html>
<?php echo $__env->make('frontend.include.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body id="body">
    <?php ($themeType = Auth::check() ? Auth::user()->theme : ''); ?>
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
    <?php echo $__env->yieldContent('content'); ?>

</body>
<?php echo $__env->make('frontend.include.bottom', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</html>
<?php /**PATH /var/www/html/php/fanclub/resources/views/errors/layout.blade.php ENDPATH**/ ?>