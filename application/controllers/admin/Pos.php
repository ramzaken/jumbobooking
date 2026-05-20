<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pos extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        //check auth
        if (!is_staff() && !is_user()) {
            redirect(base_url());
        }

        if (check_feature_access('booking-pos') != TRUE){
            redirect(base_url('admin/dashboard/user'));
        }
    }

    public function index()
    {

        $data = array();
        $data['page'] = 'Pos';
        $data['page_title'] = 'Pos';
        $data['services'] = $this->admin_model->select_by_order_user('services');
        $disabled_customers = explode(',', $this->business->disabled_customers);
        $data['customers'] = $this->admin_model->get_pos_customers($disabled_customers);
        $cus_val = array();
        foreach($data['customers'] as $row)
        {
           $cus_val[] = $row->id;
        }
        $customer_array = implode (",", $cus_val);
        $customer_array = explode(",", $customer_array);
        $data['customers_app'] = $this->admin_model->get_booking_customers_by_disabled($customer_array,$disabled_customers);
        $data['company'] = $this->admin_model->get_company(user()->id);
        $data['main_content'] = $this->load->view('admin/user/pos',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function get_service($id='')
    {  
            
        $data = array();
        $data['page_title'] = 'Pos'; 
        $data['service'] = $this->admin_model->get_by_id($id,'services');
        $loaded= $this->load->view('admin/user/include/pos_service_row',$data,  TRUE);
        echo json_encode(array('st'=>1, 'loaded'=>$loaded, 'price'=> $data['service']->price));
           

    }

    public function get_service_extra($id='')
    {  
         
        $data = array();
        $data['page_title'] = 'Pos'; 
        $data['service_extra'] = $this->admin_model->get_by_id($id,'service_extra');
        $loaded= $this->load->view('admin/user/include/pos_service_extra',$data,  TRUE);
        echo json_encode(array('st'=>1, 'loaded'=>$loaded, 'se_price'=>$data['service_extra']->price));
           

    }

    public function get_staff($id='')
    {  
         
        $data = array();
        $data['page_title'] = 'Pos'; 
        $data['staff'] = $this->admin_model->get_by_id($id,'staffs');
        $loaded= $this->load->view('admin/user/include/pos_staff',$data,  TRUE);
        echo json_encode(array('st'=>1, 'loaded'=>$loaded,));

    }

    public function get_customer($id='')
    {  
         
        $data = array();
        $data['page_title'] = 'Pos'; 
        $data['customer'] = $this->admin_model->get_by_id($id,'customers');
        $loaded= $this->load->view('admin/user/include/pos_customer',$data,  TRUE);
        echo json_encode(array('st'=>1, 'loaded'=>$loaded,));

    }

    public function get_coupon($service_id='')
    {  
         
        $data = array();
        $data['page_title'] = 'Pos'; 
        $data['coupons'] = $this->admin_model->get_coupon_by_service($service_id);
        $loaded= $this->load->view('admin/user/include/pos_coupon',$data,  TRUE);

        if (empty($data['coupons'])) {
            $st = 0;
        }else{
            $st = 1;
        }
        echo json_encode(array('st'=>$st, 'loaded'=>$loaded,));
           

    }


    public function check_coupon($coupon_id)
    {
        $sub_total = $this->input->post('sub_total');
        $customer_id = $this->input->post('customer_id');

        if($coupon_id != 0){

            $coupon = $this->admin_model->get_by_id($coupon_id,'coupons');
            $service = $this->admin_model->get_by_id($coupon->service_id,'services');
            $ccode = $coupon->code;
            $service_id = $coupon->service_id;
            $discount = $coupon->discount;
            $discount_amount = ($sub_total * $discount)/100;
            $amount_after_discount = $sub_total - $discount_amount;

            if (empty($coupon)) {
            echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code')));
            } else {
                if (date('Y-m-d') >= $coupon->start_date && date('Y-m-d') <= $coupon->end_date) {
                    
                    //check coupon limit
                    if ($coupon->usages_limit == 0) {
                        echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code'))); exit();
                    }

                    if ($coupon->once_per_customer == 1) {
                        $check = $this->admin_model->check_coupon_apply($code, $service_id, $customer_id);
                        if (isset($check)) {
                            echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('already-applied-code'))); exit();
                        }
                    }

                    echo json_encode(array('st' => 1, 'discount'=> $discount, 'discount_amount' => $discount_amount, 'total_price' => $amount_after_discount, 'msg' => '<i class="fas fa-check-circle"></i> '.trans('coupon-applied-successfully')));

                }else{
                    echo json_encode(array('st' => 0, 'msg' => '<i class="fas fa-times-circle"></i> '.trans('invalid-code')));
                }
            }exit(); 
        }else{

            echo json_encode(array('st' => 1, 'total_price' => $sub_total, 'msg' => '<i class="fas fa-check-circle"></i> '.trans('coupon-applied-successfully')));

                
            exit(); 
        }
       
        
    }

    public function service_search($value)
    {  

        $data = array();
        $data['page_title'] = 'Pos'; 
        $data['services'] = $this->admin_model->get_search_services($value);

        if (!empty($data['services'])) {
            $loaded= $this->load->view('admin/user/include/service_search',$data,  true);
            echo json_encode(array('st' => 1, 'loaded' => $loaded));
        }else{
            $loaded= $this->load->view('admin/include/not-found',$data,  true);
            echo json_encode(array('st' => 0, 'loaded' => $loaded));
        } 

    }


    public function load_staff($id)
    {

        
        $data = array();
        $data['service'] = $this->common_model->get_service_staffs($id);
        $data['company'] = $this->common_model->get_by_uid($data['service']->business_id, 'business');

        $data['inputs'] = $this->common_model->get_custom_inputs($data['company']->uid,$id); 
        
        //$this->set_language($data['company']->lang);

        $my_days = $this->admin_model->get_my_days($data['company']->uid);
          
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
        $data['page_title'] = 'Booking';
        $data['holidays'] = $data['company']->holidays;
        $data['company_id'] = $data['company']->uid;


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


    public function customer_search($value)
    {  
         
        $data = array();
        $data['page_title'] = 'Pos';
        $disabled_customers = explode(',', $this->business->disabled_customers); 
        $data['customers'] = $this->admin_model->get_search_customers($value,$disabled_customers);

        if (!empty($data['customers'])) {
            $loaded= $this->load->view('admin/user/include/customer_search',$data,  true);
            echo json_encode(array('st' => 1, 'loaded' => $loaded));
        }else{
            $loaded= $this->load->view('admin/include/not-found',$data,  true);
            echo json_encode(array('st' => 0, 'loaded' => $loaded));
        }

           

    }

    public function add_customer(){
        if($_POST)
        {
            $data=array(
                'user_id' => user()->id,
                'business_id' => $this->business->uid,
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'phone' => $this->input->post('phone', true),
                'role' => 'customer',
                'status' => 1,
                'created_at' => my_date_now(),
            );
            $data = $this->security->xss_clean($data);
            $id = $this->admin_model->insert($data, 'customers');

            $customer = $this->admin_model->get_by_id($id,'customers'); 
            $cdata = array();
            $cdata['customer'] = $customer;
            $loaded= $this->load->view('admin/user/include/pos_customer',$cdata,  TRUE);
            $url = base_url('admin/pos');
            echo json_encode(array('st'=>1, 'loaded'=>$loaded, 'customer_id'=> $id)); 
        }
    }

    public function booking_add(){
       
        if($_POST)
        {   
            
            if (empty( $this->input->post('service_extra', true))) {
                $service_extra = 0;
            }else{
                $service_extra_aray = $this->input->post('service_extra');
                $service_extra = implode(",", $service_extra_aray); 
            }

            $this->session->unset_userdata('date');
            $this->session->unset_userdata('time');

            $data=array(
                'user_id' => user()->id,
                'business_id' => $this->business->uid,
                'date' => $this->input->post('date', true),
                'time' => $this->input->post('time', true),
                'service_id' => $this->input->post('service_id', true),
                'pos_coupon_discount' => $this->input->post('pos_coupon_discount', true),
                'service_extra' => $service_extra,
                'staff_id' => $this->input->post('staff_id', true),
                'customer_id' => $this->input->post('customer_id', true),
                'status' => 1,
                'pos_payment_method' => $this->input->post('pos_payment_method', true),
                'created_at' => my_date_now(),
            );
            $data = $this->security->xss_clean($data);
            $this->session->set_userdata($data);

            $bdata = array();
            $bdata['date'] = $this->input->post('date');
            $bdata['time'] = $this->input->post('time');
            $loaded= $this->load->view('admin/user/include/pos_booking_step2',$bdata,  TRUE);
            echo json_encode(array('st'=>1,'loaded'=> $loaded));

        }      
    }

     public function confirm_pos_booking(){
        check_status();
        
        if($_POST)
        {   

            $booking_number = random_string('numeric',5);
            $data=array(
                'user_id' => $this->session->userdata('user_id'),
                'business_id' => $this->session->userdata('business_id'),
                'number' => $booking_number,
                'date' => $this->session->userdata('date'),
                'time' => $this->session->userdata('time'),
                'service_id' => $this->session->userdata('service_id'),
                'service_extra' => $this->session->userdata('service_extra'),
                'staff_id' => $this->session->userdata('staff_id'),
                'customer_id' => $this->session->userdata('customer_id'),
                'status' => $this->session->userdata('status'),
                'is_pos' => 1,
                'pos_payment_method' => $this->input->post('pos_payment_method', true),
                'pos_payment_receive' => $this->input->post('pos_received_amount', true),
                'pos_change_return' => $this->input->post('pos_change_return', true),
                'created_at' => $this->session->userdata('created_at'),
            );
            $data = $this->security->xss_clean($data);
            $appointment_id = $this->admin_model->insert($data, 'appointments');
            $appointment = $this->admin_model->get_by_id($appointment_id,'appointments');
            $puid = random_string('numeric',5);

            $pdata=array(
                'user_id' => $appointment->user_id,
                'puid' => $puid,
                'appointment_id' => $appointment_id,
                'customer_id' => $appointment->customer_id,
                'amount' => $this->input->post('pos_paying_amount',true),
                'payment_method' => $this->input->post('pos_payment_method',true),
                'payment_note' => $this->input->post('payment_note',true),
                'status' => 'verified',
                'created_at' => my_date_now(),
            );
            $pdata = $this->security->xss_clean($pdata);
            $payment_id = $this->admin_model->insert($pdata, 'payment_user');

            $invoice_sub_total = $this->input->post('invoice_sub_total',true);
            $invoice_total = $this->input->post('invoice_total',true);
            $invoice_coupon_amount = $this->input->post('invoice_coupon_amount',true);

            $idata=array(
                'invoice_sub_total' => $invoice_sub_total,
                'invoice_total' => $invoice_total,
                'invoice_coupon_amount' => $invoice_coupon_amount,
            );
            $idata = $this->security->xss_clean($idata);
            $this->session->set_userdata($idata);

            $url = base_url('admin/pos/invoice/'.$payment_id);
            echo json_encode(array('st'=>1,'url'=>$url, 'total'=> $invoice_total, 'sub_total'=> $invoice_sub_total, 'ic_amount'=> $invoice_coupon_amount));

        }      
    }

    public function invoice($payment_id){
        
        $data = array();
        $data['page'] = 'Pos';
        $data['page_title'] = 'Pos Invoice';
        $data['payment'] = $this->admin_model->get_pos_payment($payment_id);
        $data['main_content'] = $this->load->view('admin/user/pos_invoice',$data,TRUE);
        $this->load->view('admin/index',$data);
    }
}