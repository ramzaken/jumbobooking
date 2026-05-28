<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_campaigns extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        // track_open and track_click are public — skip auth for those two
        $action = $this->router->fetch_method();
        if (!in_array($action, ['track_open', 'track_click']) && !is_admin()) {
            redirect(base_url());
        }
        $this->load->model('email_model');
    }

    // ── List ────────────────────────────────────────────────────────────────

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Email Campaigns';
        $data['page']       = 'EmailCampaigns';
        $data['campaigns']  = $this->db->order_by('id', 'DESC')->get('email_campaigns')->result();
        $data['main_content'] = $this->load->view('admin/email_campaigns/index', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    // ── Create / Edit ───────────────────────────────────────────────────────

    public function create()
    {
        if ($this->input->post()) {
            $this->_save();
        }

        $data = array();
        $data['page_title']  = 'Email Campaigns';
        $data['page']        = 'EmailCampaigns';
        $data['campaign']    = null;
        $data['packages']    = $this->db->get('package')->result();
        $data['main_content'] = $this->load->view('admin/email_campaigns/create', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    public function edit($id)
    {
        $campaign = $this->db->get_where('email_campaigns', ['id' => $id])->row();
        if (empty($campaign)) {
            redirect(base_url('admin/email_campaigns'));
        }

        if ($this->input->post()) {
            $this->_save($id);
        }

        $data = array();
        $data['page_title']  = 'Email Campaigns';
        $data['page']        = 'EmailCampaigns';
        $data['campaign']    = $campaign;
        $data['packages']    = $this->db->get('package')->result();
        $data['main_content'] = $this->load->view('admin/email_campaigns/create', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    private function _save($id = null)
    {
        $schedule_type = $this->input->post('schedule_type', true);

        $d = array(
            'subject'        => $this->input->post('subject', true),
            'body'           => $this->input->post('body'),  // HTML allowed
            'recipient_type' => $this->input->post('recipient_type', true),
            'plan_id'        => $this->input->post('plan_id') ?: null,
            'manual_emails'  => $this->input->post('manual_emails', true),
            'schedule_type'  => $schedule_type,
            'scheduled_at'   => ($schedule_type == 'once') ? $this->input->post('scheduled_at', true) : null,
            'recur_days'     => ($schedule_type == 'recurring') ? implode(',', (array)$this->input->post('recur_days')) : null,
            'recur_time'     => ($schedule_type == 'recurring') ? $this->input->post('recur_time', true) : null,
            'status'         => $this->input->post('status', true) ?: 'draft',
        );

        if ($id) {
            $this->db->where('id', $id)->update('email_campaigns', $d);
            $this->session->set_flashdata('msg', 'Campaign updated successfully.');
            redirect(base_url('admin/email_campaigns'));
        } else {
            $d['created_at'] = my_date_now();
            $this->db->insert('email_campaigns', $d);
            $new_id = $this->db->insert_id();
            $this->session->set_flashdata('msg', 'Campaign created successfully.');
            redirect(base_url('admin/email_campaigns'));
        }
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function delete($id)
    {
        $this->db->where('campaign_id', $id)->delete('email_campaign_recipients');
        $this->db->where('id', $id)->delete('email_campaigns');
        $this->session->set_flashdata('msg', 'Campaign deleted.');
        redirect(base_url('admin/email_campaigns'));
    }

    // ── Send Now ─────────────────────────────────────────────────────────────

    public function send($id)
    {
        $campaign = $this->db->get_where('email_campaigns', ['id' => $id])->row();
        if (empty($campaign) || $campaign->status == 'sending') {
            redirect(base_url('admin/email_campaigns'));
        }

        $recipients = $this->_get_recipients($campaign);

        $this->db->where('id', $id)->update('email_campaigns', ['status' => 'sending']);

        $sent = 0;
        foreach ($recipients as $r) {
            $token = md5($campaign->id . $r['email'] . time() . random_string('alnum', 8));

            // Check not already sent
            $exists = $this->db->get_where('email_campaign_recipients',
                ['campaign_id' => $id, 'email' => $r['email']])->row();
            if ($exists) continue;

            $body = $this->_build_body($campaign, $r, $token);

            $sent_ok = $this->email_model->send_email($r['email'], $campaign->subject, $body);

            if ($sent_ok) {
                $this->db->insert('email_campaign_recipients', [
                    'campaign_id' => $id,
                    'email'       => $r['email'],
                    'name'        => $r['name'],
                    'token'       => $token,
                    'sent_at'     => my_date_now(),
                ]);
                $sent++;
            }
        }

        $new_status = ($campaign->schedule_type == 'none' || $campaign->schedule_type == 'once') ? 'sent' : 'sending';
        $this->db->where('id', $id)->update('email_campaigns', [
            'status'     => $new_status,
            'sent_count' => $campaign->sent_count + $sent,
        ]);

        $this->session->set_flashdata('msg', "Sent to {$sent} recipients.");
        redirect(base_url('admin/email_campaigns'));
    }

    // ── Stats ─────────────────────────────────────────────────────────────────

    public function stats($id)
    {
        $campaign = $this->db->get_where('email_campaigns', ['id' => $id])->row();
        if (empty($campaign)) {
            redirect(base_url('admin/email_campaigns'));
        }

        $recipients = $this->db->get_where('email_campaign_recipients', ['campaign_id' => $id])->result();

        $total      = count($recipients);
        $opened     = count(array_filter($recipients, fn($r) => !empty($r->opened_at)));
        $clicked    = count(array_filter($recipients, fn($r) => $r->click_count > 0));

        $data = array();
        $data['page_title']  = 'Email Campaigns';
        $data['page']        = 'EmailCampaigns';
        $data['campaign']    = $campaign;
        $data['recipients']  = $recipients;
        $data['total']       = $total;
        $data['opened']      = $opened;
        $data['clicked']     = $clicked;
        $data['main_content'] = $this->load->view('admin/email_campaigns/stats', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    // ── Open Tracking ─────────────────────────────────────────────────────────

    public function track_open($campaign_id, $token)
    {
        $r = $this->db->get_where('email_campaign_recipients',
            ['campaign_id' => $campaign_id, 'token' => $token])->row();

        if ($r && empty($r->opened_at)) {
            $this->db->where('id', $r->id)->update('email_campaign_recipients', [
                'opened_at' => my_date_now(),
            ]);
        }

        // Return 1x1 transparent GIF
        header('Content-Type: image/gif');
        header('Cache-Control: no-store, no-cache');
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        exit;
    }

    // ── Click Tracking (proxy redirect) ───────────────────────────────────────

    public function track_click($campaign_id, $token, $encoded_url)
    {
        $r = $this->db->get_where('email_campaign_recipients',
            ['campaign_id' => $campaign_id, 'token' => $token])->row();

        if ($r) {
            $update = ['click_count' => $r->click_count + 1];
            if (empty($r->visited_at)) {
                $update['visited_at'] = my_date_now();
            }
            $this->db->where('id', $r->id)->update('email_campaign_recipients', $update);

            // Store attribution in session so registration/payment can be credited
            $this->session->set_userdata([
                'ec_cid'   => (int) $campaign_id,
                'ec_tok'   => $token,
                'ec_email' => $r->email,
            ]);
        }

        // Decode URL-safe base64
        $url = base64_decode(strtr($encoded_url, '-_', '+/'));
        if (empty($url) || strpos($url, 'http') !== 0) {
            redirect(base_url());
        }
        redirect($url);
    }

    // ── Golden Funnel ─────────────────────────────────────────────────────────

    public function golden_funnel()
    {
        $date_from = $this->input->get('from') ?: date('Y-m-d', strtotime('-30 days'));
        $date_to   = $this->input->get('to')   ?: date('Y-m-d');

        $from_dt = $date_from . ' 00:00:00';
        $to_dt   = $date_to   . ' 23:59:59';

        // Aggregate totals across all campaigns in period
        $row = $this->db
            ->select("COUNT(*) as sent,
                      SUM(opened_at IS NOT NULL) as opened,
                      SUM(click_count > 0) as clicked,
                      SUM(visited_at IS NOT NULL) as visited,
                      SUM(registered_at IS NOT NULL) as registered,
                      SUM(paid_at IS NOT NULL) as paid")
            ->where('sent_at >=', $from_dt)
            ->where('sent_at <=', $to_dt)
            ->get('email_campaign_recipients')
            ->row();

        $funnel = [
            'sent'       => (int)($row->sent       ?? 0),
            'opened'     => (int)($row->opened     ?? 0),
            'clicked'    => (int)($row->clicked    ?? 0),
            'visited'    => (int)($row->visited    ?? 0),
            'registered' => (int)($row->registered ?? 0),
            'paid'       => (int)($row->paid       ?? 0),
        ];

        // Daily breakdown
        $daily = $this->db
            ->select("DATE(sent_at) as date,
                      COUNT(*) as sent,
                      SUM(opened_at IS NOT NULL) as opened,
                      SUM(click_count > 0) as clicked,
                      SUM(visited_at IS NOT NULL) as visited,
                      SUM(registered_at IS NOT NULL) as registered,
                      SUM(paid_at IS NOT NULL) as paid")
            ->where('sent_at >=', $from_dt)
            ->where('sent_at <=', $to_dt)
            ->group_by('DATE(sent_at)')
            ->order_by('date', 'DESC')
            ->get('email_campaign_recipients')
            ->result();

        $data = array();
        $data['page_title']  = 'Email Campaigns';
        $data['page']        = 'EmailCampaigns';
        $data['page_sub']    = 'golden_funnel';
        $data['funnel']      = $funnel;
        $data['daily']       = $daily;
        $data['date_from']   = $date_from;
        $data['date_to']     = $date_to;
        $data['main_content'] = $this->load->view('admin/email_campaigns/golden_funnel', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    // ── Cron: process scheduled campaigns ────────────────────────────────────

    public function cron()
    {
        $now = date('Y-m-d H:i:s');

        // One-time campaigns whose scheduled_at has passed
        $due = $this->db->where('status', 'draft')
                        ->where('schedule_type', 'once')
                        ->where('scheduled_at <=', $now)
                        ->get('email_campaigns')->result();

        foreach ($due as $c) {
            $this->send($c->id);
        }

        // Recurring campaigns
        $recurring = $this->db->where('status', 'sending')
                               ->where('schedule_type', 'recurring')
                               ->get('email_campaigns')->result();

        $today     = strtolower(date('D')); // mon, tue, wed...
        $time_now  = date('H:i');

        foreach ($recurring as $c) {
            $days = explode(',', strtolower($c->recur_days));
            $send_time = $c->recur_time ?? '09:00';
            if (in_array($today, $days) && substr($time_now, 0, 5) == substr($send_time, 0, 5)) {
                $this->_send_recurring($c);
            }
        }

        echo 'cron ok';
        exit;
    }

    private function _send_recurring($campaign)
    {
        $recipients = $this->_get_recipients($campaign);
        $sent = 0;
        foreach ($recipients as $r) {
            $token = md5($campaign->id . $r['email'] . time() . random_string('alnum', 8));
            $body  = $this->_build_body($campaign, $r, $token);
            $ok    = $this->email_model->send_email($r['email'], $campaign->subject, $body);
            if ($ok) {
                $this->db->insert('email_campaign_recipients', [
                    'campaign_id' => $campaign->id,
                    'email'       => $r['email'],
                    'name'        => $r['name'],
                    'token'       => $token,
                    'sent_at'     => my_date_now(),
                ]);
                $sent++;
            }
        }
        $this->db->where('id', $campaign->id)->update('email_campaigns', [
            'sent_count' => $campaign->sent_count + $sent,
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function _get_recipients($campaign)
    {
        $recipients = [];

        if ($campaign->recipient_type == 'all') {
            $users = $this->db->where('status', 'verified')->get('users')->result();
            foreach ($users as $u) {
                $recipients[] = ['email' => $u->email, 'name' => $u->name ?? ''];
            }
        } elseif ($campaign->recipient_type == 'plan') {
            $payments = $this->db->where('package_id', $campaign->plan_id)
                                 ->where('status', 'verified')
                                 ->get('payment')->result();
            $seen = [];
            foreach ($payments as $p) {
                if (isset($seen[$p->user_id])) continue;
                $seen[$p->user_id] = true;
                $u = $this->db->get_where('users', ['id' => $p->user_id])->row();
                if ($u) {
                    $recipients[] = ['email' => $u->email, 'name' => $u->name ?? ''];
                }
            }
        } elseif ($campaign->recipient_type == 'manual') {
            $lines = array_filter(array_map('trim', explode("\n", $campaign->manual_emails)));
            foreach ($lines as $line) {
                $recipients[] = ['email' => $line, 'name' => ''];
            }
        }

        return $recipients;
    }

    private function _build_body($campaign, $recipient, $token)
    {
        $body = str_replace(
            ['{name}', '{email}'],
            [html_escape($recipient['name']), html_escape($recipient['email'])],
            $campaign->body
        );

        // Wrap all href links with click-tracking proxy
        $cid = $campaign->id;
        $body = preg_replace_callback(
            '/href=["\']([^"\'#][^"\']*)["\']/',
            function ($m) use ($cid, $token) {
                $orig = $m[1];
                // Skip already-tracked or mailto/tel links
                if (strpos($orig, 'track_') !== false
                    || strpos($orig, 'mailto:') === 0
                    || strpos($orig, 'tel:') === 0) {
                    return $m[0];
                }
                $encoded  = rtrim(strtr(base64_encode($orig), '+/', '-_'), '=');
                $track    = base_url('admin/email_campaigns/track_click/' . $cid . '/' . $token . '/' . $encoded);
                return 'href="' . $track . '"';
            },
            $body
        );

        // Open-tracking pixel
        $pixel = '<img src="' . base_url('admin/email_campaigns/track_open/' . $cid . '/' . $token) . '" width="1" height="1" alt="" style="display:none">';

        $edata = ['subject' => $campaign->subject, 'msg' => $body . $pixel];
        return $this->load->view('email_template/common', $edata, true);
    }
}
