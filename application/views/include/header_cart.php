
<ul id="card_dropdown" class="dropdown-menu shadow w-350">
  <?php if ($cart = $this->cart->contents()): ?>
    <li>
        <div class="cart_body mt-2">
          <?php 
            $delivert_charge = 0;
            $grand_total = 0; $i = 1;        
            foreach ($cart as $item):?>
              
                <div class="d-flex justify-content-between mb-3 pl-3 pr-3 bm-1 pb-3">
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
    </li>

    <li>
      <div class="cart_body mb-3 pl-3 pr-3">
        <div class="d-flex justify-content-end">
           <div class="mr-3 text-right">
             <p class="mb-1 text-dark"><?php echo trans('sub-total') ?></p>
           </div>
           <div class="text-right text-dark">
              <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
              <?php echo number_format($this->cart->total(), $company->num_format) ?>
              <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
           </div>
        </div>

        <div class="d-flex justify-content-end">
           <div class="mr-3 text-right">
             <p class="mb-1 text-dark"><?php echo trans('total') ?></p>
           </div>
           <div class="text-right text-dark">
              <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
              <?php echo number_format($this->cart->total(), $company->num_format) ?>
              <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
           </div>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-5 mb-2 mr-3">
        <a href="<?php echo base_url('cart/'.$slug);?>" class="btn btn-light-primary btn-xs fw-600 mr-2"> <i class="bi bi-cart"></i> <?php echo trans('view-cart') ?></a>
        <a href="<?php echo base_url('cart/'.$slug.'?type=login');?>" class="btn btn-light-secondary btn-xs fw-600"><i class="bi bi-arrow-right"></i> <?php echo trans('checkout') ?></a>
      </div>
    </li>
  <?php else: ?>
    <li>
      <div class="cart_body mt-2 text-center">
        <p class="pt-3"><i class="bi bi-info-circle"></i> <?php echo trans('your-shopping-cart-is-empty') ?></p>
      </div>
    </li>
  <?php endif;?>
</ul>
