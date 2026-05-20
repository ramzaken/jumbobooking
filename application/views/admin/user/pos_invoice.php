

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Payment Invoice</title>
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/admin_default.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/custom-invoice.css">
    </head>

    <body>
      <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <?php $this->load->view('admin/include/breadcrumb'); ?>

        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">

            

            <div class="main-box">
              <div class="pr-5 mb-3 d-flex justify-content-between">
                <span><a href="#" class="btn btn-outline-secondary pos_print_invoice"><i class="bi bi-printer"></i> <?php echo trans('print') ?></a></span>
                <span><a href="<?php echo base_url('admin/pos') ?>" class="btn btn-outline-secondary pull-right"><i class="bi bi-arrow-left"></i> <?php echo trans('pos') ?></a></span>
              </div>

              <div class="invoice-box pos_print_area card-body">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="3">
                            <table>
                                <tr>
                                    <?php if (!empty($this->business->logo)): ?>
                                        <td class="title">
                                            <img src="<?php echo base_url($this->business->logo) ?>" class="w-40">
                                        </td>
                                    <?php endif ?>
                                    
                                    <td>
                                        <h6><?php echo trans('booking-id') ?>: #<?php echo html_escape($payment->booking_number) ?></h6>
                                        <span><?php echo my_date_show($payment->created_at) ?></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <?php $price = $user->amount;  ?>
                    
                    <tr class="information">
                        <td colspan="3">
                            <table>
                                <tr>
                                    <td>
                                        <?php echo html_escape($this->business->name) ?><br>
                                        <?php echo html_escape($this->business->email) ?><br>
                                        <?php echo html_escape($this->business->phone) ?><br>
                                        <?php echo html_escape($this->business->address) ?>
                                    </td>
                                    
                                    <td>
                                        <strong><?php echo get_by_id($payment->customer_id, 'customers')->name ?></strong><br>
                                          <p class="mb-0"><?php echo get_by_id($payment->customer_id, 'customers')->email ?></p>
                                        <?php if (!empty(get_by_id($payment->customer_id, 'customers')->phone)): ?>
                                          <p class="mb-1"><?php echo get_by_id($payment->customer_id, 'customers')->phone ?></p>
                                        <?php endif ?>
                                        
                                        <?php if ($payment->status == 'pending'): ?>
                                          <span class="float-right badge badge-danger-soft"> <?php echo trans('pending') ?></span>
                                        <?php elseif ($payment->status == 'verified'): ?>
                                          <span class="float-right badge badge-success-soft"><i class="bi bi-check-circle"></i> <?php echo trans('paid') ?></span>
                                        <?php endif ?> 
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                

                    <tr class="heading">
                        <td>
                            <?php echo trans('service') ?>
                        </td>
                        
                        <td>
                            <?php echo trans('price') ?>
                        </td>

                        <td class="text-right">
                            <?php echo trans('total') ?>
                        </td>
                    </tr>
                    
                    
                    <?php 
                      $country = get_by_id($this->business->country, 'country');
                      $currency_symbol = $country->currency_symbol;
                      //$payment = get_by_id($payment->payment_id, 'payments');
                      $price = $payment->service_price;
                      
                      $total_extra = 0;
                      if(!empty($payment->service_extra)):
                     
                        $service_extra = explode(',', $payment->service_extra);
                        foreach ($service_extra as $value) {
                            $extra_price = get_by_id($value,'service_extra')->price;
                            $total_extra += $extra_price;
                        }
                        //$totalCost = $totalCost + $total_extra;
                      endif;
                    ?>


                    <tr class="item">
                      <td>
                        <?php echo html_escape($payment->service_name) ?><br>
                        <span class="small"><?php echo html_escape($payment->date) ?> &bull; <?php echo html_escape($payment->time) ?></span>
                        
                      </td>

                      <td class="text-right">
                        <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <?php echo number_format($price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                      </td>
                      <td class="text-right">
                        <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <?php echo number_format($price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                      </td>
                    </tr>

                    
                    <?php if(!empty($payment->service_extra)): ?>
                        <?php 
                            $service_extra = explode(',', $payment->service_extra);
                        ?>
                            
                        <?php foreach ($service_extra as $sextra): ?>
                            <?php $extra = get_by_id($sextra,'service_extra'); ?>
                            <tr class="item">
                                <td>
                                   <p class="mb-0 fs-12"><?php echo html_escape($extra->name); ?></p>
                                </td>
                                <td class="text-right">
                                    <span><?php if($this->business->curr_locate == 0){echo $currency_symbol;} ?><?php echo number_format($extra->price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo $currency_symbol;} ?></span>
                                </td>
                                <td class="text-right">
                                    <span><?php if($this->business->curr_locate == 0){echo $currency_symbol;} ?><?php echo number_format($extra->price, $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo $currency_symbol;} ?></span></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                   

                    <tr class="item">
                        <td></td>
                        <td class="text-right"><strong><?php echo trans('sub-total') ?></strong></td>
                        <td class="text-right"><span><strong><?php if($this->business->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format($this->session->userdata('invoice_sub_total'),$this->business->num_format)) ?> <?php if($this->business->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
                    </tr>

                    <!-- <?php if (!empty($this->session->userdata('invoice_coupon_amount'))): ?>
                      <tr class="item">
                          <td></td>
                          <td class="text-right"><?php echo trans('discount') ?> </td>
                          <td class="text-right"><span class="show_invoice_coupon_amount"><?php if($this->business->curr_locate == 0){echo $currency_symbol;} ?><?php echo number_format($this->session->userdata('invoice_coupon_amount'), $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo $currency_symbol;} ?></span></td>
                      </tr>
                    <?php else: ?>
                      <tr class="item">
                          <td></td>
                          <td class="text-right"><?php echo trans('discount') ?> </td>
                          <td class="text-right"><span class="show_invoice_coupon_amount"><?php if($this->business->curr_locate == 0){echo $currency_symbol;} ?><?php echo number_format('0.00', $this->business->num_format) ?> <?php if($this->business->curr_locate == 1){echo $currency_symbol;} ?></span></td>
                      </tr>
                    <?php endif; ?> -->

                    <tr class="total">
                        <td></td>
                        <td class="text-right"><strong><?php echo trans('total') ?></strong></td>
                        <td class="text-right"><span class="show_invoice_total"><strong><?php if($this->business->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format($this->session->userdata('invoice_total'),$this->business->num_format)) ?> <?php if($this->business->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
                    </tr>

                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>

                </table>

                <div class="pwf">
                  <?php echo trans('powered-by') ?> - <?php echo html_escape(settings()->site_name) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



    </body>
</html>
