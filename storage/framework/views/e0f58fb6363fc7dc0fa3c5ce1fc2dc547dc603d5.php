<?php
$sidebarMenuData = app('App\Models\HomePageComponent')->getSidebarMenuData();
$authCheck = Auth::check();
$authRoleMain = $authCheck ? Auth::user()->role_id : 0;
$authRole = getAuthProps();
?>
<div class="sideMenu">
    <?php if(!$authCheck): ?>
        <div class="side-menu-profile">
            <img src="<?php echo e(asset('public/assets/frontend/img/guest-user.svg')); ?>" class="user">
            <a href="<?php echo e(url('login')); ?>">
                <p class="s1">Login / Sign Up</p>
            </a>
            <img src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" class="closeIcons">
        </div>
    <?php else: ?>
        <div class="side-menu-profile">
            <img src="<?php echo e(app('App\Models\UserProfilePhoto')->getProfilePhoto(Auth::user()->id)); ?>"
                class="user">
            <p class="s1">Hello,
                <?php echo e($authCheck ? Auth::user()->firstname . ' ' . Auth::user()->lastname : ''); ?></p>
            <img src="<?php echo e(asset('public/assets/frontend/img/close.svg')); ?>" class="closeIcons">
        </div>
    <?php endif; ?>

    <div class="first-menu">
        <ul>
            <?php if(($authCheck && $authRole == '3') || !$authCheck): ?>
                <li><a href="<?php echo e(url('/')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/s1.svg')); ?>">
                        <p class="s2">Home</p>
                    </a></li>
            <?php endif; ?>
            <?php if($authCheck && $authRole == '3'): ?>
                <li><a href="<?php echo e(url('my-favourite')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/s2.svg')); ?>">
                        <p class="s2">My Collection</p>
                    </a></li>
            <?php endif; ?>
            <?php if($authCheck && $authRole == '2'): ?>
                <li><a href="<?php echo e(route('ArtistDashboard')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/s1.svg')); ?>">
                        <p class="s2">Dashboard</p>
                    </a></li>
            <?php endif; ?>
            <li class="change-mode">
                <a href="javascript:void(0)">
                    <img src="<?php echo e(asset('public/assets/frontend/img/s3.svg')); ?>">
                    <p class="s2">Dark Mode</p>
                    <div class="button r" id="button-1">
                        <input type="checkbox" id="dark-thene-checkbox" class="checkbox dark-thene-checkbox">
                        <div class="knobs"></div>
                        <div class="layer"></div>
                    </div>
                </a>
            </li>
            <?php if($authCheck && $authRole == '2'): ?>
                <li class="change-mode allow-message">
                    <a href="javascript:void(0)">
                        <img src="<?php echo e(asset('public/assets/frontend/img/message.svg')); ?>">
                        <p class="s2">Let fans send messages</p>
                        <div class="button r" id="button-1">
                            <input type="checkbox"
                                <?php echo e(Auth::user() && Auth::user()->allow_message == '1' ? 'checked' : ''); ?>

                                id="allow-message-checkbox" class="checkbox allow-message-checkbox">
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="first-hr"></div>

    <div class="second-menu">
        <h6>Quick Access</h6>
        <ul>
            <?php if($authCheck && $authRoleMain == '3'): ?>
                <li>
                    <a href="<?php echo e(route('mySubscription')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/s4.svg')); ?>">
                        <p class="s2">Your Subscriptions</p>
                    </a>
                </li>
            <?php endif; ?>
            <?php if($authCheck): ?>
                <li>
                    <a
                        href="<?php echo e($authRole == '3' ? route('myReviewsFan') : route('artistSongListForReview')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/s5.svg')); ?>">
                        <p class="s2">My Reviews</p>
                    </a>
                </li>
            <?php endif; ?>
            <?php if($authCheck && $authRole == '2'): ?>
                <li>
                    <a href="<?php echo e(route('songList')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/ds1.svg')); ?>">
                        <p class="s2">My Songs</p>
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo e(route('forumsList')); ?>">
                    <img src="<?php echo e(asset('public/assets/frontend/img/s6.svg')); ?>">
                    <p class="s2">Forum</p>
                </a>
            </li>
            <?php if($authCheck): ?>
                <li>
                    <a href="<?php echo e(route('chatModule')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/message.svg')); ?>">
                        <p class="s2">Message</p>
                        <?php if(getCountChatUnread()): ?>
                            <div class="msg-count"><?php echo e(getCountChatUnread()); ?></div>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo e(url('about-us')); ?>">
                    <img src="<?php echo e(asset('public/assets/frontend/img/s7.svg')); ?>">
                    <p class="s2">About fanclub</p>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('faq')); ?>">
                    <img src="<?php echo e(asset('public/assets/frontend/img/s8.svg')); ?>">
                    <p class="s2">FAQs</p>
                </a>
            </li>
        </ul>
    </div>

    <?php if($authCheck): ?>
        <div class="first-hr profile-hr"></div>

        <div class="profile-menu">
            <h6>Profile</h6>
            <ul>
                <li>
                    <a href="<?php echo e($authCheck && $authRole == '3' ? route('editProfileFan') : route('ArtistProfile')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/p1.svg')); ?>">
                        <p class="s2">Profile</p>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo e(url('logout')); ?>">
                        <img src="<?php echo e(asset('public/assets/frontend/img/p4.svg')); ?>">
                        <p class="s2">Log Out</p>
                    </a>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    <div class="how-can-block">
        <div class="how-can-hr">
            <a href="<?php echo e(url('contact-us')); ?>">
                <p>How can we help you?</p>
                <img src="<?php echo e(asset('public/assets/frontend/img/right-arrow.png')); ?>">
            </a>
        </div>
    </div>

    <?php if(($authCheck && $authRole == '3') || !$authCheck): ?>
        <div class="third-menu">
            <h6>Quick Access</h6>
            <ul class="sidebarScroll">
                <?php if(!$authCheck): ?>
                    <li><a href="<?php echo e(route('showSignup')); ?>">New to fanclub</a></li>
                <?php endif; ?>

                <?php if($sidebarMenuData): ?>
                    <?php $__currentLoopData = $sidebarMenuData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php if($authCheck): ?>
                            <li><a href="<?php echo e(url($row['value'])); ?>"><?php echo e($row['key']); ?></a></li>
                        <?php else: ?>
                            <li><a href="<?php echo e(Route::current() && Route::current()->getName() == 'home' ? 'javascript:void(0)' : url('/#' . $row['value'])); ?>"
                                    data="#<?php echo e($row['value']); ?>" class="tab-link"><?php echo e($row['key']); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="terms-wrapper">
        <div class="terms-block">
            <a href="<?php echo e(url('terms-conditions')); ?>">
                <p class="caption">Terms & Conditions</p>
            </a>
            <div class="dot"></div>
            <a href="<?php echo e(url('privacy-policy')); ?>">
                <p class="caption">Privacy Policy</p>
            </a>
        </div>
    </div>
    <div class="install-app">
        <span>Install App</span>
        <a href=""><img src="<?php echo e(asset('public/assets/frontend/img/Apple.svg')); ?>"></a>
        <a href=""><img src="<?php echo e(asset('public/assets/frontend/img/Android.svg')); ?>"></a>
    </div>
</div>
<?php /**PATH /var/www/html/php/fanclub/resources/views/frontend/include/sidebar.blade.php ENDPATH**/ ?>