    
    
    <section class="border-bottom border-light py-8 py-lg-10" style="background:
        <?php if(site_mode() == 'light'){echo adjustBrightness(settings()->site_color, -50);} ?>">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-12 col-lg-6 order-md-1 pr-lg-5 pr-xl-0 mb-8 mb-lg-0">
                    <p class="text-uppercase text-light fs-15 mb-3" data-aos="zoom-in"><?php echo trans('one-platform-for-any-business') ?></p>
                    <h1 data-aos="fade-left" data-aos-delay="250" class="display-4 text-white font-weight-bold custom-fonts">
                        <?php echo trans('smart-booking-tool-to-grow-your-online-business') ?></h1>
                    <p data-aos="fade-left" data-aos-delay="250" class="text-light fs-20 mt-3 mb-4 <?php if(text_dir() == 'rtl'){echo "pl-15";}else{echo "pr-15";} ?>"><?php echo lang_value()->description ?></p>
                    
                    <a href="<?php echo base_url('pricing') ?>" class="brd-6 btn btn-light mt-3 fs-14 py-3 px-4" data-aos="fade-left" data-aos-delay="350"><i class="bi bi-tag"></i> <?php echo trans('pricing') ?> <?php echo trans('plans') ?></a>

                    <?php if (settings()->trial_days != 0): ?>
                        <a href="<?php echo base_url('pricing?trial=start') ?>" class="brd-6 btn btn-outline-light mt-3 ml-3 fs-14 py-3 px-4" data-aos="fade-left" data-aos-delay="350"><?php echo trans('start') ?> <?php echo settings()->trial_days; ?> <?php echo trans('days-trial') ?> <i class="bi bi-arrow-right"></i></a>
                    <?php endif ?>
                </div>

                <div class="col-md-12 col-lg-6 order-md-2">
                    <div class="banner-img pl-lg-6" data-aos="zoom-in">
                        <img src="<?php echo base_url(settings()->hero_img) ?>" class="text-right" alt="Hero Image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if(!empty($brands)): ?>
        <section class="bg-grays py-4 bbm-1 border-bottom">
            <div class="container">
                <div class="brand-carousel-5 owl-carousel owl-theme">
                    <?php foreach ($brands as $brand): ?>
                        <?php include APPPATH.'views/templates/common/brands.php'; ?>
                    <?php endforeach ?>
                </div>

            </div>
        </section>
    <?php endif; ?>


    <?php if (settings()->enable_feature == 1): ?>
        <section class="bg-light py-4 pb-8">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 mx-auto">
                        <div class="text-center mt-7 mb-9" data-aos="fade-up">
                            <p class="mb-1 text-primary font-weight-bold"><?php echo trans('tailored-solutions-for-every-sector') ?></p>
                            <h1 class="h1"><?php echo trans('elevate-your-booking-business') ?></h1>
                            <p class="leads text-muted fs-18"><?php echo trans('elevate-your-booking-business-details') ?></p>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <!-- Feature box -->

                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-warning-soft px-5 py-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-calendar2-plus"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-3 mx-auto text-dark h5 oba"><?php echo trans('online-booking-appointment-management') ?></h4>
                                        <p class="text-dark mb-0 w-lg-60"><?php echo trans('online-booking-appointment-management-desc') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-2 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-blue px-5 py-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-window"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-3 mx-auto text-white h5"><?php echo trans('booking-website') ?></h4>
                                        <p class="text-white mb-0 w-lg-80"><?php echo trans('booking-website-desc') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-blue-soft py-5 px-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-envelope-check"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-3 mx-auto text-dark h5 ant"><?php echo trans('automated-notifications') ?></h4>
                                        <p class="text-dark mb-0 w-lg-80"><?php echo trans('automated-notifications-desc') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-navy py-5 px-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-credit-card-2-back-fill"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-3 mx-auto text-white h5"><?php echo trans('accept-payments-with-deposit-options') ?></h4>
                                        <p class="text-light mb-0 w-lg-90"><?php echo trans('accept-payments-with-deposit-options-desc') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-success-soft py-5 px-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-arrow-repeat"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-3 mx-auto h5"><?php echo trans('recurring-group-bookings') ?></h4>
                                        <p class="text-dark mb-0 w-lg-90"><?php echo trans('recurring-group-bookings-desc') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-lgreen py-5 px-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-clipboard-data"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-3 mx-auto text-white h5"><?php echo trans('analytics-reporting') ?></h4>
                                        <p class="dark-light w-lg-70"><?php echo trans('analytics-reporting-desc') ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-purple-soft py-5 px-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-toggles"></i></span>
                                    <div class="fitem-body">
                                        <!-- <h4 class="mb-3 mx-auto h5 bpos">Booking <br> POS System</h4>
                                        <p class="mb-0 w-lg-70">Use the built-in POS system to manage on-site payments and bookings seamlessly in your shop</p> -->
                                        <h4 class="mb-3 mx-auto h5 bpos"><?php echo trans('custom-booking-rules') ?></h4>
                                        <p class="mb-0 w-lg-70"><?php echo trans('custom-booking-rules-desc') ?></p>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4 mb-3 mb-md-0" data-aos="zoom-in-up">
                                <div class="text-left bg-warning py-5 px-5 fitem-lg round-1 mb-4">
                                    <span class="fitem-icon"><i class="bi bi-patch-check"></i></span>
                                    <div class="fitem-body">
                                        <h4 class="mb-4 mx-auto text-dark h5"><?php echo trans('more-features') ?></h4>
                                        <div class="d-flex justify-content-between">
                                            <div class="">
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('embed-booking') ?></p>
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('event-booking') ?> </p>
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('affiliate') ?> </p>
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('calendar-sync') ?> </p>
                                            </div>
                                            <div class="">
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('coupons') ?> </p>
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('location-branches') ?> </p>
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('qr-code') ?> </p>
                                                <p class="fs-14 link-grey"><i class="bi bi-check-circle"></i> <?php echo trans('gallery') ?> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                        <!-- End Feature box -->
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>



    <?php if (settings()->enable_workflow == 1): ?>
        <section class="zindex-low">
            <div class="container z0">
                <div class="w-md-80 w-lg-50 text-center mx-auto mb-8 mb-lg-10" data-aos="fade-up">
                    <span class="badge badge-secondary-soft badge-square mb-3"><?php echo trans('workflow') ?></span>
                    <h1 class="text-dark font-weight-bold mx-auto mb-1"><?php echo trans('workflow-title') ?></h2>
                </div>

                <div class="row">
                    <?php $w=1; foreach ($workflows as $workflow): ?>
                        <div class="col-md-4 mb-7 mb-md-0" data-aos="zoom-in-up" data-aos-delay="150">
                            <div class="text-center m-2 py-6 px-4 <?php if($w==2){echo "shadow-workflow brd-10";} ?>">
                                <div class="mb-5 workflow-img bg-light"><img class="display-5" src="<?php echo base_url($workflow->image) ?>" alt="Image"></div>

                                <h5 class="mb-3 text-dark mx-auto w-lg-80"><?php echo html_escape($workflow->title) ?></h5>
                                <p class="text-muted mx-auto w-lg-90"><?php echo html_escape($workflow->details) ?></p>
                            </div>
                        </div>
                    <?php $w++; endforeach ?>
                </div>
            </div>
        </section>
    <?php endif; ?>



    <section class="zindex-low resultbg py-8 pb-10">
        <div class="container z0">
            <div class="w-md-80 w-lg-80 text-center mx-auto mb-6" data-aos="fade-up">
                <h1 class="display-5 font-weight-bold mx-auto mt-0 w-lg-70 text-dblue"><?php echo trans('a-platform-that-engineered-for-success') ?></h1>
            </div>

            <div class="row">
                <div class="col-md-4" data-aos="zoom-in-up" data-aos-delay="150">
                    <div class="text-center m-2 py-6 px-4 round-2 resultitem">
                        <p class="text-muted"><?php echo trans('empowered-by') ?></p>
                        <h1 class="mb-2 display-2 mx-auto text-dblue font-weight-bold"><?php echo shortend_number(get_count('business')) ?></h1>
                        <p class="mb-2 fs-20 mx-auto text-dark"><?php echo trans('business') ?></p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="zoom-in-up" data-aos-delay="150">
                    <div class="text-center m-2 py-6 px-4 round-2 resultitem">
                        <p class="text-muted"><?php echo trans('global-community-from') ?></p>
                        <h1 class="mb-2 display-2 mx-auto text-dblue font-weight-bold"><?php echo shortend_number($count_countries) ?></h1>
                        <p class="mb-2 fs-20 mx-auto text-dark"><?php echo trans('countries') ?></p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="zoom-in-up" data-aos-delay="150">
                    <div class="text-center m-2 py-6 px-4 round-2 resultitem">
                        <p class="text-muted"><?php echo trans('we-have-scheduled-over') ?></p>
                        <h1 class="mb-2 display-2 mx-auto text-dblue font-weight-bold"><?php echo shortend_number($count_bookings) ?></h1>
                        <p class="mb-2 fs-20 mx-auto text-dark"><?php echo trans('bookings') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <!-- features -->
    <?php if (!empty($features)): ?>

        <section class="bg-primary-soft dkp" style="background: <?php if(site_mode() == 'light'){echo adjustBrightness(settings()->site_color, -20);} ?>">
            <div class="container">

                <div class="row">

                    <div class="col-md-5">
                        <div class="text-left mb-8 mt-3 w-90" data-aos="fade-up">
                            <p class="badge-primary badge-pill badge badge-square"><?php echo trans('features') ?></p>
                            <h1 class="h1 text-white"><?php echo trans('powerfull-features-specifically-designed-to-meet-your-business-needs') ?></h1>
                            <p class="fs-18 text-white"><?php echo trans('make-your-online-booking-business-desc') ?></p>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <!-- Feature box -->
                        <div class="row">
                            <div class="row mt-3 mb-3">
                                <?php $i=1; foreach ($features as $feature): ?>
                                     
                                    <div class="col-md-6 text-left mb-md-0" data-aos="fade-up">
                                        <div class="shadow-xs brd-10 border-0 card p-4 mb-5">
                                            <img src="<?php echo base_url($feature->image) ?>" class="screen-one w-80" alt="Feature Image">
                                    
                                            <h4 class="h6 mb-2 custom-fontss"><?php echo html_escape($feature->name); ?></h4>
                                            <p class="text-muted mb-6"><?php echo html_escape($feature->details); ?></p>
                                        </div>
                                    </div>
                                       
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>
                        <!-- End Feature box -->
                    </div>

                </div>

            </div>
        </section>

    <?php endif; ?>
    <!-- features -->



    <!-- Blog -->
    <?php if (settings()->enable_blog == 1 && !empty($posts)): ?>
        <section class="bg-lights pt-6">
            <div class="container">
                <div class="text-center mb-5 mt-5 mb-lg-7 mt-6" data-aos="zoom-in-up">
                    <p class="badge-primary-soft badge-pill badge badge-square"><?php echo trans('blogs') ?></p>
                    <h3 class="h2 mb-3 custom-fontss"><?php echo trans('learn-more-empower-yourself') ?></h3>
                </div>

                <div class="row">
                    <?php $b=1; foreach ($posts as $post): ?>
                        <?php include'include/blog_post_item2.php'; ?>
                    <?php $b++; endforeach ?>
                </div>
            </div>
        </section>
    <?php endif ?>
    <!-- End Blog -->



    <?php if (!empty($testimonials)): ?>
        <section class="bg-light parent-shapes">
            <div class="container counter-padding">
                <div class="row justify-content-center">
                    <div class="col-md-4 text-left pt-4 pr-3">
                        <p class="badge-primary-soft badge-pill badge badge-square"><?php echo trans('testimonials') ?></p>
                        <h1 class="h1 text-dark font-weight-bold custom-fontss text-left mb-5"><?php echo trans('testimonial-title') ?> us</h1>
                    </div>
                    <div class="col-md-8 pl-xs-5">
                        <div class="testimonial-carousel-2 owl-carousel owl-theme navleft">
                            <?php foreach ($testimonials as $testimonial): ?>
                                <div class="col-6s item">
                                    <div class="cards border-1 h-100 bg-white mr-2 round-2 mb-5">
                                        <div class="card-body testimonial-box shadow-sms">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center pt-3">
                                                    <img src="<?php echo base_url($testimonial->image) ?>" class="rounded-circle md-avatar tes-img" alt="Name">
                                                    <div class="ml-3">
                                                        <h6 class="mb-0"><?php echo html_escape($testimonial->name) ?></h6>
                                                        <span class="fs-16">
                                                            <?php echo ucfirst($testimonial->designation) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-4">
                                                <p class="font-weight-normal text-muted"><?php echo character_limiter($testimonial->feedback, 300) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif ?>
    <!-- Testimonials -->


    

