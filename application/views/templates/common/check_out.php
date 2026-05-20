<?php include APPPATH.'views/include/banner.php'; ?>


<section class="h-100 h-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">

      <div class="col-12">
        <div class="mb-4 mt-4">
            <div class="success text-success"></div>
            <div class="error text-danger"></div>
            <div class="warning text-warning"></div>
        </div>
      </div>


      <div class="col-12">
        <div class="card card-registration card-registration-2 overhidden">
          <div class="card-body p-0">

            <form id="checkout_form" action="<?php echo base_url('company/order_submit' .$slug) ?>" method="post" enctype="multipart/form-data">
              <div class="row g-0">
                <div class="col-lg-8">
                  <div class="p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                      <h3 class="fw-bold mb-0 text-black"><?php echo trans('checkout') ?></h3>
                    </div>
                    <hr class="my-4">


                    <?php if(check_auth() == false && !is_customer()): ?>

                      <div class="container p-0">
                        <div class="d-flex justify-content-between mb-4 bm-1 pb-3">
                            <div>
                              <?php if (isset($_GET) && $_GET['type'] == 'login'): ?>
                                <h5 class="mb-0"><?php echo trans('login') ?></h5>
                              <?php else: ?>
                                <h5 class="mb-0"><?php echo trans('create-new-account') ?></h5>
                              <?php endif ?>
                              
                            </div>
                            <div>
                              <?php if (isset($_GET) && $_GET['type'] == 'login'): ?>
                                <a class="badge badge-secondary-soft badge-pill" href="<?php echo base_url('company/check_out/'.$slug.'?type=register') ?>"><?php echo trans('create-new-account') ?></a>
                              <?php else: ?>
                                <a class="badge badge-secondary-soft badge-pill" href="<?php echo base_url('company/check_out/'.$slug.'?type=login') ?>"> <?php echo trans('login') ?></a>
                              <?php endif ?>
                            </div>
                        </div>

                        <?php if(isset($_GET) && $_GET['type'] == 'login'): ?>
                          <div class="row">
                              <div class="box col-md-12 m-auto text-center">
                                  <div class="box-body text-left">
                                    
                                      <div class="row ">
                                        <div class="col-md-12">
                                          <div class="form-group">
                                            <label><?php echo trans('user-name') ?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control requ1" name="user_name" value="" required>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row ">
                                        <div class="col-md-12">
                                          <div class="form-group">
                                            <label><?php echo trans('password') ?><span class="text-danger">*</span></label>
                                            <input type="password" class="form-control requ1" name="password" value="" required>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <?php else: ?>
                          <div class="row">
                              <div class="box col-md-12 m-auto text-center">
                                  <div class="box-body text-left">
                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="form-group">
                                            <label><?php echo trans('name') ?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="" required>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="row ">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                              <label><?php echo trans('email') ?><span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" name="email" value="" required>
                                            </div>
                                          </div>
                                      </div>

                                      <div class="row ">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                              <label><?php echo trans('password') ?><span class="text-danger">*</span></label>
                                              <input type="password" class="form-control" name="password" value="" required>
                                            </div>
                                          </div>
                                      </div>

                                      <div class="row">
                                        <div class="col-md-12">
                                          <div class="form-group">
                                            <label><?php echo trans('phone') ?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone" value="" required>
                                          </div>
                                        </div>
                                      </div>

                                      
                                  </div>
                              </div>
                          </div>
                        <?php endif; ?>
                      </div>

                    <?php else: ?>

                      <div class="container p-0">


                      
                        <p class="p-3 bg-success-soft rounded"><i class="bi bi-info-circle-fill text-success"></i><?php echo trans('already-signed-in-msg') ?></p>

                        
                      </div>

                    <?php endif; ?>
                  </div>
                </div>


                <!-- include sidebar -->
                <?php $this->load->view('include/cart_sidebar'); ?>


              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section> 