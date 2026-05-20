<?php include"topbar.php"; ?>

<section class="pt-0 cus-account">
    <div class="container cw-14">
        <div class="row mb-100">
            <div class="col-md-3">
                <?php include'side_menu.php'; ?>
            </div>

            <div class="col-md-9 mt--110">
                <div class="card shadow-sm br-10 over-hiddens mb-4">
                    <div class="card-header bg-white px-5 py-2 mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title font-weight-normal"><?php echo trans('orders') ?> <span class="count"><?php echo count($orders) ?></span> </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <form class="sort_form d-none" method="get" action="<?php echo base_url('customer/orders') ?>">
                                    <div class="input-group mt--8">
                                        <input type="text" class="form-control daterange" name="daterange" aria-describedby="button-addon2" autocomplete="off" placeholder="Select date">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary sort_btn" type="button" id="button-addon2"><i class="fas fa-search"></i> </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                    <?php if (empty($orders)): ?>
                        <?php $this->load->view('include/not_found_msg'); ?>
                    <?php else: ?>  
                    <div class="card-body bg-white p-0 table-responsive">
                        <table class="table table-hover <?php if(count($orders) > 10){echo "datatable";} ?>">
                            <thead class="thead-light">
                                <tr class="bt-0">
                                    <th class="pl-5" scope="col">#</th>
                                    <th scope="col"><?php echo trans('order-id') ?></th>
                                    <th scope="col"><?php echo trans('date') ?></th>
                                    <th scope="col"><?php echo trans('total') ?></th>
                                    <th scope="col"><?php echo trans('order-status') ?></th>
                                    <th scope="col"><?php echo trans('payment-status') ?></th>
                                    <th scope="col"><?php echo trans('details') ?></th>
                                    <th scope="col"><?php echo trans('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; foreach ($orders as $order): ?>
                                  <tr>
                                    <td class="pl-5"><?= $i; ?></td>
                                    
                                    <td>
                                        <p class="mb-0 font-weight-bold">#<?php echo html_escape($order->order_id) ?></p>
                                    </td>


                                    <td>
                                        <p class="mb-0 fs-13"> <?php echo my_date_show($order->created_at) ?></p>
                                    </td>

                                    <td>
                                        <p class="mb-0"><?php echo round($order->total_items) ?> pcs</p>
                                        <p>
                                        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                        <?php echo number_format($order->total_price, $company->num_format) ?>
                                        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                            
                                        </p>
                                    </td>

                                    <td>
                                        <?php if ($order->order_status == 0): ?>
                                          <span class="badge badge-xs badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                                        <?php elseif ($order->order_status == 1): ?>
                                          <span class="badge badge-xs badge-primary-soft"><i class="fas fa-check-circle"></i> <?php echo trans('confirm') ?></span>
                                        <?php elseif ($order->order_status == 2): ?>
                                          <span class="badge badge-xs badge-success-soft"><i class="bi bi-truck"></i> <?php echo trans('delivered') ?></span>
                                        <?php else: ?>
                                          <span class="badge badge-xs badge-danger-soft"><i class="bi bi-x-circle"></i> <?php echo trans('cancelled') ?></span>
                                        <?php endif; ?>
                                      </td>

                                    <td>
                                        <?php if ($order->payment_status == 0): ?>
                                          <span class="badge badge-xs badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                                        <?php else : ?>
                                          <span class="badge badge-xs badge-primary-soft"><i class="fas fa-check-circle"></i> <?php echo trans('paid') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                      <a href="<?php echo base_url('customer/order_details/'.($order->id));?>" class="badge badge-xs badge-primary-soft"><i class="far fa-eye"></i> <?php echo trans('view-details') ?></a>
                                    </td>
                                    <?php if($order->payment_status==0): ?>
                                        <td>
                                            <?php if ($order->total_price != 0 && $order->payment_type=='online'): ?>
                                                <span>
                                                    <a data-toggle="tooltip" data-title="<?php echo trans('complete-your-payment') ?>" data-placement="left" class="btn btn-light-primary btn-sm" data-id="<?php echo html_escape($order->id) ?>" href="<?php echo base_url('customer/product_payment/'.md5($order->id)) ?>"><i class="fas fa-credit-card"></i>
                                                    </a>
                                                </span>
                                            <?php endif ?>
                                            <?php if($order->order_status==0 || $order->order_status==1): ?>
                                                <span>
                                                    <a class="btn btn-sm btn-danger cancel-status" data-id="<?php echo html_escape($order->id) ?>" href="<?php echo base_url('customer/order_cancel/'.md5($order->id)) ?>"><i class="bi bi-x"></i>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>    
                                    
                                  </tr>
                                <?php $i++; endforeach ?>
                              </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>
</seciton>

