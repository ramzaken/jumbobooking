<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Payment Invoice</title>
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/admin_default.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/custom-invoice.css">
    </head>

    <body>

        <div class="main-box">
            
            <div class="invoice-box print_area <?php if(isset($page_title) && $page_title != 'Export'){echo "br1 shadow";} ?>">
                <table cellpadding="0" cellspacing="0">
                    <tr class="top">
                        <td colspan="4">
                            <table>
                                <tr>
                                    <?php if (!empty($company->logo)): ?>
                                        <td class="title">
                                            <img src="<?php echo base_url($company->logo) ?>" class="w-40">
                                        </td>
                                    <?php endif ?>
                                    
                                    <td>
                                        <?php echo trans('invoice') ?> - <?php echo html_escape(sprintf('%02d', $user->id)) ?><br>
                                        <?php echo trans('order-no') ?>: <?php echo html_escape($user->puid) ?> <br> 
                                        <?php echo trans('date') ?>: <?php echo my_date_show($user->created_at) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <?php $price = $user->amount;  ?>
                    
                    <tr class="information">
                        <td colspan="4">
                            <table>
                                <tr>
                                    <td>
                                        <?php echo html_escape($company->name) ?><br>
                                        <?php echo html_escape($company->email) ?>
                                    </td>
                                    
                                    <td>
                                        <strong><?php echo get_by_id($user->customer_id, 'customers')->name ?></strong><br>
                                          <p class="mb-0"><?php echo get_by_id($user->customer_id, 'customers')->email ?></p>
                                        <?php if (!empty(get_by_id($user->customer_id, 'customers')->phone)): ?>
                                          <p class="mb-1"><?php echo get_by_id($user->customer_id, 'customers')->phone ?></p>
                                        <?php endif ?>
                                        
                                        <?php if ($user->status == 'pending'): ?>
                                          <span class="float-right badge badge-danger-soft"> <?php echo trans('payment') ?> - <?php echo trans('pending') ?></span>
                                        <?php elseif ($user->status == 'verified'): ?>
                                          <span class="float-right badge badge-success-soft"> <?php echo trans('payment') ?> - <?php echo trans('paid') ?></span>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                

                    <tr class="heading">
                        <td>
                            <?php echo trans('product') ?>
                        </td>

                        <td class="text-left ">
                            <?php echo trans('quantity') ?>
                        </td>
                        
                        <td>
                            <?php echo trans('price') ?>
                        </td>

                        <td class="text-right">
                            <?php echo trans('total') ?>
                        </td>
                    </tr>
                    
                    

                    <?php foreach ($orders as $order): ?>

                        <?php  $product = $this->admin_model->get_by_id($order->product_id,'products'); ?>

                        <tr class="item">
                          <td>
                            <?php echo html_escape($product->title) ?> 
                            
                          </td>
                          <td class="text-left pl-3">
                            <?php echo html_escape($order->qty) ?>
                                
                          </td>

                          <td>
                           
                            <span><?php if($company->curr_locate == 0){echo get_currency_by_country($company->country)->currency_symbol;} ?> <?php echo number_format($product->price, $company->num_format) ?> <?php if($company->curr_locate == 1){echo get_currency_by_country($company->country)->currency_symbol;} ?></span>
                          </td>

                          <td class="text-right">
                            <?php if($company->curr_locate == 0){echo get_currency_by_country($company->country)->currency_symbol;} ?> <?php echo number_format($product->price * $order->qty, $company->num_format) ?> <?php if($company->curr_locate == 1){echo get_currency_by_country($company->country)->currency_symbol;} ?>
                          </td>
                        </tr>

                        
                    <?php endforeach ?>

                    
                    

                    
                    <!-- <?php if ($discount > 0): ?>
                        <tr class="item">
                            <td></td>
                            <td class="text-right"><?php echo trans('discount') ?> <?php echo html_escape($discount) ?>% </td>
                            <td class="text-right"><span><?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo number_format($discount_amount, $company->num_format) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></span></td>
                        </tr>
                    <?php endif ?> -->


                    <!-- <?php if ($tax > 0): ?>
                        <tr class="item">
                            <td></td>
                            <td class="text-right"><?php echo trans('service-tax') ?> <?php echo html_escape($tax) ?>% </td>
                            <td class="text-right"><span><?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo number_format($tax_amount, $company->num_format) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></span></td>
                        </tr>
                    <?php endif ?> -->

                    <tr class="item">
                        <td></td>
                        <td class="text-right"><strong><?php echo trans('sub-total') ?></strong></td>
                        <td class="text-right"><span><strong><?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format($user->amount,$company->num_format)) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
                    </tr>

                    <tr class="total">
                        <td></td>
                        <td class="text-right"><strong><?php echo trans('total') ?></strong></td>
                        <td class="text-right"><span><strong><?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format($user->amount,$company->num_format)) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
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



    </body>
</html>
