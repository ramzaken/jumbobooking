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
                                <h5 class="card-title font-weight-normal"><?php echo trans('order-details') ?></h5>
                            </div>
                            <div class="col-md-6">
                              <div class="card-tools text-right">
                                <a class="pull-right btn btn-secondary btn-sm" href="<?php echo base_url('customer/orders') ?>"><i class="fas fa-angle-left">  </i>
                                  Back
                                </a>
                              </div>
                            </div>
                            
                            
                        </div>
                    </div>

                    <div class="card-body bg-white p-0 table-responsive">
                      <table class="table table-hover table-valign-middle <?php if(count($orders) > 10){echo "datatable";} ?>">
                        <thead>
                          <tr>
                            <th><?php echo trans('product') ?></th>
                            <th><?php echo trans('image') ?></th>
                            <th><?php echo trans('quantity') ?></th>
                            <th><?php echo trans('price') ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($orders as $order): ?>
                            <tr>
                              <td><?php echo get_by_id($order->product_id,'products')->title ?></td>
                              <td>
                                <img width="80px" src="<?php echo base_url(get_product_img($order->product_id,1)->image)  ?>">
                              </td>
                              <td><?php echo html_escape($order->qty) ?></td>
                              <td>
                                <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                <?php echo number_format($order->price, $company->num_format) ?>
                                <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                  
                              </td>
                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</seciton>

