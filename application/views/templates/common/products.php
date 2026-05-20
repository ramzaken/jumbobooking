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

        <div class="row justify-content-center mb-7">
          
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12"> 
                        <form class="sort_form" method="get" action="<?php echo base_url('products/' .$slug)?>">
                            <div class="row pr-3 mt-5">
                                <div class="input-group mb-5 d-hide">
                                    <input type="text" class="form-control form-control-sm" name="product" id="search" placeholder="<?php echo trans('search') ?>" value="<?php if(!empty($_GET['product'])){echo html_escape($_GET['product']);} ?>">
                                </div>

                                <div class="col-md-6"> 
                                    <h4 class="text-dark mb-2"><?php if(isset($products)){echo count($products);} ?> <?php echo trans('products') ?> </h4>
                                </div>

                                <div class="col-md-3"> 
                                    <select class="nice_select sort_front wide small mb-5" name="product_category">
                                        <option value="0"><?php echo trans('all-category') ?></option>
                                        <?php foreach ($categories as $category): ?>
                                        <option <?php if(isset($_GET['product_category']) && $_GET['product_category']==$category->id){echo "selected";} ?> value="<?php if(!empty($category)){echo html_escape($category->id);}?>"><?php echo html_escape($category->name) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="col-md-3 pr-0"> 
                                    <select class="nice_select sort_front wide small mb-5" name="product_price" >
                                        <option><?php echo trans('sort-price') ?></option>
                                        <option <?php if(isset($_GET['product_price'])&& $_GET['product_price']=='ASC'){echo 'selected';} ?> value="ASC"><?php echo trans('low-to-high') ?></option>
                                        <option <?php if(isset($_GET['product_price'])&& $_GET['product_price']=='DESC'){echo 'selected';} ?> value="DESC"><?php echo trans('high-to-low') ?></option>
                                    </select>
                                </div>

                            </div>
                        </form>
                    </div>
                    <?php if(empty($products)): ?>
                        <?php $this->load->view('include/not_found_msg'); ?>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-3">
                                <?php include APPPATH.'views/templates/common/product_item_1.php'; ?>
                            </div>
                        <?php endforeach ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 ">
      <?php echo $this->pagination->create_links(); ?>
    </div>
</section>