<div class="content-wrapper">
    <?php $this->load->view('admin/include/breadcrumb'); ?>

    <div class="content">
        <div class="container-fluid">

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif ?>

            <form method="post" action="">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="row">

                    <!-- Left column -->
                    <div class="col-lg-8">

                        <!-- Template picker (new campaigns only) -->
                        <?php if (empty($campaign)): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header"><strong>Start from a template</strong> <small class="text-muted">— or write from scratch below</small></div>
                            <div class="card-body pb-2">
                                <div class="row text-center mb-3">
                                    <?php
                                    $templates = [
                                        ['slug'=>'newsletter', 'icon'=>'bi-newspaper',       'label'=>'Newsletter / Promo',
                                         'desc'=>'Promotions, product updates, seasonal campaigns.',
                                         'subject'=>'Special offer just for you',
                                         'body'=>"<p>Hi {name},</p>\n<p>We have an exciting offer for you. Check out our latest plans and save big!</p>\n<p>Visit us at <a href='".base_url()."'>".base_url()."</a></p>"],
                                        ['slug'=>'news',       'icon'=>'bi-megaphone',        'label'=>'News & Alert',
                                         'desc'=>'High-signal announcements — new features, updates.',
                                         'subject'=>'Important update from '.settings()->site_name,
                                         'body'=>"<p>Hi {name},</p>\n<p>We wanted to let you know about an important update.</p>\n<p>[Write your news here]</p>"],
                                        ['slug'=>'custom',     'icon'=>'bi-pencil-square',    'label'=>'Custom / Blank',
                                         'desc'=>'Write your own subject and body from scratch.',
                                         'subject'=>'',
                                         'body'=>''],
                                    ];
                                    ?>
                                    <?php foreach ($templates as $tpl): ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="card border tpl-card h-100 cursor-pointer"
                                             style="cursor:pointer"
                                             data-subject="<?php echo html_escape($tpl['subject']) ?>"
                                             data-body="<?php echo html_escape($tpl['body']) ?>">
                                            <div class="card-body py-3">
                                                <i class="bi <?php echo $tpl['icon'] ?> fs-3 d-block mb-1" style="font-size:1.6rem"></i>
                                                <strong><?php echo $tpl['label'] ?></strong>
                                                <p class="small text-muted mb-0 mt-1"><?php echo $tpl['desc'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                        <?php endif ?>

                        <!-- Campaign details -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header"><strong>Campaign details</strong></div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control" required
                                           placeholder="e.g. Special offer just for you"
                                           value="<?php echo html_escape($campaign->subject ?? '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Email body (HTML or plain text) <span class="text-danger">*</span></label>
                                    <textarea name="body" id="campaign_body" class="form-control" rows="14" style="font-family:monospace;font-size:13px"><?php echo html_escape($campaign->body ?? '') ?></textarea>
                                    <small class="text-muted">Available placeholders: <code>{name}</code> = recipient name, <code>{email}</code> = recipient email</small>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- Right column -->
                    <div class="col-lg-4">

                        <!-- Recipients -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header"><strong>Recipients</strong></div>
                            <div class="card-body">
                                <?php $rtype = $campaign->recipient_type ?? 'all'; ?>

                                <div class="form-check mb-2">
                                    <input class="form-check-input recipient-type" type="radio" name="recipient_type" value="all" id="rt_all" <?php if($rtype=='all') echo 'checked'; ?>>
                                    <label class="form-check-label" for="rt_all">All verified users</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input recipient-type" type="radio" name="recipient_type" value="plan" id="rt_plan" <?php if($rtype=='plan') echo 'checked'; ?>>
                                    <label class="form-check-label" for="rt_plan">Users on a specific plan</label>
                                </div>
                                <div id="plan_selector" class="ml-3 mb-2 <?php if($rtype!='plan') echo 'd-none'; ?>">
                                    <select name="plan_id" class="form-control form-control-sm">
                                        <option value="">— Select plan —</option>
                                        <?php foreach ($packages as $pkg): ?>
                                            <option value="<?php echo $pkg->id ?>"
                                                <?php if(($campaign->plan_id ?? '') == $pkg->id) echo 'selected'; ?>>
                                                <?php echo html_escape($pkg->name) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input recipient-type" type="radio" name="recipient_type" value="manual" id="rt_manual" <?php if($rtype=='manual') echo 'checked'; ?>>
                                    <label class="form-check-label" for="rt_manual">Manual email list</label>
                                </div>
                                <div id="manual_emails" class="<?php if($rtype!='manual') echo 'd-none'; ?>">
                                    <textarea name="manual_emails" class="form-control form-control-sm" rows="4"
                                              placeholder="One email per line"><?php echo html_escape($campaign->manual_emails ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header"><strong>Schedule</strong></div>
                            <div class="card-body">
                                <?php $stype = $campaign->schedule_type ?? 'none'; ?>

                                <div class="btn-group btn-group-sm w-100 mb-3" role="group">
                                    <?php foreach (['none'=>'No schedule','once'=>'One-time','recurring'=>'Recurring'] as $sv => $sl): ?>
                                    <label class="btn btn-outline-secondary <?php if($stype==$sv) echo 'active'; ?>"
                                           style="cursor:pointer">
                                        <input type="radio" name="schedule_type" value="<?php echo $sv ?>"
                                               class="sched-radio" <?php if($stype==$sv) echo 'checked'; ?> style="display:none">
                                        <?php echo $sl ?>
                                    </label>
                                    <?php endforeach ?>
                                </div>

                                <!-- One-time -->
                                <div id="sched_once" class="<?php if($stype!='once') echo 'd-none'; ?>">
                                    <label class="small">Date &amp; Time</label>
                                    <input type="datetime-local" name="scheduled_at" class="form-control form-control-sm"
                                           value="<?php echo $campaign->scheduled_at ? date('Y-m-d\TH:i', strtotime($campaign->scheduled_at)) : '' ?>">
                                </div>

                                <!-- Recurring -->
                                <div id="sched_recurring" class="<?php if($stype!='recurring') echo 'd-none'; ?>">
                                    <div class="form-group mb-2">
                                        <label class="small">Send time</label>
                                        <input type="time" name="recur_time" class="form-control form-control-sm"
                                               value="<?php echo html_escape($campaign->recur_time ?? '09:00') ?>">
                                    </div>
                                    <label class="small d-block mb-1">On these days</label>
                                    <div class="btn-group btn-group-sm flex-wrap" role="group">
                                        <?php
                                        $day_opts = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'];
                                        $sel_days = explode(',', $campaign->recur_days ?? '');
                                        foreach ($day_opts as $dv => $dl):
                                        ?>
                                        <label class="btn btn-outline-secondary <?php if(in_array($dv,$sel_days)) echo 'active'; ?>" style="cursor:pointer">
                                            <input type="checkbox" name="recur_days[]" value="<?php echo $dv ?>"
                                                   <?php if(in_array($dv,$sel_days)) echo 'checked'; ?>
                                                   style="display:none"> <?php echo $dl ?>
                                        </label>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card shadow-sm mb-3">
                            <div class="card-header"><strong>Status</strong></div>
                            <div class="card-body">
                                <select name="status" class="form-control">
                                    <?php foreach (['draft'=>'Draft','sending'=>'Active / Sending','paused'=>'Paused'] as $sv => $sl): ?>
                                        <option value="<?php echo $sv ?>" <?php if(($campaign->status??'draft')==$sv) echo 'selected'; ?>><?php echo $sl ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <?php echo empty($campaign) ? 'Create Campaign' : 'Save Changes' ?>
                        </button>
                        <a href="<?php echo base_url('admin/email_campaigns') ?>" class="btn btn-light btn-block">Cancel</a>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
// Recipient type toggle
document.querySelectorAll('.recipient-type').forEach(function(el) {
    el.addEventListener('change', function() {
        document.getElementById('plan_selector').classList.toggle('d-none', this.value !== 'plan');
        document.getElementById('manual_emails').classList.toggle('d-none', this.value !== 'manual');
    });
});

// Schedule type toggle
document.querySelectorAll('.sched-radio').forEach(function(el) {
    el.addEventListener('change', function() {
        document.getElementById('sched_once').classList.toggle('d-none', this.value !== 'once');
        document.getElementById('sched_recurring').classList.toggle('d-none', this.value !== 'recurring');
        // Update btn-group active state
        document.querySelectorAll('.sched-radio').forEach(function(r) {
            r.closest('label').classList.toggle('active', r.checked);
        });
    });
});

// Template picker
document.querySelectorAll('.tpl-card').forEach(function(card) {
    card.addEventListener('click', function() {
        document.querySelectorAll('.tpl-card').forEach(function(c) { c.classList.remove('border-primary'); });
        this.classList.add('border-primary');
        var subj = this.dataset.subject;
        var body = this.dataset.body;
        if (subj) document.querySelector('[name=subject]').value = subj;
        if (body) document.getElementById('campaign_body').value = body.replace(/&#039;/g, "'").replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    });
});

// Day checkbox toggle active class
document.querySelectorAll('#sched_recurring input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        this.closest('label').classList.toggle('active', this.checked);
    });
});
</script>
