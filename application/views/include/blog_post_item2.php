<div class="col-md-6 col-lg-4 mb-4 mb-md-5 mb-lg-0 mt-6 lift-sm">
    <article class="cards shadow-hovers h-100" data-aos="fade-up" data-aos-delay="<?= $b * 100; ?>"> 
        <a href="<?php echo base_url('post/'.$post->slug) ?>">
            <div class="blog-cardimg" style="background-image: url(<?php echo base_url($post->image) ?>);"></div>
        </a>

        <div class="card-body px-0">
            <div class="d-flex justify-content-start align-items-center mb-1">
                <span class="mr-3 fs-16"><i class="bi bi-folder"></i> <?php echo character_limiter($post->category, 12) ?></span>
                <span class=" fs-16"><i class="bi bi-clock"></i> <?php echo my_date_show($post->created_at) ?></span>
            </div>
            
            <a class="text-dark" href="<?php echo base_url('post/'.$post->slug) ?>">
                <h3 class="h4 mb-0 blog-title">
                    <?php echo html_escape($post->title) ?>
                </h3>
            </a>
        </div>
    </article>
</div>
