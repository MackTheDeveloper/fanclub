<?php if(Auth::check()): ?>
    <?php ($authenticateClass = ''); ?>
<?php else: ?>
    <?php ($authenticateClass = ' loginBeforeGo'); ?>
<?php endif; ?>
<div class="column">
    <div class="songs-box my-song-box" <?php echo e($authenticateClass == '' ? 'data-song=' . $songId : ''); ?>>
        <?php if(!empty($artistId) && Auth::check() && $artistId == Auth::user()->id): ?>
        <div class="dropdown c-dropdown round-drop">
            <button class="dropdown-toggle" data-bs-toggle="dropdown">
                <img src="<?php echo e(url('public/assets/frontend/img/menu-dot.svg')); ?>" class="c-icon"  alt="" >
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="<?php echo e(route('SongEditView',$songId)); ?>">
                    <img src="<?php echo e(url('public/assets/frontend/img/edit.svg')); ?>" alt="" >
                    <span>edit</span>
                </a>
            </div>
        </div>
        <?php endif; ?>
        <a href="javascript:void(0)" class="img<?php echo e($authenticateClass); ?> <?php echo e($authenticateClass == '' ? 'playSingleSongInPlayer' : ''); ?>"
            <?php echo e($authenticateClass == '' ? 'data-song-id=' . $songId : ''); ?>>
            <img src="<?php echo e($icon); ?>">
        </a>
        <a href="javascript:void(0)" class="img<?php echo e($authenticateClass); ?> <?php echo e($authenticateClass == '' ? 'playSingleSongInPlayer' : ''); ?>"
            <?php echo e($authenticateClass == '' ? 'data-song-id=' . $songId : ''); ?>>
            <p class="s1"><?php echo e($name); ?></p>
        </a>
        <div class="caption">
            <a href="">
                <p><?php echo e($artistName); ?></p>
            </a>
        </div>
        <?php if(!isset($hideLikeViews) || $hideLikeViews == '0'): ?>
            <div class="views-and-likes">
                <div class="viewer-box">
                    <img src="<?php echo e(url('public/assets/frontend/img/aakh.svg')); ?>" alt="">
                    <p class="caption blur-color"><?php echo e($noViews); ?></p>
                </div>
                <div class="liker-box">
                    <img src="<?php echo e(url('public/assets/frontend/img/fill-like.svg')); ?>" alt="">
                    <p class="caption blur-color"><?php echo e($noLikes); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/components/song-grid.blade.php ENDPATH**/ ?>