<?php include"topbar.php"; ?>


<section class="bg-lights p-4 cus-account">
  <div class="container">
      <div class="row justify-content-center text-center">
          <div class="col-lg-10 col-xl-8">
              <h4 class="mb-0"><?php echo trans('order-confirmation') ?></h4>
              <p class="w-100 w-lg-80 mx-auto mb-0"><?php echo trans('confirm-the-booking') ?></p>
          </div>
      </div>
  </div>

  <div class="container mt-7">
      <div class="row justify-content-center">
          <div class="col-md-10 card shadow-light br-10">

              <div class="booking_confirm">
                  <div class="row">
                      <div class="col-md-6">
                          <p class="mb-0"><?php echo trans('order-id') ?></p>
                          <h4># <?php echo html_escape($order->order_id) ?></h4>
                      </div>

                      <div class="col-md-6 text-right">
                          <a href="<?php echo base_url('customer/orders') ?>" class="btn btn-outline-secondary btn-sm mt-1"><i class="lnib lni-arrow-left"></i> <?php echo trans('back') ?></a>
                          <button type="button" class="btn btn-outline-secondary btn-sm mt-3 hide"><i class="fas fa-print"></i><?php echo trans('print') ?></button>
                      </div>

                      <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead>
                              <tr>
                                <th><?php echo trans('order-date') ?></th>
                                <th><?php echo trans('quantity') ?></th>
                                <th><?php echo trans('price') ?></th>
                                <th><?php echo trans('payment-type') ?></th>
                                <th><?php echo trans('payment-status') ?></th>
                                <th><?php echo trans('status') ?></th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                  <td><?php echo my_date_show($order->created_at) ?></td>
                                  <td><?php echo round($order->total_items) ?></td>
                                
                                  <td>
                                    <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                    <?php echo number_format($order->total_price, $company->num_format) ?>
                                    <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                  </td>

                                  <td>
                                    <?php if ($order->payment_type == 'cod'): ?>
                                      <span class="badge badge-secondary-soft"><i class="fas fa-credit-card"></i><?php echo trans('cash-on-delivery') ?></span>
                                    <?php else: ?>
                                      <span class="badge badge-primary-soft"><i class="fas fa-credit-card"></i><?php echo trans('online-payment') ?></span>
                                    <?php endif ?>
                                  </td>

                                  <td>
                                    <?php if ($order->payment_status == 0): ?>
                                      <span class="badge badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                                    <?php elseif ($order->payment_status == 1): ?>
                                      <span class="badge badge-primary-soft"><i class="fas fa-check-circle"></i> <?php echo trans('paid')?></span>
                                    <?php endif ?>
                                  </td>

                                  <td>
                                    <?php if ($order->order_status == 0): ?>
                                      <span class="badge badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                                    <?php elseif ($order->order_status == 1): ?>
                                      <span class="badge badge-primary-soft"><i class="fas fa-check-circle"></i> <?php echo trans('confirm') ?></span>
                                    <?php elseif ($order->order_status == 2): ?>
                                      <span class="badge badge-success-soft"><i class="bi bi-truck"></i> <?php echo trans('delivered') ?></span>
                                    <?php else: ?>
                                      <span class="badge badge-danger-soft"><i class="bi bi-x-circle"></i> <?php echo trans('cancelled') ?></span>
                                    <?php endif ?>
                                  </td>

                                </tr>
                            </tbody>
                        </table>
                      </div>


                  </div>

                  <?php foreach ($order_lists as $list): ?>
                  
                  <div class="row mt-3">
                      <div class="col-md-6"></div>
                      <div class="col-md-6 pull-right">
                        <div class="d-flex justify-content-between mb-1 bm-1 pb-1 w-100">
                            <div class="mr-3">
                               <p class="mb-1 text-dark fs-15"><?php echo html_escape($list->title); ?></p>
                               <p class="mb-0 text-dark fs-12">
                                 <span><?php echo html_escape($list->qty);?></span> x 
                                 <span>
                                    <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                    <?php echo number_format($list->price, $company->num_format) ?>
                                    <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                  </span>
                               </p>
                            </div>
                            <div class="text-right">
                               <img width="70px" class="img-fluid br-4 mt-1" title="<?php echo html_escape($list->title); ?>" alt="<?php echo html_escape($list->title); ?>" src="<?php echo base_url(get_product_img($list->product_id, 1)->thumb) ?>">
                            </div>
                        </div>
                      </div>
                  </div>
              
                  <?php endforeach ?>


                  <div class="d-flex justify-content-end mt-4">
                    <p class="mb-0 mr-5 text-dark"><?php echo trans('sub-total') ?></p>
                    <p class="font-weight-bold text-dark mb-2">
                      <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                      <?php echo number_format($order->total_price, $company->num_format) ?>
                      <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                    </p>
                  </div>

                  <div class="d-flex justify-content-end mb-5">
                    <p class="mb-0 mr-5 text-dark"> <?php echo trans('total') ?></p>
                    <p class="font-weight-bold text-dark mb-2">
                      <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                      <?php echo number_format($order->total_price, $company->num_format) ?>
                      <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                    </p>
                  </div>

                  <?php if($order->payment_type=='online'): ?>
                    <div class="row mt-2">
                        <div class="col-md-12 mt-4">
                            <?php $this->load->view('include/product_payment_section.php');?>
                        </div>
                    </div>
                <?php endif; ?>

              </div>

          </div>
      </div>
  </div>
</section>