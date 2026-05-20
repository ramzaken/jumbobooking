<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Meeting extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('zoom/zoom');
        $this->load->library('googlemeet');
    }


    public function zoom($id){
       
        $edit_data = array(
            'is_start' => 1
        );
        if ($id != 0) {
            $this->admin_model->edit_option($edit_data, $id, 'appointments');
        }
        redirect($_SERVER['HTTP_REFERER']);
        
    }

    public function create_meeting($id){

       
        $zoom_account_id = $this->business->zoom_account_id; 
        $zoom_client_id = $this->business->zoom_client_id; 
        $zoom_client_secret = $this->business->zoom_client_secret; 
        
        $appointment = $this->db->get_where('appointments',['id'=>$id])->row();
        $customer = $this->db->get_where('customers',['id'=>$appointment->customer_id])->row();
        $user = $this->db->get_where('users',['id'=>$appointment->user_id])->row();

        $time = explode('-', $appointment->time);
        $date = date('Y-m-d');

        if(count($time) && $appointment->date != null ){
            $time1 = $time[0];
            $time2 = $time[1];
        }

        $start_time = $date.' '.$time1;

        $agenda = 'Zoom Meeting Session with - '.$mentor->name;
        $duration = $appointment->duration;
        $password = mt_rand(1111,9999);;

        $fields = array(
            'agenda'=>$agenda,
            'default_password'=>false,
            'duration'=>$duration, //minutes
            'password'=> $password,
            'start_time'=>$start_time,
            'waiting_room'=>true
        );

        $result = $this->zoom->get_meeting($zoom_account_id, $zoom_client_id, $zoom_client_secret, $fields);
        $result = json_decode($result);

        $customers['attendees'] = array(
            0 => array('name'=> $customer->name)
        );

        $invitation = $this->zoom->send_invitation($zoom_account_id, $zoom_client_id, $zoom_client_secret, $result->id, $customers);

        $host_url = $result->start_url;
        $join_url = null;

        if(isset($invitation->attendees)){
            $join_url = $invitation->attendees[0]->join_url;
        }


        $this->db->where('id',$id);
        $this->db->update('appointments',['join_url'=>$join_url,'host_url'=>$host_url,'zoom_password'=>$password]);

        $this->session->set_flashdata('msg','Meeting added successfully'); 
        redirect(base_url('admin/appointment'));

    }


    public function test_zoom_api_connection(){

        

        $this->load->library('zoom');
        $date = date('Y-m-d');
        $start_time = $date.' 10:00';

        $agenda = 'Zoom Meeting Test Connection';
        $duration = 60;
        $password = mt_rand(1111,9999);;

        $fields = array(
            'agenda'=>$agenda,
            'default_password'=>false,
            'duration'=>$duration, //minutes
            'password'=> $password,
            'start_time'=>$start_time,
            'waiting_room'=>true
        );

        $result = $this->zoom->get_meeting($this->business->zoom_account_id, $this->business->zoom_client_id, $this->business->zoom_client_secret, $fields);
        $result = json_decode($result);
        if (!empty($result->start_url)) {
            echo json_encode(array('st' => 1, 'msg' => '<i class="fa fa-check-circle"></i> Zoom api connected successfully'));
        }else{
            echo json_encode(array('st' => 2, 'msg' => '<i class="fa fa-times-circle"></i> '.$result->message));
        }

    }


    public function cancel_meeting($id)
    {
        $edit_data = array(
            'is_start' => 0
        );
        if ($id != 0) {
            $this->admin_model->edit_option($edit_data, $id, 'appointments');
        }
        $this->session->set_flashdata('msg', trans('meeting-canceled-successfully')); 
        redirect($_SERVER['HTTP_REFERER']);
    }



}