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

<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-7 pr-5">
                <div class="image-gallery">
                  <aside class="thumbnails">
                    <?php $p=1; foreach ($product_images as $value): ?>
                        <span  class="<?php if($p==1){echo 'selected';} ?> thumbnail" data-big="<?php echo base_url($value->image) ?>">
                          <div class="thumbnail-image" style="background-image: url(<?php echo base_url($value->image) ?>)"></div>
                        </span>
                    <?php $p++; endforeach ?>
                  </aside>

                  <main class="primary" style="background-image: url(<?php echo base_url($product_images[0]->image) ?>);"></main>
                </div>
                <p class="mt-5"><?php echo $product->details ?></p>
            </div>

            <div class="col-lg-5">
                <div>
                    <h2 class="mb-3 mt-0"><?php echo html_escape($product->title) ?></h24>
                    
                    <h5 class="font-weight-bold text-primary">
                        <?php if($company->curr_locate == 0){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                        <?php echo number_format($product->price, $company->num_format) ?>
                        <?php if($company->curr_locate == 1){echo get_by_id($company->country,'country')->currency_symbol;} ?>
                    </h5>
                    <p class="mb-1 text-dark fs-14 mb-5"><?php echo html_escape($product->short_desc) ?></p>

                    <form id="single_pro_cart_newg" method="post" action="<?php echo base_url('company/add_to_cart/'.$product->id.'/'.$slug); ?>">
                        <?php if($product->quantity>0): ?>
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <div class="input-group qty">
                                          <span class="input-group-btn">
                                              <button type="button" class="quantity-left-minus btn btn-default btn-number"  data-type="minus" data-id="1" data-field="">
                                                <span class="quantity-icon">
                                                    <i class="bi bi-dash"></i>
                                                </span>
                                              </button>
                                          </span>

                                          <input type="text" id="quantity_1" name="quantity" class="form-control border-0 form-control-sm br-4 input-number text-center" value="1" min="1" max="20">

                                          <span class="input-group-btn">
                                              <button type="button" class="quantity-right-plus btn btn-default btn-number" data-type="plus" data-id="1" data-field="">
                                                <span class="quantity-icon">
                                                    <i class="bi bi-plus"></i>
                                                </span>
                                              </button>
                                          </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-7 text-left">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <input type="hidden" name="product_id" value="<?php echo html_escape($product->id); ?>" >
                                    <button type="submit" class="btn btn-primary btn-md mb-4"><?php echo trans('add-to-cart') ?></button>
                                </div>
                            </div>

                        <?php else: ?>
                            <p class="text-danger"><?php echo trans('stock-out') ?></p>
                        <?php endif; ?>
                    </form>
                    <hr class="pb-2">

                    <p class="mb-1 mt-2"><span class="text-dark"><?php echo trans('category')  ?> :</span> <?php echo get_by_id($product->category_id,'product_category')->name; ?></p>
                    <?php if (!empty($product->subcategory_id)): ?>
                        <p><span class="text-dark"><?php echo trans('subcategory') ?> :</span> <?php echo get_by_id($product->subcategory_id,'product_category')->name; ?></p>
                    <?php endif ?>

                    <?php if (!empty($product->tags)): ?>
                    <div class="tags">
                        <div class="h6"><?php echo trans('tags') ?>:
                        <?php $tags = explode(",", $product->tags);  ?>
                        <?php foreach ($tags as $tag): ?>
                            <span  class="badge badge-light badge-small">#<?php echo html_escape($tag) ?></span>
                        <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>

                </div>
            </div>
        </div>
    </div>
</section>


<?php if(!empty($related_products)): ?>
<section class="bg-light mt-0">
    <div class="container ">

        <div class="row mb-5 text-center">
            <div class="col">
                <h3><?php echo trans('related-products') ?></h3>
            </div>
        </div>

        <div class="row">
            <div class="carousel-4 owl-carousel owl-theme navtop">
                <?php foreach ($related_products as $product): ?>
                    <?php include APPPATH.'views/templates/common/product_item_1.php'; ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>