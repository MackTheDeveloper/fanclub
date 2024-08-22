<?php if(Auth::check()): ?>
    <?php ($authenticateClass = ''); ?>
<?php else: ?>
    <?php ($authenticateClass = ' loginBeforeGo'); ?>
<?php endif; ?>

<div class="genres section" id="<?php echo e($data->componentSlug); ?>">
    <div class="container-fluid">
        <div class="slider-header">
            <h5><?php echo e($data->componentName); ?></h5>
            <a href="" class="a d-none">See All</a>
        </div>
    </div>
    <div class="double-img-carousel">
        <div class="owl-carousel owl-theme">
            <?php $i = 1; ?>
            <?php $__currentLoopData = $data->componentDynamicGroup->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2 => $row2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if ($i % 2 == 1) { ?>
                <div href="" class="item">
                    <?php } ?>
                    <?php if($data->componentDynamicGroup->commonDetails->groupType == 2): ?>
                        <a href="<?php echo e($authenticateClass ? 'javascript:void(0)' : $row2->detailUrl); ?>"
                            class="img-content<?php echo e($authenticateClass); ?>">
                            <img style="width: 190px;height: 95px;" src="<?php echo e($row2->Icon); ?>">
                            <div class="img-content-overlay">
                                <p class="s1"><?php echo e($row2->Name); ?></p>
                            </div>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e($row2->detailUrl); ?>"
                            class="img-content">
                            <img style="width: 190px;height: 95px;" src="<?php echo e($row2->Icon); ?>">
                            <div class="img-content-overlay">
                                <p class="s1"><?php echo e($row2->Name); ?></p>
                            </div>
                        </a>
                    <?php endif; ?>
                    <?php if ($i % 2 == 0 || count($data->componentDynamicGroup->data) == $i) { ?>
                </div>
                <?php } ?>
                <?php $i++; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/homepage-component/rectangle.blade.php ENDPATH**/ ?>