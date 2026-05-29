<?php
$site = settings()->site_name;
$url  = base_url();

// ── Starting-point template library ───────────────────────────────────────────
// Each group maps to one of the four top-level template cards.
// 'subject' and 'body' are pre-filled into the form when a row is clicked.
$starting_points = [

    'onboarding' => [
        [
            'label'   => 'Day 0 — Welcome email',
            'day'     => 'Day 0',
            'subject' => "Welcome to {$site}! Here's how to get started",
            'body'    => "<p>Hi {name},</p>
<p>Welcome to <strong>{$site}</strong>! We're excited to have you on board.</p>
<p>Here's what you can do right now:</p>
<ul>
  <li>✅ <strong>Set up your first service</strong> — add what you offer so clients can book you</li>
  <li>✅ <strong>Customize your booking page</strong> — make it yours</li>
  <li>✅ <strong>Share your booking link</strong> — start taking appointments</li>
</ul>
<p><a href='{$url}admin/dashboard' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>Go to Dashboard</a></p>
<p>Have questions? Reply to this email — we're here to help.</p>
<p>The {$site} Team</p>",
        ],
        [
            'label'   => 'Day 1 — Set up your first service',
            'day'     => 'Day 1',
            'subject' => 'Day 1: Add your first bookable service',
            'body'    => "<p>Hi {name},</p>
<p>Day 1! Let's make sure your clients can start booking you.</p>
<p>Head to your dashboard and add your first service — it only takes a minute.</p>
<p><a href='{$url}admin/dashboard' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>Add a Service</a></p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Day 3 — Invite your team',
            'day'     => 'Day 3',
            'subject' => 'Day 3: Bring your whole team onto {$site}',
            'body'    => "<p>Hi {name},</p>
<p>Running a team? You can add staff members so each person has their own schedule and bookings.</p>
<p><a href='{$url}admin/dashboard' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>Manage Team</a></p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Day 7 — Check in: first booking?',
            'day'     => 'Day 7',
            'subject' => 'Day 7: Have you received your first booking?',
            'body'    => "<p>Hi {name},</p>
<p>It's been a week! We wanted to check in — have you received your first booking yet?</p>
<p>If not, here are a few tips to get started:</p>
<ul>
  <li>Share your booking link on social media</li>
  <li>Add the link to your email signature</li>
  <li>Embed your booking page on your website</li>
</ul>
<p><a href='{$url}admin/dashboard' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>View My Booking Page</a></p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Day 14 — Upgrade to unlock more',
            'day'     => 'Day 14',
            'subject' => 'Day 14: Ready to unlock more features?',
            'body'    => "<p>Hi {name},</p>
<p>Two weeks in — great work! Here are some features waiting for you on an upgraded plan:</p>
<ul>
  <li>Unlimited bookings &amp; services</li>
  <li>Custom domain</li>
  <li>Priority support</li>
</ul>
<p><a href='{$url}admin/subscription' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>View Plans</a></p>
<p>– The {$site} Team</p>",
        ],
    ],

    'newsletter' => [
        [
            'label'   => 'Seasonal promotion',
            'subject' => "🌟 Special offer — book now and save with {$site}",
            'body'    => "<p>Hi {name},</p>
<p>For a limited time, we're running a special offer exclusively for our members.</p>
<p><strong>[Describe your offer here — e.g., 20% off all bookings made before DATE]</strong></p>
<p><a href='{$url}' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>Claim Offer</a></p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Monthly product update',
            'subject' => "What's new at {$site} — [Month] update",
            'body'    => "<p>Hi {name},</p>
<p>Here's what we shipped this month:</p>
<ul>
  <li>🆕 <strong>[Feature 1]</strong> — [brief description]</li>
  <li>🛠 <strong>[Improvement]</strong> — [brief description]</li>
  <li>🐛 <strong>[Bug fix]</strong> — [brief description]</li>
</ul>
<p>More updates coming soon. As always, reply to this email with feedback.</p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Holiday promo',
            'subject' => "🎁 A gift from {$site} — [X]% off this holiday season",
            'body'    => "<p>Hi {name},</p>
<p>Season's greetings! To celebrate the holidays, we're giving all members a special discount.</p>
<p>Use code <strong>[HOLIDAYCODE]</strong> at checkout to get <strong>[X]% off</strong> your next plan upgrade.</p>
<p>Offer expires [DATE].</p>
<p><a href='{$url}admin/subscription' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>Upgrade Now</a></p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Quarterly roundup / case study',
            'subject' => "How our members are getting more bookings — Q[X] roundup",
            'body'    => "<p>Hi {name},</p>
<p>Every quarter we look at how our members are using {$site} to grow their business. Here's what we found:</p>
<p><strong>[Share a key stat or user story here]</strong></p>
<p>Want results like these? Here's what you can do today:</p>
<ul>
  <li>[Tip 1]</li>
  <li>[Tip 2]</li>
  <li>[Tip 3]</li>
</ul>
<p>– The {$site} Team</p>",
        ],
    ],

    'news' => [
        [
            'label'   => 'New feature release',
            'subject' => "🚀 New: [Feature] is live — here's how to use it",
            'body'    => "<p>Hi {name},</p>
<p>We just launched <strong>[Feature name]</strong> and we think you're going to love it.</p>
<p><strong>What it does:</strong> [One-sentence explanation]</p>
<p><strong>How to use it:</strong></p>
<ol>
  <li>[Step 1]</li>
  <li>[Step 2]</li>
  <li>[Step 3]</li>
</ol>
<p><a href='{$url}admin/dashboard' style='background:#4e73df;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;display:inline-block;margin-top:8px'>Try It Now</a></p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Scheduled maintenance notice',
            'subject' => "⚠️ Scheduled maintenance on [Date] — brief downtime expected",
            'body'    => "<p>Hi {name},</p>
<p>We're performing scheduled maintenance on <strong>[DATE] from [START TIME] to [END TIME] [TIMEZONE]</strong>.</p>
<p>During this window, {$site} may be temporarily unavailable.</p>
<p>We recommend completing any urgent bookings <strong>before</strong> the maintenance window.</p>
<p>We apologize for any inconvenience and appreciate your patience.</p>
<p>– The {$site} Team</p>",
        ],
        [
            'label'   => 'Important account update',
            'subject' => "Important update to your {$site} account",
            'body'    => "<p>Hi {name},</p>
<p>We're writing to let you know about an important change to your {$site} account.</p>
<p><strong>[Describe the change clearly and concisely]</strong></p>
<p>If you have any questions, reply to this email or visit our help centre.</p>
<p>– The {$site} Team</p>",
        ],
    ],

    'custom' => [],
];
?>
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
                            <div class="card-header">
                                <strong>Campaign details</strong>
                            </div>
                            <div class="card-body">

                                <!-- Template type cards -->
                                <p class="mb-2 text-muted small font-weight-bold text-uppercase" style="letter-spacing:.05em">Start from a template <span class="font-weight-normal">(optional — or write from scratch below)</span></p>
                                <div class="row text-center mb-0" id="tpl-cards">
                                    <?php
                                    $tpl_types = [
                                        ['slug'=>'onboarding', 'icon'=>'bi-mortarboard-fill',  'label'=>'Onboarding Drip',    'desc'=>'Short action-focused emails in the first days after signup.'],
                                        ['slug'=>'newsletter', 'icon'=>'bi-newspaper',          'label'=>'Newsletter / Promo', 'desc'=>'Promotions, product updates, seasonal campaigns.'],
                                        ['slug'=>'news',       'icon'=>'bi-exclamation-triangle-fill', 'label'=>'News &amp; Alert',    'desc'=>'High-signal announcements — new features, critical updates.'],
                                        ['slug'=>'custom',     'icon'=>'bi-pencil-square',      'label'=>'Custom / Blank',     'desc'=>'Write your own subject and body from scratch.'],
                                    ];
                                    ?>
                                    <?php foreach ($tpl_types as $t): ?>
                                    <div class="col-6 col-md-3 mb-3">
                                        <div class="card border tpl-type-card h-100"
                                             style="cursor:pointer;transition:border-color .15s"
                                             data-slug="<?php echo $t['slug'] ?>">
                                            <div class="card-body py-3 px-2">
                                                <i class="bi <?php echo $t['icon'] ?> d-block mb-1 text-secondary" style="font-size:1.8rem"></i>
                                                <strong style="font-size:.85rem"><?php echo $t['label'] ?></strong>
                                                <p class="small text-muted mb-0 mt-1" style="font-size:.75rem"><?php echo $t['desc'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach ?>
                                </div>

                                <!-- Starting-point list (changes per template type) -->
                                <div id="starting-points-wrap" class="d-none mt-1 mb-2">
                                    <p class="mb-2 text-muted small font-weight-bold text-uppercase" style="letter-spacing:.05em">Pick a starting point</p>
                                    <div class="list-group" id="starting-points-list"></div>
                                </div>

                            </div>
                        </div>
                        <?php endif ?>

                        <!-- Body editor card -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header"><strong><?php echo empty($campaign) ? 'Campaign details' : 'Edit Campaign' ?></strong></div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Subject <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" id="field-subject" class="form-control" required
                                           placeholder="e.g. Special offer just for you"
                                           value="<?php echo html_escape($campaign->subject ?? '') ?>">
                                </div>

                                <div class="form-group mb-1">
                                    <label>Email body (HTML or plain text) <span class="text-danger">*</span></label>
                                    <textarea name="body" id="campaign_body" class="form-control" rows="16"
                                              style="font-family:monospace;font-size:13px"><?php echo html_escape($campaign->body ?? '') ?></textarea>
                                    <small class="text-muted">
                                        Available placeholders: <code>{name}</code> = recipient name &nbsp;·&nbsp; <code>{email}</code> = recipient email
                                    </small>
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
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <label class="btn btn-outline-secondary btn-sm mb-0" style="cursor:pointer">
                                            <i class="bi bi-upload"></i> Import CSV
                                            <input type="file" id="csv_import" accept=".csv,text/csv" hidden>
                                        </label>
                                        <a href="#" id="csv_sample" class="small"><i class="bi bi-download"></i> Sample CSV</a>
                                    </div>
                                    <small id="csv_status" class="text-success d-block mt-1"></small>
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
                                    <label class="btn btn-outline-secondary <?php if($stype==$sv) echo 'active'; ?>" style="cursor:pointer">
                                        <input type="radio" name="schedule_type" value="<?php echo $sv ?>"
                                               class="sched-radio" <?php if($stype==$sv) echo 'checked'; ?> style="display:none">
                                        <?php echo $sl ?>
                                    </label>
                                    <?php endforeach ?>
                                </div>

                                <div id="sched_once" class="<?php if($stype!='once') echo 'd-none'; ?>">
                                    <label class="small">Date &amp; Time</label>
                                    <input type="datetime-local" name="scheduled_at" class="form-control form-control-sm"
                                           value="<?php echo $campaign->scheduled_at ? date('Y-m-d\TH:i', strtotime($campaign->scheduled_at)) : '' ?>">
                                </div>

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
// ── Template data (injected from PHP) ─────────────────────────────────────────
var STARTING_POINTS = <?php echo json_encode($starting_points, JSON_HEX_TAG | JSON_HEX_APOS); ?>;

// ── Template type card click ───────────────────────────────────────────────────
var activeSlug = null;

document.querySelectorAll('.tpl-type-card').forEach(function(card) {
    card.addEventListener('click', function() {
        var slug = this.dataset.slug;

        // Toggle: click the same card again to deselect
        if (activeSlug === slug) {
            activeSlug = null;
            document.querySelectorAll('.tpl-type-card').forEach(function(c) {
                c.classList.remove('border-primary');
                c.querySelector('i').classList.remove('text-primary');
                c.querySelector('i').classList.add('text-secondary');
            });
            document.getElementById('starting-points-wrap').classList.add('d-none');
            return;
        }

        activeSlug = slug;

        // Highlight selected card
        document.querySelectorAll('.tpl-type-card').forEach(function(c) {
            var isThis = c.dataset.slug === slug;
            c.classList.toggle('border-primary', isThis);
            c.classList.toggle('shadow', isThis);
            c.querySelector('i').classList.toggle('text-primary', isThis);
            c.querySelector('i').classList.toggle('text-secondary', !isThis);
        });

        var points = STARTING_POINTS[slug] || [];
        var wrap   = document.getElementById('starting-points-wrap');
        var list   = document.getElementById('starting-points-list');

        if (points.length === 0) {
            // Custom / blank — hide starting points, clear fields
            wrap.classList.add('d-none');
            document.getElementById('field-subject').value = '';
            document.getElementById('campaign_body').value = '';
            return;
        }

        // Render rows
        list.innerHTML = '';
        points.forEach(function(pt) {
            var row = document.createElement('a');
            row.href = '#';
            row.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2';
            row.innerHTML =
                '<span>' +
                    (pt.day ? '<span class="badge badge-secondary mr-2" style="font-size:.7rem">' + pt.day + '</span>' : '') +
                    '<strong style="font-size:.9rem">' + pt.label + '</strong>' +
                '</span>' +
                '<small class="text-muted text-truncate ml-3" style="max-width:220px">' + pt.subject + '</small>';

            row.addEventListener('click', function(e) {
                e.preventDefault();
                // Highlight row
                list.querySelectorAll('a').forEach(function(r) { r.classList.remove('active'); });
                this.classList.add('active');
                // Fill form fields
                document.getElementById('field-subject').value = pt.subject;
                document.getElementById('campaign_body').value = pt.body;
            });

            list.appendChild(row);
        });

        wrap.classList.remove('d-none');
    });
});

// ── Recipient type toggle ──────────────────────────────────────────────────────
document.querySelectorAll('.recipient-type').forEach(function(el) {
    el.addEventListener('change', function() {
        document.getElementById('plan_selector').classList.toggle('d-none', this.value !== 'plan');
        document.getElementById('manual_emails').classList.toggle('d-none', this.value !== 'manual');
    });
});

// ── Schedule type toggle ───────────────────────────────────────────────────────
document.querySelectorAll('.sched-radio').forEach(function(el) {
    el.addEventListener('change', function() {
        document.getElementById('sched_once').classList.toggle('d-none', this.value !== 'once');
        document.getElementById('sched_recurring').classList.toggle('d-none', this.value !== 'recurring');
        document.querySelectorAll('.sched-radio').forEach(function(r) {
            r.closest('label').classList.toggle('active', r.checked);
        });
    });
});

// ── Recurring day-of-week checkbox active state ───────────────────────────────
document.querySelectorAll('#sched_recurring input[type=checkbox]').forEach(function(cb) {
    cb.addEventListener('change', function() {
        this.closest('label').classList.toggle('active', this.checked);
    });
});

// ── Manual list: CSV import + sample download ─────────────────────────────────
(function() {
    var fileInput  = document.getElementById('csv_import');
    var sampleLink = document.getElementById('csv_sample');
    var statusEl   = document.getElementById('csv_status');
    var textarea   = document.querySelector('#manual_emails textarea');
    if (!fileInput || !textarea) return;

    var EMAIL_RE = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i;

    fileInput.addEventListener('change', function() {
        var file = this.files[0];
        this.value = ''; // allow re-importing the same file
        if (!file) return;

        var reader = new FileReader();
        reader.onload = function(e) {
            var text  = e.target.result || '';
            var found = [];
            text.split(/\r\n|\r|\n/).forEach(function(line) {
                if (!line.trim()) return;
                // grab the first email-looking value in the row (handles email-only,
                // name,email or email,name — any delimiter)
                line.split(/[,;\t]/).some(function(cell) {
                    var m = cell.match(EMAIL_RE);
                    if (m) { found.push(m[0].trim()); return true; }
                    return false;
                });
            });

            // merge with whatever is already in the box, dedupe case-insensitively
            var existing = textarea.value.split(/\n/).map(function(s){ return s.trim(); }).filter(Boolean);
            var seen = {}, merged = [];
            existing.concat(found).forEach(function(em) {
                var k = em.toLowerCase();
                if (em && !seen[k]) { seen[k] = 1; merged.push(em); }
            });

            textarea.value = merged.join('\n');
            statusEl.textContent = found.length
                ? 'Imported ' + found.length + ' email(s) · ' + merged.length + ' total.'
                : 'No valid email addresses found in that file.';
            statusEl.className = (found.length ? 'text-success' : 'text-danger') + ' d-block mt-1';
        };
        reader.readAsText(file);
    });

    if (sampleLink) {
        sampleLink.addEventListener('click', function(e) {
            e.preventDefault();
            var csv = 'email,name\n'
                    + 'john@example.com,John Smith\n'
                    + 'jane@example.com,Jane Doe\n'
                    + 'no-name@example.com,\n';
            var blob = new Blob([csv], { type: 'text/csv' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'email-list-sample.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(a.href);
        });
    }
})();
</script>
