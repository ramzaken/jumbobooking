<div class="content-wrapper">
    <?php $this->load->view('admin/include/breadcrumb'); ?>

    <div class="content">
        <div class="container-fluid">

            <?php if ($this->session->flashdata('msg')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $this->session->flashdata('msg') ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif ?>

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-envelope-paper mr-2"></i> Email Campaigns</h5>
                    <div>
                        <a href="<?php echo base_url('admin/email_campaigns/golden_funnel') ?>" class="btn btn-warning btn-sm mr-1">
                            <i class="fas fa-filter"></i> Golden Funnel
                        </a>
                        <a href="<?php echo base_url('admin/email_campaigns/create') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Campaign
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Recipients</th>
                                    <th>Schedule</th>
                                    <th>Sent</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($campaigns)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            No campaigns yet. <a href="<?php echo base_url('admin/email_campaigns/create') ?>">Create your first one.</a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($campaigns as $c): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo base_url('admin/email_campaigns/stats/'.$c->id) ?>">
                                                <?php echo html_escape($c->subject) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($c->recipient_type == 'all'): ?>
                                                <span class="badge badge-secondary">All verified users</span>
                                            <?php elseif ($c->recipient_type == 'plan'): ?>
                                                <span class="badge badge-info">Specific plan</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Manual list</span>
                                            <?php endif ?>
                                        </td>
                                        <td>
                                            <?php if ($c->schedule_type == 'once' && $c->scheduled_at): ?>
                                                <i class="far fa-calendar-alt text-muted"></i>
                                                <?php echo date('d M Y H:i', strtotime($c->scheduled_at)) ?>
                                            <?php elseif ($c->schedule_type == 'recurring'): ?>
                                                <i class="fas fa-sync-alt text-muted"></i>
                                                <?php
                                                    $days_map = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'];
                                                    $d_labels = array_map(fn($d) => $days_map[trim($d)] ?? $d, explode(',', $c->recur_days ?? ''));
                                                    echo implode(', ', $d_labels) . ' · ' . ($c->recur_time ?? '09:00');
                                                ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif ?>
                                        </td>
                                        <td><?php echo number_format($c->sent_count) ?></td>
                                        <td>
                                            <?php
                                                $badges = ['draft'=>'secondary','sending'=>'warning','sent'=>'success','paused'=>'light'];
                                                $badge = $badges[$c->status] ?? 'secondary';
                                            ?>
                                            <span class="badge badge-<?php echo $badge ?>"><?php echo ucfirst($c->status) ?></span>
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo base_url('admin/email_campaigns/stats/'.$c->id) ?>" class="btn btn-outline-secondary" title="Stats">
                                                    <i class="fas fa-chart-bar"></i>
                                                </a>
                                                <?php if (in_array($c->status, ['draft','paused'])): ?>
                                                <a href="<?php echo base_url('admin/email_campaigns/send/'.$c->id) ?>"
                                                   class="btn btn-outline-success"
                                                   title="Send now"
                                                   onclick="return confirm('Send this campaign now?')">
                                                    <i class="fas fa-paper-plane"></i>
                                                </a>
                                                <?php endif ?>
                                                <a href="<?php echo base_url('admin/email_campaigns/edit/'.$c->id) ?>" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo base_url('admin/email_campaigns/delete/'.$c->id) ?>"
                                                   class="btn btn-outline-danger"
                                                   title="Delete"
                                                   onclick="return confirm('Delete this campaign?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
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
