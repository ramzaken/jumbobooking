<div class="col-lg-4 bg-gray">
  <div class="p-5">
    <h3 class="fw-bold mb-0 mt-2"><?php echo trans('cart-total') ?></h3>
    <hr class="my-4">

    <?php if ($this->cart->total_items() != 0): ?>
      <div class="cart_body mb-3">
        <?php 
          $delivert_charge = 0;
          $grand_total = 0; $i = 1;        
          foreach ($this->cart->contents() as $item):?>
            
              <div class="d-flex justify-content-between mb-1 bm-1 pb-1">
                 <div class="mr-3">
                   <p class="mb-1 text-dark fs-15"><?php echo html_escape($item['name']); ?></p>
                   <p class="mb-0 text-dark fs-12">
                     <span><?php echo html_escape($item['qty']);?></span> x 
                     <span>
                        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                        <?php echo number_format($item['price'], $company->num_format) ?>
                        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                      </span>
                   </p>
                 </div>
                 <div class="text-right">
                   <img width="70px" class="img-fluid br-4 mt-1" title="<?php echo html_escape($item['name']); ?>" alt="<?php echo html_escape($item['name']); ?>" src="<?php echo base_url($item['thumb']) ?>">
                 </div>
              </div>
        <?php endforeach;?>
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-2">
      <p class="mb-0 text-dark"><?php echo trans('sub-total') ?></p>
      <p class="font-weight-bold text-dark mb-2">
        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
        <?php echo number_format($this->cart->total(), $company->num_format) ?>
        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
      </p>
    </div>

    <div class="d-flex justify-content-between mb-5 text-right">
      <p class="mb-0 text-dark"> <?php echo trans('total') ?></p>
      <p class="font-weight-bold text-dark mb-2">
        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
        <?php echo number_format($this->cart->total(), $company->num_format) ?>
        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
      </p>
    </div>

    
    <?php if (isset($page_title) && $page_title == 'Shopping Cart'): ?>
      
      <a class="btn btn-dark btn-block btn-lg fs-15" href="<?php echo base_url('checkout/'.$slug.'?type=login') ?>" 
      data-mdb-ripple-color="dark"> <?php echo trans('checkout') ?></a>

    <?php else: ?>

      <div class="mb-5 row">
        <div class="col-6">
          <label class="staff-rdo">
              <input class="form-check-input" type="radio" name="payment_type" id="exampleRadios1" value="cod" checked>
              <div class="bg-lights rounded-sm text-center staff_item">
                  <p class="mb-0 mt-0 py-3 px-2"><i class="fas fa-hand-holding-usd"></i> <br><?php echo trans('cash-on-delivery') ?></p>
              </div>
          </label>
        </div>

        <div class="col-6 <?php if(settings()->site_info == 1){echo "d-none";} ?>">
          <label class="staff-rdo">
              <input class="form-check-input" type="radio" name="payment_type" id="exampleRadios2" value="online">
              <div class="bg-lights rounded-sm text-center staff_item">
                  <p class="mb-0 mt-0 py-3 px-2"><i class="bi bi-credit-card"></i> <br><?php echo trans('online-payment') ?></p>
              </div>
          </label>
        </div>

        <?php 

          if (check_auth() == true && is_customer()) {
            $customer = $this->admin_model->get_by_id($this->session->userdata('id'),'customers');
          }


        ?>

       
        <div class="col-12">
          <div class="form-group">
            <label><?php echo trans('shipping-address') ?><?php echo check_auth(); ?></label>
            <textarea class="form-control" name="shipping_address" rows="2" required><?php if (isset(get_by_id($this->session->userdata('id'),'customers')->shipping_address)) {echo get_by_id($this->session->userdata('id'),'customers')->shipping_address;} ?></textarea>
          </div>
        </div>
      </div>


      <!-- csrf token -->
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
      <?php if(check_auth() == false && !is_customer()): ?>
        <input type="hidden" class="is_customer_exist" name="type" value="<?php if(isset($_GET) && $_GET['type'] == 'login'){echo "login";}else{echo "register";} ?>">
      <?php else: ?>
        <input type="hidden" class="is_customer_exist" name="type" value="loged_in">
      <?php endif; ?>
      <button type="submit" class="btn btn-dark btn-block btn-lg fs-15 checkout_btn"
        data-mdb-ripple-color="dark"><?php echo trans('checkout') ?></button>
    <?php endif ?>

  </div>
</div>