<section class="pt-20">
    <div class="container">
        <div class="row mb-200 mt-200">
            <div class="col-md-12 text-center">
                <div class="payment-msg">
                    <?php if (isset($type) && $type=='Success'): ?>
                        <h4 class="text-success mb-0"><i class="icon-check"></i><br> <?php echo trans('success') ?></h4>
                        <p class="text-success mb-0"><?php echo trans('payment-completed-successfully') ?></p><br>
                        <a href="<?php echo base_url('customer/orders') ?>"
                            class="btn btn-sm btn-light-secondary">Continue <i class="fas fa-long-arrow-alt-right"></i></a>
                    <?php endif; ?>
                    <?php if (isset($type) && $type=='Failed'): ?>
                        <h4 class="text-danger mb-0"><i class="icon-close"></i><br> </h4>
                        <p class="text-danger mb-0"><?php echo trans('your-payment-has-been-failed') ?></p><br>
                        <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-sm btn-secondary">Try
                            again <i class="fas fa-long-arrow-alt-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</seciton>