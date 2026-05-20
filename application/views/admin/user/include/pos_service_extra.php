
<div class="col-md-12 single_service_extra_<?php echo html_escape($service_extra->id) ?>">
  <div class="d-flex justify-content-between align-items-center">
    <div class=" mb-2 mt-0">
      <p class="text-dark-75 h6 mt-0 mb-0"> <?php echo $service_extra->name  ?></p>

      <p class="text-muted mt-0 mb-0">
        <i class="bi bi-clock"></i> <?php echo $service_extra->duration  ?> <?php echo trans($service_extra->duration_type) ?>
      </p>
    </div>
    
    <div class="mb-0 mt-0 pr-5">
      <span class="text-muted">
      <?php if($company->curr_locate == 0){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
      <?php echo number_format($service_extra->price, $this->business->num_format) ?>
      <?php if($this->business->curr_locate == 1){echo get_currency_by_country($this->business->country)->currency_symbol;} ?>
      </span>
    </div>

    <input type="hidden" class="check_extra_<?php echo html_escape($service_extra->id) ?>" value="0">

    

    
  </div>
</div>