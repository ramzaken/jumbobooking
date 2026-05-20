<?php include"topbar.php"; ?>

<section class="pt-0 cus-account">
    <div class="container cw-14">
        <div class="row mb-100">
            <div class="col-md-3 col-sm-12">
                <?php include'side_menu.php'; ?>
            </div>

            <div class="col-md-9 col-sm-12">
                <div class="card shadow-sm br-10 over-hiddens mb-4">
                    <div class="card-header bg-white px-5 py-2 mt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title font-weight-normal"><?php echo trans('appointments') ?> <span class="count"><?php echo count($appointments) ?></span> </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <form class="sort_form" method="get" action="<?php echo base_url('customer/appointments') ?>">
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

                    <div class="card-body bg-white p-0 table-responsive">

                        <!-- new items style -->
                        <div class="d-nones accordion" id="accordionExample">

                            <div class="cards">
                                <div class="card-header bg-gray hide-xs">
                                    <div class="row">
                                      <div class="col-md-6 d-flex align-items-center">
                                          <h5 class="mb-0"><?php echo trans('services') ?></h5>
                                      </div>
                                      <div class="col-md-2">
                                        <h5 class="mb-0"><?php echo trans('staff') ?></h5>
                                      </div>
                                      <div class="col-md-2">
                                        <h5 class="mb-0"><?php echo trans('meeting') ?></h5>
                                      </div>
                                      <div class="col-md-2 text-right">
                                        <h5 class="mb-0"></h5>
                                      </div>
                                    </div>
                                </div>
                            </div>

                            <?php $a=1; foreach ($appointments as $appointment): ?>
                                <div class="cards">
                                    <div class="card-header bg-<?php if($a % 2 == 0){echo "gray";} ?>" id="headingOne_<?php echo html_escape($appointment->id) ?>">
                                      
                                            <div class="row">
                                                <div class="col-md-6 align-items-center">
                                                   
                                                    <?php 
                                                        $check_coupon = check_coupon($appointment->id, $appointment->service_id, $appointment->business_id);
                                                        if ($check_coupon != FALSE):
                                                            if (!empty($check_coupon)):
                                                                $price =  get_price($appointment->price, $appointment->group_booking, $appointment->total_person);
                                                                $discount = $check_coupon->discount;
                                                                $amount = $price - ($price * ($discount / 100));
                                                                $discount_amount = $price - $amount;
                                                            else:
                                                                $price =  get_price($appointment->price, $appointment->group_booking, $appointment->total_person);
                                                                $discount = 0;
                                                                $discount_amount = 0;
                                                                $amount = $price;
                                                            endif;
                                                        else:

                                                            $discount = 0;
                                                            $amount =  get_price($appointment->price, $appointment->group_booking, $appointment->total_person);
                                                        endif;

                                                        //calculate service tax
                                                        if ($company->tax_type != 0):
                                                            if ($company->tax_type == 1 && $company->tax_amount > 0):
                                                                $amount = str_replace(',','', get_tax($amount,  $company->tax_amount));
                                                            endif;

                                                            $service = $this->admin_model->get_by_id($appointment->service_id, 'services');
                                                            if ($company->tax_type == 2 && $service->tax > 0):
                                                                $amount = str_replace(',','', get_tax($amount,  $service->tax));
                                                            endif;
                                                        endif;
                                                    ?>

                                                    <p class="mb-1">
                                                        <span class="mb-1 mr-1 smalls badge badge-sm badge-secondary-soft fs-12">#<?php echo html_escape($appointment->number) ?></span>
                                                        <?php if ($discount != 0): ?>
                                                          <span class="mb-1 mr-1 smalls badge badge-sm badge-secondary-soft fs-12">
                                                              <?php echo html_escape($discount) ?>% <?php echo trans('off') ?> 
                                                          </span>
                                                        <?php endif ?>

                                                        <span class="mb-1 mr-1 smalls badge badge-sm badge-secondary-soft fs-12">
                                                            <?php if ($appointment->price == 0): ?>
                                                                <?php echo trans('free') ?> 
                                                            <?php else: ?>
                                                                <?php $amount = get_appointment_price($appointment, $company) ?>
                                                                <?php if($company->curr_locate == 0){echo get_currency_by_country($company->country)->currency_symbol;} ?><?php echo number_format($amount, $company->num_format) ?> <?php if($company->curr_locate == 1){echo get_currency_by_country($company->country)->currency_symbol;} ?> 
                                                            <?php endif ?>
                                                        </span>
                                                        
                                                        <span class="mb-1 mr-1 smalls badge badge-sm badge-secondary-soft fs-12"><i class="bi bi-clock"></i> <?php echo html_escape($appointment->duration).' '.trans($appointment->duration_type) ?> </span> 

                                                        <span class="mb-1 mr-1 smalls badge badge-sm badge-secondary-soft fs-12"><i class="far fa-calendar-alt"></i> <?php echo my_date_show($appointment->date) ?></span> 

                                                        <?php if ($appointment->duration_type != 'day'): ?>
                                                        <span class="mb-1 mr-1 smalls badge badge-sm badge-secondary-soft fs-12"><i class="bi bi-clock-fill"></i> 
                                                            <?php $convert_time = convert_to_customer_timezone($appointment->time, $company->id, $appointment->customer_id); ?>
                                                            <?php echo format_time($convert_time, $company->time_format) ?></span>
                                                        <?php endif ?>

                                                        <?php if ($appointment->status == 0): ?>
                                                            <span class="mb-1 mr-1 smalls badge badgesm badge-warning-soft fs-12"><i class="far fa-circle fs-13"></i> <?php echo trans('pending') ?></span>
                                                        <?php elseif ($appointment->status == 1): ?>
                                                            <span class="mb-1 mr-1 smalls badge badgesm badge-success-soft fs-12"><i class="far fa-circle fs-13"></i> <?php echo trans('approved') ?></span>
                                                        <?php elseif ($appointment->status == 2): ?>
                                                            <span class="mb-1 mr-1 smalls badge badgesm badge-danger-soft fs-12"><i class="far fa-circle fs-13"></i> <?php echo trans('rejected') ?></span>
                                                        <?php elseif ($appointment->status == 3): ?>
                                                            <span class="mb-1 mr-1 smalls badge badgesm badge-primary-soft fs-12"><i class="far fa-circle fs-13"></i> <?php echo trans('completed') ?></span>
                                                        <?php endif ?>

                                                        <?php 
                                                            $amp_payment = appointment_payment_details($appointment->id);
                                                            if (!empty($amp_payment) && $amp_payment->status == 'verified') {
                                                              $check_payment = true;
                                                            }else{
                                                              $check_payment = false;
                                                            }
                                                        ?>

                                                        <?php if ($check_payment == true): ?>
                                                            <?php if ($amp_payment->pay_later != '0'): ?>
                                                              <span class="mb-1 mr-1 smalls badge badgesm badge-success-soft fs-12"><i class="fas fa-check-circle"></i>
                                                               <?php echo trans('partially-paid') ?>
                                                              </span>

                                                              <span class="mb-1 mr-1 smalls badge badgesm badge-danger-soft fs-12">
                                                                <?php echo trans('due') ?> 
                                                                <?php $amount = get_appointment_price($appointment, $this->business) ?>
                                                                <?php if($this->business->curr_locate == 0){echo $this->business->currency_symbol;} ?>
                                                                <?php echo number_format($amp_payment->pay_later, $this->business->num_format) ?>
                                                                <?php if($this->business->curr_locate == 1){echo $this->business->currency_symbol;} ?>
                                                              </span>
                                                            <?php else: ?>
                                                              <span class="mb-1 mr-1 smalls badge badgesm badge-success-soft fs-12"><i class="fas fa-check-circle"></i> <?php echo trans('paid').' '.trans('payment') ?></span>
                                                            <?php endif ?>

                                                        <?php else: ?>
                                                            <?php if ($appointment->price != 0): ?>
                                                                <span class="mb-1 mr-1 smalls badge badgesm badge-warning-soft fs-12"><i class="bi bi-hourglass-top"></i> <?php echo trans('pending').' '.trans('payment') ?></span>
                                                            <?php endif ?>
                                                        <?php endif; ?>

                                                        <?php if(!empty($appointment->service_extra)): ?>
                                                            <span class="mt-0"><a data-toggle="modal" href="#extraServiceModal_<?php echo $a ?>" class="mb-1 mr-1 smalls badge badge-sm badge-primary-soft fs-12"><i class="bi bi-eye-fill"></i> <?php echo trans('booked-service-extra') ?> </a></span>
                                                        <?php endif; ?>

                                                        <?php if ($appointment->group_booking != 0): ?>
                                                        <span class="mb-1 mr-1 smalls badge badgesm badge-success-soft fs-12"><i class="bi bi-people"></i> <?php echo trans('group-booking') ?> - <?php echo $appointment->total_person + 1 ?> </span>
                                                        <?php endif ?>
                                                    </p>


                                                    <p class="text-dark font-weight-bold fs-15 mb-1"> <?php echo html_escape($appointment->service_name) ?> </p>

                                                    <p class="mb-1">
                                                        <?php if ($appointment->location_id != 0): ?>
                                                            <span class="mb-1">
                                                                <i class="bi bi-geo-alt-fill"></i> <?php echo get_by_id($appointment->location_id, 'locations')->name ?>
                                                            </span>
                                                        <?php endif ?>
                                                        <?php if ($appointment->sub_location_id != 0): ?>
                                                            <span class="mb-0">
                                                                <i class="bi bi-arrow-right"></i> <?php echo get_by_id($appointment->sub_location_id, 'locations')->name ?> (<?php echo get_by_id($appointment->sub_location_id, 'locations')->address ?>)</span>
                                                        <?php endif ?>
                                                    </p>

                                                    <?php if($service->service_type == 2): ?>
                                                      <p class="mb-0">
                                                        <span><?php echo trans('repeated-in') ?><b class="text-dark"><?php echo trans('monthly') ?></b></span>
                                                      &bull; <b class="text-dark"><?php echo trans('next') ?> : </b>  <?php echo my_date_show($appointment->next_recur_date) ?>
                                                      &bull; <b class="text-dark"><?php echo trans('recurring-count') ?> : </b><?php echo html_escape($appointment->recurring_count) ?></p>
                                                    <?php endif; ?>

                                                </div>


                                                <!-- staff column -->
                                                <div class="col-md-2">
                                                    <p class="text-dark font-weight-bold mb-0">
                                                        <?php echo html_escape($appointment->business_name) ?>
                                                    </p>
                                                    <p class="font-weight-bold mb-0"><?php echo html_escape($appointment->staff_name) ?></p>
                                                </div>


                                                <div class="col-md-2">
                                                    

                                                    <?php if ($appointment->date >= date('Y-m-d')): ?>
                                                        <?php if($appointment->is_start == 1): ?>
                                                            
                                                            <?php $user = get_by_id($appointment->user_id, 'users')  ?>

                                                            <?php if ($company->default_meeting == 'zoom'): ?>
                                                             
                                                              <?php if(!empty($appointment->join_url)):?>                         
                                                                <a target="_blank" href="<?= $appointment->join_url ?>" class="btn btn-danger btn-sm position-relative"><i class="bi bi-person-video"></i> <?php echo trans('join-meeting') ?>

                                                                <div class="pulse" data-toggle="tooltip" data-title="Doctor started the meeting click the join button"></div>
                                                              </a>
                                                              <?php endif;?>
                                                              <span class="badge badge-secondary-soft"><?php echo trans('meeting-password') ?>: <b><?php echo html_escape($appointment->zoom_password); ?></b></span>
                                                            <?php endif; ?>

                                                        <?php else: ?>
                                                          <label class="badge badge-secondary-soft brd-20"><i class="bi bi-camera-video-off-fill"></i> <?php echo trans('not-started-yet') ?> </label>
                                                        <?php endif ?>

                                                    <?php else: ?>
                                                        <label class="badge badge-danger-soft brd-20"><i class="bi bi-clock"></i> <?php echo trans('expired') ?></label>
                                                    <?php endif ?>




                                                </div>


                                                <!-- action column -->
                                                <div class="col-md-2 text-right">
                                                    <?php $cpayment = appointment_payment_details($appointment->id) ?>
                                                    <?php if ($check_payment == true): ?>
                                                        <a data-toggle="tooltip" data-title="<?php echo trans('view-invoice') ?>" target="_blank" href="<?php echo base_url('customer/customer_receipt/'.$cpayment->puid) ?>" class="pull-right btn btn-light-secondary btn-sm ml-1"><i class="fa fa-eye"></i></a>
                                                    <?php endif; ?>

                                                    <?php if (empty($appointment->pay_info) && $check_payment != true): ?>
                                                        <?php if ($appointment->price != 0 && $cpayment->payment_method != 'offline'): ?>
                                                            <a data-toggle="tooltip" data-title="<?php echo trans('complete-your-payment') ?>" data-placement="left" class="btn btn-light-primary btn-sm" data-id="<?php echo html_escape($appointment->id) ?>" href="<?php echo base_url('customer/payment/'.md5($appointment->id)) ?>"><i class="fas fa-credit-card"></i></a>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                    <?php if ($appointment->pay_info == 1 && $check_payment != true): ?>
                                                        <?php if ($appointment->price != 0 && $cpayment->payment_method != 'offline'): ?>
                                                            <a data-toggle="tooltip" data-title="<?php echo trans('complete-your-payment') ?>" data-placement="left" class="btn btn-light-primary btn-sm" data-id="<?php echo html_escape($appointment->id) ?>" href="<?php echo base_url('customer/payment/'.md5($appointment->id)) ?>"><i class="fas fa-credit-card"></i></a>
                                                        <?php endif ?>
                                                    <?php endif ?>

                                                    <?php if ($check_payment != true): ?>
                                                        <?php if ($cancelation_date <= $appointment->date && $company->cancelation_time != 0): ?>
                                                            <a data-toggle="tooltip" data-title="<?php echo trans('cancel-appointment') ?>" data-placement="top" class="btn btn-light-danger btn-sm not_cancel_item" data-id="<?php echo html_escape($appointment->id) ?>" href="<?php echo base_url('customer/cancel/'.md5($appointment->id)) ?>"><i class="fas fa-times-circle fs-12"></i></a>

                                                        <?php else: ?>
                                                            <a data-toggle="tooltip" data-title="<?php echo trans('cancel-appointment') ?>" data-placement="top" class="btn btn-light-danger btn-sm cancel_item" data-id="<?php echo html_escape($appointment->id) ?>" href="<?php echo base_url('customer/cancel/'.md5($appointment->id)) ?>"><i class="fas fa-times-circle fs-12"></i></a>
                                                        <?php endif ?>
                                                    <?php endif ?>

                                                    <?php if (!empty($appointment->zoom_link)): ?>
                                                        <!-- <a target="_blank" data-toggle="tooltip" data-title="<?php //echo trans('zoom-meeting-link') ?>" data-placement="top" class="btn btn-primary btn-sm" data-id="<?php //echo html_escape($appointment->id) ?>" href="<?php //echo html_escape($appointment->zoom_link) ?>"><i class="fas fa-video fs-12"></i></a> -->
                                                    <?php endif ?>

                                                    <?php if ($company->default_meeting == 'meet' && !empty($appointment->google_meet)): ?>
                                                        <a target="_blank" data-toggle="tooltip" data-title="<?php echo trans('google-meet-link') ?>" data-placement="top" class="btn btn-success btn-sm" data-id="<?php echo html_escape($appointment->id) ?>" href="<?php echo html_escape($appointment->google_meet) ?>"><i class="fas fa-video fs-12"></i></a>
                                                    <?php endif ?>

                                                    <?php if ($appointment->status == 3): ?>
                                                        <?php $check = check_apo_rating($appointment->id); ?>
                                                        <?php if (check_apo_rating($appointment->id) == 0): ?>
                                                            <a href="#ratingModal_<?php echo $a ?>" data-toggle="modal" class="btn btn-outline-warning btn-sm"> <i class="far fa-star fs-14"></i></a>
                                                        <?php else: ?>
                                                            <a href="#ratingModal_<?php echo $a ?>" data-toggle="modal" class="btn btn-warning btn-sm"> <i class="fas fa-star fs-14"></i></a>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                       
                                    </div>

                                    <div id="collapse_<?php echo html_escape($appointment->id) ?>" class="collapse" aria-labelledby="headingOne_<?php echo html_escape($appointment->id) ?>" data-parent="#accordionExample">
                                      <div class="card">
                                        <div class="card-body row px-6">
                                            <div class="col-md-12">
                                                te
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <input type="hidden" name="cancelation_before" class="cancelation_before" value="<?php echo html_escape($company->cancelation_time) ?>">
                            <?php $a++; endforeach ?>
                        </div>
                        <!-- new items style -->
                    </div>
                    
                </div>

                <div class="col-md-12 text-center mt-4">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
        </div>
    </div>
