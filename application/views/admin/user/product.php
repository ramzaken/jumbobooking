<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <?php $this->load->view('admin/include/breadcrumb'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
        
        <div class="row">
          <div class="col-md-12">
            <div class="card add_area <?php if(!empty($page_title) && $page_title == "Edit"){echo "d-block";}else{echo "hide";} ?>">
              <div class="card-header with-border">
                <?php if (!empty($page_title) && $page_title == "Edit"): ?>
                  <h3 class="card-title"><?php echo trans('edit') ?></h3>
                <?php else: ?>
                  <h3 class="card-title"><?php echo trans('create-new') ?> </h3>
                <?php endif; ?>

                <div class="card-tools pull-right">
                  <?php if (!empty($page_title) && $page_title == "Edit"): ?>
                    <a href="<?php echo base_url('admin/product') ?>" class="pull-right btn btn-secondary btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a>
                  <?php else: ?>
                    <a href="#" class="text-right btn btn-secondary cancel_btn btn-sm"><?php echo trans('view') ?></a>
                  <?php endif; ?>
                </div>
              </div>

              <?php if (isset($limit) && $limit == TRUE): ?>
                <div class="empty-data text-center card-bodys bg-danger-soft">
                  <h2 class="text-danger"><i class="lni lni-ban"></i></h2>
                  <p class="text-danger fs-14"><?php echo trans('reached-maximum-limit') ?></p>
                </div>
              <?php endif ?>

              <form method="post" enctype="multipart/form-data" class="validate-form <?php if (isset($limit) && $limit == TRUE){echo 'd-none';} ?>" action="<?php echo base_url('admin/product/add')?>" role="form" novalidate>

                <div class="row">
                  <div class="col-md-8 col-sm-12">
                    <div class="card-body">
                      <div class="form-group">
                        <div class="row">
                          <?php if(!empty($product_images)): ?>
                            <?php foreach ($product_images as $product_img): ?>
                              <div id="row_<?php echo html_escape($product_img->id) ?>"  class="col-1 mr-5 product_img mt-4">
                                <div class="product_edit_img " style="background-image: url(<?php echo base_url($product_img->image) ?>);">
                                  <a class="delete_item image_product_remove" data-id="<?php echo html_escape($product_img->id) ?>" href="<?php echo base_url('admin/product/delete_img/' . $product_img->id ) ?>"><b><i class="bi bi-x"></i></b></a>
                                </div>
                              </div>
                            <?php endforeach ?>
                          <?php endif ; ?>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="input-images"></div>
                      </div>
                    
                      <div class="form-group">
                        <label><?php echo trans('title') ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" value="<?php if(!empty($product)){echo html_escape($product->title);} ?>" required>
                      </div>
                  
                      <div class="form-group">
                        <label><?php echo trans('slug') ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="slug" value="<?php if(!empty($product)){echo html_escape($product->slug);} ?>" required>
                      </div>
                
                      <div class="form-group">
                        <label><?php echo trans('short_desc') ?></label>
                        <textarea class="form-control "  name="short_desc" rows="3"><?php  if(!empty($product)){echo html_escape($product->short_desc);} ?></textarea>
                      </div>
                
                      <div class="form-group">
                        <label><?php echo trans('details') ?></label>
                        <textarea class="form-control summernote"  name="details" rows="6"><?php  if(!empty($product)){echo html_escape($product->details);} ?></textarea>
                      </div>
                
                      <div class="form-group">
                        <label><?php echo trans('attributes') ?></label>
                        <textarea class="form-control "  name="attributes" rows="3"><?php  if(!empty($product)){echo html_escape($product->attributes);} ?></textarea>
                      </div>

                      <div class="form-group hide">
                        <div class="custom-control custom-checkbox">
                          <input <?php if(!empty($product->file)){echo 'checked';} ?> class="custom-control-input is_downloadable" type="checkbox" id="customCheckbox2" name="downloadable_product">
                          <label for="customCheckbox2" class="custom-control-label"><?php echo trans('is-downloadable') ?></label>
                        </div>
                      </div>
                    
                      <div class="form-group  file_area <?php if(!empty($product->file)){echo 'd-show';}else{echo 'd-hide';} ?>">
                        <?php if (!empty($product->file)) : ?>
                          <span class="badge badge-primary-soft mt-2"><?php if(!empty($product)){echo html_escape($product->file);} ?></span> <br><br>
                        <?php endif ?>
                        <div class="custom-file w-50 mt-2">
                          <input type="file" class="custom-file-input" name="file" id="customFileUp">
                          <label class="custom-file-label" for="customFileUp"><?php echo trans('upload-file') ?></label>
                        </div>
                      </div>
                    </div>

                    <div class="card-body mt-3">
                      <div class="form-group mb-0">
                        <div class="icheck-primary radio radio-inline d-inline mr-4 mt-2">
                          <input type="radio" id="radioPrimary1" value="1" name="status" <?php if(!empty($product) && $product->status == 1){echo "checked";} ?> <?php if (!empty($page_title) && $page_title == "Edit"){echo "checked";} ?>>
                          <label for="radioPrimary1"> <?php echo trans('enable') ?>
                          </label>
                        </div>

                        <div class="icheck-primary radio radio-inline d-inline">
                          <input type="radio" id="radioPrimary2" value="0" name="status" <?php if(!empty($product) && $product->status == 0){echo "checked";} ?>>
                          <label for="radioPrimary2"> <?php echo trans('disable') ?>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="card-footer">
                      <input type="hidden" name="id" value="<?php if(!empty($product)){echo html_escape($product->id);} ?>">
                      <!-- csrf token -->
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">

                      <?php if (!empty($page_title) && $page_title == "Edit"): ?>
                        <button type="submit" class="btn btn-primary btn-block pull-left"><?php echo trans('save-changes') ?></button>
                      <?php else: ?>
                        <button type="submit" class="btn btn-primary btn-block pull-left"> <?php echo trans('save') ?></button>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="col-md-4 col-sm-12">

                    <div class="card-body">
                      <div class="form-group">
                        <label class="control-label p-0" for="example-input-normal"><?php echo trans('categories') ?> <span class="text-danger">*</span></label>
                        <select class="form-control product_category" name="category_id" required>
                          <option value=""><?php echo trans('select') ?></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo html_escape($category->id); ?>" 
                                  <?php if(!empty($product) && $product->category_id == $category->id){echo 'selected';} ?>> 
                                  <?php echo html_escape($category->name); ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="control-label p-0" for="example-input-normal"><?php echo trans('subcategories') ?> <span class="text-danger">*</span></label>
                        <select class="form-control subcategory" name="subcategory_id"  required>
                            <?php if (isset($page_title) && $page_title == 'Edit'): ?>
                              <option selected value="<?php echo html_escape($product->subcategory_id) ?>"><?php echo get_by_id($product->subcategory_id, 'product_category')->name ?></option>
                            <?php endif; ?>
                        </select>
                      </div>
                    </div>

                    <div class="card-body mt-3">
                     
                      <div class="form-group">
                        <label><?php echo trans('price') ?><span class="text-danger">*</span></label>
                        <div class="input-group">
                          <span class="input-group-text" id="basic-addon1">
                            <?php echo html_escape($this->business->currency_symbol) ?>
                          </span>
                          <input type="text" class="form-control" name="price" value="<?php if(!empty($product)){echo html_escape($product->price);} ?>" required>
                        </div>
                      </div>
                  
                      <div class="form-group d-hide">
                        <label><?php echo trans('old_price') ?></label>
                        <input type="number" class="form-control" name="old_price" value="<?php if(!empty($product)){echo html_escape($product->old_price);} ?>">
                      </div>
                  
                      <div class="form-group d-hide">
                        <label><?php echo trans('sku') ?></label>
                        <input type="text" class="form-control" name="sku" value="<?php if(!empty($product)){echo html_escape($product->sku);} ?>">
                      </div>
                  
                      <div class="form-group">
                        <label><?php echo trans('quantity') ?></label>
                        <input type="number" class="form-control" name="quantity" value="<?php if(!empty($product)){echo html_escape($product->quantity);} ?>" required>
                      </div>
                    </div>

                    <div class="card-body mt-3">
                      <div class="form-group">
                        <label><?php echo trans('tags') ?></label>
                        <input type="text" class="form-control" name="tags" data-role="tagsinput" value="<?php if(!empty($product)){echo html_escape($product->tags);} ?>">
                      </div>

                      <div class="form-group">
                        <label><?php echo trans('meta-tags') ?></label>
                        <input type="text" class="form-control" name="meta_tags" data-role="tagsinput" value="<?php if(!empty($product)){echo html_escape($product->meta_tags);} ?>">
                      </div>

                      <div class="form-group">
                        <label><?php echo trans('meta-desc') ?></label>
                        <textarea class="form-control "  name="meta_desc" rows="3"><?php  if(!empty($product)){echo html_escape($product->meta_desc);} ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <?php if (!empty($page_title) && $page_title != "Edit"): ?>
              <div class="card list_area">
                <div class="card-header with-border">
                  <?php if (!empty($page_title) && $page_title == "Edit"): ?>
                    <h3 class="card-title pt-2"><?php echo trans('edit') ?> <a href="<?php echo base_url('admin/gallery') ?>" class="pull-right btn btn-sm btn-primary btn-sm"><i class="fa fa-angle-left"></i> <?php echo trans('back') ?></a></h3>
                  <?php else: ?>
                    <h3 class="card-title pt-2"><?php echo trans('product') ?> </h3>
                  <?php endif; ?>

                  <div class="card-tools pull-right">
                   <a href="#" class="pull-right btn btn-sm btn-secondary add_btn"><i class="fa fa-plus"></i> <?php echo trans('create-new') ?></a>

                   <a href="#" class="filter-action pull-right btn btn-outline-primary btn-sm"><i class="fas fa-filter"></i></a>
                  </div>
                </div>

                <div class="filter_popup showFilter">
                    <p class="leads mb-3"><?php echo trans('filters') ?></p>

                    <form action="<?php echo base_url('admin/product') ?>" class="sort_form" method="get">
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo trans('') ?></label>
                                <input placeholder="search" class="form-control form-control-sm" type="text" name="product_search" value="<?php if(!empty($_GET['product_search'])){echo html_escape($_GET['product_search']);} ?>">
                            </div>
                            <div class="form-group">
                              <select name="category" class="form-control form-control-sm nice_select small wide">
                                <option value=""><?php echo trans('category') ?></option>
                                <?php foreach ($categories as $category): ?>
                                <option <?php if(isset($_GET['category']) && $_GET['category']==$category->id){echo "selected";} ?> value="<?php if(!empty($category)){echo html_escape($category->id);}?>"><?php echo html_escape($category->name) ?></option>
                                <?php endforeach ?>
                              </select>
                            </div>
                            <a href="<?php echo base_url('admin/product') ?>" class="btn btn-default btn-xs pull-right mt-3"><i class="fas fa-redo-alt"></i> <?php echo trans('reset') ?></a>
                        </div>

                        <div class="col-md-12 mt-3">
                          <button type="submit" class="btn btn-primary btn-sm btn-block"><?php echo trans('submit') ?></button>
                        </div>

                      </div>
                    </form>
                </div>

                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th><?php echo trans('image') ?></th>
                        <th><?php echo trans('title') ?></th>
                        <th><?php echo trans('category') ?></th>
                        <th><?php echo trans('price') ?></th>
                        <th><?php echo trans('quantity') ?></th>
                        <th class="d-hide"><?php echo trans('file') ?></th>
                        <th><?php echo trans('status') ?></th>
                        <th><?php echo trans('action') ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i=1; foreach ($products as $product): ?>
                        <tr id="row_<?php echo html_escape($product->id); ?>">
                          <td><?= $i; ?></td>
                          <td><img class="feature-img" src="<?php echo base_url(get_product_img($product->id,1)->image) ?>"></td>
                          <td><?php echo html_escape($product->title) ?></td>
                          <td>
                            <span class="font-weight-bold"><?php echo get_by_id($product->category_id, 'product_category')->name ?></span><br>
                            <i class="bi bi-arrow-return-right text-muted"></i> <span class="text-muted"><?php echo get_by_id($product->subcategory_id, 'product_category')->name ?></span>
                          </td>
                          <td>
                            <?php if($this->business->curr_locate == 0){echo html_escape($this->business->currency_symbol);} ?>
                            <?php echo number_format($product->price, $this->business->num_format) ?>
                            <?php if($this->business->curr_locate == 1){echo html_escape($this->business->currency_symbol);} ?>  
                          </td>
                          <td>
                            <span class="font-weight-bold"><?php echo html_escape($product->quantity) ?> </span> <?php echo trans('items') ?>
                            <?php if($product->quantity < 1): ?>
                              <p class="text-danger mb-0 fs-12"><?php echo trans('stock-out') ?></p>
                            <?php elseif($product->quantity < 10) : ?> 
                              <p class="text-muted mb-0 fs-12"><?php echo trans('limited-stock') ?></p>
                            <?php endif ?>
                          </td>
                          <td class="d-hide">
                            <a class="badge badge-primary-soft btn-sm" href="<?php echo base_url('admin/product/download/'.html_escape($product->id));?>"><i class="fas fa-download"></i></a>
                            <a target="_blank" class="badge badge-secondary-soft btn-sm" href="<?php echo base_url($product->file) ?>"><i class="fas fa-eye"></i></a>
                          </td>
                          <td>
                            <?php if ($product->status == 1): ?>
                              <span class="badge badge-success"><i class="fas fa-check-circle"></i> <?php echo trans('active') ?></span>
                            <?php else: ?>
                              <span class="badge badge-secondary"><i class="fas fa-eye-slash"></i> <?php echo trans('hidden') ?></span>
                            <?php endif ?>
                          </td>
                          <td class="actions">
                            <div class="btn-group">
                              <button type="button" class="btn btn-tool" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right" role="menu" >
                                <a href="<?php echo base_url('admin/product/edit/'.html_escape($product->id));?>" class="dropdown-item"><?php echo trans('edit') ?></a>

                                <a data-val="Category" data-id="<?php echo html_escape($product->id); ?>" href="<?php echo base_url('admin/product/delete/'.html_escape($product->id));?>" class="dropdown-item delete_item"><?php echo trans('delete') ?></a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php $i++; endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endif; ?>
            <div class="mt-4 ">
              <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
