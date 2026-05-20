<?php foreach ($customers as $customer): ?>
  <a href="#" class="select_customer" data-id="<?php echo html_escape($customer->id) ?>" style="color: #222;">
    <div class="d-flex justify-content-start align-items-center mb-3 cust_box">
      <?php if(!empty($customer->thumb)): ?>
        <div class="avatar-xs-pos" style="background-image: url(<?php echo base_url($customer->thumb) ?>);"></div>
      <?php else: ?>
        <div class="avatar-xs-pos" style="background-image: url(<?php echo base_url('assets/images/no-photo.png') ?>);"></div>
      <?php endif; ?>
      <div class="ml-3">
        <p class="mb-0 mt-0"><?php echo html_escape($customer->name) ?></p>
        <p class="mb-0 mt-0 text-muted fs-13 font-weight-normal"><?php echo html_escape($customer->email) ?></p>
      </div>
    </div>
  </a>
<?php endforeach ?>

<?php if (!empty($customers_app)): ?>
  <?php foreach ($customers_app as $app_customer): ?>
    <a href="#" class="select_customer" data-id="<?php echo html_escape($app_customer->customer_id) ?>" style="color: #222;">
      <div class="d-flex justify-content-start align-items-center mb-3 cust_box">
        <?php if(!empty($app_customer->thumb)): ?>
          <div class="avatar-xs-pos" style="background-image: url(<?php echo base_url($app_customer->thumb) ?>);"></div>
        <?php else: ?>
          <div class="avatar-xs-pos" style="background-image: url(<?php echo base_url('assets/images/no-photo.png') ?>);"></div>
        <?php endif; ?>
        <div class="ml-3">
          <p class="mb-0 mt-0"><?php echo html_escape($app_customer->name) ?></p>
          <p class="mb-0 mt-0 text-muted fs-13 font-weight-normal"><?php echo html_escape($app_customer->email) ?></p>
        </div>
      </div>
    </a>
  <?php endforeach ?>
<?php endif ?>