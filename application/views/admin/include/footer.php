<?php if (is_user() && check_my_payment_status() == TRUE): ?>
  <div class="feature-steps <?php if(check_data('working_days') == TRUE && check_data('staffs') == TRUE && check_data('customers') == TRUE && check_data('services') == TRUE){echo "d-none";} ?>">

      <a class="heading" href="#"><i class="fas fa-arrow-down micon mr-1"></i> <?php echo trans('please-complete-these-steps') ?> <span class="fs-close pull-right"><i class="fas fa-times-circle"></i></span></a>

      <a class="<?php if(check_data('working_days') == TRUE){echo "active";} ?>" href="<?php echo base_url('admin/settings/working_hours') ?>"><i class="fas fa-<?php if(check_data('working_days') == TRUE){echo "check-";}else{echo "";} ?>circle mr-1"></i> <?php echo trans('set-business-hours') ?></a>

      <a class="<?php if(check_data('staffs') == TRUE){echo "active";} ?>" href="<?php echo base_url('admin/staff') ?>"><i class="fas fa-<?php if(check_data('staffs') == TRUE){echo "check-";}else{echo "";} ?>circle mr-1"></i> <?php echo trans('add-staff') ?></a>

      <a class="<?php if(check_data('customers') == TRUE){echo "active";} ?>" href="<?php echo base_url('admin/customers') ?>"><i class="fas fa-<?php if(check_data('customers') == TRUE){echo "check-";}else{echo "";} ?>circle mr-1"></i><?php echo trans('add-customer') ?></a>

      <a class="<?php if(check_data('services') == TRUE){echo "active";} ?>" href="<?php echo base_url('admin/services') ?>"><i class="fas fa-<?php if(check_data('services') == TRUE){echo "check-";}else{echo "";} ?>circle mr-1"></i> <?php echo trans('add-service') ?></a>
  </div>
<?php endif ?>

  <?php include APPPATH.'views/include/js_msg_list.php'; ?>

  <?php $success = $this->session->flashdata('msg'); ?>
  <?php $error = $this->session->flashdata('error'); ?>
  <input type="hidden" id="success" value="<?php if(isset($success)){echo html_escape($success);} ?>">
  <input type="hidden" id="error" value="<?php if(isset($error)){echo html_escape($error);} ?>">  
  <input type="hidden" id="lc" value="<?php echo strlen(settings()->ind_code); ?>">
  <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
  <?php echo $this->session->unset_userdata('msg'); $this->session->unset_userdata('error'); ?>

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <?php echo trans('version') ?> <?php echo html_escape(settings()->version) ?>
    </div>
    <!-- Default to the left -->
    <strong><?php echo trans('copyright') ?> &copy; <?php echo date('Y') ?>  <?php echo trans('all-rights-reserved') ?>.
  </footer>

  <aside class="controlsidebar-cm control-sidebar control-sidebar-light">
     <div class="p-3 control-sidebar-content os-host os-theme-light os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition os-host-overflow os-host-overflow-y" >
        <div class="os-resize-observer-host observed">
           <div class="os-resize-observer"></div>
        </div>
        <div class="os-size-auto-observer observed">
           <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue"></div>
        <div class="os-padding">
            <div class="d-flex ml-3 justify-content-start align-items-center mb-3">
              <div class="mr-2">
                <a  href="#" class="btn btn-outline-secondary active existing_customer"><i class="bi bi-people mr-2"></i><?php echo trans('all-customers') ?></a>
              </div>
              <div class="mr-2">
                <a  href="#" class="btn btn-outline-secondary add_new_customer"><i class="bi bi-plus-circle mr-2"></i><?php echo trans('create-new-customer') ?></a>
              </div>
              <div class="mr-0">
                <a  href="#" class="btn btn-outline-danger" data-widget="control-sidebar" data-controlsidebar-slide="true"><i class="bi bi-x-lg"></i></a>
              </div>
            </div>
             
            <div class=" ml-3">
              <div class="mr-3">

                <div class="search-input mb-2">
                  <span class="search-icon">
                    <i class="bi bi-search"></i>
                  </span>
                  <input type="text" name="pos_customer_search" class="pos_customer_search"  placeholder="<?php echo trans('search') ?>" />
                </div>

                <div class="customer_search_area">
                  <?php $this->load->view('admin/user/include/customer_search') ?>
                </div>

                  
              </div>

              <div class="add_new_customer_area hide">
      
                <form method="post" enctype="multipart/form-data" class="validate-form pos_cadd_form" action="<?php echo base_url('admin/pos/add_customer')?>" role="form" novalidate>
                  <div class="modal-contentg">
                      <div class="form-group">
                        <label><?php echo trans('name') ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="name" value="">
                      </div>

                      <div class="form-group">
                        <label><?php echo trans('email') ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="email" value="">
                      </div>

                      <div class="form-group">
                        <label><?php echo trans('phone') ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" required name="phone" value="">
                      </div>

                    
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                      <button type="submit" class="btn btn-primary add_pos_customer"><?php echo trans('submit') ?></button>
                  </div>
                </form>
              </div>
            </div>
        </div>
     </div>
  </aside>

</div>
<!-- ./wrapper -->


<!-- jQuery -->
<script src="<?php echo base_url() ?>assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url() ?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="<?php echo base_url() ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url() ?>assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>


<!-- Admin App -->
<script src="<?php echo base_url() ?>assets/admin/js/admin.js?var=<?= settings()->version ?>&time=<?=time();?>"></script>

<script src="<?php echo base_url() ?>assets/admin/js/validation.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/sweet-alert.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-tagsinput.min.js"></script>

