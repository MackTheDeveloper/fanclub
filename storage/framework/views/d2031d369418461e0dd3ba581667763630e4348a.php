<?php $__env->startSection('title', $content['artistImage']->artistImageData->name); ?>

<?php $__env->startSection('content'); ?>
<?php
$authCheck = Auth::check();
$authRoleMain = $authCheck ? Auth::user()->role_id : 0;
$authRole = getAuthProps();
?>
    <!--------------------------
                    ARTIST DETAIL START
            --------------------------->

    <div class="upcoming-event artist-details">
        <div class="header-gradient">
            <div class="container">
                <div class="header-content">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="<?php echo e(url('/')); ?>">fanclub</a></li>
                            <li><a href="<?php echo e(route('allArtists')); ?>">Artists</a></li>
                            <li><?php echo e($content['artistImage']->artistImageData->name); ?></li>
                        </ul>
                    </div>
                    <div class="header-img-data">
                        <img src="<?php echo e($content['artistImage']->artistImageData->image); ?>" alt="" class="big-img" />
                        <div class="data-wrapper">
                            <h3><?php echo e($content['artistImage']->artistImageData->name); ?></h3>
                            <div class="btn-block">
                                <?php if($authCheck && $authRole==3 && $id!=Auth::user()->id): ?>
                                <a href="javascript:void(0)" class="fill-btn sendMessageNow">Send Message</a>
                                <label class="heart">
                                    <?php ($disabled = $authCheck ? '' : "disabled='disabled'"); ?>
                                    <?php ($checked = $content['artistImage']->artistImageData->isFav ? 'checked' : ''); ?>

                                    <input <?php echo e($checked); ?> type="checkbox" <?php echo e($disabled); ?> class="artistLikeDislike"
                                        value="yes" name="heart" data-id="<?php echo e($id); ?>">
                                    <span class="heart-checkmark"></span>
                                </label>
                                <?php else: ?>
                                    <a href="<?php echo e(route('showSignup', $content['artistDetail']->artistDetailData->artistReferSignUp)); ?>" class="fill-btn subscribeNow">Subscribe</a>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($content['banner']->bannerData->list) && count($content['banner']->bannerData->list)>3): ?>
        <div class="container">
            <div class="header-carousel artist-c">
                <div class="owl-carousel owl-theme custom-cara">
                    <?php $__currentLoopData = $content['banner']->bannerData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="javascript:void(0)" class="item">
                        <img src="<?php echo e($item->file); ?>">
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="container">
            <div class="about">
                <div class="about-content-header">
                    <h5>About</h5>
                </div>
                <p class="blur-color toggle-content toggle-apply"><?php echo nl2br(e($content['artistDetail']->artistDetailData->aboutFullDesc)); ?></p>
                <a href="javascript:void(0)" class="a toggle-about">Read More</a>
            </div>
            
            <div class="news">
                <div class="flex-beetwen">
                    <h5>News</h5>
                    <?php if($content['news']->newsData->list): ?>
                        <a class="a"
                            href="<?php echo e(route('artistNewsList', $content['artistDetail']->artistDetailData->slug)); ?>">See
                            All</a>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <?php if($content['news']->newsData->list): ?>
                        <?php $__currentLoopData = $content['news']->newsData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="news-box-border">
                                    <div class="news-box">
                                        <h6 class='news-title'><?php echo e($row->name); ?></h6>
                                        <p class="blur-color"><?php echo e($row->description); ?></p>
                                        <a href="javascript:void(0)" class="a showMore" data-toggle="modal"
                                            data-target="#newsModal">Read More</a>
                                        <span class="date"><?php echo e($row->date); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-12 col-sm-12">
                            <p><?php echo e($content['news']->newsData->artistNewsNotFoundMsg); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="event-data">
                <div class="flex-beetwen">
                    <h5>Upcoming Events</h5>
                    <?php if($content['upcomingEvent']->upcomingEventData->list): ?>
                        <a class="a"
                            href="<?php echo e(route('artistEventList', $content['artistDetail']->artistDetailData->slug)); ?>">See
                            All</a>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <?php if($content['upcomingEvent']->upcomingEventData->list): ?>
                        <?php $__currentLoopData = $content['upcomingEvent']->upcomingEventData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="event-box">
                                    <img src="<?php echo e($row->banner); ?>" alt="" />
                                    <div class="date-box">
                                        <input type="hidden" name="dateValue" value="<?php echo e($row->date); ?>">
                                        <p class="s1"><?php echo e(date('d', strtotime($row->date))); ?></p>
                                        <span><?php echo e(date('M', strtotime($row->date))); ?></span>
                                    </div>
                                    <div class="title-content">
                                        <p class="s2"><?php echo e($row->name); ?></p>
                                        <span class="t-content"><?php echo e($row->description); ?></span>
                                        <div class="time-location">
                                            <a class="location" href="<?php echo e($row->location_url?:'javascript:void(0)'); ?>" <?php echo e($row->location_url?'target="_blank"':''); ?>>
                                                <img src="<?php echo e(asset('public/assets/frontend/img/location.svg')); ?>"
                                                    alt="" />
                                                <?php echo e($row->location); ?>

                                            </a>
                                            <div class="time">
                                                <img src="<?php echo e(asset('public/assets/frontend/img/time.svg')); ?>" alt="" />
                                                <?php echo e($row->time); ?>

                                            </div>
                                            <a href="javascript:void(0)" class="a showMoreEvent" data-toggle="modal"
                                                data-target="#upcomingEventModal">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-12 col-sm-12">
                            <p><?php echo e($content['upcomingEvent']->upcomingEventData->artistEventNotFoundMsg); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
                <div class="search-section">
                    <div class="search-box">
                        <button><img src="<?php echo e(asset('public/assets/frontend/img/search.svg')); ?>" alt="" /></button>
                        <input type="text" name="searchSong" class="searchSong" placeholder="Search Songs" />
                    </div>
                </div>
                <div class="songs-data">
                    <div class="row-5 filteredSongList">
                        <?php $__currentLoopData = $content['artistSongList']->artistSongListData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('frontend.components.song-grid',['songId' => $row->id,'icon' => $row->icon,'name' =>
                            $row->name,'artistName' =>
                            $row->artist,'noViews' => $row->noViews,'noLikes' => $row->noLikes,'hideLikeViews' => 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            
        </div>
    </div>
    <div class="modal fade newsPopup" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">About Me</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="<?php echo e(asset('public/assets/frontend/img/cancel-popup.svg')); ?>"></img></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer text-left">
                    <span class="blur-color">20 Sep, 2021</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade newsPopup" id="upcomingEventModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="<?php echo e(asset('public/assets/frontend/img/cancel-popup.svg')); ?>"></img></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer text-left">
                    <div class="time-location-popup">
                        <a class="location" href="javascript:void(0)">
                            <img src="<?php echo e(asset('public/assets/frontend/img/location-black.svg')); ?>" alt="" />
                            <span>Unknown</span>
                        </a>
                        <div class="time">
                            <img src="<?php echo e(asset('public/assets/frontend/img/time-black.svg')); ?>" alt="" />
                            <span>Undefined</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade addNewsPopup sendMsgPopup" id="newChatInitiate" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Message to
                        <?php echo e($content['artistImage']->artistImageData->name); ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="<?php echo e(url('public/assets/frontend/img/cancel-popup.png')); ?>" /></span>
                    </button>
                </div>
                <form id="chatInitiate" class="modal-body" method="POST" action="<?php echo e(route('intiateChat')); ?>">
                    <?php if($content['artistDetail']->artistDetailData->allowMessage == 1): ?>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="receiver_id" value="<?php echo e($id); ?>">
                        <div class="inputs-group">
                            <textarea name="message" id="message"></textarea>
                            <span>Message*</span>
                        </div>
                        <div class="m-footer">
                            <button type="button" class="border-btn" data-bs-dismiss="modal">Cancel</button>
                            <button class="fill-btn">Send</button>
                        </div>
                    <?php else: ?>
                        <h6><?php echo e($content['artistDetail']->artistDetailData->messageToArtistNotAllowed); ?></h6>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <!--------------------------
                    ARTIST DETAIL END
            --------------------------->
    <?php echo $__env->make('frontend.components.music-player.form-for-single-song', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footscript'); ?>
    <script src="<?php echo e(asset('public/assets/frontend/js/redirect-video-player.js')); ?>?r=090820200" data-base-url="<?php echo e(url('/')); ?>">
    </script>
    <script type="text/javascript">
        $(document).on('click', '.showMore', function() {
            var content = $(this).parent().find('p.blur-color').text();
            var date = $(this).parent().find('span.date').text();
            var title = $(this).parent().find('h6').text();
            $('#newsModal .modal-body p').html(nl2br(content));
            $('#newsModal .modal-footer span.blur-color').text(date);
            $('#newsModal .modal-header h5').text(title);
        });
        $(document).on('click', '.showMoreEvent', function() {
            var content = $(this).closest('.event-box').find('.t-content').text();
            var date = $(this).closest('.event-box').find('.date-box input').val();
            var time = $(this).closest('.event-box').find('.time-location .time').text().trim();
            var location = $(this).closest('.event-box').find('.time-location .location').text().trim();
            var location_url = $(this).closest('.event-box').find('.time-location .location').attr('href');
            var title = $(this).closest('.event-box').find('.title-content .s2').text();
            $('#upcomingEventModal .modal-body p').html(nl2br(content));
            $('#upcomingEventModal .modal-footer .location span').text(location);
            if (location_url!='javascript:void(0)') {
                $('#upcomingEventModal .modal-footer .location').attr('href',location_url).attr('target','_blank');
            }
            $('#upcomingEventModal .modal-footer .time span').text(date + ' ' + time);
            $('#upcomingEventModal .modal-header h5').text(title);
        });
        $(document).on('click', '.sendMessageNow', function() {
            $('#newChatInitiate').modal('show');
        });

        // $(document).on('submit','#chatInitiate',function(e){
        //     e.preventDefault();
        // });

        $("#chatInitiate").validate({
            ignore: [],
            rules: {
                message: "required",
            },
            messages: {
                message: "Message is required",
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        $('#newChatInitiate').modal('hide');
                        window.location.href =
                            "<?php echo e(route('chatModule', $content['artistDetail']->artistDetailData->slug)); ?>"
                    }
                });
                return false;
                // if (grecaptcha.getResponse()) {
                //         // 2) finally sending form data
                //         form.submit();
                // }else{
                //         // 1) Before sending we must validate captcha
                //     grecaptcha.reset();
                //     grecaptcha.execute();
                // }           
            }
        });

        $(document).ready(function() {
            $('.header-carousel .owl-carousel').owlCarousel({
                margin: 0,
                dots: true,
                nav: true,
                center: true,
                loop: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 3,
                        dots: false,
                    },
                    576: {
                        items: 3,
                        dots: false,
                    },
                    768: {
                        items: 3
                    },
                    1200: {
                        items: 3
                    }
                }
            })
        })
        $(document).on('change keyup', '.searchSong', function() {
            var value = $(this).val();
            var artistId = "<?php echo e($id); ?>";
            $.ajax({
                url: "<?php echo e(route('filterSongs')); ?>",
                method: 'post',
                data: 'search=' + value + '&filter[artist_id]=' + artistId +
                    '&_token=<?php echo e(csrf_token()); ?>',
                success: function(response) {
                    $('.filteredSongList').html(response);
                }
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/artist/artist-detail.blade.php ENDPATH**/ ?>