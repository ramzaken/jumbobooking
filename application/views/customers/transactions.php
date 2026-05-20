
<?php include"topbar.php"; ?>

<section class="pt-0 cus-account">
    <div class="container cw-14">
        <div class="row mb-100">
            <div class="col-md-3 col-sm-12">
                <?php include'side_menu.php'; ?>
            </div>
            
            <div class="col-md-9 col-sm-12">
              <div class="card-body table-responsive p-0">
                <nav id="btab" class="mb-4 nav over-scroll pl-4 mt-3" role="tablist">
                  <a href="<?php echo base_url('customer/transactions/?type=appointment') ?>" role="tab" data-rb-event-key="appointment" aria-selected="true" class="nav-item nav-link btn btn-light <?php if($_GET['type'] == 'appointment'){ echo 'active';} ?>">
                    <span class="badge fs-12 py-2 px-2 badge-primary mr-1"></span>
                    <span class="text-dark fw-500 "><?php echo trans('appointment') ?></span>
                  </a>


                  <a href="<?php echo base_url('customer/transactions/?type=event') ?>" role="tab" data-rb-event-key="event" aria-selected="true" class="nav-item nav-link btn btn-light <?php if($_GET['type'] == 'event'){ echo 'active';} ?>">
                    <span class="badge fs-12 py-2 px-2 badge-danger mr-1"></span>
                    <span class="text-dark fw-500"><?php echo trans('event') ?></span>
                  </a>

                  <a href="<?php echo base_url('customer/transactions/?type=product') ?>" role="tab" data-rb-event-key="product" aria-selected="true" class="nav-item nav-link btn btn-light <?php if($_GET['type'] == 'product'){ echo 'active';} ?>">
                    <span class="badge fs-12 py-2 px-2 badge-danger mr-1"></span>
                    <span class="text-dark fw-500"><?php echo trans('product') ?></span>
                  </a>
                </nav>

                <table class="table table-hover <?php if(count($payments) > 10){echo "datatable";} ?> cushover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo trans('date') ?></th>
                            <?php if ($_GET['type'] == 'appointment'): ?>
                              <th><?php echo trans('service') ?></th>
                             <?php endif ?>
                            <?php if ($_GET['type'] == 'event'): ?>
                              <th><?php echo trans('event') ?></th>
                            <?php endif ?>
                           
                            <th><?php echo trans('customer') ?></th>
                            <th><?php echo trans('price') ?></th>
                            <th><?php echo trans('status') ?></th>
                            <th><?php echo trans('payment') ?></th>
                            <th><?php echo trans('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $i=1; foreach ($payments as $payment): ?>

                        <?php if ($payment->amount != '0.00'): ?>
                          <tr id="row_<?php echo html_escape($payment->id); ?>">
                              
                              <td><?php echo $i; ?></td>
                              
                              <td><?php echo my_date_show($payment->created_at); ?> </td>

                              <?php if ($_GET['type'] == 'appointment'): ?>
                                <td><?php echo get_by_id($payment->service_id, 'services')->name; ?> </td>
                               <?php endif ?>
                               <?php if ($_GET['type'] == 'event'): ?>
                                <td><?php echo get_by_id($payment->event_id, 'events')->name; ?> </td>
                              <?php endif ?>

                              <?php $customer = get_by_id($payment->customer_id, 'customers') ?>
                              <?php if ($customer->thumb == ''): ?>
                                  <?php $avatar = 'assets/images/avatar.png'; ?> 
                              <?php else: ?>
                                  <?php $avatar = $customer->thumb; ?>
                              <?php endif ?>
                              <td>
                                <img width="40px" class="img-circle mr-2" src="<?php echo base_url($avatar) ?>"> 
                                <?php echo ucfirst($customer->name); ?>
                              </td>

                              <td>

                                <?php if($company->curr_locate == 0){echo $company->currency_symbol;} ?>
                                <?php echo number_format($payment->amount, $company->num_format); ?>
                                <?php if($company->curr_locate == 1){echo $company->currency_symbol;} ?>
                              </td>

                              <td>
                                <?php if ($payment->status == 'verified'): ?>
                                  <span class="badge badge-success-soft brd-20"><i class="fas fa-check-circle"></i> <?php echo trans('paid') ?></span>
                                <?php else: ?>
                                  <span class="badge badge-danger-soft brd-20"><?php echo ucfirst($payment->status); ?></span>
                                <?php endif ?>
                              </td>

                              <td>
                                <?php if (settings()->enable_wallet == 1): ?>
                                  <span class="badge badge-danger-soft brd-20"><i class="fas fa-credit-card"></i> <?php echo trans('wallet') ?></span>
                                <?php else: ?>
                                  <?php if (!empty($payment->payment_method)): ?>
                                    <span class="badge badge-secondary-soft brd-20"><i class="fas fa-hand-holding-usd"></i> <?php echo ucfirst($payment->payment_method); ?></span>
                                  <?php endif ?>
                                <?php endif ?>

                                
                              </td>
                              
                              <td class="actions">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-tool" data-toggle="dropdown" aria-expanded="false">
                                      <i class="fas fa-ellipsis-h"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right" role="menu" >
                                     

                                      <?php if ($_GET['type'] == 'appointment'): ?>
                                        <a target="_blank" href="<?php echo base_url('admin/payment/customer_receipt/'.$payment->puid) ?>" class="dropdown-item"><i class="far fa-eye"></i> <?php echo trans('view-invoice') ?></a>
                                      <?php endif ?>
                                      <?php if ($_GET['type'] == 'event'): ?>
                                        <a target="_blank" href="<?php echo base_url('admin/payment/customer_receipt_event/'.$payment->puid) ?>" class="dropdown-item"><i class="far fa-eye"></i> <?php echo trans('view-invoice') ?></a>
                                      <?php endif ?>

                                      <?php if ($_GET['type'] == 'product'): ?>
                                        <a target="_blank" href="<?php echo base_url('admin/payment/customer_receipt_product/'.$payment->puid) ?>" class="dropdown-item"><i class="far fa-eye"></i> <?php echo trans('view-invoice') ?></a>
                                      <?php endif ?>
                                      
                                    </div>
                                </div>

                              </td>
                          </tr>
                        <?php endif ?>
                        
                      <?php $i++; endforeach; ?>
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
</seciton>




