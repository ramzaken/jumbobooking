<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    //expire payments
    public function expire_payments()
    {   

        $this->recurring_services();
        $payments = $this->common_model->get_expire_payments();
        foreach ($payments as $payment) {
            $data = array(
                'status' => 'expire'
            );
            $data = $this->security->xss_clean($data);
            if ($payment->billing_type != 'lifetime') {
                $this->common_model->update($data, $payment->id, 'payment');
            }
        }

        //check trial expire users
        $trial_users = $this->common_model->get_trial_users();
        foreach ($trial_users as $user) {
            $user_data = array(
                'status' => 1,
                'user_type' => 'registered',
                'trial_expire' => '1971-01-01'
            );
            $user_data = $this->security->xss_clean($user_data);
            $this->common_model->update($user_data, $user->id, 'users');
        }


        $time_zone = $this->admin_model->get_by_id(settings()->time_zone, 'time_zone');
        $time_zone = $time_zone->name;

        if (isset(settings()->reminder_before)) {
            $reminder_before = settings()->reminder_before;
        } else {
            $reminder_before = '1 day';
        }

        $reminder_before = $reminder_before;
        $reminder_before = explode(" ", $reminder_before);
        $reminder_before_type = $reminder_before[1];

        if ($reminder_before_type == 'hour') {
            $date =  new DateTime('now', new DateTimezone($time_zone));
            $date = $date->modify('+'.$reminder_before[0] .'hours');
            $date = $date->format('Y-m-d H:i');
            $times_later = $date;
            $appointments = $this->common_model->today_appointments($times_later,'hour');
        }else{
            $appointments = $this->common_model->today_appointments($reminder_before[0],'day');
        }

        if (!empty($appointments)) {
            
            foreach ($appointments as $appointment) {
                $company = $this->common_model->get_by_uid($appointment->business_id, 'business');
                $customer = $this->common_model->get_by_id($appointment->customer_id, 'customers');
                $service = $this->common_model->get_by_id($appointment->service_id, 'services');
                $booking_number_text = 'Booking Number: #'.$appointment->number;


                // send email to user with dynamic msg 
                $staff_info = $this->admin_model->get_by_id($staff_id, 'staffs');


                $subject = get_email_by_slug('appointment-reminder-customer')->subject;
                $body = get_email_by_slug('appointment-reminder-customer')->body;
                $variables_data = [
                    'customer_name'  =>$customer->name,
                    'business_name'  =>$company->name,
                    'service_name' => $service->name,
                    'appointment_date' => my_date_show($appointment->date),
                    'appointment_time' => $appointment->time,
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

                if (!empty($customer->email)) {
                    $this->email_model->send_email($customer->email, $subject, $msg);
                }


                if (!empty($appointment->customer_phone)) {
                    
                    // this session is used for the wapblaster api
                    $this->session->set_userdata('wap_template', 'bookingreminder');
                    $sess_booking_data = array(
                        'reminder_before' => settings()->reminder_before,
                        'serviceId' => $appointment->service_id,
                        'customerId' => $appointment->customer_id,
                        'date' => my_date_show($appointment->date),
                        'time' => $appointment->time,
                    );
                    $this->session->set_userdata($sess_booking_data);
                    // this session is used for the wapblaster api


                    $this->load->model('sms_model');
                    $response = $this->sms_model->send_cron($appointment->customer_phone, $content, $appointment->user_id);

                    //check ultramsg api keys for user
                    if (settings()->global_wapp_msg == 0) {
                        $this->sms_model->send_whatsapp_user($appointment->customer_phone, $content, $company);
                    }

                    //check ultramsg api keys for global
                    if (settings()->global_wapp_msg == 1) {
                        $this->sms_model->send_whatsapp_user($appointment->customer_phone, $content, 'settings');
                    }
                }

                $reminder_data = array(
                    'is_sent_reminder' => 1
                );
                $reminder_data = $this->security->xss_clean($reminder_data);
                $this->common_model->update($reminder_data, $appointment->id, 'appointments');
            }
        }
        
    }





    public function recurring_services()
    {  
        
        $recurr_services = $this->admin_model->get_recurr_service_by_date();
        if(!empty($recurr_services)){
            foreach ($recurr_services as $value) {
                unset($value->id);
                $this->db->insert('appointments', $value);
                $recurr_row_id = $this->db->insert_id();

                $service_repeat = get_by_id($value->service_id,'services')->service_repeat;
                $service_number = get_by_id($value->service_id,'services')->number_of_service;
                $date = new DateTime($value->next_recur_date);
                $date->modify('+'.$service_repeat.'day');
                $next_date = $date->format('Y-m-d');

                if(($service_number - 1) == $value->recurring_count){
                    $is_completed = 1;
                }else{
                    $is_completed = 0;;
                }

                $data = array(
                    'date' => $next_date,
                    'next_recur_date' => $next_date,
                    'recurring_count' => $value->recurring_count +1,
                    'is_completed' => $is_completed,
                );
                $data = $this->security->xss_clean($data);

                $this->admin_model->edit_option($data, $recurr_row_id, 'appointments');
            }
        }
        
    }


}