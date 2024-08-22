<?php $__env->startSection('title', $seo_title); ?>
<?php $__env->startSection('metaTitle', $seo_title); ?>
<?php $__env->startSection('metaKeywords', $seo_meta_keyword); ?>
<?php $__env->startSection('metaDescription', $seo_description); ?>

<?php $__env->startSection('content'); ?>

    <?php if(Auth::check()): ?>
        <?php ($authenticateClass = ''); ?>
    <?php else: ?>
        <?php ($authenticateClass = ' loginBeforeGo'); ?>
    <?php endif; ?>


    <!--------------------------
                    HOME START
                --------------------------->
    <div class="tab-wrapper">
        <div class="container-fluid">
            <div class="tab-section">
                <ul>
                    <?php if($content): ?>
                        <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyHomePageHeaderMenu => $rowHomePageHeaderMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($rowHomePageHeaderMenu->componentId == 'HomePageHeaderMenu'): ?>
                                <li><a data="#home" class="tab-link active">Home</a></li>
                                <?php $__currentLoopData = $rowHomePageHeaderMenu->HomePageHeaderMenuData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyHomePageHeaderMenuData => $rowHomePageHeaderMenuData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <li><a data="#<?php echo e($rowHomePageHeaderMenuData->value); ?>"
                                            class="tab-link"><?php echo e($rowHomePageHeaderMenuData->key); ?></a></li>
                                    
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <!-- <li><a href="" class="tab-link">How It Works</a></li>
                        <li><a href="" class="tab-link">Best of fanclub</a></li>
                        <li><a href="" class="tab-link">Trending Now</a></li>
                        <li><a href="" class="tab-link">New to fanclub</a></li>
                        <li><a href="" class="tab-link">Playlists</a></li>
                        <li><a href="" class="tab-link">Featured Artists</a></li> -->
                </ul>
            </div>
        </div>
    </div>

    <div class="">
        <div class="header-carousel section" id="home">
            <div class="owl-carousel owl-theme custom-cara">
                <?php if($content): ?>
                    <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($row->componentId == 'HomePageBannerComponent'): ?>

                            <?php $__currentLoopData = $row->HomePageBannerData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyHomePageBannerData => $valueHomePageBannerData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($valueHomePageBannerData->bannerUrl); ?>"
                                    class="item">
                                    <img src="<?php echo e($valueHomePageBannerData->Image); ?>">
                                </a>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if($content): ?>
            <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyHowItWorksWebComponent => $rowHowItWorksWebComponent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($rowHowItWorksWebComponent->componentId == 'HowItWorksWebComponent'): ?>
                    <div class="how-it-work" id="how-it-works">
                        <div class="container-fluid">
                            <div class="text-center">
                                <h4><?php echo e($rowHowItWorksWebComponent->HowItWorksWebData->label->mainLabel); ?></h4>
                                <span
                                    class="blur-color mb-21"><?php echo e($rowHowItWorksWebComponent->HowItWorksWebData->label->mainDescription); ?></span>
                            </div>
                            <div class="hiw-wrapper">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6 custome-column">
                                        <div class="hiw-fan-box">
                                        <h5><?php echo e($rowHowItWorksWebComponent->HowItWorksWebData->label->fanLabel); ?></h5>
                                        <span
                                            class="blur-color"><?php echo e($rowHowItWorksWebComponent->HowItWorksWebData->label->fanDescription); ?></span>
                                        <!-- <img src="assets/img/Fan.png"> -->
                                        <div class="hiw-box">

                                            <?php if($content): ?>
                                                <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($row->componentId == 'HowItWorksWebComponent'): ?>
                                                        <?php ($i = 1); ?>
                                                        <?php $__currentLoopData = $rowHowItWorksWebComponent->HowItWorksWebData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyHowItWorksWebData => $valueHowItWorksWebData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($valueHowItWorksWebData->Type == 'fan'): ?>

                                                                <?php ($renderFile = ''); ?>
                                                                <?php if($i % 2 == 0): ?>
                                                                    <?php ($renderFile = 'left-content--right-image'); ?>
                                                                <?php else: ?>
                                                                    <?php ($renderFile = 'left-image-content-right'); ?>
                                                                <?php endif; ?>

                                                                <?php echo $__env->make('frontend.how-it-works-web.'.$renderFile,['valueHowItWorksWebData'
                                                                => $valueHowItWorksWebData,'i' => $i], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                                                <?php ($i++); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6 custome-column">
                                    <div class="hiw-artist-box">
                                        <h5><?php echo e($rowHowItWorksWebComponent->HowItWorksWebData->label->artistLabel); ?></h5>
                                        <span
                                            class="blur-color"><?php echo e($rowHowItWorksWebComponent->HowItWorksWebData->label->artistDescription); ?></span>
                                        <div class="hiw-box">
                                            <?php if($content): ?>
                                                <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($row->componentId == 'HowItWorksWebComponent'): ?>
                                                        <?php ($i = 1); ?>
                                                        <?php $__currentLoopData = $row->HowItWorksWebData->list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyHowItWorksWebData => $valueHowItWorksWebData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($valueHowItWorksWebData->Type == 'artist'): ?>

                                                                <?php ($renderFile = ''); ?>
                                                                <?php if($i % 2 == 0): ?>
                                                                    <?php ($renderFile = 'left-image-content-right'); ?>
                                                                <?php else: ?>
                                                                    <?php ($renderFile = 'left-content--right-image'); ?>
                                                                <?php endif; ?>

                                                                <?php echo $__env->make('frontend.how-it-works-web.'.$renderFile,['valueHowItWorksWebData'
                                                                => $valueHowItWorksWebData,'i' => $i], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                                                <?php ($i++); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                        </div>
                                        <!-- <img src="assets/img/Artist.png"> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if($content): ?>
            <?php ($classAdded = 0); ?>
            <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyLoginSections => $rowLoginSections): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php if($rowLoginSections->componentId == 'myPlaylist' && !empty($rowLoginSections->myPlaylistData)): ?>
                    <div class="new-on-fanclub section" id="my-playlists">
                        <div class="container-fluid">
                            <div class="slider-header">
                                <h5>My Playlists</h5>
                                <a href="<?php echo e(url('myplaylist')); ?>" class="a">See All</a>
                            </div>
                        </div>
                        <div class="square-img-carousel">
                            <div class="owl-carousel owl-theme">
                                <?php $__currentLoopData = $rowLoginSections->myPlaylistData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyMyPlaylistData => $rowMyPlaylistData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(url('my-playlist/' . $rowMyPlaylistData->playlistSlug)); ?>"
                                        class="item">
                                        <img src="<?php echo e($rowMyPlaylistData->playListIcon); ?>">
                                        <p class="s1"><?php echo e($rowMyPlaylistData->playlistName); ?></p>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php ($classAdded++); ?>
                <?php endif; ?>
                <?php if($rowLoginSections->componentId == 'favPlaylist' && !empty($rowLoginSections->favPlaylistData)): ?>
                    <div class="new-on-fanclub section <?php echo e($classAdded ? '' : 'pt-76'); ?>" id="fav-playlists">
                        <div class="container-fluid">
                            <div class="slider-header">
                                <h5><?php echo e($rowLoginSections->title); ?></h5>
                                
                                <a href="<?php echo e(url('favourite-playlist')); ?>" class="a">See
                                    All</a>
                            </div>
                        </div>
                        <div class="square-img-carousel">
                            <div class="owl-carousel owl-theme">
                                <?php $__currentLoopData = $rowLoginSections->favPlaylistData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyFavPlaylistData => $rowFavPlaylistData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(url('songs/' . $rowFavPlaylistData->groupSlug)); ?>"
                                        class="item">
                                        <img src="<?php echo e($rowFavPlaylistData->groupIcon); ?>">
                                        <p class="s1"><?php echo e($rowFavPlaylistData->groupName); ?></p>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php ($classAdded++); ?>
                <?php endif; ?>
                <?php if($rowLoginSections->componentId == 'myRecent' && !empty($rowLoginSections->myRecentData)): ?>
                    <div class="my-collection section <?php echo e($classAdded ? '' : 'pt-76'); ?>" id="<?php echo e('recently-played'); ?>">
                        <div class="slider-header">
                            <h5>Recently Played</h5>
                            <?php if($authenticateClass): ?>
                                <a href="javascript:void(0)" class="a<?php echo e($authenticateClass); ?>">See All</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('recentPlayed')); ?>" class="a">See All</a>
                            <?php endif; ?>
                        </div>
                        <div class="collection-wrapper">
                            <div class="owl-carousel owl-theme">
                                <?php ($i = 1); ?>
                                <?php $__currentLoopData = $rowLoginSections->myRecentData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyMyCollectionsData => $rowMyCollectionsData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($i % 2 == 1): ?>
                                        <div class="item">
                                    <?php endif; ?>
                                    <div class="collection-box">
                                        <img class="c-img" src="<?php echo e($rowMyCollectionsData->songIcon); ?>" />
                                        <div role="button" class="collection-data playSingleSongInPlayer"
                                            data-song-id=<?php echo e($rowMyCollectionsData->songId); ?>>
                                            <span><?php echo e($rowMyCollectionsData->songName); ?></span>
                                            <p class="caption"><?php echo e($rowMyCollectionsData->artistName); ?></p>
                                        </div>
                                        <?php echo $__env->make('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                                        'popupDownload'],'songId' => $rowMyCollectionsData->songId, 'allData' =>
                                        $rowMyCollectionsData, "refresh"=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </div>
                                    <?php if($i % 2 == 0 || count($rowLoginSections->myRecentData) == $i): ?>
                            </div>
                <?php endif; ?>
                <?php ($i++); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    </div>
    </div>
    <?php ($classAdded++); ?>
    <?php endif; ?>
    <?php if($rowLoginSections->componentId == 'myCollectionsWeb' && !empty($rowLoginSections->myCollectionsWebData)): ?>
        <div class="my-collection section <?php echo e($classAdded ? '' : 'pt-76'); ?>" id="<?php echo e('my-collection'); ?>">
            <div class="slider-header">
                <h5>My Collection</h5>
                <?php if($authenticateClass): ?>
                    <a href="javascript:void(0)" class="a<?php echo e($authenticateClass); ?>">See All</a>
                <?php else: ?>
                    <a href="<?php echo e(route('my-favourite')); ?>" class="a">See All</a>
                <?php endif; ?>
            </div>
            <div class="collection-wrapper">
                <div class="owl-carousel owl-theme">
                    <?php ($i = 1); ?>
                    <?php $__currentLoopData = $rowLoginSections->myCollectionsWebData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyMyCollectionsData => $rowMyCollectionsData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($i % 2 == 1): ?>
                            <div class="item">
                        <?php endif; ?>
                        <div class="collection-box">
                            <img class="c-img" src="<?php echo e($rowMyCollectionsData->songIcon); ?>" />
                            <div role="button" class="collection-data playSingleSongInPlayer"
                                data-song-id=<?php echo e($rowMyCollectionsData->songId); ?>>
                                <span><?php echo e($rowMyCollectionsData->songName); ?></span>
                                <p class="caption"><?php echo e($rowMyCollectionsData->artistName); ?></p>
                            </div>
                            <?php echo $__env->make('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                            'popupDownload'],'songId' => $rowMyCollectionsData->songId, 'allData' => $rowMyCollectionsData,
                            "refresh"=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <?php if($i % 2 == 0 || count($rowLoginSections->myCollectionsWebData) == $i): ?>
                </div>
    <?php endif; ?>
    <?php ($i++); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    </div>
    </div>
    <?php ($classAdded++); ?>
    <?php endif; ?>
    
    <?php if($rowLoginSections->componentId == 'favArtist' && !empty($rowLoginSections->favArtistData)): ?>
        <div class="my-artist section" id="my-artists">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>My Artists</h5>
                    <a href="<?php echo e(url('my-artists')); ?>" class="a">See All</a>
                </div>
            </div>
            <div class="rounded-img-carousel">
                <div class="owl-carousel owl-theme">
                    <?php $__currentLoopData = $rowLoginSections->favArtistData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyFavArtistData => $rowFavArtistData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(url('artist/' . $rowFavArtistData->artistSlug)); ?>"
                            class="item">
                            <img src="<?php echo e($rowFavArtistData->artistProfilePic); ?>">
                            <p class="s1"><?php echo e($rowFavArtistData->artistFullName); ?></p>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php ($classAdded++); ?>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>


    <?php if($content): ?>
        <?php $__currentLoopData = $content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($row->componentId == 'HomePageComponent'): ?>
                <?php $__currentLoopData = $row->HomePageComponentData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $row1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($row1->componentData->componentType == '1'): ?>
                        <div class="section" id="<?php echo e($row1->componentData->componentSlug); ?>">
                            <?php echo $row1->componentData->componentText; ?>

                        </div>
                    <?php elseif($row1->componentData->componentType == '2'): ?>
                        <?php echo $__env->make('frontend.homepage-component.banner',['bannerData' => $row1->componentData], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php elseif($row1->componentData->componentType == '3'): ?>
                        <?php if(!empty($row1->componentData->componentDynamicGroup->data)): ?>

                            <?php echo $__env->make('frontend.homepage-component.'.strtolower($row1->componentData->componentDynamicGroup->commonDetails->ImageShape),['data'
                            => $row1->componentData], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    </div>
    <?php echo $__env->make('frontend.components.playlist.add.add-to-my-playlist', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('frontend.components.music-player.form-for-single-song', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--------------------------
                    HOME END
                --------------------------->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footscript'); ?>
    <script src="<?php echo e(asset('public/assets/frontend/js/redirect-video-player.js')); ?>?r=090820200" data-base-url="<?php echo e(url('/')); ?>">
    </script>
    <script type="text/javascript">
        
        $(document).ready(function() {
            $('.rounded-img-carousel .owl-carousel, .square-img-carousel .owl-carousel, .double-img-carousel .owl-carousel')
                .owlCarousel({
                    margin: 16,
                    rewindNav: false,
                    dots: false,
                    nav: true,
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        576: {
                            items: 2,
                            margin: 24
                        },
                        768: {
                            items: 4,
                            margin: 24
                        },
                        1200: {
                            items: 6,
                            margin: 24
                        }
                    }
                })
        })

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

        $("#loginFormFromPopup_bk").validate({
            ignore: [],
            rules: {
                email: "required",
                password: "required",
            },
            messages: {
                email: "Email is required",
                password: "Password is required"
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            window.location = '<?php echo e(url('/')); ?>';
                        } else if (response.statusCode ==
                            '201') { // Redirect to the artist dashboard
                            window.location = '<?php echo e(route('ArtistDashboard')); ?>';
                        } else if (response.statusCode ==
                            '202') { // Redirect to the signup form for fan
                            window.location = '<?php echo e(route('showSignupFan')); ?>';
                        } else {
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });

        $("#loginWithOtpFormFromPopup_bk").validate({
            ignore: [],
            rules: {
                input: "required",
            },
            messages: {
                input: "Email is required",
            },
            submitHandler: function(form) {
                $('.loader-bg').removeClass('d-none');
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('.loader-bg').addClass('d-none');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.message);

                            $('#loginWithOtpModal').modal('hide');
                            $('#loginWithOtpVerificationModal').modal('show');
                            $('#loginWithOtpVerificationModal .opt-email-popup-text').text(response
                                .component.input);
                            $('#loginWithOtpVerificationModal .opt-email-popup-value').val(response
                                .component.input);
                        } else {
                            $('.loader-bg').addClass('d-none');

                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });
        
        $(document).on('submit','#resendOtpFormFromPopup_bk',function(e){
            e.preventDefault();
            $('.loader-bg').removeClass('d-none');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.statusCode == '200') {
                        $('.loader-bg').addClass('d-none');
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.message);

                        $('#loginWithOtpModal').modal('hide');
                        $('#loginWithOtpVerificationModal').modal('show');
                        $('#loginWithOtpVerificationModal .opt-email-popup-text').text(response
                            .component.input);
                        $('#loginWithOtpVerificationModal .opt-email-popup-value').val(response
                            .component.input);
                    } else {
                        $('.loader-bg').addClass('d-none');

                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.component.error);
                    }
                }
            });
        })

        $("#loginWithOtpVerificationFormFromPopup_bk").validate({
            rules: {
                "digit-1": "required",
                "digit-2": "required",
                "digit-3": "required",
                "digit-4": "required"
            },
            messages: {
                "digit-1" : {
                    required: "OTP is required"
                },
                "digit-2" : {
                    required: "OTP is required"
                },"digit-3" : {
                    required: "OTP is required"
                },"digit-4" : {
                    required: "OTP is required"
                }
            },
            errorPlacement: function ( error, element ) {
                $('.otp-error').text('');
                error.appendTo('.otp-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            window.location = '<?php echo e(url('/')); ?>';
                        } else {
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });

        $("#forgotPasswordFormFromPopup_bk").validate({
            ignore: [],
            rules: {
                email: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Email is required"
                }
            },
            submitHandler: function(form) {
                $('.loader-bg').removeClass('d-none');
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('.loader-bg').addClass('d-none');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.message);

                            $('#forgotPasswordModal').modal('hide');
                        } else {
                            $('.loader-bg').addClass('d-none');

                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });


        $(function() {

            var link = $('.tab-section a.tab-link');
            var link2 = $('.sidebarScroll a.tab-link');

            link.each(function(){
                var id = $(this).attr('data');
                if(!$(id).length)
                {
                    $(this).closest('li').hide();
                }
            });

            link.on('click', function(e) {
                var target = $($(this).attr('data'));
                $('html, body').animate({
                    scrollTop: target.offset().top - 150
                }, 600);
                $(this).addClass('active');
                e.preventDefault();
            });
            link2.each(function(){
                var id = $(this).attr('data');
                if(!$(id).length)
                {
                    $(this).closest('li').hide();
                }
            });

            link2.on('click', function(e) {
                var target = $($(this).attr('data'));
                $('html, body').animate({
                    scrollTop: target.offset().top - 150
                }, 600);
                $(this).addClass('active');
                e.preventDefault();
            });

            $(window).on('scroll', function() {
                scrNav();
            });

            function scrNav() {
                var sTop = $(window).scrollTop();
                $('.section').each(function() {
                    var id = $(this).attr('id'),
                        offset = $(this).offset().top - 190,
                        height = $(this).height();
                    if (sTop >= offset && sTop < offset + height) {
                        link.removeClass('active');
                        $('.tab-section').find('[data="#' + id + '"]').addClass('active');
                    }
                });
            }
            scrNav();
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/home.blade.php ENDPATH**/ ?>