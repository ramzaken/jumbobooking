<style type="text/css">
    .ui-datepicker-prev:after, .ui-datepicker-next:after {
      font-family: "Font Awesome 5 Free";
      font-weight: 500;
      content: "\f008";
      position: absolute;
      display: block;
      width: 10px;
      height: 10px;
      border-left: 2px solid #fff;
      border-bottom: 2px solid #fff;
      color: #fff;
      top: 310px !important;
    }


  .ui-datepicker-title {
    text-align: center;
    padding-top: 6px;
    font-size: 15px;
  }

</style>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <?php $this->load->view('admin/include/breadcrumb'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">

          <?php if (settings()->type == 'demo'): ?>
            <div class="row">
              <div class="col-12">
                <div class="card p-2 mr-2 bg-danger-soft">
                  <span><i class="fas fa-info-circle"></i> Booking POS is only available with Extended License</span>
                </div>
              </div>
            </div>
          <?php endif ?>

      
        <div class="pos_area">
          <div class="d-flex justify-content-between">
            <h5><?php echo trans('create-new') ?> <?php echo trans('pos-booking') ?></h5>
            <a href="<?php echo base_url('admin/appointment/?type=pos') ?>" class="btn btn-primary mr-2 mb-3"> <?php echo trans('pos-booking') ?></a>
          </div>
          <form method="post" enctype="multipart/form-data" class="validate-form pos_booking_form" action="<?php echo base_url('admin/pos/booking_add')?>" role="form" novalidate>
            <div class="row">

              <div class="col-md-5">
                <div class="card">
                  <div class="card-body">
                   
                    <div class="form-group px-3">
                      <div class="search-input mb-1">
                        <span class="search-icon">
                          <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="pos_service_search" class="pos_service_search" placeholder="Search services">
                      </div>
                    </div>

                    <div class="service-wrap p-3" data-aos="fade-up">

                      <!-- <div class="row mb-5">
                        <?php $e=1; foreach ($services as $service): ?>
                          <div class="col-md-4 mb-3">
                            <div class="pos_service_box service_input">
                              <div class="pos_service_img" style="background-image:url(<?php echo base_url('uploads/medium/5fcf540eef0329786ee3cefaa75f7095_medium-1200x800.jpg') ?>);"></div>
                              <div class="media-body">
                                <h5 class="mt-2 mb-1">Service name </h5>
                                <span>50 $</span>
                                <span class="ml-5">30 minutes</span>
                              </div>
                            </div>
                          </div>
                          <input type="hidden" name="service_id" class="hidden_service_id" value="<?php echo html_escape($service->id) ?>">
                        <?php $e++; endforeach; ?>
                      </div> -->


                      <div class="row service_search_area">
                          <?php $this->load->view('admin/user/include/service_search') ?>
                      </div>
                    </div>

                    <div class="row text-center p-3" id="load_staff_data">
                                      
                    </div>

                  </div>
                </div>
              </div>

              <div class="col-md-7 pl-3">
                <div class="card">
                  <div class="card-body px-4">

                    <div class="mb-3 pos_cus_area d-hide">
                       <a class="nav-link btn btn-light add-customer btn-block py-3 border-dash" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button"><i class="bi bi-plus-circle"></i> <?php echo trans('add-customer') ?> </a>
                    </div>


                    <div class="pos_service_empty">
                      <div class="align-items-center text-center p-3">
                        <?php echo trans('no-service-selected') ?>
                      </div>

                    </div>

                    <div class="pos_service_info hide">
                      <div class="pt-3">
                        <a href="#" class="btn btn-primary modify_pos_date hide"><i class="bi bi-pencil"></i> <?php echo trans('modify-date') ?></a>
                        <a href="#" class="btn btn-primary modify_pos_time hide"><i class="bi bi-pencil"></i> <?php echo trans('modify-time') ?></a>
                      </div>
                      
                      <div class="pos_calendar_area d-hide">
                        <div class="appointment_datepicker mt-3 d-hide">
                          <label><?php echo trans('date') ?> <span class="text-danger">*</span></label>
                          <div id="load_work_cal">
                              <div id="datepickers"></div>
                          </div>
                        </div>
                      </div>

                      <input type="hidden" class="booking_date" name="date" value="">

                      <div class="pos_time_area">
                        <div class="p-0 text-center">

                          <div id="load_data">
                            
                          </div>
                        </div>
                      </div>

                      <div class="service_row_area mt-5">
                      </div>
                      
                      <div class="row pos_service_extra_area pl-5 mt-3">
                      
                      </div>

                      <input type="hidden" name="select_service_extra" class ="select_service_extra" value="0">
                      <input type="hidden" name="service_extra_value[]" class ="service_extra_value" value="">

                      <div class="pos_staff">
                      
                      </div>

                      <div class="pos_customer">
                      
                      </div>

                      <input type="hidden" class="pos_customer_id" name="customer_id" value="">

                      <div class="mt-5">
                        <div class="d-flex justify-content-between mb-1 bm-1 pb-1 pr-5">
                          <div class="mr-5">
                           <p class="mb-1 text-dark-75 h6"><?php echo trans('sub-total') ?></p>
                          </div>

                          <div class="text-left">
                            <p class="mb-1 text-muted fs-15 ml-3">
                              <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <span class="sub_total text-muted"></span> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                            </p>
                          </div>
                        </div>

                        <div class="d-flex justify-content-between hide align-items-center mt-2 mb-2 pos_coupon_area">
                            
                        </div>
                        <input type="hidden" class="pos_coupon_discount" name="pos_coupon_discount" value="">

                        <div class="d-flex justify-content-between mb-1 bm-1 pb-1 pr-5">
                          <div class="mr-5">
                           <p class="mb-1 text-dark-75 h6"><?php echo trans('total') ?></p>
                          </div>

                          <div class="text-left">
                            <p class="mb-1 text-muted fs-15 ml-3">
                              <?php if($this->business->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?> <span class="total text-muted"></span> <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
                            </p>
                          </div>
                        </div>

                        <input type="hidden" class="enable_staff" name="enable_staff" value="<?php echo html_escape($company->enable_staff); ?>">

                        

                        <button type="submit" class="btn btn-dark py-2 btn-block pos_step1_continue mt-5"><?php echo trans('continue') ?> <i class="bi bi-arrow-right"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="pos_payment_area">
          <?php //$this->load->view('admin/user/include/pos_booking_step2') ?>
        </div>
    </div>
  </div>
</div>
