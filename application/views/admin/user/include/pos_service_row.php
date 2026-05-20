
<div class="media service_item">
    <img alt="Service" src="<?php echo base_url($service->thumb) ?>" class="shadow-sm rounded mr-4">
    <div class="media-body d-flex justify-content-between">
        <div>
            
            
            <h6 class="text-dark-75 mb-0 pt-1"><?php echo html_escape($service->name) ?></h6>
            <span class="text-muted"> <?php echo html_escape($service->duration).' '.trans($service->duration_type); ?> 
                <span class="mr-2"></span> <span class="pos_date mr-2"></span> <span class="pos_time"></span>
            </span>
            <?php if($service->service_type == 2): ?>
                <span class="mr-3 text-muted"><?php echo trans('recurring-service') ?></span>
                <?php if($service->service_repeat == 7): ?>
                    <span class="text-muted"><?php echo trans('repeats-weekly') ?></span>
                <?php endif; ?>
                <?php if($service->service_repeat == 30): ?>
                    <span class="text-muted"><?php echo trans('repeats-monthly') ?></span>
                <?php endif; ?>
            <?php endif; ?> 
        </div>
        

        <div class="pr-5">
            <span class="text-muted">
                <?php if ($service->price == 0): ?>
                    <?php echo trans('free') ?>
                <?php else: ?>
                    <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <?php echo number_format($service->price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                <?php endif ?>
            </span>
        </div>
    </div>
</div>

