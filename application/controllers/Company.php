<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        $this->load->library('cart');
    }

    public function set_language($lang)
    {
        if (settings()->enable_multilingual == 1) {
            if ($this->session->userdata('site_lang') == '') {
                $this->lang->load('website', get_by_id($lang, 'language')->slug);
            } else {
                $this->lang->load('website', $this->session->userdata('site_lang'));
            }
        }
    }

    public function check_domain()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parse = parse_url($url);
        $domain = $parse['host'];
 
        $uer_domain = $this->common_model->check_user_domain($domain);
        if (!empty($uer_domain)) {
            $business = $this->common_model->get_by_user_id($uer_domain->user_id, 'business');
            $slug = $business->slug;
            return $slug;
        } else {
            redirect(base_url());
        }
    }


    public function home($slug="")
    {  
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Company Home';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['sliders'] = $this->common_model->get_by_company($data['company']->uid,'sliders');
        $data['staffs'] = $this->common_model->get_by_company($data['company']->uid,'staffs');
        $data['testimonials'] = $this->common_model->get_user_testimonials($data['company']->uid);
        $data['brands'] = $this->common_model->get_all_company_brands($data['company']->uid);
        $data['portfolios'] = $this->common_model->get_by_company($data['company']->uid,'portfolios');

        $this->set_language($data['company']->lang);
       
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        if(empty($data['company'])){
            redirect(base_url('404_override'));
        }
        
        if ($template == 2) {
            if ($data['company']->enable_category == 1) {
                $data['categories'] = $this->common_model->get_category_services($data['company']->uid);
            }else{
                $data['services'] = $this->common_model->get_company_services($data['company']->uid, 6);
            }
        }else{
            $data['services'] = $this->common_model->get_company_services($data['company']->uid, 6);
            $data['service_categories'] = $this->common_model->get_by_company($data['company']->uid,'service_category');
        }
        
        $data['my_days'] =$this->admin_model->get_my_days($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/style_'.$template.'/home', $data, TRUE);
        $this->load->view('index', $data);
    }


    public function services($slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Services';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $this->set_language($data['company']->lang);

        $data['services'] = $this->common_model->get_company_services($data['company']->uid, 0);
        $data['staffs'] = $this->common_model->get_by_status($data['company']->uid, 'staffs');
        $data['service_categories'] = $this->common_model->get_by_company($data['company']->uid,'service_category');
        $data['my_days'] =$this->admin_model->get_my_days($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/common/services', $data, TRUE);
        $this->load->view('index', $data);
    }


    public function staff($slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Staff';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $this->set_language($data['company']->lang);
        $data['staffs'] = $this->common_model->get_by_company($data['company']->uid, 'staffs');
        $data['my_days'] =$this->admin_model->get_my_days($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/common/staff', $data, TRUE);
        $this->load->view('index', $data);
    }


    public function service($service_slug, $slug="")
    {   
        //echo "string"; exit();
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Service';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $this->set_language($data['company']->lang);
        $data['service'] = $this->common_model->get_id_by_company($service_slug, 'services', $data['company']->uid);
        $data['my_days'] =$this->admin_model->get_my_days($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/style_'.$template.'/service_details', $data, TRUE);
        $this->load->view('index', $data);
    }
    

    public function contact_submit()
    {    
        $company = $this->common_model->get_by_slug($this->input->post('slug'), 'business');

        if ($_POST) {
            $data = array(
                'name' => $this->input->post('name', true),
                'user_id' => $this->input->post('user_id', true),
                'business_id' => $this->input->post('business_id', true),
                'email' => $this->input->post('email', true),
                'message' => $this->input->post('message', true),
                'created_at' => my_date_now()
            );
            $data = $this->security->xss_clean($data);
            $this->common_model->insert($data, 'contacts');
            $this->session->set_flashdata('msg', trans('send-successfully'));

             //send message


            $subject = get_email_by_slug('contact-submit-company')->subject;
            $body = get_email_by_slug('contact-submit-company')->body;
            $variables_data = [
                'message'  =>$this->input->post('message', true),
                'sender_email' => $this->input->post('email', true),
            ]; 

            $msg = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables_data) {
                $key = trim($matches[1]);
                return isset($variables_data[$key]) ? $variables_data[$key] : $matches[0]; 
            }, $body);


            $edata = array();
            $edata['subject'] = $subject;
            $edata['msg'] = $msg;

            $msg = $this->load->view('email_template/common', $edata, true);
            $response = $this->email_model->send_email($company->email, $subject, $msg);



        }
        redirect($_SERVER['HTTP_REFERER']);
        
    }

    public function gallery($slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Gallery';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $this->set_language($data['company']->lang);
        $data['galleries'] = $this->common_model->get_by_status($data['company']->uid, 'gallery');
        $data['my_days'] =$this->admin_model->get_my_days($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/common/gallery', $data, TRUE);
        $this->load->view('index', $data);
    }

    //show pages
    public function page($slug="", $page_slug)
    {
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Page';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $data['pages'] = $this->common_model->get_user_single_page($page_slug, $data['company']->uid);
        if (empty($data['pages'])) {
            redirect(base_url());
        }
        $this->set_language($data['company']->lang);
        $data['page_name'] = $data['pages']->title;
        $data['my_days'] =$this->admin_model->get_my_days($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/common/page', $data, TRUE);
        $this->load->view('index', $data);
    }


    // get time slots
    public function get_time($date, $business_id, $service_id='')
    {
        
        ini_set('memory_limit', '-1');


        $select_service_extra= $this->input->post('select_service_extra',true);
        $service_extra_value= $this->input->post('service_extra_value',true);
        $service_extra_value= explode(',', $service_extra_value);

        //echo "<pre>"; print_r($select_service_extra); exit();
        if(empty($service_id)){
            $service_id = $this->session->userdata('service_id');
        }
        
        $day = date('l', strtotime($date));
        $day_id = get_day_id($day);
        $value = array();

        if( !empty($this->input->get('embed')) && $this->input->get('embed') == 'true'){
          $is_embed = true;
        }else{
          $is_embed = false;
        }

        $value['is_embed'] = $is_embed;
        if($is_embed == true){
          $value['page'] = FALSE;
        }

        
        $value['company'] = $this->admin_model->get_business_uid($business_id);
        $this->set_language($value['company']->lang);

        if ($value['company']->enable_staff != 1) {
            $this->session->unset_userdata('staff_id');
        }
        

        $template = $value['company']->template_style;
        $service = $this->admin_model->get_by_id($service_id, 'services');

        if ($value['company']->interval_settings == 2) {

            if ($select_service_extra == 1) {
                $total_extra_duration = 0;

                foreach ($service_extra_value as $extra_value) {
                    $service_extra = get_by_id($extra_value,'service_extra');

                    if ($service_extra->duration_type == 'minute') {
                        $extra_duration = $service_extra->duration;
                    }else{
                        $extra_duration = $service_extra->duration *60;
                    }
                    $total_extra_duration += $extra_duration;
                }


                if ($value['company']->interval_type == 'minute') {
                    $service_interval = $value['company']->time_interval;
                }else if($value['company']->interval_type == 'hour'){
                    $service_interval = $value['company']->time_interval * 60;
                }else{
                    $service_interval = 'day';
                }


                $interval = $service_interval + $total_extra_duration;


            }else{

                if ($value['company']->interval_type == 'minute') {
                    $interval = $value['company']->time_interval;
                }else if($value['company']->interval_type == 'hour'){
                    $interval = $value['company']->time_interval * 60;
                }else{
                    $interval = 'day';
                }


            }
           
        }else{
           
            if ($select_service_extra == 1) {
                $total_extra_duration = 0;

                foreach ($service_extra_value as $extra_value) {
                    $service_extra = get_by_id($extra_value,'service_extra');

                    if ($service_extra->duration_type == 'minute') {
                        $extra_duration = $service_extra->duration;
                    }else{
                        $extra_duration = $service_extra->duration *60;
                    }
                    $total_extra_duration += $extra_duration;
                }

                if ($service->duration_type == 'minute') {
                    $service_interval = $service->duration;
                }else if($service->duration_type == 'hour'){
                    $service_interval = $service->duration * 60;
                }else{
                    $service_interval = 'day';
                }


                $interval = $service_interval + $total_extra_duration;


            }else{

                if ($service->duration_type == 'minute') {
                    $interval = $service->duration;
                }else if($service->duration_type == 'hour'){
                    $interval = $service->duration * 60;
                }else{
                    $interval = 'day';
                }


            }
           
            
        }

        

        // staff days
        if (!empty($this->session->userdata('staff_id'))) {
            $staff_id = $this->session->userdata('staff_id');
        }else{
            $staff_id = session_get($business_id, 'staff_id');
        }

        //***** old code
        // $staff_days = $this->admin_model->get_staff_days($staff_id);
        // if (empty($staff_days)) {
        //     $staff_id = 0;
        // } else {
        //     $staff_id = $staff_id;
        // }

        //***** new code
        $staff_days = $this->check_staff_schedule($staff_id);
        if ($staff_days == 'company') {
            $staff_id = 0;
        } else {
            $staff_id = $staff_id;
        }

        //echo "<pre>"; print_r($staff_days); exit();
        
        // staff days for day interval
        if ($interval != 'day') {
            $slot = $this->admin_model->get_timeslot_by_day($day_id, $business_id, $staff_id);
            
      
            $value['times'] = get_time_slots($interval, $slot->start, $slot->end, $service->buffer_time_before,$service->buffer_time_after, $value['company']->uid, $day_id);
            //$value['breaks'] = get_time_by_days_staff($day_id, $business_id,$staff_id);

        }

        $value['service_id'] = $service_id;
        $value['day_id'] = $day_id;
        $value['date'] = $date;
        $value['interval'] = $interval;
        $data = array();
        $data['result'] = $this->load->view('include/time_loop', $value, TRUE);

        if ($interval != 'day') {
            if (empty($value['times'])) {
                $data['status'] = 0;
            } else {
                $data['status'] = 1;
            }
        }else{
            $data['status'] = 1;
        }
        die(json_encode($data));
    }



    



    public function booking($slug="")
    {  
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        if( !empty($this->input->get('embed')) && $this->input->get('embed') == 'true'){
          $is_embed = true;
        }else{
          $is_embed = false;
        }

        $data['is_embed'] = $is_embed;
        if($is_embed == true){
          $data['page'] = FALSE;
        }

        $data['menu'] = FALSE;
        $this->session->set_userdata('business_slug', $slug);
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        session_insert($data['company']->uid);
        $this->set_language($data['company']->lang);

        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $data['user'] = $this->common_model->get_by_id($data['company']->user_id, 'users');
        $my_days = $this->admin_model->get_my_days($data['company']->uid);

        foreach ($my_days as $day) {
            if ($day['day'] != 0) {
                $myday[] = $day['day'];
            }
        }

        $days = "1,2,3,4,5,6,7";         
        $days = explode(',', $days);
        $assign_days = $myday;
        //echo "<pre>"; print_r($my_days); exit();
        $match = array();
        $nomatch = array();

        foreach($days as $v){     
            if(in_array($v, $assign_days))
                $match[]=$v;
            else
                $nomatch[]=$v;
        }
        $data['not_available'] = $nomatch;
        $data['my_days'] = $my_days;


        $data['page'] = 'Company';
        $data['page_title'] = 'Booking';
        $data['company_id'] = $data['company']->uid;
        if ($data['company']->enable_category == 1) {
            $data['categories'] = $this->common_model->get_category_services($data['company']->uid);
        }else{
            $data['services'] = $this->common_model->get_by_status_order($data['company']->uid, 'services');
        }

        if ($data['company']->default_timezone == 1) {
            $this->store_time_zone($data['company']->time_zone);
        }
        $data['locations'] = $this->common_model->get_locations(0, $data['company']->uid);
        $data['sub_locations'] = $this->common_model->get_locations(1, $data['company']->uid);

        $data['dialing_codes'] = $this->common_model->select_asc('dialing_codes');
        $data['time_zones'] = $this->common_model->select_asc('time_zone');
        $data['main_content'] = $this->load->view('templates/common/booking', $data, TRUE);
        $this->load->view('index', $data);
        
    }


    // load currency by ajax
    public function load_sub_location($location_id)
    {
        $location = $this->common_model->get_by_id($location_id, 'locations');
        $sub_locations = $this->common_model->get_sub_locations($location_id);
        $this->session->set_userdata('location_id', $location_id);
        session_store($location->business_id, $location_id, 'location_id');

        if (empty($sub_locations)) {
            echo '<option value="0">'.trans('no-data-found').'</option>';
        }else{
            foreach ($sub_locations as $location) { 
                echo '<option value="'.$location->id.'">'.$location->name.' '.$location->address.''. '</option>';
            }

            if (!empty($sub_locations)) {
                $this->session->set_userdata('sub_location_id', $sub_locations[0]->id);
                session_store($location->business_id, $sub_locations[0]->id, 'sub_location_id');
            }
        }
    }


    // load sub_location
    public function sess_sub_location($sub_location_id)
    {
        $this->session->set_userdata('sub_location_id', $sub_location_id);
        $sub_location = $this->common_model->get_by_id($sub_location_id, 'locations');
        if (!empty($sub_location)) {
            session_store($sub_location->business_id, $sub_location_id, 'sub_location_id');
        }
    }


    // load staff
    public function sess_staff($id, $business_id)
    {

        $company = $this->common_model->get_by_uid($business_id, 'business');
        session_store($company->uid, $id, 'staff_id');

        $this->session->unset_userdata('staff_id');
        $this->session->set_userdata('staff_id', $id);
        
        $data = array();
        $staff_days = $this->check_staff_schedule($id);
      
        // if staff have schedules
        if ($staff_days == 'staff') {
            $my_days = $this->admin_model->get_staff_days($id);
            $staff = $this->common_model->get_by_id($id, 'staffs');

            foreach ($my_days as $day) {
                if ($day['day'] != 0) {
                    $myday[] = $day['day'];
                }
            }

            $days = "1,2,3,4,5,6,7";         
            $days = explode(',', $days);
            $assign_days = $myday;

            $match = array();
            $nomatch = array();

            foreach($days as $v){     
                if(in_array($v, $assign_days))
                    $match[]=$v;
                else
                    $nomatch[]=$v;
            }
            $data['not_available'] = $nomatch;
            $data['my_days'] = $my_days;

            if (empty($staff->holidays)) {
                $data['holidays'] = $company->holidays;
            }else{
                $data['holidays'] = $staff->holidays;
            }
            $data['page'] = 'Company';
            $data['page_title'] = 'Booking';
            $data['company_id'] = $company->uid;
            $data['company'] = $company;
            $loaded = $this->load->view('include/custom-js', $data, true);
            echo json_encode(array('st' => 1, 'loaded' => $loaded));
        }else{
            $my_days = $this->admin_model->get_my_days($company->uid);
          
            foreach ($my_days as $day) {
                if ($day['day'] != 0) {
                    $myday[] = $day['day'];
                }
            }

            $days = "1,2,3,4,5,6,7";         
            $days = explode(',', $days);
            $assign_days = $myday;

            $match = array();
            $nomatch = array();

            foreach($days as $v){     
                if(in_array($v, $assign_days))
                    $match[]=$v;
                else
                    $nomatch[]=$v;
            }
            $data['not_available'] = $nomatch;
            $data['my_days'] = $my_days;
            $data['page'] = 'Company';
            $data['page_title'] = 'Booking';
            $data['holidays'] = $company->holidays;
            $data['company_id'] = $company->uid;
            $data['company'] = $company;
            $loaded = $this->load->view('include/custom-js', $data, true);
            echo json_encode(array('st' => 1, 'loaded' => $loaded));
        }

    }
    

    public function check_staff_schedule($id)
    {
        $days = $this->admin_model->get_staff_days($id);
        if (!empty($days)) {
            if (empty($days[0]['start']) && empty($days[1]['start']) && empty($days[2]['start']) && empty($days[3]['start']) && empty($days[4]['start']) && empty($days[5]['start']) && empty($days[6]['start'])) {
                return 'company';
            }elseif (!empty($days[0]['start']) || !empty($days[1]['start']) || !empty($days[2]['start']) || !empty($days[3]['start']) || !empty($days[4]['start']) || !empty($days[5]['start']) || !empty($days[6]['start'])) {
                return 'staff';
            }else{
                return 'company';
            }
        }else{
            return 'company';
        }
    }


    public function load_staff($id)
    {

        $data = array();
        $data['service'] = $this->common_model->get_service_staffs($id);
        $data['company'] = $this->common_model->get_by_uid($data['service']->business_id, 'business');
        $data['inputs'] = $this->common_model->get_custom_inputs($data['company']->uid,$id); 
        $this->set_language($data['company']->lang);
        
        $data['page_title'] = 'Booking';
        $data['holidays'] = $data['company']->holidays;
        $data['company_id'] = $data['company']->uid;



        $my_days = $this->admin_model->get_my_days($data['company']->uid);
          
        foreach ($my_days as $day) {
            if ($day['day'] != 0) {
                $myday[] = $day['day'];
            }
        }

        $days = "1,2,3,4,5,6,7";         
        $days = explode(',', $days);
        $assign_days = $myday;
        //echo "<pre>"; print_r($my_days); exit();
        $match = array();
        $nomatch = array();

        foreach($days as $v){     
            if(in_array($v, $assign_days))
                $match[]=$v;
            else
                $nomatch[]=$v;
        }
        $data['not_available'] = $nomatch;
        $data['my_days'] = $my_days;
        $data['page'] = 'Company';






        // new code
        $get_service_extras = get_by_id($id,'services')->service_extra;
        $service_extras =  explode(',', $get_service_extras);

        if (empty($get_service_extras) || $data['service']->enable_service_extra == 0) {
            $is_extra = 0;
            $data['service_extras'] = '';
        }else{
            $data['service_extras'] = $service_extras;
            $is_extra = 1;
        }
        session_store($data['service']->business_id, $id, 'service_id');
        $this->session->set_userdata('service_id', $id);

        $loaded_calendar = $this->load->view('include/custom-js', $data, true);

        if (!empty($data['service'])) {
            $loaded = $this->load->view('include/staff_item', $data, true);
            $loaded_input = $this->load->view('include/custom_input', $data, true);//new code
            $loaded_service_extra = $this->load->view('include/service_extra', $data, true);//new code
            echo json_encode(array('st' => 1, 'loaded' => $loaded,  'loaded_calendar' => $loaded_calendar, 'loaded_input' => $loaded_input, 'loaded_service_extra' => $loaded_service_extra, 'is_extra' => $is_extra,)); // new code
        }else{
            $loaded = $this->load->view('include/staff_item', $data, true);
            $loaded_input = $this->load->view('include/custom_input', $data, true); // New code 
            $loaded_service_extra = $this->load->view('include/service_extra', $data, true); // New code 
            echo json_encode(array('st' => 0, 'loaded' => $loaded,   'loaded_calendar' => $loaded_calendar, 'loaded_input' => $loaded_input, 'loaded_service_extra' => $loaded_service_extra, 'is_extra' => $is_extra,)); // new code
        }

    }




    public function confirm_booking($slug="", $appointment_id)
    {

        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        if(!empty($this->input->get('embed')) && $this->input->get('embed') == 'true'){
          $is_embed = true;
        }else{
            $is_embed = false;

            if (!is_customer() && !is_guest()) {
                redirect(base_url());
            }
        }
        
        if($is_embed == true){
          $data['page'] = FALSE;
        }

        $this->session->set_userdata('is_notify_sent', '0');
       
        $data['is_embed'] = $is_embed;
        $data['page'] = 'Company';
        $data['page_title'] = 'Booking Confirm';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $this->set_language($data['company']->lang);
        $data['appointment'] = $this->common_model->get_appointment_md5($appointment_id);
        $data['service'] = $this->common_model->get_by_id($data['appointment']->service_id, 'services');
        $data['booking_id'] = $data['appointment']->id;
        $data['appointment_id'] = $data['appointment']->user_id;
        $data['md5_appointment_id'] = $appointment_id;
        $this->session->set_userdata('appointment_id', $data['appointment']->id);
        $this->session->set_userdata('company_slug', $slug);


        if (isset($_GET['pay']) && $_GET['pay'] == 'deposit') {
            $totalCost = get_appointment_price($data['appointment'], $data['company']);
            if (isset($_GET['pay']) && $_GET['pay'] == 'deposit'):
                if ($data['service']->deposite_type == 'fixed'):
                    $deposit_amount = $data['service']->deposite_amount;
                else:
                    $deposit_amount = ($data['service']->deposite_percentage / 100) * $totalCost;
                endif;
                $totalCost = $deposit_amount;
            endif;
          
            $deposit_data = array(
                'deposit' => true,
                'deposit_amount' => $totalCost,
            );
            $this->session->set_userdata($deposit_data);
        }else{
            $deposit_data = array(
                'deposit' => false,
                'deposit_amount' => 0,
            );
            $this->session->set_userdata($deposit_data);
        }


        $data['convert_time'] = convert_to_customer_timezone($data['appointment']->time, $data['company']->id, $data['appointment']->customer_id);
        
        $ses_data = array(
            'appointment_id' => $data['appointment']->id
        );
        $this->session->set_userdata($ses_data);
        $mercado = $this->mercado_api_link();
        $data['init'] = $mercado['init'];

        $data['user'] = $this->common_model->get_by_id($data['appointment']->user_id, 'users');
        $data['main_content'] = $this->load->view('templates/common/booking', $data, TRUE);
        $this->load->view('index', $data);
    }


    public function mercado(){

        $appointment = $this->admin_model->get_by_id($this->session->userdata('appointment_id'), 'appointments');
        $user = $this->admin_model->get_by_id($appointment->user_id, 'users');

        if (settings()->enable_wallet == 1) {
            $mercado_token = settings()->mercado_token;
        }else{
            $mercado_token = $user->mercado_token;
        }

        $access_token = $mercado_token;
        $respuesta = array(
            'Payment' => $_GET['payment_id'],
            'Status' => $_GET['status'],
            'MerchantOrder' => $_GET['merchant_order_id']        
        ); 
        MercadoPago\SDK::setAccessToken($access_token);
        $merchant_order = $_GET['payment_id'];

        $payment = MercadoPago\Payment::find_by_id($merchant_order);
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);

        //$merchant_order->payments
        redirect(base_url('admin/payment/payment_success/'.$appointment->id.'/mercadopago'));

    }

    public function mercado_api_link(){

        $appointment = $this->common_model->get_appointment_md5(md5($this->session->userdata('appointment_id')));

        $user = $this->admin_model->get_by_id($appointment->user_id, 'users');
        //echo "<pre>"; print_r($user); exit();

        if (settings()->enable_wallet == 1) {
            $mercado_token = settings()->mercado_token;
            $mercado_currency = settings()->mercado_currency;
        }else{
            $mercado_token = $user->mercado_token;
            $mercado_currency = $user->mercado_currency;
        }


        $check_coupon = check_coupon($appointment->id, $appointment->service_id, $appointment->business_id);
        if ($check_coupon != FALSE):
            if (!empty($check_coupon)):
                $price = get_price($appointment->price, $appointment->group_booking, $appointment->total_person);
                $discount = $check_coupon->discount;
                $totalCost = $price - ($price * ($discount / 100));
                $discount_amount = $price - $totalCost;
            else:
                $price = get_price($appointment->price, $appointment->group_booking, $appointment->total_person);
                $discount = 0;
                $discount_amount = 0;
                $totalCost = $price;
            endif;
        else:
            $totalCost = get_price($appointment->price, $appointment->group_booking, $appointment->total_person);
        endif;
       
        $data = [];
        MercadoPago\SDK::setAccessToken($mercado_token);
        $preference = new MercadoPago\Preference();
        // Create a preference item
        $item = new MercadoPago\Item();
        $item->title = 'Appointment Payment';
        $item->quantity = 1;
        $item->unit_price = $totalCost;
        $item->currency_id = $mercado_currency;
        $preference->items = array($item);
        $preference->back_urls = array(
            "success" => base_url("company/mercado"),
            "failure" => base_url("company/mercado"),
            "pending" => base_url("company/mercado")
        );
        $preference->auto_return = "approved";

        $preference->save();
        $data['f_id'] = $preference->id;
        $data['init'] = $preference->init_point;

        return $data;
    }




    public function check_coupon($code, $appointment_id)
    {
        if (empty($code)) {
            echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code'))); exit();
        }
        $appointment = $this->common_model->get_by_id($appointment_id, 'appointments');
        $service = $this->common_model->get_by_id($appointment->service_id, 'services');
        $coupon = $this->common_model->get_coupon($code, $appointment->service_id, $appointment->business_id);
        $company = $this->admin_model->get_business_uid($appointment->business_id);

        if (empty($coupon)) {
            echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code')));
        } else {
            if (date('Y-m-d') >= $coupon->start_date && date('Y-m-d') <= $coupon->end_date) {
                
                //check coupon limit
                if ($coupon->usages_limit == 0) {
                    echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code'))); exit();
                }

                if ($coupon->once_per_customer == 1) {
                    $check = $this->common_model->check_coupon_apply($code, $appointment->service_id, $service->business_id, $appointment->customer_id);
                    if (isset($check)) {
                        echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('already-applied-code'))); exit();
                    }
                }

                if ($appointment->group_booking != 0) {
                    $price = ($appointment->total_person + 1) * $service->price;
                }else{
                    $price = $service->price;
                }

                $service_extra_price = 0;
                if(!empty($appointment->service_extra)):
                    $service_extras = explode(',', $appointment->service_extra);
                    
                    foreach ($service_extras as $value):
                        $service_extra_price += get_by_id($value,'service_extra')->price;
                    endforeach;
                endif;

                $price = $price + $service_extra_price;


                //insert apply coupon data
                $data = array(
                    'code' => $code,
                    'discount' => $coupon->discount,
                    'appointment_id' => $appointment->id,
                    'business_id' => $service->business_id,
                    'service_id' => $appointment->service_id,
                    'customer_id' => $appointment->customer_id,
                    'created_at' => my_date_now()
                    //'created_at' => user_date_now($company->time_zone)
                );
                $this->admin_model->insert($data, 'coupons_apply');

                //update coupon
                $coupon_data = array(
                    'usages_limit' => $coupon->usages_limit - 1,
                    'used' => $coupon->used + 1
                );
                $this->admin_model->edit_option($coupon_data, $coupon->id, 'coupons');
                echo json_encode(array('st' => 1, 'discount' => $coupon->discount, 'total_price' => $price, 'msg' => '<i class="fas fa-check-circle"></i> '.trans('coupon-applied-successfully')));

            }else{
                echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code')));
            }
        }exit();
        
    }



    public function send_booking_notifications($slug="", $appointment_id)
    {   
        // error_reporting(-1);
        // ini_set('display_errors', 1);

        if (settings()->confirm_notify == 1) {
            echo "disabled"; exit();
        }

        $booking = $this->common_model->get_booking_by_id($appointment_id);

        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        if ($booking && $booking->email_sent == 0) {

            $convert_time = $this->session->userdata('convert_time');
            $booking_number_text = $this->session->userdata('booking_number_text');
            $service_id = $this->session->userdata('serviceId');
            $customer_id = $this->session->userdata('customerId');
            $location_id = $this->session->userdata('locationId');
            $staff_id = $this->session->userdata('staffId');
            $date = $this->session->userdata('date');
            $time = $this->session->userdata('time');


            $company = $this->common_model->get_by_slug($slug, 'business');
            $user = $this->common_model->get_by_id($company->user_id, 'users');
            $customer = $this->admin_model->get_by_id($customer_id, 'customers');
            $service = $this->admin_model->get_by_id($service_id, 'services');
            $location = $this->admin_model->get_by_id($location_id, 'locations');

            $this->session->set_userdata('wap_template', 'new-booking');
            $this->set_language($company->lang);

            //echo "<pre>"; print_r($location); exit();


            $zoom_link = '';
            $google_meet = '';

            if(!empty($service->zoom_link)){$zoom_link = trans('zoom-meeting-link').': <a href='.$service->zoom_link.'>'.$service->zoom_link.'</a>';}

            if(!empty($service->google_meet)){$google_meet = trans('google-meet-link').': <a href='.$service->google_meet.'>'.$service->google_meet.'</a>';}

            $meeting = $zoom_link .'<br>'.$google_meet;
            $meeting_sms = $service->zoom_link .' '.$service->google_meet;



            // send sms to customer
            if ($user->enable_sms_notify == 1 || settings()->global_twilio == 1) {
                $this->load->model('sms_model');
                $message = trans('appointment').' '.$company->name.' - '.$service->name.' '.trans('booking-is-confirmed-at').' '.$date.' '.$convert_time.' '.$booking_number_text;
                
                //check twillo api keys
                if ($user->enable_sms_notify == 1 && !empty($user->twillo_account_sid) && !empty($user->twillo_auth_token)) {
                    $this->sms_model->send_user($customer->phone, $message, $user->id);
                }

                //check twillo api keys for global
                if (settings()->global_twilio == 1 && !empty(settings()->twillo_account_sid) && !empty(settings()->twillo_auth_token)) {
                    $this->sms_model->send_user($customer->phone, $message, $user->id);
                }
            }

            

            // send whatsapp to customer
            if ($company->enable_whatsapp_msg == 1 || settings()->global_wapp_msg == 1) {
                $this->load->model('sms_model');
                $message = trans('appointment').' '.$company->name.' - '.$service->name.' '.trans('booking-is-confirmed-at').' '.$date.' '.$convert_time.' '.$meeting_sms.' '.$booking_number_text;
                
                //check ultramsg api keys
                if ($company->enable_whatsapp_msg == 1 && settings()->global_wapp_msg == 0) {
                    $this->sms_model->send_whatsapp_user($customer->phone, $message, $company);
                }

                //check ultramsg api keys for global
                if (settings()->global_wapp_msg == 1) {
                    $this->sms_model->send_whatsapp_user($customer->phone, $message, 'settings');
                }
            }


            // send email to customer
            if (!empty($location)) {
                //$app_location = ' on '.$location->name.' ('.$location->address.')';
                $l_name = $location->name;
                $l_address = $location->address;
            }else{
                $l_name = '';
                $l_address = '';
            }


            // send email to customer with dynamic msg 

            $subject = get_email_by_slug('appointment-booking-confirmation-customer')->subject;
            $body = get_email_by_slug('appointment-booking-confirmation-customer')->body;
            $variables_data = [
                'business_name'  =>$company->name,
                'service_name' => $service->name,
                'appointment_date' => $date,
                'appointment_time' => $convert_time,
                'location_name' => $l_name,
                'location_address' => $l_address,
                'zoom_link' => $zoom_link,
                'meet_link' => $google_meet,
                'booking_number' => $booking_number_text,
            ]; 
            //echo "<pre>"; print_r($variables_data); exit();
            $msg = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables_data) {
                $key = trim($matches[1]);
                return isset($variables_data[$key]) ? $variables_data[$key] : $matches[0]; 
            }, $body);


            $edata = array();
            $edata['subject'] = $subject;
            $edata['msg'] = $msg;

            $msg = $this->load->view('email_template/common', $edata, true);
            $this->email_model->send_email($customer->email, $subject, $msg);




            // send email to user with dynamic msg 

            $subject = get_email_by_slug('appointment-booking-confirmation-company')->subject;
            $body = get_email_by_slug('appointment-booking-confirmation-company')->body;
            $variables_data = [
                'customer_name'  =>$customer->name,
                'appointment_date' => $date,
                'appointment_time' => $convert_time,
                'booking_number' => $booking_number_text,
            ]; 

            $msg = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables_data) {
                $key = trim($matches[1]);
                return isset($variables_data[$key]) ? $variables_data[$key] : $matches[0]; 
            }, $body);


            $edata = array();
            $edata['subject'] = $subject;
            $edata['msg'] = $msg;

            $msg = $this->load->view('email_template/common', $edata, true);
            $this->email_model->send_email($company->email, $subject, $msg);




            // send email to staff with dynamic msg 
            $staff_info = $this->admin_model->get_by_id($staff_id, 'staffs');
            $subject = $service->name.' - '.trans('new-appointment-is-booked');
            $subject = get_email_by_slug('appointment-booking-confirmation-staff')->subject;
            $body = get_email_by_slug('appointment-booking-confirmation-staff')->body;
            $variables_data = [
                'customer_name'  =>$customer->name,
                'appointment_date' => $date,
                'appointment_time' => $convert_time,
                'booking_number' => $booking_number_text,
            ]; 

            $msg = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables_data) {
                $key = trim($matches[1]);
                return isset($variables_data[$key]) ? $variables_data[$key] : $matches[0]; 
            }, $body);


            $edata = array();
            $edata['subject'] = $subject;
            $edata['msg'] = $msg;

            $msg = $this->load->view('email_template/common', $edata, true);
            $this->email_model->send_email($staff_info->email, $subject, $msg);

            $this->session->set_userdata('is_notify_sent', '1');
            $this->common_model->update_email_sent_status($appointment_id);
        }

        $data = array();
        $data['status'] = 1;
        die(json_encode($data));
            
    }


    public function confirm_pay_info($slug="", $id)
    {

        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        //if ($_POST) {

            if (is_customer()) {
                $url = base_url('customer/appointments');
            }else{
                $url = base_url('company/booking_success/'.$slug);
            }

            $data = array(
                'pay_info' => 2,
            );
            $this->admin_model->edit_option($data, $id, 'appointments');
            echo json_encode(array('st' => 1, 'url' => $url));
        //}
    }


    public function confirm_event_pay_info($slug="", $id)
    {

        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        if ($_POST) {
            
            if (is_customer()) {
                $url = base_url('customer/events');
            }
            if (is_guest()) {
                //$url = base_url('company/booking_success/'.$slug);
                $url = base_url('customer/events');
            }

            $data = array(
                'pay_info' => 2,
            );
            //$this->admin_model->edit_option($data, $id, 'appointments');
            echo json_encode(array('st' => 1, 'url' => $url));
        }
    }


    public function confirm_embed_pay($slug="", $id, $status)
    {
    
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $url = base_url('company/booking_success/'.$slug.'?embed=true');
        $data = array(
            'pay_info' => $status,
        );
        $this->admin_model->edit_option($data, $id, 'appointments');
        echo json_encode(array('st' => 1, 'url' => $url));
        
    }

    public function booking_success($slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        if(!empty($this->input->get('embed')) && $this->input->get('embed') == 'true'){
          $is_embed = true;
        }else{
          $is_embed = false;
        }

        if (is_guest()) {
            $this->session->unset_userdata('id');
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('role');
            $this->session->unset_userdata('logged_in');
        }
      
        $data['is_embed'] = $is_embed;
        $data['page'] = 'Company';
        $data['page_title'] = 'Appointment Confirmation';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $this->set_language($data['company']->lang);
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $data['main_content'] = $this->load->view('templates/common/booking_msg', $data, TRUE);
        $this->load->view('index', $data);
    }


    //book appointment
    public function book_appointment($slug="")
    {   
        // error_reporting(-1);
        // ini_set('display_errors', 1);

        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $company = $this->common_model->get_by_slug($slug, 'business');
        $id = $company->user_id;
        $is_customer_exist = $this->input->post('is_customer_exist');
        $this->set_language($company->lang);
       
        $user = $this->common_model->get_by_id($id, 'users');
        $date = $this->input->post('date');
        $date = date("Y-m-d", strtotime($date));

        if ($_POST) {

            if (empty($this->input->post('location_id'))) {
                $check_location_id = 0;
            }else{
                $check_location_id = $this->input->post('location_id'); 
            }

            $check_time = check_time($this->input->post('time'), $this->input->post('date'), $this->input->post('service_id'), $this->input->post('staff_id'), $check_location_id);

            if ($check_time == true) {
                echo json_encode(array('st'=> 5, 'msg' => trans('appointment-is-not-available-on-that-time')));
                exit();
            }
            

            //check reCAPTCHA
            if (!$this->recaptcha_verify_request()) {
                $msg = trans('recaptcha-is-required');
                echo json_encode(array('st'=> 4, 'msg' => $msg)); exit();
            } else {

                if (date('Y-m-d') > $date) {  
                    $msg = trans('please-enter-a-valid-date');
                    echo json_encode(array('st'=>2, 'msg' => $msg)); exit();
                }

                if ($is_customer_exist == 0 || $is_customer_exist == 2) {
                    $this->form_validation->set_rules('email', trans('email'), 'required');
                    $this->form_validation->set_rules('phone', trans('phone'), 'required');

                    if ($is_customer_exist != 2) {
                        $this->form_validation->set_rules('new_password', trans('password'), 'required');
                    }

                    if ($this->form_validation->run() === false) {
                        $error = strip_tags(validation_errors());
                        echo json_encode(array('st'=>3,'error'=> $error));
                        exit();
                    }
                    
                    $password = hash_password($this->input->post('new_password'));
                    $role = 'customer';

                    if ($is_customer_exist == 2) {
                        $password = hash_password('1234');
                        $role = 'guest';
                    }

                } else {
                    $role = 'customer';

                    if (empty(customer())) {
                        $customer = $this->common_model->check_customer($this->input->post('user_name'));
                        if (empty($customer)) {
                            $msg = trans('incorrect-username-or-password');
                            echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
                        }

                        if (password_verify($this->input->post('old_password'), $customer->password)) {
                            $password = $customer->password;
                            $customer_id = $customer->id; 
                        }else{
                            $msg = trans('incorrect-username-or-password');
                            echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
                        }
                        
                    }else{
                        $customer_id = customer()->id;
                        $password = customer()->password;
                    }

                }

   
                $mail =  strtolower(trim($this->input->post('email', true)));
                $phone = '+'.$this->input->post('carrierCode', true).''.$this->input->post('phone', true);
                
                $newuser_data=array(
                    'user_id' => $company->user_id,
                    'business_id' => $company->uid,
                    'name' => $this->input->post('name', true),
                    'email' => $this->input->post('email', true),
                    'phone' => $phone,
                    'time_zone' => $this->input->post('time_zone', true),
                    'role' => $role,
                    'status' => 1,
                    'image' => 'assets/images/no-photo.png',
                    'thumb' => 'assets/images/no-photo-sm.png',
                    'password' => $password,
                    'created_at' => user_date_now($company->time_zone),
                );

                //insert new customer
                $newuser_data = $this->security->xss_clean($newuser_data);
                if ($is_customer_exist == 0) {
                    $check_phone = $this->auth_model->check_customer_phone($phone);
                    $check_email = $this->auth_model->check_duplicate_email($mail);

                    if (!empty($phone) && $check_phone){
                        $msg = trans('phone-exist');
                        echo json_encode(array('st'=>6, 'msg' => $msg));
                        exit();
                    } 

                    if (!empty($mail) && $check_email){
                        $msg = trans('email-exist');
                        echo json_encode(array('st'=>6, 'msg' => $msg));
                        exit();
                    } 
                    $customer_id = $this->admin_model->insert($newuser_data, 'customers');
                }

                //insert guest
                if ($is_customer_exist == 2) {
                    $customer_id = $this->admin_model->insert($newuser_data, 'customers');
                }


                if ($this->input->post('staff_id') == 0) {
                    //$random_staff = $this->common_model->get_random_staffs($company->uid, $this->input->post('service_id'), 'services');
                    //$staff_id = $random_staff->id;
                    $staff_id = 0;
                } else {
                    $staff_id = $this->input->post('staff_id');
                }

                if (empty($this->input->post('location_id'))) {
                    $location_id = 0;
                }else{
                   $location_id = $this->input->post('location_id'); 
                }

                if (empty($this->input->post('sub_location_id'))) {
                    $sub_location_id = 0;
                }else{
                    $sub_location_id = $this->input->post('sub_location_id'); 
                }

                if (empty($this->input->post('group_booking'))) {
                    $group_booking = 0;
                    $total_person = 0;
                }else{
                    $group_booking = $this->input->post('group_booking'); 
                    $total_person = $this->input->post('total_person'); 
                }



                // Reucrring service //-- new code

                if (empty($this->input->post('service_extra'))) {
                    $service_extra = 0;
                }else{
                    $service_extra_aray = $this->input->post('service_extra');
                    $service_extra = implode(",", $service_extra_aray); 
                }


                $service = $this->admin_model->get_by_id($this->input->post('service_id'), 'services');

                if($service->service_type == 2){
                    $date = new DateTime($this->input->post('date'));
                    $date->modify('+'.$service->service_repeat.'day');
                    $next_date = $date->format('Y-m-d');

                    $next_recur_date = $next_date;
                    $recurring_count= 1 ;
                    $is_completed = 0;
                }else{
                    $next_recur_date = '';
                    $recurring_count= 0 ;
                    $is_completed = 0;
                }

                // Recurring service end
                $booking_number = random_string('numeric',5);

                $booking_data = array(
                    'number' => $booking_number,
                    'user_id' => $company->user_id,
                    'business_id' => $company->uid,
                    'customer_id' => $customer_id,
                    'service_id' => $this->input->post('service_id', true),
                    'note' => $this->input->post('note'),
                    'location_id' => $location_id,
                    'sub_location_id' => $sub_location_id,
                    'group_booking' => $group_booking,
                    'total_person' => $total_person,
                    'staff_id' => $staff_id,
                    'date' => $this->input->post('date', true),
                    'time' => $this->input->post('time', true),
                    'duration' => $this->input->post('duration', true),
                    'status' => 0,
                    'recurring_count' => $recurring_count, // new code
                    'next_recur_date' => $next_recur_date, // new code 
                    'is_completed' => $is_completed, // new code
                    'service_extra' => $service_extra, // new code
                    'pay_info' => $this->input->post('pay_info', true),
                    'created_at' => user_date_now($company->time_zone)
                );

                $customer = $this->admin_model->get_by_id($customer_id, 'customers');
                $service = $this->admin_model->get_by_id($this->input->post('service_id'), 'services');
                $location = $this->admin_model->get_by_id($location_id, 'locations');

                // convert to customer time
                $time_slot = explode('-', $this->input->post('time', true));
                $time_slot_start = $time_slot[0];
                $time_slot_end = $time_slot[1];

                $from_timezone = get_by_id($company->time_zone,'time_zone')->name;
                $to_timezone = get_by_id($customer->time_zone,'time_zone')->name;
                
                
                $start_time = date("H:i", strtotime($time_slot_start));
                $converted_start_time = convert_timezone($start_time,$from_timezone,$to_timezone);
                $end_time = date("H:i", strtotime($time_slot_end));
                $converted_end_time = convert_timezone($end_time,$from_timezone,$to_timezone);
                $convert_time = $converted_start_time.'-'.$converted_end_time;

                if (empty($to_timezone)) {
                    $convert_time = $this->input->post('time');
                }
                // convert to customer time





                //load session data for sending email & sms on confirm_pay_booking function
                $sess_booking_data = array(
                    'convert_time' => $convert_time,
                    'booking_number_text' => trans('booking-number').': #'.$booking_number,
                    'serviceId' => $service->id,
                    'customerId' => $customer_id,
                    'locationId' => $location_id,
                    'staffId' => $staff_id,
                    'date' => $this->input->post('date', true),
                    'time' => $this->input->post('time', true)
                );
                $this->session->set_userdata($sess_booking_data);


                //Send Notifications
                $notify = array(
                    'user_id' => $company->user_id,
                    'action_id' => $booking_number,
                    'content_id' => 0,
                    'text' => $service->name.' - '.trans('new-appointment-is-booked'),
                    'noti_type' => 8,
                    'noti_time' => my_date_now()
                );
                $notify = $this->security->xss_clean($notify);
                $this->common_model->insert($notify, 'notifications');




                // old user
                if ($is_customer_exist == 1) {
                    $exist_customer = $this->auth_model->validate_customer();
                    
                    if (empty(customer())) {
                        if(password_verify($this->input->post('old_password'), $exist_customer->password)){
                            $data = array(
                                'id' => $exist_customer->id,
                                'name' => $exist_customer->name,
                                'thumb' => $exist_customer->thumb,
                                'email' =>$exist_customer->email,
                                'role' => 'customer',
                                'parent' => 0,
                                'logged_in' => TRUE
                            );
                            $data = $this->security->xss_clean($data);
                            $this->session->set_userdata($data);
                            $appointment_id = $this->admin_model->insert($booking_data, 'appointments');

                            $old_cusdata = array(
                                'time_zone' => $this->input->post('time_zone', true),
                            );
                            $this->admin_model->edit_option($old_cusdata, $exist_customer->id, 'customers');
                    

                            // custom input start / New code
                            $input_titles = $this->input->post('input_title');
                            $input_names = $this->input->post('input_name');
                            
                            if (!empty($input_names)) {
                                $p = 0;
                                foreach ($input_names as $name) {
                                    $input_data = array(
                                        'user_id' => $company->user_id,
                                        'business_id' => $company->uid,
                                        'booking_id' => $appointment_id,
                                        'title' => $input_titles[$p],
                                        'answer' => $name,
                                        'created_at' => user_date_now($company->time_zone),
                                    );
                                    $input_data = $this->security->xss_clean($input_data);
                                    $this->admin_model->insert($input_data, 'custom_form_answer');
                                    
                                    $p++;    
                                }
                            }
                            // custom input end


                            $url = base_url('confirm_booking/'.$slug.'/'.md5($appointment_id));
                            $msg = trans('appointment-booked-successfully');

                            echo json_encode(array('st'=>1,'url'=> $url, 'msg' => $msg));
                        }else{ 
                            $msg = trans('incorrect-username-or-password');
                            echo json_encode(array('st'=>0, 'msg' => $msg));
                        }
                    }else{
                        $ses_data = array(
                            'id' => customer()->id,
                            'name' => customer()->name,
                            'thumb' => customer()->thumb,
                            'email' =>customer()->email,
                            'role' => 'customer',
                            'parent' => 0,
                            'logged_in' => TRUE
                        );
                        $this->session->set_userdata($ses_data);

                        $appointment_id = $this->admin_model->insert($booking_data, 'appointments');

                        // custom input start / New code
                        $input_titles = $this->input->post('input_title');
                        $input_names = $this->input->post('input_name');

                        if (!empty($input_names)) {
                            $p=0;
                            foreach ($input_names as $name) {
                                $input_data = array(
                                    'user_id' => $company->user_id,
                                    'business_id' => $company->uid,
                                    'booking_id' => $appointment_id,
                                    'title' => $input_titles[$p],
                                    'answer' => $name,
                                    'created_at' => user_date_now($company->time_zone),
                                );
                                $input_data = $this->security->xss_clean($input_data);
                                $this->admin_model->insert($input_data, 'custom_form_answer');
                                $p++;   
                            }
                        }
                        // custom input end

                        $url = base_url('confirm_booking/'.$slug.'/'.md5($appointment_id));
                        $msg = trans('appointment-booked-successfully');
                        echo json_encode(array('st'=>1,'url'=> $url, 'msg' => $msg));
                    }

                }else {

                    $appointment_id = $this->admin_model->insert($booking_data, 'appointments');

                    // custom input start // New code
                    $input_titles = $this->input->post('input_title');
                    $input_names = $this->input->post('input_name');

                    if (!empty($input_names)) {
                        $p=0;
                        foreach ($input_names as $name) {
                            $input_data = array(
                                'user_id' => $company->user_id,
                                'business_id' => $company->uid,
                                'booking_id' => $appointment_id,
                                'title' => $input_titles[$p],
                                'answer' => $name,
                                'created_at' => user_date_now($company->time_zone),
                            );
                            $input_data = $this->security->xss_clean($input_data);
                            $this->admin_model->insert($input_data, 'custom_form_answer');
                            $p++;    
                        }
                    }
                    // custom input end

                    $register = $this->common_model->get_by_id($customer_id, 'customers');
                    $data = array(
                        'id' => $register->id,
                        'name' => $register->name,
                        'thumb' => $register->thumb,
                        'email' =>$register->email,
                        'role' => $register->role,
                        'parent' => 0,
                        'logged_in' => TRUE
                    );
                    $data = $this->security->xss_clean($data);
                    $this->session->set_userdata($data);

                    $msg = trans('appointment-booked-successfully');
                    $url = base_url('confirm_booking/'.$slug.'/'.md5($appointment_id));
                    echo json_encode(array('st'=>1, 'msg' => $msg, 'url'=> $url));
                }
                

            }
        }
    }



    public function book_embed_appointment($slug="")
    {   
        $company = $this->common_model->get_by_slug($slug, 'business');
        $this->set_language($company->lang);
        $id = $company->user_id;
        $is_customer_exist = $this->input->post('is_customer_exist');
        $user = $this->common_model->get_by_id($id, 'users');
        $date = $this->input->post('date');
        $date = date("Y-m-d", strtotime($date));

        if ($_POST) {


                if (date('Y-m-d') > $date) {  
                    $msg = trans('please-enter-a-valid-date');
                    echo json_encode(array('st'=>2, 'msg' => $msg)); exit();
                }

                if ($is_customer_exist == 0 || $is_customer_exist == 2) {
                    $this->form_validation->set_rules('email', trans('email'), 'required');
                    $this->form_validation->set_rules('phone', trans('phone'), 'required');

                    if ($is_customer_exist != 2) {
                        $this->form_validation->set_rules('new_password', trans('password'), 'required');
                    }

                    if ($this->form_validation->run() === false) {
                        $error = strip_tags(validation_errors());
                        echo json_encode(array('st'=>3,'error'=> $error));
                        exit();
                    }
                    
                    $password = hash_password($this->input->post('new_password'));
                    $role = 'customer';

                    if ($is_customer_exist == 2) {
                        $password = hash_password('1234');
                        $role = 'guest';
                    }

                } else {

                    $role = 'customer';
                    if (empty(customer())) {
                        $customer = $this->common_model->check_customer($this->input->post('user_name'));
                        if (empty($customer)) {
                            $msg = trans('incorrect-username-or-password');
                            echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
                        }

                        $password = $customer->password;
                        $customer_id = $customer->id;
                    }else{
                        $customer_id = customer()->id;
                        $password = customer()->password;
                    }
                }

   
                $mail =  strtolower(trim($this->input->post('email', true)));
                $phone = '+'.$this->input->post('carrierCode', true).''.$this->input->post('phone', true);
                
                $newuser_data=array(
                    'user_id' => $company->user_id,
                    'business_id' => $company->uid,
                    'name' => $this->input->post('name', true),
                    'email' => $this->input->post('email', true),
                    'phone' => $phone,
                    'role' => $role,
                    'status' => 1,
                    'image' => 'assets/images/no-photo.png',
                    'thumb' => 'assets/images/no-photo-sm.png',
                    'password' => $password,
                    'created_at' => user_date_now($company->time_zone),
                );

                //insert new customer
                $newuser_data = $this->security->xss_clean($newuser_data);
                if ($is_customer_exist == 0) {
                    $check_phone = $this->auth_model->check_customer_phone($phone);
                    $check_email = $this->auth_model->check_duplicate_email($mail);

                    if (!empty($phone) && $check_phone){
                        $msg = trans('phone-exist');
                        echo json_encode(array('st'=>6, 'msg' => $msg));
                        exit();
                    } 

                    if (!empty($mail) && $check_email){
                        $msg = trans('email-exist');
                        echo json_encode(array('st'=>6, 'msg' => $msg));
                        exit();
                    } 
                    $customer_id = $this->admin_model->insert($newuser_data, 'customers');
                }

                //insert guest
                if ($is_customer_exist == 2) {
                    $customer_id = $this->admin_model->insert($newuser_data, 'customers');
                }


                if ($this->input->post('staff_id') == 0) {
                    //$random_staff = $this->common_model->get_random_staffs($company->uid, $this->input->post('service_id'), 'services');
                    //$staff_id = $random_staff->id;
                    $staff_id = 0;
                } else {
                    $staff_id = $this->input->post('staff_id');
                }

                if (empty($this->input->post('location_id'))) {
                    $location_id = 0;
                }else{
                   $location_id = $this->input->post('location_id'); 
                }

                if (empty($this->input->post('sub_location_id'))) {
                    $sub_location_id = 0;
                }else{
                    $sub_location_id = $this->input->post('sub_location_id'); 
                }

                if (empty($this->input->post('group_booking'))) {
                    $group_booking = 0;
                    $total_person = 0;
                }else{
                    $group_booking = $this->input->post('group_booking'); 
                    $total_person = $this->input->post('total_person'); 
                }


                // Reucrring service //-- new code

                if (empty($this->input->post('service_extra'))) {
                    $service_extra = 0;
                }else{
                    $service_extra_aray = $this->input->post('service_extra');
                    $service_extra = implode(",", $service_extra_aray); 
                }


                $service = $this->admin_model->get_by_id($this->input->post('service_id'), 'services');

                if($service->service_type == 2){
                    $date = new DateTime($this->input->post('date'));
                    $date->modify('+'.$service->service_repeat.'day');
                    $next_date = $date->format('Y-m-d');

                    $next_recur_date = $next_date;
                    $recurring_count= 1 ;
                    $is_completed = 0;
                }else{
                    $next_recur_date = '';
                    $recurring_count= 0 ;
                    $is_completed = 0;
                }

                // Recurring service end
                

                $booking_data = array(
                    'number' => random_string('numeric',5),
                    'user_id' => $company->user_id,
                    'business_id' => $company->uid,
                    'customer_id' => $customer_id,
                    'service_id' => $this->input->post('service_id', true),
                    'note' => $this->input->post('note'),
                    'location_id' => $location_id,
                    'sub_location_id' => $sub_location_id,
                    'group_booking' => $group_booking,
                    'total_person' => $total_person,
                    'staff_id' => $staff_id,
                    'date' => $this->input->post('date', true),
                    'time' => $this->input->post('time', true),
                    'status' => 0,
                    'recurring_count' => $recurring_count, // new code
                    'next_recur_date' => $next_recur_date, // new code 
                    'is_completed' => $is_completed, // new code
                    'service_extra' => $service_extra, // new code
                    'pay_info' => $this->input->post('pay_info', true),
                    'created_at' => user_date_now($company->time_zone)
                );
                
                // send sms to customer
                if ($user->enable_sms_notify == 1) {
                    $this->load->model('sms_model');
                    $customer = $this->admin_model->get_by_id($customer_id, 'customers');
                    $service = $this->admin_model->get_by_id($this->input->post('service_id'), 'services');
                    $message = trans('appointment').' '.$company->name.' - '.$service->name.' '.trans('booking-is-confirmed-at').' '.$this->input->post('date').' '.$this->input->post('time');
                    
                    //check twillo api keys
                    if (!empty($user->twillo_account_sid) && !empty($user->twillo_auth_token)) {
                        //$response = $this->sms_model->send_user($customer->phone, $message, $user->id);
                    }
                }
                
                // send email to customer
                $customer = $this->admin_model->get_by_id($customer_id, 'customers');
                $service = $this->admin_model->get_by_id($this->input->post('service_id'), 'services');
                $subject = trans('appointment-confirmation').' - '.$this->settings->site_name;
                $message = trans('appointment').' '.$company->name.' - '.$service->name.' '.trans('booking-is-confirmed-at').' '.$this->input->post('date').' '.$this->input->post('time');
                //$this->email_model->send_email($customer->email, $subject, $message);


                // send email to user
                $subject = $service->name.' - '.trans('new-appointment-is-booked');
                $message = $customer->name.', '.trans('recently-booked-an-appointment').' '.$this->input->post('date').' '.$this->input->post('time');
                //$this->email_model->send_email($company->email, $subject, $message);


                // send email to staff
                $staff_info = $this->admin_model->get_by_id($staff_id, 'staffs');
                $subject = $service->name.' - '.trans('new-appointment-is-booked');
                $message = $customer->name.', '.trans('recently-booked-an-appointment').' '.$this->input->post('date').' '.$this->input->post('time');
                //$this->email_model->send_email($staff_info->email, $subject, $message);
                

                // old user
                if ($is_customer_exist == 1) {
                    $exist_customer = $this->auth_model->validate_customer();
                    
                    if (empty(customer())) {
                        if(password_verify($this->input->post('old_password'), $exist_customer->password)){
                            $data = array(
                                'id' => $exist_customer->id,
                                'name' => $exist_customer->name,
                                'thumb' => $exist_customer->thumb,
                                'email' =>$exist_customer->email,
                                'role' => 'customer',
                                'parent' => 0,
                                'logged_in' => TRUE
                            );
                            $data = $this->security->xss_clean($data);
                            $this->session->set_userdata($data);
                        
                            $appointment_id = $this->admin_model->insert($booking_data, 'appointments');

                            // custom input start / New code
                            $input_titles = $this->input->post('input_title');
                            $input_names = $this->input->post('input_name');
                            
                            if (!empty($input_names)) {
                                $p = 0;
                                foreach ($input_names as $name) {
                                    $input_data = array(
                                        'user_id' => $company->user_id,
                                        'business_id' => $company->uid,
                                        'booking_id' => $appointment_id,
                                        'title' => $input_titles[$p],
                                        'answer' => $name,
                                        'created_at' => user_date_now($company->time_zone),
                                    );
                                    $input_data = $this->security->xss_clean($input_data);
                                    $this->admin_model->insert($input_data, 'custom_form_answer');
                                    
                                    $p++;    
                                }
                            }
                            // custom input end


                            $url = base_url('confirm_booking/'.$slug.'/'.md5($appointment_id).'?embed=true');
                            $msg = trans('appointment-booked-successfully');

                            echo json_encode(array('st'=>1,'url'=> $url, 'msg' => $msg));
                        }else{ 
                            $msg = trans('incorrect-username-or-password');
                            echo json_encode(array('st'=>0, 'msg' => $msg));
                        }
                    }else{
                        $ses_data = array(
                            'id' => customer()->id,
                            'name' => customer()->name,
                            'thumb' => customer()->thumb,
                            'email' =>customer()->email,
                            'role' => 'customer',
                            'parent' => 0,
                            'logged_in' => TRUE
                        );
                        $this->session->set_userdata($ses_data);

                        $appointment_id = $this->admin_model->insert($booking_data, 'appointments');

                        // custom input start / New code
                        $input_titles = $this->input->post('input_title');
                        $input_names = $this->input->post('input_name');

                        if (!empty($input_names)) {
                            $p = 0;
                            foreach ($input_names as $name) {
                                $input_data = array(
                                    'user_id' => $company->user_id,
                                    'business_id' => $company->uid,
                                    'booking_id' => $appointment_id,
                                    'title' => $input_titles[$p],
                                    'answer' => $name,
                                    'created_at' => user_date_now($company->time_zone),
                                );
                                $input_data = $this->security->xss_clean($input_data);
                                $this->admin_model->insert($input_data, 'custom_form_answer');
                                
                                $p++;    
                            }
                        }
                        // custom input end

                        $url = base_url('confirm_booking/'.$slug.'/'.md5($appointment_id).'?embed=true');
                        $msg = trans('appointment-booked-successfully');
                        echo json_encode(array('st'=>1,'url'=> $url, 'msg' => $msg));
                    }

                }else {

                    $appointment_id = $this->admin_model->insert($booking_data, 'appointments');

                    // custom input start // New code
                    $input_titles = $this->input->post('input_title');
                    $input_names = $this->input->post('input_name');

                    if (!empty($input_names)) {
                        $p = 0;
                        foreach ($input_names as $name) {
                            $input_data = array(
                                'user_id' => $company->user_id,
                                'business_id' => $company->uid,
                                'booking_id' => $appointment_id,
                                'title' => $input_titles[$p],
                                'answer' => $name,
                                'created_at' => user_date_now($company->time_zone),
                            );
                            $input_data = $this->security->xss_clean($input_data);
                            $this->admin_model->insert($input_data, 'custom_form_answer');
                            
                            $p++;    
                        }
                    }
                    // custom input end

                    
                    $register = $this->common_model->get_by_id($customer_id, 'customers');
                    $data = array(
                        'id' => $register->id,
                        'name' => $register->name,
                        'thumb' => $register->thumb,
                        'email' =>$register->email,
                        'role' => $register->role,
                        'parent' => 0,
                        'logged_in' => TRUE
                    );
                    $data = $this->security->xss_clean($data);
                    $this->session->set_userdata($data);

                    $msg = trans('appointment-booked-successfully');
                    $url = base_url('confirm_booking/'.$slug.'/'.md5($appointment_id).'?embed=true');
                    echo json_encode(array('st'=>1, 'msg' => $msg, 'url'=> $url));
                }

            
        }
    }

  

    public function terms($slug=''){

        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = trans('terms-and-conditions');
        $data['type'] = 'terms';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $this->set_language($data['company']->lang);
        $data['main_content'] = $this->load->view('templates/common/terms_privacy', $data, TRUE);
        $this->load->view('index', $data);
    }

    public function privacy($slug=''){
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = trans('privacy-policy');
        $data['menu'] = FALSE;
        $data['type'] = 'privacy';
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $this->set_language($data['company']->lang);
        $data['main_content'] = $this->load->view('templates/common/terms_privacy', $data, TRUE);
        $this->load->view('index', $data);

    }


    public function store_time_zone($time_zone){
    
        $this->session->set_userdata('time_zone', $time_zone);
        
    }




    // Event code start




    public function events($slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Events';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $this->set_language($data['company']->lang);
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;

        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }

        if (check_user_feature_access($user->id, 'events') != TRUE) {
            redirect(base_url());
            exit();
        }

        $data['events'] = $this->event_model->get_company_events($data['company']->uid);
        $data['main_content'] = $this->load->view('templates/common/events', $data, TRUE);
        $this->load->view('index', $data);
    }


    public function event($event_slug, $slug="")
    {   
        //echo "string"; exit();
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Event';
        $data['menu'] = FALSE;
        $data['countdown'] = 'TRUE';
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['time_zones'] = $this->common_model->select_asc('time_zone');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');

        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }


        $this->set_language($data['company']->lang);
        $data['event'] = $this->event_model->get_event_slug_by_company($event_slug, 'events', $data['company']->uid);
        $data['main_content'] = $this->load->view('templates/common/event_details', $data, TRUE);
        $this->load->view('index', $data);
    }



    public function event_booking($slug="")
    {   

        $ticket_id = $this->input->post('ticket_id');
        $ticket = $this->common_model->get_by_id($ticket_id,'event_ticket');
        $event_id = $this->input->post('event_id');
        $event = $this->common_model->get_by_id($event_id,'events');
        $quantity = $this->input->post('quantity');

        $time_zone = $this->input->post('event_time_zone');


        if ($quantity > $ticket->tickets_per_attendee) {
            $this->session->set_flashdata('error', trans('ticket-limit-msg').' '.$ticket->tickets_per_attendee.' '.trans('tickets')); 
            redirect($_SERVER['HTTP_REFERER']);
            
        }

        if($quantity > $ticket->limit){
                $this->session->set_flashdata('error', trans('sorry-we-have-only').' '.$ticket->limit.' '.trans('more-tickets-available')); 
                redirect($_SERVER['HTTP_REFERER']);
        }


        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }
       
        $this->session->unset_userdata('ebook_number');
        $data['ebook_number'] = random_string('numeric',5);
        $this->session->set_userdata('ebook_number', $data['ebook_number']);

        $data['page'] = 'Company';
        $data['page_title'] = 'Event';
        $data['menu'] = FALSE;
        $data['countdown'] = 'TRUE';
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $this->set_language($data['company']->lang);
        $data['event'] = $event;
        $data['quantity'] = $quantity;
        $data['ticket'] = $ticket;
        $data['time_zone'] = $time_zone;
        $data['main_content'] = $this->load->view('templates/common/event_booking', $data, TRUE);
        $this->load->view('index', $data);


       
    }


    public function confirm_event_booking($slug=''){


        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $company = $this->common_model->get_by_slug($slug, 'business');
        $this->set_language($company->lang);
        if (empty($slug)) {
            $slug = $company->slug;
        }

        $id = $company->user_id;
        $is_customer_exist = $this->input->post('is_customer_exist');
       
        $user = $this->common_model->get_by_id($id, 'users');
        $ebook_number = $this->session->userdata('ebook_number');


        if ($_POST) {

            if ($is_customer_exist == 0 || $is_customer_exist == 2) {
                $this->form_validation->set_rules('email', trans('email'), 'required');
                $this->form_validation->set_rules('phone', trans('phone'), 'required');

                if ($is_customer_exist != 2) {
                    $this->form_validation->set_rules('new_password', trans('password'), 'required');
                }

                if ($this->form_validation->run() === false) {
                    $error = strip_tags(validation_errors());
                    echo json_encode(array('st'=>3,'error'=> $error));
                    exit();
                }
                
                $password = hash_password($this->input->post('new_password'));
                $role = 'customer';

                if ($is_customer_exist == 2) {
                    $password = hash_password('1234');
                    $role = 'guest';
                }

            } else {
                $role = 'customer';

                if (empty(customer())) {
                    $customer = $this->common_model->check_customer($this->input->post('user_name'));
                    if (empty($customer)) {
                        $msg = trans('incorrect-username-or-password');
                        echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
                    }

                    if (password_verify($this->input->post('old_password'), $customer->password)) {
                        $password = $customer->password;
                        $customer_id = $customer->id; 
                    }else{
                        $msg = trans('incorrect-username-or-password');
                        echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
                    }
                    
                }else{
                    $customer_id = customer()->id;
                    $password = customer()->password;
                }

            }


            $mail =  strtolower(trim($this->input->post('email', true)));
            $phone = '+'.$this->input->post('carrierCode', true).''.$this->input->post('phone', true);
            
            $event_id = $this->input->post('event_id', true);
            $ticket_id = $this->input->post('ticket_id', true);

            $event = get_by_id($event_id, 'events');
            $ticket = get_by_id($ticket_id, 'event_ticket');
            $ticket_price = $ticket->price;
            $ticket_limit = $ticket->limit;

            $total_slot = $this->input->post('quantity', true);
            $total_price =  $total_slot * $ticket_price ;


            $newuser_data=array(
                'user_id' => $company->user_id,
                'business_id' => $company->uid,
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'phone' => $phone,
                'time_zone' => $this->input->post('time_zone', true),
                'role' => $role,
                'status' => 1,
                'image' => 'assets/images/no-photo.png',
                'thumb' => 'assets/images/no-photo-sm.png',
                'password' => $password,
                'created_at' => user_date_now($company->time_zone),
            );

            //insert new customer
            $newuser_data = $this->security->xss_clean($newuser_data);
            if ($is_customer_exist == 0) {
                $check_phone = $this->auth_model->check_customer_phone($phone);
                $check_email = $this->auth_model->check_duplicate_email($mail);

                if (!empty($phone) && $check_phone){
                    $msg = trans('phone-exist');
                    echo json_encode(array('st'=>6, 'msg' => $msg));
                    exit();
                } 

                if (!empty($mail) && $check_email){
                    $msg = trans('email-exist');
                    echo json_encode(array('st'=>6, 'msg' => $msg));
                    exit();
                } 
                $customer_id = $this->admin_model->insert($newuser_data, 'customers');
            }

            if ($is_customer_exist == 2) {
                $customer_id = $this->admin_model->insert($newuser_data, 'customers');
            }



            $booking_data = array(
                'booking_number' => $ebook_number,
                'user_id' => $event->user_id,
                'business_id' => $event->business_id,
                'customer_id' => $customer_id,
                'event_id' => $event_id,
                'ticket_id' => $ticket_id,
                'venue_id' => $event->venue,
                'date' => $event->date,
                'time' => $event->time,
                'status' => 0,
                'total_slot' => $this->input->post('quantity', true),
                'total_price' => $total_price,
                'created_at' => user_date_now($company->time_zone)
            );

            $check_booking = $this->admin_model->check_duplicate_event_booking($ebook_number, $customer_id);

            $customer = $this->admin_model->get_by_id($customer_id, 'customers');

            $notify = array(
                'user_id' => $event->user_id,
                'action_id' => 0,
                'content_id' => 0,
                'text' => $customer->name.' '.trans('has-just-booked-an-event').' - '.$event->name,
                'noti_type' => 12,
                'noti_time' => my_date_now()
            );
            $notify = $this->security->xss_clean($notify);
            $this->common_model->insert($notify, 'notifications');


            $text_content = 'Hello '.$customer->name.', \n
                '.trans('here-is-the-status-of-your-appointment').':  \n
                ✅ '.trans('confirmed-event').': '.$event->name.'  \n
                🗓️ '.trans('date').': '.$event->date.'  \n
                ⏰ '.trans('time').': '.$event->time.' \n
                '.trans('if-you-have-any-questions-we-are-here-to-help').'  \n
                - '.$company->name. ' '.trans('team');

            // send sms to customer
            if ($user->enable_sms_notify == 1 || settings()->global_twilio == 1) {
                $this->load->model('sms_model');
                
                //$content = trans('event-booking-confirmation').' - '.$event->name.' '.trans('has-been-successfully-confirmed').' '.$event->date;
                
                //check twillo api keys
                if ($user->enable_sms_notify == 1 && !empty($user->twillo_account_sid) && !empty($user->twillo_auth_token)) {
                    $response = $this->sms_model->send_user($customer->phone, $text_content, $user->id);
                }

                //check twillo api keys for global
                if (settings()->global_twilio == 1 && !empty(settings()->twillo_account_sid) && !empty(settings()->twillo_auth_token)) {
                    $response = $this->sms_model->send_user($customer->phone, $text_content, $user->id);
                }
            }


            // send whatsapp to customer
            if ($company->enable_whatsapp_msg == 1 || settings()->global_wapp_msg == 1) {
                $this->load->model('sms_model');
                
                //check ultramsg api keys
                if ($company->enable_whatsapp_msg == 1 && settings()->global_wapp_msg == 0) {
                    $response = $this->sms_model->send_whatsapp_user($customer->phone, $text_content, $company);
                    //echo "<pre>"; print_r($response ); exit();
                }

                //check ultramsg api keys for global
                if (settings()->global_wapp_msg == 1) {
                    $response = $this->sms_model->send_whatsapp_user($customer->phone, $text_content, 'settings');
                }
            }


            // send email to customer


            $subject = get_email_by_slug('event-booking-customer')->subject;
            $body = get_email_by_slug('event-booking-customer')->body;
            $variables_data = [
                'customer_name'  =>$customer->name,
                'event_name' => $event->name,
                'event_date' => $event->date,
                'event_time' => $event->time,
                'business_name' => $company->name,
            ]; 

            $msg = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables_data) {
                $key = trim($matches[1]);
                return isset($variables_data[$key]) ? $variables_data[$key] : $matches[0]; 
            }, $body);

            $edata = array();
            $edata['subject'] = $subject;
            $edata['msg'] = $msg;

            $msg = $this->load->view('email_template/common', $edata, true);
            $this->email_model->send_email($customer->email, $subject, $msg);
            


            //send email to user

            $subject = get_email_by_slug('event-booking-company')->subject;
            $body = get_email_by_slug('event-booking-company')->body;
            $variables_data = [
                'customer_name'  =>$customer->name,
                'event_name' => $event->name,
                'event_date' => $event->date,
            ]; 

            $msg = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($variables_data) {
                $key = trim($matches[1]);
                return isset($variables_data[$key]) ? $variables_data[$key] : $matches[0]; 
            }, $body);

            $edata = array();
            $edata['subject'] = $subject;
            $edata['msg'] = $msg;

            $msg = $this->load->view('email_template/common', $edata, true);
            $this->email_model->send_email($company->email, $subject, $msg);
            


            // old user
            if ($is_customer_exist == 1) {
                $exist_customer = $this->auth_model->validate_customer();
                
                if ($check_booking == false) {
                    $booking_id = $this->admin_model->insert($booking_data, 'event_booking');
                }else{
                    $response_booking = $this->admin_model->get_ebook_enumber_cus($ebook_number, $customer_id);
                    $booking_id = $response_booking->id;
                }

                if (empty(customer())) {
                    if(password_verify($this->input->post('old_password'), $exist_customer->password)){
                        $data = array(
                            'id' => $exist_customer->id,
                            'name' => $exist_customer->name,
                            'thumb' => $exist_customer->thumb,
                            'email' =>$exist_customer->email,
                            'role' => 'customer',
                            'parent' => 0,
                            'logged_in' => TRUE
                        );
                        $data = $this->security->xss_clean($data);
                        $this->session->set_userdata($data);
                        //$booking_id = $this->admin_model->insert($booking_data, 'event_booking');



                        $limit_data = array(
                            'limit' => $ticket_limit - $this->input->post('quantity', true),
                        );
                        $this->session->set_userdata($limit_data);
                        $this->admin_model->edit_option($limit_data, $ticket_id, 'event_ticket');


                        $sales_data = array(
                            'total_sales' => $event->total_sales + $this->input->post('quantity', true),
                        );
                        $this->session->set_userdata($sales_data);
                        $this->admin_model->edit_option($sales_data, $event_id, 'events');
                    }else{ 
                        $msg = trans('incorrect-username-or-password');
                        echo json_encode(array('st'=>0, 'msg' => $msg));
                    }
                }else{
                    $ses_data = array(
                        'id' => customer()->id,
                        'name' => customer()->name,
                        'thumb' => customer()->thumb,
                        'email' =>customer()->email,
                        'role' => 'customer',
                        'parent' => 0,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($ses_data);

                    //$booking_id = $this->admin_model->insert($booking_data, 'event_booking');


                    $limit_data = array(
                        'limit' => $ticket_limit - $this->input->post('quantity', true),
                    );

                    $this->session->set_userdata($limit_data);
                    $this->admin_model->edit_option($limit_data, $ticket_id, 'event_ticket');
                   

                    $sales_data = array(
                        'total_sales' => $event->total_sales + $this->input->post('quantity', true),
                    );
                    $this->session->set_userdata($sales_data);
                    $this->admin_model->edit_option($sales_data, $event_id, 'events');
                }

                $url = base_url('event_booking_details/'.$slug.'/'.md5($booking_id));
                $msg = trans('event-booked-successfully');
                echo json_encode(array('st'=>1, 'url' =>  $url, 'msg' => $msg)); exit();

            }else {

                if ($check_booking == false) {
                    $booking_id = $this->admin_model->insert($booking_data, 'event_booking');
                }else{
                    $response_booking = $this->admin_model->get_ebook_enumber_cus($ebook_number, $customer_id);
                    $booking_id = $response_booking->id;
                }

                 $limit_data = array(
                    'limit' => $ticket_limit - $this->input->post('quantity', true),
                );
                $this->session->set_userdata($limit_data);
                $this->admin_model->edit_option($limit_data, $ticket_id, 'event_ticket');


                $sales_data = array(
                    'total_sales' => $event->total_sales + $this->input->post('quantity', true),
                );
                $this->session->set_userdata($sales_data);
                $this->admin_model->edit_option($sales_data, $event_id, 'events');


                $register = $this->common_model->get_by_id($customer_id, 'customers');
                $data = array(
                    'id' => $register->id,
                    'name' => $register->name,
                    'thumb' => $register->thumb,
                    'email' =>$register->email,
                    'role' => $register->role,
                    'parent' => 0,
                    'logged_in' => TRUE
                );
                $data = $this->security->xss_clean($data);
                $this->session->set_userdata($data);

                $msg = trans('event-booked-successfully');
                $url = base_url('event_booking_details/'.$slug.'/'.md5($booking_id));
                echo json_encode(array('st'=>1, 'url' =>  $url, 'msg' => $msg)); exit();
            }
            
        }


    }



    public function event_booking_details($slug, $booking_id){

      
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $data['booking_type'] = 'Event';
        $data['page'] = 'Company';
        $data['slug'] = $slug;
        $data['page_title'] = 'Event Booking Confirm';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['template'] = $data['company']->template_style;
        $template = $data['company']->template_style;
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $this->set_language($data['company']->lang);

        $data['user'] = $this->common_model->get_by_id($data['company']->user_id, 'users');

        $data['booking'] = $this->event_model->get_event_booking_by_id_md5($booking_id);
        $data['event'] = $this->common_model->get_by_id($data['booking']->event_id, 'events');
        $ses_data = array(
            'booking_id' => $data['booking']->id,
            'event_id' => $data['event']->id,
            'total_price' => $data['booking']->total_price
        );
        $this->session->set_userdata($ses_data);

        $mercado = $this->mercado_event_api_link();
        $data['init'] = $mercado['init'];


        $data['main_content'] = $this->load->view('templates/common/event_booking_details', $data, TRUE);
        $this->load->view('index', $data);
    }


    public function mercado_event(){
        $event_booking = $this->common_model->get_by_id($this->session->userdata('booking_id'), 'event_booking');
        $user = $this->admin_model->get_by_id($event_booking->user_id, 'users');
        $mercado_token = $user->mercado_token;
        $access_token = $mercado_token;

        $respuesta = array(
            'Payment' => $_GET['payment_id'],
            'Status' => $_GET['status'],
            'MerchantOrder' => $_GET['merchant_order_id']        
        ); 
        MercadoPago\SDK::setAccessToken($access_token);
        $merchant_order = $_GET['payment_id'];

        $payment = MercadoPago\Payment::find_by_id($merchant_order);
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);

        //$merchant_order->payments
        redirect(base_url('admin/payment/event_payment_success/'.$event_booking->id.'/mercadopago'));

    }

    public function mercado_event_api_link(){

        $event_booking = $this->common_model->get_by_id($this->session->userdata('booking_id'), 'event_booking');
        $user = $this->admin_model->get_by_id($event_booking->user_id, 'users');
        //echo "<pre>"; print_r($user); exit();

        $mercado_token = $user->mercado_token;
        $mercado_currency = $user->mercado_currency;

        $totalCost = $this->session->userdata('total_price');
        
        $data = [];
        MercadoPago\SDK::setAccessToken($mercado_token);
        $preference = new MercadoPago\Preference();
        // Create a preference item
        $item = new MercadoPago\Item();
        $item->title = 'Event Ticket Payment';
        $item->quantity = 1;
        $item->unit_price = $totalCost;
        $item->currency_id = $mercado_currency;
        $preference->items = array($item);
        $preference->back_urls = array(
            "success" => base_url("company/mercado_event"),
            "failure" => base_url("company/mercado_event"),
            "pending" => base_url("company/mercado_event")
        );
        $preference->auto_return = "approved";

        $preference->save();
        $data['f_id'] = $preference->id;
        $data['init'] = $preference->init_point;
        return $data;
    }

    // Event code End


    // product code start

    public function product($slug="")
    {   
        
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }
        $config['base_url'] = base_url('company/product/'.$slug);
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $total_row = $this->common_model->get_products($data['company']->uid, $total=1, 0, 0);
        $config['total_rows'] = $total_row;
        $config['per_page'] =$data['company']->pagination_limit;
        $this->pagination->initialize($config);
        $page = $this->security->xss_clean($this->input->get('page'));
        if (empty($page)) {
            $page = 0;
        }
        if ($page != 0) {
            $page = $page - 1;
        }

        $data['page'] = 'Company';
        $data['page_title'] = 'Products';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');


        if (check_user_feature_access($user->id, 'products') != TRUE) {
            redirect(base_url());
            exit();
        }


        $data['template'] = $data['company']->template_style;
        $data['products']=$this->common_model->get_products($data['company']->uid, $total=0, $config['per_page'], $page * $config['per_page']);
        $data['categories'] = $this->common_model->get_product_categories_by_company($data['company']->uid);
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
  
        $data['main_content'] = $this->load->view('templates/common/products', $data, TRUE);
        $this->load->view('index', $data);
    }

    public function product_details($product_slug,$slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }
        $data['page'] = 'Company';
        $data['page_title'] = 'Product';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['product'] = $this->common_model->get_by_slug($product_slug, 'products');
        $data['categories'] = $this->common_model->get_product_categories_by_company($data['company']->uid);
        $data['subcategories'] = $this->common_model->get_product_subcategories_by_company($data['company']->uid);
        $data['product_images'] = $this->common_model->get_product_img($data['product']->id,6);
        $data['related_products'] = $this->common_model->get_related_products($data['product']->id,$data['company']->uid, $data['product']->category_id, 4);
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $data['main_content'] = $this->load->view('templates/common/product_details', $data, TRUE);
        $this->load->view('index', $data);
    }

    //product cat page
    public function cart($slug="")
    {  
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = '/'.$slug;
        }
        $data['page'] = 'Company';
        $data['page_title'] = 'Shopping Cart';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $data['main_content'] = $this->load->view('templates/common/cart', $data, TRUE);
        $this->load->view('index', $data);
    }


    //add to cart function
    public function add_to_cart($product_id, $slug="") {
        //$this->load->library('cart');
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = $slug;
        }

        $product = $this->common_model->get_by_id($product_id, 'products');
        $product_img = $this->common_model->get_single_product_img($product_id, 1);
        //$slug = 'iconic';
        if (!empty($this->input->post('quantity', true))) {
            $quantity = $this->input->post('quantity', true);
        }else{
            $quantity = 1;
        }
        //echo "<pre>"; print_r($quantity); exit();
        if (empty($product_img)) {
            $thumb = 'assets/front/img/no-image.png';
        }else{
            $thumb = $product_img->thumb;
        }
        
        if ($product->quantity >= 1) {
            if (!empty($product)) {
               
                $price = $product->price;
                $data = array();
                $data['slug'] = $slug;
                $data['company'] = $this->common_model->get_by_slug($slug, 'business');
                $cdata = array(
                    'id' => $product_id,
                    'qty' => $quantity,
                    'price' => $price,
                    'delivery_charge' => 0,
                    'thumb' => $thumb,
                    'name' => str_replace("'", "", $product->title)
                );
                
                $this->cart->product_name_rules = '[:print:]';
                $this->cart->insert($cdata);
                $this->session->set_flashdata('msg', 'This Product has been added to your cart');
            }
        } else {
            $quantity = $product->quantity;
            $this->session->set_flashdata('msg', 'Available Stock is ' .$quantity. '. Please order in available stock.');
        }

        redirect($_SERVER['HTTP_REFERER']);
    }


    //add to cart single product
    public function add_to_cart_single($slug="") 
    {
        $product_id = $this->input->post('product_id', true);
        $product_sku_id = $this->input->post('product_sku_id', true);
        $quantity = $this->input->post('quantity', true);
        $product = $this->common_model->get_by_id($product_id, 'products');
        $product_img = $this->common_model->get_single_product_img($product_id, 1);
        
        if (empty($product_img)) {
            $thumb = 'assets/front/img/no-image.png';
            $thumb = 'assets/front/img/no-image.png';
        }else{
            $thumb = $product_img->thumb;
        }
        
        if ($product->quantity >= $quantity) {
            if (!empty($product)) {
               
                $price = $product->price;

                $discount = 0;
                if ($discount != 0) {
                    if ($pro['discount'] == 0) {
                        $price = $pro['price'];
                    } else {
                        $price = ($pro['price'] - ($pro['price'] * ($pro['discount'] / 100)));
                    }
                }

                $data = array();
                $data['slug'] = $slug;
                $data['company'] = $this->common_model->get_by_slug($slug, 'business');
                $cdata = array(
                    'id' => $product_id,
                    'qty' => $this->input->post('quantity'),
                    'price' => $price,
                    'delivery_charge' => 0,
                    'thumb' => $thumb,
                    'name' => str_replace("'", "", $product->title)
                );
                $this->cart->product_name_rules = '[:print:]';
                $this->cart->insert($cdata);
                $load_data = $this->load->view('include/header_cart', $data, TRUE);
                echo json_encode(array('st' => 1, 'cartData' => $load_data, 'totalQty' => $this->cart->total_items()));
                
            }
        } else {
            $quantity = $product->quantity;
            echo json_encode(array('st' => 0,'stock'=>$quantity));
        }
    }

    //check product quantity
    public function quantity_check($id) {
        $product = $this->common_model->get_by_id($id, 'products');
        $quantity = $product->quantity;

        $cart = $this->cart->contents();
        $cart_qty = 0;
        foreach ($cart as $crt) {
            if ($crt['id'] == $id) {
                $cart_qty = $crt['qty'];
            }
        }

        if ($cart_qty >= $quantity) {
            return false;
        } else {
            return true;
        }
    }


    function remove_cart_item($rowid) {   
        $data = array(
            'rowid'   => $rowid,
            'qty'     => 0
        );

        $this->cart->update($data);
        echo json_encode(array('st' => 1));
    }

    function update_cart() {
        $items = $this->input->post('items', true);
        $qty = $this->input->post('qty', true);
        
        for ($i=0; $i < count($items); $i++) { 
            $data = array(
                'rowid' => $items[$i],
                'qty' => $qty[$i]
            );
            $this->cart->update($data);
        }
        $this->session->set_flashdata('msg', trans('updated-successfully'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    function distroy_cart(){
        $this->cart->destroy();
        echo json_encode(array('st' => 1));
    }

    //checkout function
    public function check_out($slug="")
    {   
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = '/'.$slug;
        }
        $data['page'] = 'Company';
        $data['page_title'] = 'Check Out';
        $data['menu'] = FALSE;
        $data['company'] = $this->common_model->get_by_slug($slug, 'business');
        $data['countries'] = $this->common_model->select('country');
        $user = $this->common_model->get_by_id($data['company']->user_id, 'users');
        if ($user->status == 2) {
            redirect(base_url());
            exit();
        }
        $data['main_content'] = $this->load->view('templates/common/check_out', $data, TRUE);
        $this->load->view('index', $data);
    }


    //submit order
    public function order_submit($slug="")
    {  
        $data = array();
        if (empty($slug)) {
            $slug = $this->check_domain();
            $data['is_cdomain'] = true;
            $data['slug'] = '';
        }else{
            $data['is_cdomain'] = false;
            $data['slug'] = '/'.$slug;
        }
        $company = $this->common_model->get_by_slug($slug, 'business');
        $type = $this->input->post('type',true);
        
        if($type == 'register'){
            $mail = $this->input->post('email',true);
            $check_email = $this->auth_model->check_duplicate_email($mail);
            if ($check_email){
                $msg = trans('email-exist');
                echo json_encode(array('st'=>0, 'msg' => $msg));
                exit();
            }

            $data = array(
                'user_id' => user()->id,
                'business_id' =>$company->uid,
                'name' =>$this->input->post('name',true), 
                'email' =>$this->input->post('email',true), 
                'password' =>hash_password($this->input->post('password',true)), 
                //'zip' =>$this->input->post('zip',true), 
                'phone' =>$this->input->post('phone',true), 
                //'country' =>$this->input->post('country',true), 
                //'city' =>$this->input->post('city',true), 
                //'state' =>$this->input->post('state',true), 
                'address' =>$this->input->post('address',true)
            );
            
            $data = $this->security->xss_clean($data);
            $customer_id = $this->admin_model->insert($data, 'customers');
            $customer = $this->common_model->get_by_id($customer_id,'customers');
        }elseif($type == 'loged_in'){
            //echo "<pre>"; print_r($type); exit();
            $customer_id = $this->session->userdata('id');
            $customer = $this->common_model->get_by_id($customer_id,'customers'); 
        }else{
            $user_name = $this->input->post('user_name',true);
            $customer = $this->common_model->check_customer_email($user_name);


            
            if (!empty($customer)) {
                $customer_id = $customer->id;
            } else {
                $msg = trans('incorrect-username-or-password');
                echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
            }

            if(!password_verify($this->input->post('password',true), $customer->password)){
                $msg = trans('incorrect-username-or-password');
                echo json_encode(array('st'=>0, 'msg' => $msg)); exit();
            }
        }

        $cus_data = array(
            'shipping_address' => $this->input->post('shipping_address', true),
        );
        $cus_data = $this->security->xss_clean($cus_data);
        $this->admin_model->edit_option($cus_data, $customer_id, 'customers');

        $data = array(
            'id' => $customer->id,
            'name' => $customer->name,
            'slug' => $customer->slug,
            'thumb' => $customer->thumb,
            'email' =>$customer->email,
            'role' =>$customer->role,
            'logged_in' => TRUE
        );
        $data = $this->security->xss_clean($data);
        $this->session->set_userdata($data);
                
        $code = random_string('numeric', 5);
        $payment_type = $this->input->post('payment_type', true);
        $odata = array( 
            'business_id' =>$company->uid,
            'customer_id' => $customer_id,
            'order_id' => $code,
            'total_items' => $this->cart->total_items(),
            'total_price' => $this->cart->total(),
            'payment_type' => $payment_type ,
            'payment_status' => 'pending',
            'order_status' => 'processing',
            'created_at' => my_date_now()
        );
        $odata = $this->security->xss_clean($odata);
        
        $order_id = $this->admin_model->insert($odata, 'product_orders');

        //echo "<pre>"; print_r(); exit();
        foreach ($this->cart->contents() as $item):
            $oldata = array( 
                'order_id' => $order_id, 
                'product_id' => $item['id'], 
                'qty' => $item['qty'],
                'price' => $item['price']
            );
            
            $this->admin_model->insert($oldata, 'product_order_lists');
        endforeach; 

        if ($payment_type == 'cod') {
            $url = base_url('customer/orders');
        }else{
            $url = base_url('customer/product_payment/'.md5($order_id));
        }

        echo json_encode(array('st' => 1, 'url'=>$url ));
        
    }

    

}

