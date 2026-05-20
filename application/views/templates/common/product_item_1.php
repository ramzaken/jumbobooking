<div class="mb-7">
    <div class="card border-0 rounded-lg shadow-lights h-100 overhidden lifts">
    
        <div class="p-img">
            <a href="<?php echo base_url('product/'). $product->slug .'/'. $slug   ?>">
                <?php foreach ($product->images as  $value): ?>
                    <div class="product-img" style="background-image: url(<?php echo base_url($value->image) ?>);">
                    </div>
                <?php endforeach ?>
            </a>

            <!-- <div class="add_single_cart">
                
            </div> -->
        </div>

        

        <div class="card-body product-bottom-part">
            <div class="text-center mt-3">
                    <p class="mb-1"><a class="fs-18 text-dark hover-link" href="<?php echo base_url('product/'). $product->slug .'/'. $slug   ?>"><?php echo html_escape($product->title) ?></a></p>

                    <p class="mb-0 mt-0 text-primary">
                        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                        <?php echo number_format($product->price, $company->num_format) ?>
                        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                    </p>
            </div>

            <!-- <div class="d-flex justify-content-between mt-5 mb-0">
                <div>
                   
                </div>
                <div>
                    <p class="mb-2 badge badge-secondary-soft"><?php //echo get_by_id($product->category_id,'product_category')->name ?></p>
                </div>
            </div> -->

            <div class="text-center mt-5">
                <?php if ($product->quantity>0): ?>
                    <a class="btn btn-light-secondary btn-sm fs-13" data-id="<?php echo base_url('company/add_to_cart/' . $product->id . '/' . $slug) ?>" href="<?php echo base_url('company/add_to_cart/' . $product->id . '/' . $slug) ?>"><i class="bi bi-cart"></i> <?php echo trans('add-to-cart') ?></a>
                <?php endif ?>
            </div>
            
        </div>
    </div>
</div>