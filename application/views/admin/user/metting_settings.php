<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <?php $this->load->view('admin/include/breadcrumb'); ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        
            <?php $this->load->view('admin/user/include/settings_menu.php'); ?>

            <div class="col-lg-9 pl-3">
                <div class="card">
                    <form method="post" enctype="multipart/form-data" action="<?php echo base_url('admin/settings/update_meeting') ?>" role="form" class="form-horizontal pl-20">


                        <div class="card-body">
                            <div class="row">


                              <div class="col-md-12">
                                <div class="form-group mb-4">
                                  <label><?php echo trans('defaul-metting') ?></label>
                                  <select name="default_meeting" class="form-control">
                                      <option value="zoom" <?php echo ('zoom' == $this->business->default_meeting) ? "selected" : ""; ?>><?php echo trans('zoom') ?></option>
                                      <option value="meet" <?php echo ($this->business->default_meeting == 'meet') ? "selected" : ""; ?>><?php echo trans('google-meet') ?></option>
                                  </select>
                                </div>
                              </div>


                              <div class="col-md-12 mb-3">
                                  <h5><?php echo trans('zoom-api') ?></h5>
                                <div class="card-body bdk">

                                  <div class="d-flex justify-content-between">
                                    <div class="form-group">
                                      <p class="mb-0"><a class="badge badge-primary fs-18 font-weight-bold" target="_blank" href="https://doxe.originlabsoft.com/docs/#docs_zoom"><i class="bi bi-file-text-fill"> </i><?php echo trans('zoom-intigration-docs') ?> </a></p>
                                    </div>
                                  </div>

                                  <div class="conn_info text-success mb-3">
                                    
                                  </div>

                                  <div class="conn_error text-danger mb-3">
                                    
                                  </div>
                                  

                                  <div class="form-group">
                                    <label><?php echo trans('zoom-account-id') ?></label>
                                      <input type="text" name="zoom_account_id" value="<?php echo html_escape($this->business->zoom_account_id); ?>" class="form-control" >
                                  </div>

                                  <div class="form-group">
                                    <label><?php echo trans('zoom-client-id') ?></label>
                                      <input type="text" name="zoom_client_id" value="<?php echo html_escape($this->business->zoom_client_id); ?>" class="form-control" >
                                  </div>

                                  <div class="form-group">
                                    <label><?php echo trans('zoom-client-secret') ?></label>
                                      <input type="password" name="zoom_client_secret" value="<?php echo html_escape($this->business->zoom_client_secret); ?>" class="form-control" >
                                  </div>

                                  <a href="#" class="btn btn-danger pull-rights text-white test_zoom_api_connection"><i class="bi bi-arrow-repeat"></i> <?php echo trans('check-api-connection') ?></a>
                                </div>
                              </div>


                              <!-- <div class="col-md-12 mb-3 mt-3">
                                <h5><?php //echo trans('google-meet-api') ?></h5>
                                <div class="card-body bdk">

                                  <div class="d-flex justify-content-between">
                                    <div class="form-group">
                                      <p><a class="badge badge-success fs-18 font-weight-bold" target="_blank" href="https://S.originlabsoft.com/docs/#docs_zoom"><i class="bi bi-file-text-fill"></i> <?php echo trans('meet-intigration-docs') ?></a></p>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label><?php echo trans('meet-client-id') ?></label>
                                      <input type="text" name="meet_client_id" value="<?php echo html_escape($this->business->meet_client_id); ?>" class="form-control" >
                                  </div>

                                  <div class="form-group">
                                    <label><?php echo trans('meet-client-secret') ?></label>
                                      <input type="password" name="meet_client_secret" value="<?php echo html_escape($this->business->meet_client_secret); ?>" class="form-control" >
                                  </div>
                                  

                                  <div class="form-group">
                                    <label><?php echo trans('meet-redirect-url') ?></label>
                                      <input type="text" name="meet_redirect_url" value="<?php echo html_escape($this->business->meet_redirect_url); ?>" class="form-control" >
                                  </div>

                                  <a href="#" class="btn btn-danger pull-rights text-white test_meet_api_connection d-none"><i class="bi bi-arrow-repeat"></i> <?php echo trans('check-api-connection') ?></a>
                                </div>
                              </div> -->

                            </div>
                        </div>

                        <div class="card-footer">
                            <input type="hidden" name="id" value="<?php echo html_escape(user()->id); ?>">
                            <!-- csrf token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                            <button type="submit" class="btn btn-primary mt-2"> <?php echo trans('save-changes') ?></button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
