<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        //check auth
        if (!is_staff() && !is_user()) {
            redirect(base_url());
        }

        if (check_feature_access('staffs') != TRUE){
            redirect(base_url('admin/dashboard/user'));
        }
    }


    public function index()
    {
        $data = array();
        $data['page_title'] = 'Staff';      
        $data['page'] = 'Staff';   
        $data['staff'] = FALSE;
        $data['staffs'] = $this->admin_model->select_by_user('staffs');
        $data['services'] = $this->admin_model->select_by_user('services');
        $data['locations'] = $this->admin_model->get_locations(0);
        $data['sub_locations'] = $this->admin_model->get_locations(1);
        $data['main_content'] = $this->load->view('admin/user/staff',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function set($staff_id)
    {   
        check_status();

        $user_id = user()->id;
        $this->admin_model->delete_assaign_staff_days($staff_id, 'working_days');
        //$this->admin_model->delete_assaign_staff_time($staff_id, 'working_time');

            for ($i=0; $i < 7; $i++) { 
                if(empty($this->input->post("day_".$i))){
                    $day = 0;
                }else{
                    $day = $this->input->post("day_".$i);
                }

                if ($day == 0) {
                    $start = '';
                    $end = '';
                }else{
                    $start = $this->input->post("start_hour_".$i);
                    $end = $this->input->post("end_hour_".$i);
                    $start = date("H:i", strtotime($start));
                    $end = date("H:i", strtotime($end));
                }


                $data = array(
                    'user_id' => $user_id,
                    'staff_id' => $staff_id,
                    'business_id' => $this->business->uid,
                    'day' => $day,
                    'start' => $start,
                    'end' => $end,
                );
                $data = $this->security->xss_clean($data);

                //if ($day != 0) {
                    $this->admin_model->insert($data, 'working_days');
                //}





                
                if ($day != 0) {
                    
                    if ($day == 0) {
                        $start_time = '';
                        $end_time = '';
                    }else{
                        $start_time = $this->input->post("start_time_".$i);
                        $end_time = $this->input->post("end_time_".$i);
                    }

                    $phpversion = phpversion();
                    if ($phpversion <= 8.0) {
                        for ($a=0; $a < count($start_time); $a++) {
                            $time_data = array(
                                'user_id' => $user_id,
                                'staff_id' => $staff_id,
                                'business_id' => $this->business->uid,
                                'day_id' => $day,
                                'time' => date("H:i", strtotime($start_time[$a])).'-'.date("H:i", strtotime($end_time[$a])),
                                'start' => date("H:i", strtotime($start_time[$a])),
                                'end' => date("H:i", strtotime($end_time[$a]))
                            );
                            $time_data = $this->security->xss_clean($time_data);
                            $this->admin_model->insert($time_data, 'working_time');
                        }
                    }else{

                        if (is_countable($start_time)) {
                            $limit = count($start_time);
                        }else{
                            $limit =0;
                        }

                        for ($a=0; $a < $limit; $a++) {
                            $time_data = array(
                                'user_id' => $user_id,
                                'staff_id' => $staff_id,
                                'business_id' => $this->business->uid,
                                'day_id' => $day,
                                'time' => date("H:i", strtotime($start_time[$a])).'-'.date("H:i", strtotime($end_time[$a])),
                                'start' => date("H:i", strtotime($start_time[$a])),
                                'end' => date("H:i", strtotime($end_time[$a]))
                            );
                            $time_data = $this->security->xss_clean($time_data);
                            $this->admin_model->insert($time_data, 'working_time');
                        }
                    }
                    
                }






            }
              
        
    }


    public function add()
    {	
        check_status();

        if($_POST)
        {   

            $id = $this->input->post('id', true);

            //validate inputs
            $this->form_validation->set_rules('name', trans('name'), 'required|max_length[100]');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('error', validation_errors());
                redirect(base_url('admin/staff'));
            } else {
                if ($id != '') {
                    $password = $this->input->post('password');
                    if (empty($password)) {
                        $staff = $this->admin_model->get_by_id($id, 'staffs');
                        $password = $staff->password;
                    } else {
                        $password = hash_password($this->input->post('password'));
                    }
                    
                } else {
                    $email = $this->auth_model->check_duplicate_email(trim($this->input->post('email')));
                    if ($email){
                        $this->session->set_flashdata('error', trans('email-exist'));
                        redirect(base_url('admin/staff'));
                    }

                    $phone = $this->auth_model->check_existing_phone(trim($this->input->post('phone')));
                    if ($phone){
                        $this->session->set_flashdata('error', trans('phone-exist'));
                        redirect(base_url('admin/staff'));
                    }

                    $password = hash_password($this->input->post('password'));
                }

                $data=array(
                    'user_id' => user()->id,
                    'business_id' => $this->business->uid,
                    'name' => $this->input->post('name', true),
                    'designation' => $this->input->post('designation', true),
                    'slug' => str_slug($this->input->post('name', true)),
                    'email' => $this->input->post('email', true),
                    'phone' => $this->input->post('phone', true),
                    'facebook' => $this->input->post('facebook', true),
                    'twitter' => $this->input->post('twitter', true),
                    'linkedin' => $this->input->post('linkedin', true),
                    'whatsapp' => $this->input->post('whatsapp', true),
                    'status' => $this->input->post('status'),
                    'password' => $password,
                    'created_at' => my_date_now()
                );
                $data = $this->security->xss_clean($data);

                if ($id != '') {
                    $this->admin_model->edit_option($data, $id, 'staffs');
                    $this->session->set_flashdata('msg', trans('updated-successfully')); 
                } else {

                    $total = get_total_value('staffs');
                    if (ckeck_plan_limit('staffs', $total) == FALSE) {
                        $this->session->set_flashdata('error', trans('reached-maximum-limit'));
                        redirect(base_url('admin/staff'));
                        exit();
                    }
                    
                    $id = $this->admin_model->insert($data, 'staffs');
                    $edata=array(
                        'image' => 'assets/images/no-photo.png',
                        'thumb' => 'assets/images/no-photo-sm.png'
                    );
                    $this->admin_model->edit_option($edata, $id, 'staffs');

                    $this->session->set_flashdata('msg', trans('inserted-successfully')); 
                }

                $this->set($id);

                if (!empty($this->input->post('location_id'))) {
                    $this->admin_model->delete_staff_location($id, 'staff_locations');
                    
                    if (!empty($this->input->post('sub_location_id'))) {
                        foreach ($this->input->post('sub_location_id') as $sub_location) {
                            $sub_locations = explode(",", $sub_location);
                            for ($i=0; $i < count($sub_locations); $i++) { 

                                $lc_data = array(
                                    'business_id' => $this->business->uid,
                                    'staff_id' => $id,
                                    'location_id' => $this->input->post('location_id'),
                                    'sub_location_id' => $sub_locations[$i]
                                );
                                $this->admin_model->insert($lc_data, 'staff_locations');
                            }
                        }
                    }else{
                        $lc_data = array(
                            'business_id' => $this->business->uid,
                            'staff_id' => $id,
                            'location_id' => $this->input->post('location_id'),
                            'sub_location_id' => 0
                        );
                        $this->admin_model->insert($lc_data, 'staff_locations');
                    }
                    
                }

                // insert photos
                if($_FILES['photo']['name'] != ''){
                    $up_load = $this->admin_model->upload_image('600');
                    $data_img = array(
                        'image' => $up_load['images'],
                        'thumb' => $up_load['thumb']
                    );
                    $this->admin_model->edit_option($data_img, $id, 'staffs');   
                }

                redirect(base_url('admin/staff'));

            }
        }      
        
    }



    public function edit($id)
    {  
       
        $data = array();
        $data['page_title'] = 'Edit';   
        $staff = $this->admin_model->get_by_md5($id, 'staffs');
        $id = $staff->id;
        $data['staff'] = $this->admin_model->select_option($id, 'staffs');
        $data['my_days'] = $this->admin_model->get_user_days($id);
        $data['staff_locations'] = $this->admin_model->get_staff_locations($id);
        $data['staff_sub_locations'] = $this->admin_model->get_staff_sub_locations($data['staff_locations'][0]->location_id);
        $data['services'] = $this->admin_model->select_by_user('services');
        $data['locations'] = $this->admin_model->get_locations(0);
        $data['sub_locations'] = $this->admin_model->get_locations(1);
        $data['main_content'] = $this->load->view('admin/user/staff',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    
    public function active($id) 
    {
        $data = array(
            'status' => 1
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'staffs');
        $this->session->set_flashdata('msg', trans('activate-successfully')); 
        redirect(base_url('admin/staff'));
    }

    public function deactive($id) 
    {
        $data = array(
            'status' => 0
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'staffs');
        $this->session->set_flashdata('msg', trans('deactivate-successfully')); 
        redirect(base_url('admin/staff'));
    }

    public function delete($id)
    {
        $staff = $this->admin_model->get_by_md5($id, 'staffs');
        $this->admin_model->delete($staff->id,'staffs'); 
        echo json_encode(array('st' => 1));
    }

}
	

