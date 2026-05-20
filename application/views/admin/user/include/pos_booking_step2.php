<form method="post" enctype="multipart/form-data" class="validate-form confirm_pos_booking" action="<?php echo base_url('admin/pos/confirm_pos_booking')?>" role="form" novalidate>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <h5><?php echo trans('confirm-booking') ?></h5>

        <div class="card-body">
          <div class="form-group mt-3">
            <label class="text-dark-75 h6"><?php echo trans('payment-method') ?></label>
            <select class="form-control pos_payment_method" name="pos_payment_method">
                <option value=""><?php echo trans('select') ?></option>
                <option value="cash" selected><?php echo trans('cash') ?></option>
                <option value="card"><?php echo  trans('card') ?></option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mt-3">
                <label class="text-dark-75 h6"><?php echo trans('received-amount') ?></label>
                <div class="input-group">
                  <input type="text" class="form-control pos_received_amount" name="pos_received_amount" value="" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><?php echo html_escape($this->business->currency_symbol) ?></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mt-3">
                <label class="text-dark-75 h6"><?php echo trans('paying-amount') ?></label>
                <div class="input-group">
                  <input type="number" class="form-control pos_paying_amount" name="pos_paying_amount" value="" disabled>
                  <div class="input-group-append">
                    <span class="input-group-text"><?php echo html_escape($this->business->currency_symbol) ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-3 form-group d-hide prp">
            <label class="text-dark-75 h6"><?php echo trans('change-return') ?> </label><br>
            <div class="h2"><?php echo html_escape($this->business->currency_symbol) ?> <span class="pos_change_return"></span> </div>
            <input type="hidden" name="pos_change_return" class="pos_change_return_inp form-control" value="0">
          </div>

          <div class="form-group mt-5">
            <label class="text-dark-75 h6"><?php echo trans('payment-notes') ?></label>
            <textarea class="form-control" name="payment_note" rows="2"></textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
        <div class="card cmtp">

          <div class="card-body">
            <h6 class="mb-0 border-bottom-2 mb-2"><?php echo trans('service') ?></h6>

            <div class="d-flex justify-content-between">

              <div>
                <p><?php echo get_by_id($this->session->userdata('service_id'),'services')->name ?></p>
              </div>
              <div class="pr-5">
                <span class="text-muted">
                  <?php if (get_by_id($this->session->userdata('service_id'),'services')->price == 0 ): ?>
                      <?php echo trans('free') ?>
                  <?php else: ?>
                      <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <?php echo number_format(get_by_id($this->session->userdata('service_id'),'services')->price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                  <?php endif ?>
                </span>
              </div>
            </div>
            

            <?php if(!empty($this->session->userdata('service_extra'))): ?>
              <h6 class="border-bottom-2 mb-2"><?php echo trans('service-extra') ?></h6>
             <?php  $service_extras = explode(',', $this->session->userdata('service_extra')); ?>

             <?php foreach ($service_extras as $value): ?>

              <div class="d-flex justify-content-between">
                <div>
                  <p class="mt-0 mb-0"><?php echo get_by_id($value,'service_extra')->name; ?></p>
                </div>
                <div class="pr-5">
                  <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <?php echo number_format(get_by_id($value,'service_extra')->price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                </div>
              </div>
             <?php endforeach ?>
            <?php endif; ?>


            <h6 class="mt-3 mb-0 border-bottom-2 mb-2"><?php echo trans('date-time') ?></h6>
            <div class="d-flex justify-content-start align-items-center">
              <div class="text-muted">
                <p class="mt-0 mb-0"> <i class="bi bi-calendar2-check mr-2"></i>  <?php echo my_date_show($date) ?></p>
                <p class="mt-0 mb-0"> <i class="bi bi-clock mr-2"></i>  <?php echo html_escape ($time) ?></p>
              </div>
            </div>

            <?php if (!empty(get_by_id($this->session->userdata('staff_id'),'staffs')->name)): ?>
            <h6 class="mt-3 mb-0 border-bottom-2 mb-2"><?php echo trans('staff') ?></h6>
            <div class="d-flex justify-content-start align-items-center">
              <div class="text-muted">
                <p class="mt-0 mb-0 text-dark"><?php echo get_by_id($this->session->userdata('staff_id'),'staffs')->name ?></p>
                <p class="mt-0 mb-0 fs-12"><?php echo get_by_id($this->session->userdata('staff_id'),'staffs')->email ?></p>
              </div>
            </div>
            <?php endif ?>

            <h6 class="mt-3 mb-0 border-bottom-2 mb-2"><?php echo trans('customer') ?></h6>
            <div class="d-flex justify-content-start align-items-center">
              <div class="text-muted">
                <p class="mt-0 mb-0 text-dark"><?php echo get_by_id($this->session->userdata('customer_id'),'customers')->name ?></p>
                <p class="mt-0 mb-0 fs-12"><?php echo get_by_id($this->session->userdata('customer_id'),'customers')->email ?></p>
              </div>
            </div>



            <div class="d-flex justify-content-between mb-1 bm-1 pb-1 pr-5 mt-5">
              <div class="mr-5">
               <p class="mb-1 text-dark-75 h6"><?php echo trans('sub-total') ?></p>
              </div>

              <div class="text-left">
                <p class="mb-1 text-muted fs-15 ml-3">
                  <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <span class="step2_sub_total text-muted"></span> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                </p>
              </div>
            </div>

            <?php if (!empty($this->session->userdata('pos_coupon_discount'))): ?>
              <div class="d-flex justify-content-between mb-1 bm-1 pb-1 pr-5">
                <div class="mr-5">
                 <p class="mb-1 text-dark-75 h6"><?php echo trans('discount') ?></p>
                </div>

                <div class="text-left">
                  <p class="mb-1 text-muted fs-15 ml-3">
                    <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <span class="step2_coupon_amount text-muted"></span> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                  </p>
                </div>
              </div>
            <?php endif ?>

            

            <div class="d-flex justify-content-between mb-3 bm-1 pb-1 pr-5">
              <div class="mr-5">
               <p class="mb-1 text-dark-75 h6"><?php echo trans('total') ?></p>
              </div>

              <div class="text-left">
                <p class="mb-1 text-muted fs-15 ml-3">
                  <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <span class="step2_total text-muted"></span> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                </p>
              </div>
            </div>

            <button type="button" class="btn btn-secondary pos_step2_back d-none"><?php echo trans('back') ?></button>
            <button type="submit" class="btn btn-dark py-2 btn-block confirm_pos_booking_btn"><i class="bi bi-check-circle"></i> <?php echo trans('place-an-order') ?></button>

            <input type="hidden" name="invoice_sub_total" class="invoice_sub_total" value="">
            <input type="hidden" name="invoice_total" class="invoice_total" value="">
            <input type="hidden" name="invoice_coupon_amount" class="invoice_coupon_amount" value="">

          </div>
        </div>
    </div>
  </div>
</form>