</seciton>



<!-- Modal -->
<?php $b=1; foreach ($appointments as $appointment): ?>
<div class="modal fade d-hide" id="ratingModal_<?= $b; ?>" aria-hidden="true">
  <div class="modal-dialog">
  
    <form method="post" enctype="multipart/form-data" class="validate-form" action="<?php echo base_url('customer/add_rating')?>" role="form" novalidate>
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">
            <?php if (check_apo_rating($appointment->id) == 0): ?>
                <?php echo trans('rate-this-service') ?>
            <?php else: ?>
                <?php echo trans('your-feedback') ?>
            <?php endif; ?>
        </h4>
          <div class="mclose" data-dismiss="modal"><i class="lnib lni-close"></i></div>
        </div>

        <div class="modal-body">
            <?php if (check_apo_rating($appointment->id) == 0): ?>
                <?php 
                $id1 = $b+rand(); $id2 = $b+rand(); $id3 = $b+rand(); $id4 = $b+rand(); $id5 = $b+rand();
            ?>
                <div class="form-group mt-2">
                    <fieldset class="rating one mb-4">
                        <input type="radio" id="star<?= $id1 ?>" name="rating" value="5" /><label for="star<?= $id1 ?>"><span><i class="fas fa-star"></i></span></label>
                        <input type="radio" id="star<?= $id2 ?>" name="rating" value="4" /><label for="star<?= $id2 ?>"><span><i class="fas fa-star"></i></span></label>
                        <input type="radio" id="star<?= $id3 ?>" name="rating" value="3" /><label for="star<?= $id3 ?>"><span><i class="fas fa-star"></i></span></label>
                        <input type="radio" id="star<?= $id4 ?>" name="rating" value="2" /><label for="star<?= $id4 ?>"><span><i class="fas fa-star"></i></span></label>
                        <input type="radio" id="star<?= $id5 ?>" name="rating" value="1" /><label for="star<?= $id5 ?>"><span><i class="fas fa-star"></i></span></label>
                    </fieldset>
                </div>

                <div class="form-group mt-2">
                    <textarea class="form-control" name="feedback" rows="2" placeholder="<?php echo trans('write-feedback') ?>"></textarea>
                </div>
            <?php else: ?>
                <?php $rating = check_apo_rating($appointment->id); ?>
                <?php for($i = 1; $i <= 5; $i++):?>
                <?php 
                if($i > $rating->rating){
                  $star = 'far fa-star';
                }else{
                  $star = 'fas fa-star';
                }
                ?>
                <i class="<?php echo $star;?> text-warning fs-13"></i> 
              <?php endfor;?>

                <p class="mt-2 lead"><?php echo $rating->feedback ?></p>
            <?php endif; ?>
        </div>

        <?php if (check_apo_rating($appointment->id) == 0): ?>
        <div class="modal-footer justify-content-between">
          <input type="hidden" name="appointment_id" value="<?php echo $appointment->id ?>">
          <!-- csrf token -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
          <button type="submit" class="btn btn-primary btn-sm"><?php echo trans('submit') ?></button>
        </div>
        <?php endif; ?>

      </div>
    </form>

    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php $b++; endforeach; ?>
