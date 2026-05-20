<!-- <div class="form-group">
    <label class="text-dark-75 h6"><?php echo trans('coupon') ?></label>
    <select class="form-control pos_coupon_code" name="coupon" required>
        <option value="0"><?php echo trans('select') ?></option>
        <?php foreach ($coupons as $coupon): ?>
        <option value="<?php echo html_escape($coupon->id) ?>" ><?php echo html_escape($coupon->code) ?></option>
        <?php endforeach ?>                 
    </select>
</div>
<div class="mr-5">
    <p class="mb-0">
        <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <span class="coupon_amount text-muted"></span> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
    </p>
</div> -->