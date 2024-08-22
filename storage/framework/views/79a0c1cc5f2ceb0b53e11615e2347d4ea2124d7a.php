<form action="" id="singleSongInPlayerForm" method="post">
    <input type="hidden" name="_token" id="token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="songId" id="singleSongInPlayer-songId" value="" />
    <input type="hidden" name="page" value="single-song-in-player" />
</form>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/components/music-player/form-for-single-song.blade.php ENDPATH**/ ?>