<!-- End Modal -->


<?php if (!empty($appointments)): ?>
  <?php $q=1; foreach ($appointments as $appointment): ?>

    <div class="modal fade d-hide" id="extraServiceModal_<?= $q; ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg">
      
        
        <?php $service_extras = explode(',', $appointment->service_extra); ?>
          <div class="modal-content">
            <div class="modal-header border-0">
            <h4 class="modal-title">
                <?php echo trans('service-extra') ?> of - <?php echo get_by_id($appointment->service_id,'services')->name ?>
            </h4>
              <div class="mclose" data-dismiss="modal"><i class="lnib lni-close"></i></div>
            </div>

            <?php if(!empty($appointment->service_extra)): ?>
              <div class="modal-body mb-4">
                <?php $business = $this->admin_model->get_company($appointment->user_id) ?>

                <?php foreach ($service_extras as $value): ?>
                  <?php $service_extra = get_by_id($value,'service_extra'); ?>
                  <div class="row px-4 py-3 bg-light mx-2 my-3">
                    <div class="col-md-6">
                      <p class="mb-0"><?php echo html_escape($service_extra->name) ?></p>
                    </div>

                    <div class="col-md-3">
                      <p class="mb-0">
                        <?php if($company->curr_locate == 0){echo get_currency_by_country($company->country)->currency_symbol;} ?>
                        <?php echo number_format($service_extra->price, $company->num_format) ?>
                        <?php if($company->curr_locate == 1){echo get_currency_by_country($company->country)->currency_symbol;} ?>
                      </p>
                    </div>
                    <div class="col-md-3">
                      <p class="mb-0">
                        <i class="far fa-clock"></i> <?php echo html_escape($appointment->duration).' '.trans($appointment->duration_type); ?>
                      </p>
                    </div>
                  </div>
                <?php endforeach ?>
             
              </div>
            <?php else: ?>
              <?php $this->load->view('admin/include/not-found') ?>
            <?php endif; ?>

          </div>

        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
  <?php $q++; endforeach; ?>
<?php endif; ?>
