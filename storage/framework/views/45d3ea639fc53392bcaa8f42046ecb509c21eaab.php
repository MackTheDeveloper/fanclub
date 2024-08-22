<?php if(Auth::check()): ?>
    <?php ($authenticateClass = ''); ?>
<?php else: ?>
    <?php ($authenticateClass = ' loginBeforeGo'); ?>
<?php endif; ?>
<div class="subscribe section" id="<?php echo e($bannerData->componentSlug); ?>">
		<div class="container-fluid">
			<div class="subscribe-block">
				<a class="<?php echo e($bannerData->componentBannerUrlType == '6' ? $authenticateClass : ''); ?> <?php echo e($bannerData->componentBannerUrlType == '6' ? 'playSingleSongInPlayer' : ''); ?>" 
					href="<?php echo e($bannerData->componentBannerUrlType != '6' ? url($bannerData->componentBannerUrl) : 'javascript:void(0)'); ?>" 
					<?php echo e($bannerData->componentBannerUrlType == '6' ? "data-song-id=$bannerData->componentBannerUrlTypeId" : ""); ?>>
				<img src="<?php echo e($bannerData->componentBanner); ?>">
				</a>
			</div>
		</div>
	</div>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/homepage-component/banner.blade.php ENDPATH**/ ?>