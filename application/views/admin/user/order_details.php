<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <?php $this->load->view('admin/include/breadcrumb'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
        <div class="row">
          <?php foreach ($orders as $order): ?>
            <div class="col-md-3 pt-2">
                <div class="card-body box-profile pt-4 mt-5 p-0">


                  <div class="text-center">
                    <img class="cprofile-img" src="<?php echo base_url(get_by_id($order->customer_id,'customers')->image) ?>" alt="no photo">
                  </div>

                  <h3 class="profile-username text-center mb-1"><?php echo get_by_id($order->customer_id,'customers')->name ?></h3>
                  <p class="text-center"><?php echo get_by_id($order->customer_id,'customers')->email ?></p>

                  <ul class="list-group list-group-unbordered pt-3">
                    <li class="list-group-item pl-3 pr-3 text-dark">
                      <span class="font-weight-bold fs-12"><?php echo trans('total').' '.trans('items') ?></span> <a class="float-right badge badge-xs badge-secondary-soft"><?php echo round($order->total_items) ?></a>
                    </li>
                    <li class="list-group-item pl-3 pr-3 text-dark">
                      <span class="font-weight-bold fs-12"><?php echo trans('total').' '.trans('price') ?></span>
                      <a class="float-right badge badge-xs badge-secondary-soft">
                        <?php if($this->business->curr_locate == 0){echo html_escape($this->business->currency_symbol);} ?>
                        <?php echo number_format($order->total_price , $this->business->num_format) ?>
                        <?php if($this->business->curr_locate == 1){echo html_escape($this->business->currency_symbol);} ?>
                        
                      </a>
                    </li>
                    <li class="list-group-item pl-3 pr-3 text-dark">
                      <span class="font-weight-bold fs-12"><?php echo trans('order-status') ?></span>

                      <a class="float-right ">
                        <?php if ($order->order_status == 0): ?>
                          <span class="badge badge-xs badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                        <?php elseif ($order->order_status == 1): ?>
                          <span class="badge badge-xs badge-primary-soft"><i class="fas fa-check-circle"></i> <?php echo trans('confirm') ?></span>
                        <?php elseif ($order->order_status == 2): ?>
                          <span class="badge badge-xs badge-success-soft"><i class="bi bi-truck"></i> <?php echo trans('delivered') ?></span>
                        <?php else: ?>
                          <span class="badge badge-xs badge-danger-soft"><i class="bi bi-x-circle"></i> <?php echo trans('cancelled') ?></span>
                        <?php endif ?>
                      </a>
                    </li>
                    <li class="list-group-item pl-3 pr-3 text-dark">
                      <span class="font-weight-bold fs-12"><?php echo trans('payment-status') ?></span>
                      <a class="float-right">
                        <?php if ($order->payment_status == 0): ?>
                          <span class="badge badge-xs badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                        <?php else: ?>
                          <span class="badge badge-xs badge-success-soft"><i class="bi bi-check-circle"></i> <?php echo trans('paid') ?></span>
                        <?php endif ?>
                      </a>
                    </li>
                  </ul>
                </div>
            </div>

            <div class="col-md-9">
                <?php if (!empty($order->list_orders)): ?>
                  <div class="card pl-3">
                    <div class="card-header">
                      <h5 class="card-title mb-0"><?php echo trans('orders') ?> </h5>

                      <div class="card-tools pull-right"><a class="pull-right btn btn-secondary btn-sm" href="<?php echo base_url('admin/product/orders') ?>"><i class="fas fa-angle-left"></i><?php echo trans('back') ?></a></div>
                    </div>
                    
                    <div class="card-body table-responsive p-0">
                      <table class="table table-hover table-valign-middle <?php if(count($order->list_orders) > 10){echo "datatable";} ?>">
                        <thead>
                        <tr>
                          <th><?php echo trans('image') ?></th>
                          <th><?php echo trans('product') ?></th>
                          <th><?php echo trans('quantity') ?></th>
                          <th><?php echo trans('price') ?></th>
                          <th><?php echo trans('order-date') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($order->list_orders as $orders): ?>
                            <tr>
                              <td>
                                <img width="80px" src="<?php echo base_url(get_product_img($orders->product_id,1)->image)  ?>">
                              </td>
                              <td><?php echo get_by_id($orders->product_id,'products')->title ?></td>
                              <td><?php echo html_escape($orders->qty) ?></td>
                              <td>
                                <?php if($this->business->curr_locate == 0){echo html_escape($this->business->currency_symbol);} ?>
                                <?php echo number_format($orders->price , $this->business->num_format) ?>
                                <?php if($this->business->curr_locate == 1){echo html_escape($this->business->currency_symbol);} ?>
                                  
                              </td>
                              <td>
                                  <?php echo my_date_show($order->created_at) ?>
                              </td>
                              
                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="card mt-5">
                    <div class="card-body mt-2 text-center p-5 pt-4">
                        <p><?php echo trans('no-data-found') ?></p>
                    </div>
                  </div>
                <?php endif ?>
            </div>
            
          <?php endforeach ?>

        </div>
    </div>
  </div>
</div>