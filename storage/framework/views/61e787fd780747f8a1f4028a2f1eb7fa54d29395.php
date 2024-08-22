<?php if(Auth::check()): ?>
    <?php ($authenticateClass = ''); ?>
<?php else: ?>
    <?php ($authenticateClass = ' loginBeforeGo'); ?>
<?php endif; ?>

<?php ($urlPre = ''); ?>
<?php ($urlClick = ''); ?>
<?php if($data->componentDynamicGroup->commonDetails->groupType == 1): ?>
    <?php ($urlPre = 'artists'); ?>
<?php elseif($data->componentDynamicGroup->commonDetails->groupType == 2): ?>
    <?php ($urlPre = 'songs'); ?>
<?php elseif($data->componentDynamicGroup->commonDetails->groupType == 4): ?>
    <?php ($urlPre = 'categories'); ?>
<?php elseif($data->componentDynamicGroup->commonDetails->groupType == 5): ?>
    <?php ($urlPre = 'languages'); ?>
<?php endif; ?>

<div class="fanclub-artist section" id="<?php echo e($data->componentSlug); ?>">
    <div class="container-fluid">
        <div class="slider-header">
            <h5><?php echo e($data->componentName); ?></h5>
            <?php if($data->componentDynamicGroup->commonDetails->viewAll &&
                $data->componentDynamicGroup->commonDetails->viewAll == 1): ?>
                <?php if($data->componentDynamicGroup->commonDetails->groupType == 1): ?>
                    <a href="<?php echo e(route('allArtists')); ?>"
                        class="a">See All</a>
                <?php elseif($data->componentDynamicGroup->commonDetails->groupType == 2): ?>
                    <a href="<?php echo e(route('allSongs')); ?>"
                        class="a">See All</a>
                    <?php ($urlClick = route('allSongs')); ?>
                <?php else: ?>
                    <a href="<?php echo e(url($urlPre . '/' . $data->componentDynamicGroup->commonDetails->DynamicGroupSlug)); ?>"
                        class="a">See All</a>
                <?php endif; ?>
            <?php else: ?>
                <?php ($urlClick = url($urlPre . '/' . $data->componentDynamicGroup->commonDetails->DynamicGroupSlug)); ?>
                <a href="<?php echo e(url($urlPre . '/' . $data->componentDynamicGroup->commonDetails->DynamicGroupSlug)); ?>"
                    class="a">See All</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="rounded-img-carousel">
        <div class="owl-carousel owl-theme">
            <?php if($data->componentDynamicGroup->commonDetails->groupType == 2): ?>
                <?php $__currentLoopData = $data->componentDynamicGroup->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $row2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <a href="<?php echo e($urlClick); ?>"
                        class="item">
                        <img src="<?php echo e($row2->Icon); ?>">
                        <p class="s1"><?php echo e($row2->Name); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php $__currentLoopData = $data->componentDynamicGroup->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $row2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a <?php echo e($authenticateClass == '' && $urlPre == 'songs' ? 'data-song-id=' . $row2->Id : ''); ?>

                        href="<?php echo e($row2->detailUrl); ?>"
                        class="item">
                        <img src="<?php echo e($row2->Icon); ?>">
                        <p class="s1"><?php echo e($row2->Name); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/homepage-component/circle.blade.php ENDPATH**/ ?>