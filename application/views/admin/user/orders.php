<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <?php $this->load->view('admin/include/breadcrumb'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-12">

            <?php if (!empty($page_title) && $page_title != "Edit"): ?>
              <div class="card list_area">
                <div class="card-header with-border">
                  <?php if (!empty($page_title) && $page_title == "Edit"): ?>
                    <h3 class="card-title pt-2"><?php echo trans('edit') ?> <a href="<?php echo base_url('admin/product/orders') ?>" class="pull-right btn btn-sm btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
                  <?php else: ?>
                    <h3 class="card-title pt-2"><?php echo trans('order') ?> </h3>
                  <?php endif; ?>

                  <div class="card-tools pull-right">

                   <a href="#" class="filter-action pull-right btn btn-outline-primary btn-sm"><i class="fas fa-filter"></i></a>
                  </div>
                </div>

                <div class="filter_popup showFilter">
                    <p class="leads mb-3"><?php echo trans('filters') ?></p>

                    <form action="<?php echo base_url('admin/product/orders') ?>" class="sort_form" method="get">
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group d-none">
                              <label><?php echo trans('') ?></label>
                              <input placeholder="" class="form-control form-control-sm" type="text" name="product" value="<?php if(!empty($_GET['product'])){echo html_escape($_GET['product']);} ?>">
                            </div>

                            <div class="form-group">
                              <select name="customer" class="form-control form-control-sm nice_select small wide">
                                <option value="" ><?php echo trans('customer') ?></option>
                                <?php foreach ($customers as $customer): ?>
                                <option <?php if(isset($_GET['customer']) && $_GET['customer']==$customer->id){echo "selected";} ?> value="<?php if(!empty($customer)){echo html_escape($customer->id);}?>"><?php echo html_escape($customer->name) ?></option>
                                <?php endforeach ?>
                              </select>

                              <select name="order_status" class="form-control form-control-sm nice_select small wide mt-3">
                                <option value=""><?php echo trans('status') ?></option>
                                <option <?php if(isset($_GET['order_status']) && $_GET['order_status']==1){echo "selected";} ?> value="0">Pending</option>

                                <option <?php if(isset($_GET['order_status']) && $_GET['order_status']==1){echo "selected";} ?> value="1">Confirm</option>

                                <option <?php if(isset($_GET['order_status']) && $_GET['order_status']==2){echo "selected";} ?> value="2">Delivered</option>
                              </select>
                            </div>
                            
                            <a href="<?php echo base_url('admin/product/orders') ?>" class="btn btn-default btn-xs pull-right mt-3"><i class="fas fa-redo-alt"></i> <?php echo trans('reset') ?></a>
                        </div>

                        <div class="col-md-12 mt-3">
                          <button type="submit" class="btn btn-primary btn-sm btn-block"><?php echo trans('submit') ?></button>
                        </div>

                      </div>
                    </form>
                </div>

                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap <?php if(is_countable($product_orders) && count($product_orders)  > 20){echo "datatable";} ?>">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th><?php echo trans('order-info') ?></th>
                        <th><?php echo trans('customer') ?></th>
                        <th><?php echo trans('order-date') ?></th>
                        <th><?php echo trans('details') ?></th>
                        <th><?php echo trans('payment-status') ?></th>
                        <th><?php echo trans('order-status') ?></th>
                        <th><?php echo trans('action') ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i=1; foreach ($product_orders as $order): ?>
                        <tr id="row_<?php echo html_escape($order->id); ?>">
                          <td><?= $i; ?></td>
                          <td>
                            <p class="mb-0 mt-0">
                              <b><?php echo trans('order-id') ?>:</b> #<?php echo html_escape($order->order_id) ?>
                            </p>
                            <p class="mb-0 mt-0">
                              <b><?php echo trans('quantity') ?>:</b> <?php echo html_escape($order->total_items) ?> pcs
                            </p>
                            <p class="mb-0 mt-0">
                              <b><?php echo trans('total-price') ?>:</b> <?php if($this->business->curr_locate == 0){echo html_escape($this->business->currency_symbol);} ?>
                              <?php echo number_format($order->total_price, $this->business->num_format) ?>
                              <?php if($this->business->curr_locate == 1){echo html_escape($this->business->currency_symbol);} ?>
                            </p>
                           
                          </td>
                          <td>
                            <p class="mt-1 mb-0">
                              <?php echo get_by_id($order->customer_id, 'customers')->name ?>
                            </p>
                            <p class="mt-1 mb-0">
                              <?php echo get_by_id($order->customer_id, 'customers')->email ?>
                            </p>
                            <p class="mt-1 mb-0">
                              <b><?php echo trans('shipping-address') ?>:</b> <?php echo get_by_id($order->customer_id, 'customers')->shipping_address ?>
                            </p>
                              
                          </td>

                          <td><?php echo my_date_show($order->created_at) ?></td>

                          <td>
                            <a href="<?php echo base_url('admin/product/order_details/'.$order->id) ?>" class="badge badge-xs badge-primary-soft"><i class="far fa-eye"></i>Order Details <?php echo trans('order-details') ?></a>
                          </td>

                          <td>
                              <?php if ($order->payment_status == 0): ?>
                                <span class="badge badge-xs badge-warning-soft"><i class="bi bi-clock mr-1"></i><?php echo trans('pending') ?></span>
                              <?php else : ?>
                                <span class="badge badge-xs badge-primary-soft"><i class="fas fa-check-circle"></i> <?php echo trans('paid') ?></span>
                              <?php endif; ?>
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
                            <?php endif ?>
                          </td>

                          <td class="actions">
                            <div class="btn-group">
                              <button type="button" class="btn btn-tool" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right" role="menu" >

                                <a data-val="Category" data-id="<?php echo html_escape($order->id); ?>" href="<?php echo base_url('admin/product/order_delete/'.html_escape($order->id));?>" class="dropdown-item delete_item">  <?php echo trans('delete') ?>
                                </a>

                                <?php if($order->order_status==1): ?>
                                  <a data-val="Category" data-id="<?php echo html_escape($order->id); ?>" href="<?php echo base_url('admin/product/order_delivered/' . $order->id) ?>" class="dropdown-item">    <?php echo trans('order-delivered') ?>
                                  </a>
                                <?php endif; ?>

                                <?php if($order->order_status==0): ?>
                                  <a data-val="Category" data-id="<?php echo html_escape($order->id); ?>" href="<?php echo base_url('admin/product/order_confirm/' . $order->id) ?>" class="dropdown-item"> <?php echo trans('confirm-order') ?></a>

                                  <a data-val="Category" data-id="<?php echo html_escape($order->id); ?>" href="<?php echo base_url('admin/product/order_cancel/' . $order->id) ?>" class="dropdown-item"> <?php echo trans('cancel-order') ?></a>
                                <?php endif; ?>



                                <?php if($order->order_status==2 && $order->payment_type == 'cod' && $order->payment_status == 0): ?>
                                  
                                  <a href="#paymentModal_<?= $i; ?>" data-toggle="modal" class="dropdown-item"><i class="lni lni-coin mr-1"></i> <?php echo trans('record-payment') ?></a>
                                <?php endif; ?>

                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php $i++; endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              
                <div class="mt-8 mt-lg-10">
                  <?php echo $this->pagination->create_links(); ?>
                </div>
            <?php endif; ?>
          </div>
      </div>
    </div>
  </div>
</div>



<?php $k=1; foreach ($product_orders as $order): ?>

<div class="modal fade d-hide" id="paymentModal_<?= $k; ?>" aria-hidden="true">
  <div class="modal-dialog">
  
    <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('admin/product/record_payment/'.$order->id)?>" role="form" novalidate>
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo trans('record-payment') ?> - # <?php echo html_escape($order->order_id); ?></h4>
          <div class="mclose" data-dismiss="modal"><i class="lnib lni-close"></i></div>
        </div>

        
     
        <div class="modal-body">
          <div class="form-group">
            <label><?php echo trans('price') ?> <span class="text-danger">*</span></label>
            <input type="text" class="form-control" required name="price" value="<?php echo number_format($order->total_price, $this->business->num_format) ?>" disabled>
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
          <button type="submit" class="btn btn-primary"><?php echo trans('save') ?></button>
        </div>
      </div>
    </form>

    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php $k++; endforeach; ?>