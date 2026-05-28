<div class="content-wrapper">
    <?php $this->load->view('admin/include/breadcrumb'); ?>

    <div class="content">
        <div class="container-fluid">

            <!-- Breadcrumb row -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <a href="<?php echo base_url('admin/email_campaigns') ?>" class="text-muted small">
                        <i class="fas fa-arrow-left"></i> Email Campaigns
                    </a>
                    <span class="text-muted small"> › <?php echo html_escape($campaign->subject) ?> › Stats</span>
                </div>
                <div>
                    <a href="<?php echo base_url('admin/email_campaigns/edit/'.$campaign->id) ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i> Edit Campaign
                    </a>
                    <?php if (in_array($campaign->status, ['draft','paused'])): ?>
                    <a href="<?php echo base_url('admin/email_campaigns/send/'.$campaign->id) ?>"
                       class="btn btn-sm btn-primary ml-1"
                       onclick="return confirm('Send this campaign now?')">
                        <i class="fas fa-paper-plane"></i> Send Now
                    </a>
                    <?php endif ?>
                </div>
            </div>

            <!-- Campaign name & status -->
            <h4 class="mb-1">
                <?php
                $badges = ['draft'=>'secondary','sending'=>'warning','sent'=>'success','paused'=>'light'];
                $badge  = $badges[$campaign->status] ?? 'secondary';
                ?>
                <span class="badge badge-<?php echo $badge ?> mr-2"><?php echo ucfirst($campaign->status) ?></span>
                <?php echo html_escape($campaign->subject) ?>
            </h4>
            <p class="text-muted small mb-4">Created <?php echo my_date_show($campaign->created_at) ?></p>

            <!-- Stat cards -->
            <div class="row mb-4">
                <?php
                $open_pct   = $total > 0 ? round($opened  / $total * 100, 1) : 0;
                $click_pct  = $total > 0 ? round($clicked / $total * 100, 1) : 0;
                $stats_cards = [
                    ['label'=>'Sent',    'value'=>$total,   'sub'=>'',                       'icon'=>'fas fa-envelope',       'color'=>'primary'],
                    ['label'=>'Opened',  'value'=>$opened,  'sub'=>$open_pct.'% of sent',    'icon'=>'fas fa-eye',            'color'=>'info'],
                    ['label'=>'Clicked', 'value'=>$clicked, 'sub'=>$click_pct.'% of sent',   'icon'=>'fas fa-mouse-pointer',  'color'=>'warning'],
                ];
                ?>
                <?php foreach ($stats_cards as $sc): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                     style="width:44px;height:44px;background:var(--<?php echo $sc['color'] ?>);opacity:.15;flex-shrink:0"></div>
                                <i class="<?php echo $sc['icon'] ?> text-<?php echo $sc['color'] ?> mr-3" style="font-size:1.4rem;margin-left:-54px;position:relative;z-index:1"></i>
                                <div>
                                    <div class="h4 mb-0"><?php echo number_format($sc['value']) ?></div>
                                    <div class="text-muted small"><?php echo $sc['label'] ?><?php if($sc['sub']): ?> &mdash; <?php echo $sc['sub'] ?><?php endif ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>

            <!-- Recipients table -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Recipients <span class="badge badge-secondary ml-1"><?php echo $total ?></span></strong>
                    <?php if ($total > 0): ?>
                    <a href="#" id="export-csv" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                    <?php endif ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" id="recipients-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Sent</th>
                                    <th>Opened</th>
                                    <th>Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recipients)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No recipients yet — send the campaign first.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recipients as $r): ?>
                                    <tr>
                                        <td><?php echo html_escape($r->email) ?></td>
                                        <td><?php echo html_escape($r->name ?: '—') ?></td>
                                        <td>
                                            <?php if ($r->sent_at): ?>
                                                <span class="text-muted small"><?php echo date('d M H:i', strtotime($r->sent_at)) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <?php if ($r->opened_at): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i>
                                                    <?php echo date('d M H:i', strtotime($r->opened_at)) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <?php if ($r->click_count > 0): ?>
                                                <span class="badge badge-info"><?php echo $r->click_count ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif ?>
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
document.getElementById('export-csv') && document.getElementById('export-csv').addEventListener('click', function(e) {
    e.preventDefault();
    var rows = [['Email','Name','Sent','Opened','Clicks']];
    document.querySelectorAll('#recipients-table tbody tr').forEach(function(tr) {
        var cells = tr.querySelectorAll('td');
        if (cells.length >= 5) {
            rows.push([cells[0].innerText.trim(), cells[1].innerText.trim(), cells[2].innerText.trim(), cells[3].innerText.trim(), cells[4].innerText.trim()]);
        }
    });
    var csv = rows.map(function(r){ return r.map(function(c){ return '"'+c.replace(/"/g,'""')+'"'; }).join(','); }).join('\n');
    var blob = new Blob([csv], {type:'text/csv'});
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'campaign_<?php echo $campaign->id ?>_recipients.csv';
    a.click();
});
</script>
