<?php if($template == 2): ?>
    <?php include APPPATH.'views/include/banner2.php'; ?>
<?php endif; ?>

<section class="bg-light p-6">
    <div class="container">
        <div class="rows d-flex justify-content-center hide-xs">
            <h2 class="pt-2"><?php echo trans(str_slug($page_title))?></h2>
        </div>
    </div>
</section>

<section class="h-100 h-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12">
        <div class="card card-registration card-registration-2 overhidden">
          <div class="card-body p-0">
            <div class="row g-0">
              <div class="col-lg-8">
                <div class="p-5">
                  <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="fw-bold mb-0 text-black"><?php echo trans('shopping-cart') ?></h3>
                    <?php if ($this->cart->total_items() != 0): ?>
                      <h6 class="mb-0 text-muted"><?php echo html_escape($this->cart->total_items()) ?> <?php echo trans('items') ?></h6>
                    <?php endif ?>
                  </div>
                  
                  <hr class="my-4">

                  <?php if ($this->cart->contents()): ?>
                    <div class="table-responsive">
                      <table class="table">
                          <thead>
                            <tr>
                              <th scope="col"><?php echo trans('product') ?></th>
                              <th scope="col"></th>
                              <th scope="col"><?php echo trans('qty') ?></th>
                              <th scope="col"><?php echo trans('price') ?></th>
                              <th scope="col"></th>
                            </tr>
                          </thead>

                        
                          <form method="post" action="<?php echo base_url('company/update_cart') ?>">
                            <tbody>
                              <?php foreach ($this->cart->contents() as $item): ?>
                                <tr id="row_<?php echo html_escape($item['rowid']) ?>" >
                                  
                                    <td width="20%">
                                      <img class="img-fluid w-80 br-4" title="<?php echo html_escape($item['name']); ?>" alt="<?php echo html_escape($item['name']); ?>" src="<?php echo base_url($item['thumb']) ?>">
                                    </td>

                                    <td class="text-left">
                                      <p class="text-dark mb-1"><?php echo html_escape($item['name']) ?></p>
                                      <p class="text-black mb-0">
                                        <span><?php echo html_escape($item['qty']);?></span> x 
                                        <span>
                                          <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                          <?php echo number_format($item['price'], $company->num_format) ?>
                                          <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                        </span>
                                      </p>
                                    </td>

                                    <td>
                                        <input type="number" name="qty[]" class="form-control form-control-sm br-4 input-number text-center" value="<?php echo html_escape($item['qty']) ?>" min="1" max="50">

                                        
                                    </td>

                                    <td>
                                      <p class="mb-0 text-dark">
                                        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                        <?php echo number_format($item['qty']*$item['price'], $company->num_format) ?>
                                        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                                      </p>
                                    </td>

                                    <td class="text-right">
                                      <input type="hidden" value="<?php echo html_escape($item['rowid']);?>" name="items[]">

                                      <a href="<?php echo base_url('company/remove_cart_item/'.$item['rowid']) ?>" data-id="<?php echo html_escape($item['rowid']) ?>" class="delete_item btn btn-sm btn-light-danger"><i class="bi bi-x-circle"></i></a>
                                    </td>
                                  
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                         
                            <tfoot>
                              <tr>
                                <td colspan="5">
                                  <div class="d-flex justify-content-between mt-4">
                                    <div class="text-left">
                                      <a href="<?php echo base_url('products/'.$slug);?>" class="btn btn-light-secondary btn-sm mr-2"><i class="fas fa-long-arrow-alt-left me-2"></i> <?php echo trans('continue') ?></a>
                                    </div>

                                    <div class="text-right">
                                      <!-- csrf token -->
                                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                        <button type="submit" class="btn btn-light-secondary btn-sm mr-2"><i class="bi bi-check-circle-fill"></i> <?php echo trans('update-cart') ?></button>
                                      <a href="#" class="btn btn-light-danger btn-sm clear_cart"><i class="bi bi-trash"></i> <?php echo trans('clear-cart') ?></a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tfoot>

                          </form>

                      </table>
                    </div>
                  <?php else: ?>
                    <div class="row d-flex justify-content-between align-items-center">
                      <div class="col-md-12">
                          <p class="pt-3"><i class="bi bi-info-circle"></i> <?php echo trans('your-shopping-cart-is-empty') ?></p>
                      </div>
                    </div>
                  <?php endif; ?>

                </div>
              </div>

              <!-- include sidebar -->
              <?php $this->load->view('include/cart_sidebar'); ?>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>