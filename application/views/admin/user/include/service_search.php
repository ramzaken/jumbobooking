<?php $e=1; foreach ($services as $service): ?>
    <div class="col-md-12">
        <label class="service-rdo">
            <input type="radio" name="service_id" class="service_input" value="<?php echo html_escape($service->id) ?>"/>
            <div class="d-flex justify-content-between py-2 align-items-center mb-1 m-0">
                <div class="col-auto mb-sm-0">
                    <div class="media service_item">
                        <!-- <?php if (file_exists(FCPATH. $service->image)) {
                        $service_img = base_url($service->image);
                        }else{
                        $service_img = base_url('assets/front/img/no-image.png');
                        } ?> -->

                        <?php if (!empty($service->image)): ?>
                        <img alt="Service" src="<?php echo base_url($service->image) ?>" class="shadow-sm  mr-4">
                        <?php endif ?>

                        <div class="media-body">
                            <h5 class="text-dark mb-0 pt-1 fs-15"><?php echo html_escape($service->name) ?></h5>
                            <span class="text-dark-75 fs-12"> <?php echo html_escape($service->duration).' '.trans($service->duration_type); ?> 
                            <span class="mr-2"></span> 
                            </span>

                            <span class="text-dark-75 fs-12">
                            <?php if ($service->price == 0): ?>
                            <?php echo trans('free') ?>
                            <?php else: ?>
                            <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <?php echo number_format($service->price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                            <?php endif ?>
                            </span>

                            <?php if($service->service_type == 2): ?>
                            <span class="mr-3"><?php echo trans('recurring-service') ?></span>
                            <?php if($service->service_repeat == 7): ?>
                            <span><?php echo trans('repeats-weekly') ?></span>
                            <?php endif; ?>
                            <?php if($service->service_repeat == 30): ?>
                            <span><?php echo trans('repeats-monthly') ?></span>
                            <?php endif; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </label>

        <input type="hidden" class="pos_enable_staff" name="pos_enable_staff" value="<?php echo html_escape($this->business->enable_staff); ?>">

        <div class="service_extra service_extra_area_<?php echo $service->id ?>">
        </div>
    </div>
<?php $e++; endforeach; ?>