<!-- select2 js -->
<script src="<?php echo base_url()?>assets/admin/plugins/select2/js/select2.full.min.js"></script>
<!-- nice select js -->
<script src="<?php echo base_url()?>assets/admin/js/nice-select.min.js"></script>
<script src="<?php echo base_url()?>assets/admin/js/tata.js"></script>

<!-- date & time picker -->
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url()?>assets/admin/js/timepicker.min.js"></script>
<!-- animation js -->
<script src="<?php echo base_url() ?>assets/front/js/aos.js"></script>

<!-- bs-custom-file-input -->
<script src="<?php echo base_url() ?>assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Summernote -->
<script src="<?php echo base_url() ?>assets/admin/plugins/summernote/summernote-bs4.min.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/clipboard.min.js"></script>

<script src="<?php echo base_url() ?>assets/admin/js/bootstrapicon-iconpicker.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/printThis.js"></script>

<script type="text/javascript" src="<?php echo base_url() ?>assets/admin/js/image-uploader.min.js"></script>
<!-- stripe js -->
<?php include'stripe-js.php'; ?>


<!-- chart js -->
<?php if (isset($page) && $page == 'Dashboard'): ?>
  <?php $this->load->view('admin/include/charts'); ?>
<?php elseif (isset($page) && $page == 'Reports'): ?>
  <?php $this->load->view('admin/include/user-charts'); ?>
<?php endif ?>

<!-- calendar js -->
<?php if (isset($page_title) && $page_title == 'Calendars'): ?>
<?php include'calendar-js.php'; ?>
<?php endif ?>

<script type="text/javascript">
  $(document).ready(function () {

      //Colorpicker
      $('.colorpicker').colorpicker();

      $('.default').click(function () {    
        $('.default').not($(this)).removeClass('active');
        $(this).toggleClass('active').next().find('.sub-table-wrap').slideToggle();
        $(".toggle-row").not($(this).next()).find('.sub-table-wrap').slideUp('fast');
      });
      
      //$('[data-toggle="tooltip"]').tooltip();  
      
      $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd'
      });

      $.datepicker.regional ['en'] = {
          clearText: 'Clear', 
          clearStatus: '',
          closeText: 'Close',
          closeStatus: 'Close without modifying',
          prevStatus: 'See previous month',
          nextStatus: 'See next month',
          currentText: 'Current',
          currentStatus: 'See current month',
          monthNames: ['<?php echo trans('january') ?>', '<?php echo trans('february') ?>', '<?php echo trans('march') ?>', '<?php echo trans('april') ?>', '<?php echo trans('may') ?>', '<?php echo trans('june') ?>',
          '<?php echo trans('july') ?>', '<?php echo trans('august') ?>', '<?php echo trans('september') ?>', '<?php echo trans('october') ?>', '<?php echo trans('november') ?>', '<?php echo trans('december') ?>'],
          monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
          'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          monthStatus: 'See another month',
          yearStatus: 'See another year',
          weekHeader: 'Sm',
          weekStatus: '',
          dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
          dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
          dayNamesMin: ['<?php echo trans('su') ?>', '<?php echo trans('mo') ?>', '<?php echo trans('tu') ?>', '<?php echo trans('we') ?>', '<?php echo trans('th') ?>', '<?php echo trans('fr') ?>', '<?php echo trans('sa') ?>'],
          dayStatus: 'Use DD as the first day of the week',
          dateStatus: 'Choose the DD, MM of',
          firstDay: 0,
          initStatus: 'Choose date',
          isRTL: false
      }; 

      $.datepicker.setDefaults($.datepicker.regional['en']);

  });
</script>


<script type="text/javascript">
  <?php if (isset($this->business->time_format) && $this->business->time_format == 'HH'): ?>
    $(document).on("focusin",".timepicker", function () {
      $('input.timepicker').timepicker({ timeFormat: 'HH:mm', interval: 30 });
    });

    $(document).on("focusin",".hourpicker", function () {
      $('input.hourpicker').timepicker({ timeFormat: 'HH:mm', interval: 60 });
    });
  <?php else: ?>
    $(document).on("focusin",".timepicker", function () {
      $('input.timepicker').timepicker({ timeFormat: 'hh:mm p', interval: 30 });
    });

    $(document).on("focusin",".hourpicker", function () {
      $('input.hourpicker').timepicker({ timeFormat: 'hh:mm p', interval: 60 });
    });
  <?php endif ?>
</script>


<div id="load_work">
    <?php $this->load->view('admin/include/datepicker-js.php'); ?>
</div>


<?php if (isset($page) && $page == 'Appointment' || $page_title == 'Pos'): ?>
<script type="text/javascript">
  <?php if (!empty($this->session->userdata('staff_id'))): ?>
  (function($) {
      $(document).ready(function() {
          var base_url = $('#base_url').val();
          var staffId = <?php echo $this->session->userdata('staff_id') ?>;
          if(staffId != ''){
              $(".appointment_datepicker").show();

              var url = base_url+'admin/appointment/sess_staff/'+staffId;
              $.post(url, { data: 'value', 'csrf_test_name': csrf_token }, function(json) {
                if(json.st == 1){
                  $("#load_work_cal").html('<div id="datepickers"></div>');
                  $("#load_work").html(json.loaded);
                }else{
                  $("#load_work").html(json.loaded);
                }
              }, 'json' );
          }
      });
  })(jQuery);
  <?php endif; ?>
</script>
<?php endif ?>

<script>
    $('.print_invoice').on("click", function () {
      $('.print_area').printThis({
        base: "https://jasonday.github.io/printThis/"
      });
    });
</script>

<script type="text/javascript">
  $(".input-images").imageUploader({ label: "Drag & Drop files here or click to browse" });
</script>

</body>
</html>
