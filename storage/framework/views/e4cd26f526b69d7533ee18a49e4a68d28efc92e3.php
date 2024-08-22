<title><?php echo e(config('app.name')); ?> | <?php echo $__env->yieldContent('title'); ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="keywords" content="<?php echo $__env->yieldContent('metaKeywords'); ?>">
<meta name="description" content="<?php echo $__env->yieldContent('metaDescription'); ?>">

<meta property="og:url" content="<?php echo e(url()->current()); ?>" />
<meta property="og:type" content="product" />
<meta property="og:title" content="<?php echo $__env->yieldContent('metaTitle'); ?>" />
<meta property="og:description" content="<?php echo $__env->yieldContent('metaDescription'); ?>"/>

<?php if (! empty(trim($__env->yieldContent('metaImage')))): ?>
    <meta property="og:image" content="<?php echo $__env->yieldContent('metaImage'); ?>" />
<?php else: ?>
    <meta property="og:image" content="<?php echo e(asset('public/assets/frontend/img/og-img.png')); ?>" />
<?php endif; ?>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/include/meta_header.blade.php ENDPATH**/ ?>