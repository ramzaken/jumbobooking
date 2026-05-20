<!DOCTYPE html>
<html>
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
                        <td colspan="3">
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
                        <td colspan="3">
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
                            <?php echo trans('event') ?>
                        </td>


                        
                        <td>
                           
                        </td>

                        <td class="text-right">
                            <?php echo trans('total') ?>
                        </td>
                    </tr>
                    
                    <?php $event = get_by_id($user->event_id, 'events'); ?>
                    <?php 
                      $country = get_by_id($company->country, 'country');
                      $currency_symbol = $country->currency_symbol;
                      $event_booking = get_by_id($user->event_booking_id, 'event_booking');
                      $price = $user->amount;
                       
                    ?>


                    <tr class="item">
                      <td>
                       
                        
                      </td>

                      <td>
                        
                        
                      </td>

                     
                    </tr>

                   <tr class="item">
                        <td> <?php echo html_escape($event->name) ?> <br> <span class="fs-12"></span></td>
                        <td class="text-right"><strong><?php echo get_by_id($event_booking->ticket_id,'event_ticket')->name ?> (<?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format(get_by_id($event_booking->ticket_id,'event_ticket')->price,$company->num_format)) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?>)</strong></td>
                        <td class="text-right"><span><strong><?php echo html_escape($event_booking->total_slot) ?> X <?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format(get_by_id($event_booking->ticket_id,'event_ticket')->price,$company->num_format)) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
                    </tr>

                    <tr class="item">
                        <td></td>
                        <td class="text-right"><strong><?php echo trans('sub-total') ?></strong></td>
                        <td class="text-right"><span><strong><?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format($price,$company->num_format)) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
                    </tr>

                    <tr class="total">
                        <td></td>
                        <td class="text-right"><strong><?php echo trans('total') ?></strong></td>
                        <td class="text-right"><span><strong><?php if($company->curr_locate == 0){echo $currency_symbol;} ?><?php echo html_escape(number_format($price,$company->num_format)) ?> <?php if($company->curr_locate == 1){echo $currency_symbol;} ?></strong></span></td>
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