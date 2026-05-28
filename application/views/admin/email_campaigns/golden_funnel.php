<?php
// Funnel step definitions
$steps = [
    ['key' => 'sent',       'label' => 'Send mail',   'color' => '#6f42c1'],
    ['key' => 'opened',     'label' => 'Open mail',   'color' => '#0d6efd'],
    ['key' => 'clicked',    'label' => 'Click mail',  'color' => '#0dcaf0'],
    ['key' => 'visited',    'label' => 'Visit page',  'color' => '#6c757d'],
    ['key' => 'registered', 'label' => 'Register',    'color' => '#fd7e14'],
    ['key' => 'paid',       'label' => 'Subscribe',   'color' => '#198754'],
];

$values = array_values(array_map(fn($s) => $funnel[$s['key']], $steps));

// Overall conversion = paid / sent
$overall_pct = ($funnel['sent'] > 0)
    ? number_format($funnel['paid'] / $funnel['sent'] * 100, 2)
    : '0.00';
?>
<div class="content-wrapper">
    <?php $this->load->view('admin/include/breadcrumb'); ?>

    <div class="content">
        <div class="container-fluid">

            <!-- Header row -->
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <div>
                    <a href="<?php echo base_url('admin/email_campaigns') ?>" class="text-muted small">
                        <i class="fas fa-arrow-left"></i> Email Campaigns
                    </a>
                    <h4 class="mb-0 mt-1"><i class="fas fa-filter text-warning mr-2"></i>Golden Funnel</h4>
                </div>

                <!-- Period filter -->
                <form method="get" class="d-flex align-items-center" style="gap:8px">
                    <span class="text-muted small mr-1"><i class="far fa-calendar-alt"></i> Period:</span>
                    <input type="date" name="from" class="form-control form-control-sm" value="<?php echo html_escape($date_from) ?>">
                    <span class="text-muted">–</span>
                    <input type="date" name="to" class="form-control form-control-sm" value="<?php echo html_escape($date_to) ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    <a href="<?php echo base_url('admin/email_campaigns/golden_funnel') ?>" class="btn btn-sm btn-light">Reset</a>
                </form>
            </div>

            <!-- Period + overall -->
            <div class="d-flex align-items-center mb-4 text-muted small">
                <i class="far fa-clock mr-1"></i>
                Period: <strong class="ml-1 mr-1"><?php echo date('d M Y', strtotime($date_from)) ?> – <?php echo date('d M Y', strtotime($date_to)) ?></strong>
                &nbsp;·&nbsp; Overall conversion:
                <strong class="ml-1 <?php echo $overall_pct > 0 ? 'text-success' : 'text-muted' ?>">
                    <?php echo $overall_pct ?>%
                </strong>
            </div>

            <!-- Funnel step cards -->
            <div class="row mb-4">
                <?php foreach ($steps as $i => $step): ?>
                <?php
                    $val  = $funnel[$step['key']];
                    $prev = $i > 0 ? $values[$i - 1] : null;
                    $pct_from_prev = ($prev && $prev > 0) ? round($val / $prev * 100, 2) : null;
                    $delta = ($prev !== null) ? ($val - $prev) : null;
                ?>
                <div class="col-6 col-md-4 col-xl-2 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body py-3 px-3">
                            <p class="text-muted mb-1" style="font-size:.7rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase">
                                STEP <?php echo $i + 1 ?>
                            </p>
                            <h3 class="mb-0" style="font-size:1.7rem;font-weight:700;color:<?php echo $step['color'] ?>">
                                <?php echo number_format($val) ?>
                            </h3>
                            <p class="text-muted mb-2" style="font-size:.82rem"><?php echo $step['label'] ?></p>
                            <?php if ($pct_from_prev !== null): ?>
                            <div style="font-size:.75rem;color:<?php echo $pct_from_prev >= 50 ? '#198754' : ($pct_from_prev >= 10 ? '#fd7e14' : '#dc3545') ?>">
                                <?php echo $pct_from_prev >= 50 ? '↑' : '↓' ?>
                                <?php echo $pct_from_prev ?>% from prev
                            </div>
                            <?php endif ?>
                            <!-- Mini bar -->
                            <div class="mt-2" style="height:4px;background:#f0f0f0;border-radius:2px;overflow:hidden">
                                <?php $bar_w = ($values[0] > 0) ? round($val / $values[0] * 100) : 0; ?>
                                <div style="width:<?php echo $bar_w ?>%;height:100%;background:<?php echo $step['color'] ?>;transition:width .4s"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>

            <!-- Drop-off table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header"><strong>Drop-off between stages</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Stage</th>
                                <th>Lost</th>
                                <th>Conversion rate</th>
                                <th style="width:35%">Visual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stage_pairs = [
                                ['Sent → Opened',      'sent',       'opened'],
                                ['Opened → Clicked',   'opened',     'clicked'],
                                ['Clicked → Visited',  'clicked',    'visited'],
                                ['Visited → Registered','visited',   'registered'],
                                ['Registered → Paid',  'registered', 'paid'],
                                ['Overall (Sent → Paid)','sent',     'paid'],
                            ];
                            foreach ($stage_pairs as $pair):
                                $from_val = $funnel[$pair[1]];
                                $to_val   = $funnel[$pair[2]];
                                $lost     = $to_val - $from_val;
                                $conv_pct = $from_val > 0 ? round($to_val / $from_val * 100, 2) : 0;
                                $bar_pct  = min(100, $conv_pct);
                                $is_overall = $pair[0] === 'Overall (Sent → Paid)';
                                $bar_color  = $conv_pct >= 50 ? '#198754' : ($conv_pct >= 10 ? '#0d6efd' : '#6c757d');
                            ?>
                            <tr <?php if ($is_overall): ?>style="border-top:2px solid #dee2e6;font-weight:600"<?php endif ?>>
                                <td><?php echo $pair[0] ?></td>
                                <td class="text-danger"><?php echo number_format($lost) ?></td>
                                <td><?php echo $conv_pct ?>%</td>
                                <td>
                                    <div style="background:#f0f0f0;border-radius:3px;height:8px;overflow:hidden">
                                        <div style="width:<?php echo $bar_pct ?>%;height:100%;background:<?php echo $bar_color ?>"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Daily breakdown -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Daily Breakdown</strong>
                    <?php if (!empty($daily)): ?>
                    <button id="export-daily-csv" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                    <?php endif ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" id="daily-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Sent</th>
                                    <th>Opened</th>
                                    <th>Clicked</th>
                                    <th>Visited</th>
                                    <th>Registered</th>
                                    <th>Paid</th>
                                    <th>Overall %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($daily)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No data in this period.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($daily as $d): ?>
                                    <?php
                                        $d_overall = ($d->sent > 0) ? number_format($d->paid / $d->sent * 100, 2) : '0.00';
                                        $d_open_pct   = $d->sent > 0 ? number_format($d->opened / $d->sent * 100, 2) : '0.00';
                                        $d_click_pct  = $d->sent > 0 ? number_format($d->clicked / $d->sent * 100, 2) : '0.00';
                                        $d_visit_pct  = $d->sent > 0 ? number_format($d->visited / $d->sent * 100, 2) : '0.00';
                                        $d_reg_pct    = $d->sent > 0 ? number_format($d->registered / $d->sent * 100, 2) : '0.00';
                                    ?>
                                    <tr>
                                        <td><?php echo date('Y-m-d', strtotime($d->date)) ?></td>
                                        <td><?php echo number_format($d->sent) ?></td>
                                        <td>
                                            <?php echo number_format($d->opened) ?>
                                            <br><small class="text-muted"><?php echo $d_open_pct ?>%</small>
                                        </td>
                                        <td>
                                            <?php echo number_format($d->clicked) ?>
                                            <br><small class="text-muted"><?php echo $d_click_pct ?>%</small>
                                        </td>
                                        <td>
                                            <?php echo number_format($d->visited) ?>
                                            <br><small class="text-muted"><?php echo $d_visit_pct ?>%</small>
                                        </td>
                                        <td>
                                            <?php echo number_format($d->registered) ?>
                                            <br><small class="text-muted"><?php echo $d_reg_pct ?>%</small>
                                        </td>
                                        <td><?php echo number_format($d->paid) ?></td>
                                        <td>
                                            <span class="<?php echo $d_overall > 0 ? 'text-success' : 'text-muted' ?>">
                                                <?php echo $d_overall ?>%
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('export-daily-csv') && document.getElementById('export-daily-csv').addEventListener('click', function() {
    var headers = ['Date','Sent','Opened','Clicked','Visited','Registered','Paid','Overall %'];
    var rows = [headers];
    document.querySelectorAll('#daily-table tbody tr').forEach(function(tr) {
        var cells = tr.querySelectorAll('td');
        if (cells.length >= 8) {
            rows.push(Array.from(cells).map(function(c) { return '"' + c.innerText.trim().replace(/\n/g, ' ').replace(/"/g, '""') + '"'; }));
        }
    });
    var csv = rows.map(function(r) { return r.join(','); }).join('\n');
    var a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], {type: 'text/csv'}));
    a.download = 'golden_funnel_<?php echo $date_from ?>_<?php echo $date_to ?>.csv';
    a.click();
});
</script>
