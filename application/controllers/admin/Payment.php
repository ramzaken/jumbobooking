<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        //check auth
        if (!is_admin() && !is_user()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Payment';      
        $data['page'] = 'Settings'; 
        $payment = $this->admin_model->get_my_payment();
        $data['payment_id'] = $payment->puid;
        $data['my_payment'] = $payment;
        $data['package'] = $this->common_model->get_package_by_slug($payment->package);
        $data['main_content'] = $this->load->view('admin/payment',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function settings(){
        //check auth
        if (!is_admin()) {
            redirect(base_url());
        }

        if (get_user_info() == FALSE) {
            redirect(base_url('404_override'));
        }
        $data = array();
        $data['page_title'] = 'Payment Settings';      
        $data['page'] = 'Settings';   
        $data['packages'] = $this->admin_model->select_asc('package');
        $data['currencies'] = $this->admin_model->select_asc('country');
        $data['users'] = $this->common_model->get_users();
        $data['main_content'] = $this->load->view('admin/payment_settings',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    //update settings
    public function update(){

        //check auth
        if (!is_admin()) {
            redirect(base_url());
        }

        check_status();
        
        if ($_POST) {
            
            if(!empty($this->input->post('enable_payment'))){$enable_payment = $this->input->post('enable_payment', true);}
            else{$enable_payment = 0;}

            if(!empty($this->input->post('paypal_payment'))){$paypal_payment = $this->input->post('paypal_payment', true);}
            else{$paypal_payment = 0;}

            if(!empty($this->input->post('stripe_payment'))){$stripe_payment = $this->input->post('stripe_payment', true);}
            else{$stripe_payment = 0;}

            if(!empty($this->input->post('razorpay_payment'))){$razorpay_payment = $this->input->post('razorpay_payment', true);}
            else{$razorpay_payment = 0;}

            if(!empty($this->input->post('paystack_payment'))){$paystack_payment = $this->input->post('paystack_payment', true);}
            else{$paystack_payment = 0;}

            if(!empty($this->input->post('flutterwave_payment'))){$flutterwave_payment = $this->input->post('flutterwave_payment', true);}
            else{$flutterwave_payment = 0;}

            if(!empty($this->input->post('enable_offline_payment'))){$enable_offline_payment = $this->input->post('enable_offline_payment', true);}
            else{$enable_offline_payment = 0;}

            if(!empty($this->input->post('mercado_payment'))){$mercado_payment = $this->input->post('mercado_payment', true);}
            else{$mercado_payment = 0;}

            if(!empty($this->input->post('enable_iyzico'))){$enable_iyzico = $this->input->post('enable_iyzico', true);}
            else{$enable_iyzico = 0;}

            
            $data = array(
                'country' => $this->input->post('country', true),
                'offline_details' => $this->input->post('offline_details'),
                'paypal_mode' => $this->input->post('paypal_mode', true),
                'paypal_email' => $this->input->post('paypal_email', true),
                'publish_key' => $this->input->post('publish_key', true),
                'secret_key' => $this->input->post('secret_key', true),
                'paystack_secret_key' => $this->input->post('paystack_secret_key', true),
                'paystack_public_key' => $this->input->post('paystack_public_key', true),
                'razorpay_key_id' => $this->input->post('razorpay_key_id', true),
                'razorpay_key_secret' => $this->input->post('razorpay_key_secret', true),
                'enable_payment' => $enable_payment,
                'paypal_payment' => $paypal_payment,
                'stripe_payment' => $stripe_payment,
                'razorpay_payment' => $razorpay_payment,
                'paystack_payment' => $paystack_payment,
                'enable_offline_payment' => $enable_offline_payment,
                'flutterwave_payment' => $flutterwave_payment,
                'flutterwave_public_key' => $this->input->post('flutterwave_public_key', true),
                'flutterwave_secret_key' => $this->input->post('flutterwave_secret_key', true),
                'mercado_payment' => $mercado_payment,
                'mercado_api_key' => $this->input->post('mercado_api_key', true),
                'mercado_token' => $this->input->post('mercado_token', true),
                'mercado_currency' => $this->input->post('mercado_currency', true),
                'enable_iyzico' => $this->input->post('enable_iyzico', true),
                'iyzico_api_key' => $this->input->post('iyzico_api_key', true),
                'iyzico_secret_key' => $this->input->post('iyzico_secret_key', true),
                'iyzico_mode' => $this->input->post('iyzico_mode', true)
            );
            $this->admin_model->edit_option($data, 1, 'settings');
            $this->session->set_flashdata('msg', trans('updated-successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }



    public function offline()
    {   
        if($_POST)
        {   
            $package = $this->admin_model->get_by_id($this->input->post('package'), 'package');
            $payment = $this->admin_model->get_user_payment($this->input->post('user'));

            if($this->input->post('billing_type') =='monthly'):
                $amount = round($package->monthly_price); 
                $expire_on = date('Y-m-d', strtotime('+1 month'));
            else:
                $amount = round($package->price); 
                $expire_on = date('Y-m-d', strtotime('+12 month'));
            endif;
            
            //validate inputs
            $this->form_validation->set_rules('user', trans('user'), 'required');
            $this->form_validation->set_rules('package', trans('package'), 'required');
            $this->form_validation->set_rules('status', trans('payment-status'), 'required');

            if ($this->form_validation->run() === false) {
                $this->session->set_flashdata('errors', validation_errors());
                redirect(base_url('admin/payment'));
            } else {

                $data=array(
                    'user_id' => $this->input->post('user', true),
                    'package_id' => $package->id,
                    'billing_type' => $this->input->post('billing_type', true),
                    'amount' => $amount,
                    'status' => $this->input->post('status', true),
                    'created_at' => my_date_now(),
                    'expire_on' => $expire_on
                );
                $data = $this->security->xss_clean($data);

                if (empty($payment)) {
                    $this->admin_model->insert($data, 'payment');
                } else {
                    $this->admin_model->update_payment($data, $this->input->post('user'), 'payment');
                }

                $this->session->set_flashdata('msg', trans('inserted-successfully')); 
                redirect(base_url('admin/users'));

            }
        }      
        
    }


    public function approve_offline($id) 
    {
        $data = array(
            'status' => 'verified'
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id, 'payment');
        $this->session->set_flashdata('msg','Updated Successfully'); 
        redirect($_SERVER['HTTP_REFERER']);
    }


    public function receipt($puid)
    {
        //check auth
        if (!is_admin() && !is_user()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Payment Receipt'; 
        $data['user'] = $this->admin_model->get_user_payment_details($puid);

        if (!is_admin()) {
            if ($data['user']->user_id != $this->session->userdata('id')) {
                redirect(base_url());
            }
        }

        $this->load->view('admin/payment/payment_invoice_receipt',$data);
    }

    public function lists()
    {
        if (!is_user()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Payment list';
        $data['payments'] = $this->admin_model->get_users_payment_lists(user()->id);
        $data['main_content'] = $this->load->view('admin/payment/payment_invoice_lists',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function transactions()
    {
        //check auth
        if (!is_admin()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Transactions';
        $data['payments'] = $this->admin_model->get_payment_lists(0);
        $data['main_content'] = $this->load->view('admin/payment/transactions',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function customer_transactions()
    {
        //check auth
        if (!is_user()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Transactions';
        if (isset($_GET['type']) && $_GET['type'] == 'appointment') {
            $data['payments'] = $this->admin_model->get_customer_payment_lists(0);
        }elseif(isset($_GET['type']) && $_GET['type'] == 'product'){
            $data['payments'] = $this->admin_model->get_customer_order_payment_lists(0);
        }
        else{
            $data['payments'] = $this->admin_model->get_customer_event_payment_lists(0);
        }
        
        $data['main_content'] = $this->load->view('admin/payment/customer_transactions',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function customer_receipt($puid)
    {
        //check auth
        if (!is_user() && !is_customer()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Payment Receipt'; 
        $data['user'] = $this->admin_model->get_customer_payment_details($puid);
        $data['company'] = $this->admin_model->get_company($data['user']->user_id);

        // if ($data['user']->user_id != $this->session->userdata('id')) {
        //     redirect(base_url());
        // }
            
        $this->load->view('admin/payment/customer_invoice_receipt',$data);
    }

    public function customer_receipt_event($puid)
    {
        //check auth
        
        if (!is_user() && !is_customer()) {
            redirect(base_url());
        }

        $data = array();
        $data['page_title'] = 'Payment Receipt';
        $data['user'] = $this->admin_model->get_customer_event_payment_details($puid);
        $data['company'] = $this->admin_model->get_company($data['user']->user_id);
        
        if ($data['user']->user_id != $this->session->userdata('id') && $data['user']->customer_id != $this->session->userdata('id')) {
            redirect(base_url());
        }
            
        $this->load->view('admin/payment/customer_event_invoice_receipt',$data);
    }

    public function customer_receipt_product($puid)
    {
        //check auth
        
        if (!is_user() && !is_customer()) {
            redirect(base_url());
        }
        
        $data = array();
        $data['page_title'] = 'Payment Receipt';
        $data['user'] = $this->admin_model->get_customer_product_payment_details($puid);
        $data['orders'] = $this->admin_model->get_customer_product_payment_details_orders($data['user']->order_id);
        //echo "<pre>"; print_r($data['orders']); exit();
        $data['company'] = $this->admin_model->get_company($data['user']->user_id);
        
        if ($data['user']->user_id != $this->session->userdata('id') && $data['user']->customer_id != $this->session->userdata('id')) {
            redirect(base_url());
        }
            
        $this->load->view('admin/payment/customer_product_invoice_receipt',$data);
    }


    public function upgrade()
    {
        $data = array();
        $data['page_title'] = 'Upgrade';      
        $data['page'] = 'Payment'; 
        $payment = $this->admin_model->get_my_payment();
        $data['payment_id'] = $payment->puid;
        $data['package'] = $this->common_model->get_package_by_slug($payment->package);
        $data['main_content'] = $this->load->view('admin/upgrade',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    
    public function upgrade_operation() 
    {
        $data = array(
            'account_type' => 'pro'
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, user()->id, 'users');

        $pkg = $this->common_model->get_package_price('pro');
        $payment = $this->common_model->get_user_payment(user()->id);

        //create payment
        $pay_data=array(
            'package' => 'pro',
            'amount' => $pkg->price,
            'status' => 'pending',
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
        $this->admin_model->update($pay_data, $payment->id, 'payment');

        if (get_settings()->enable_paypal == 1) {
            redirect(base_url('admin/payment'));
        } else {
            redirect(base_url('admin/profile'));
        }
        
    }

    public function deactive($id) 
    {
        $data = array(
            'status' => 0
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'testimonials');
        $this->session->set_flashdata('msg', trans('deactivate-successfully')); 
        redirect(base_url('admin/testimonial'));
    }

    public function delete($id)
    {
        $this->admin_model->delete($id,'testimonials'); 
        echo json_encode(array('st' => 1));
    }






    //******* User Payments *******//

    public function user()
    {
        //check auth
        if (!is_user()) {
            redirect(base_url());
        }
        
        $data = array();
        $data['page_title'] = 'Payment Settings'; 
        $data['page'] = 'Settings';
        $data['settings'] = $this->admin_model->get('settings');
        $data['currencies'] = $this->admin_model->select_asc('country');
        $data['packages'] = $this->admin_model->select_asc('package');
        $data['main_content'] = $this->load->view('admin/user/user_payment_settings',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    //update payment settings
    public function user_update(){
        //check auth
        if (!is_user()) {
            redirect(base_url());
        }
        
        if ($_POST) {
            
            if(!empty($this->input->post('paypal_payment'))){$paypal_payment = $this->input->post('paypal_payment', true);}
            else{$paypal_payment = 0;}

            if(!empty($this->input->post('stripe_payment'))){$stripe_payment = $this->input->post('stripe_payment', true);}
            else{$stripe_payment = 0;}

            if(!empty($this->input->post('razorpay_payment'))){$razorpay_payment = $this->input->post('razorpay_payment', true);}
            else{$razorpay_payment = 0;}

            if(!empty($this->input->post('paystack_payment'))){$paystack_payment = $this->input->post('paystack_payment', true);}
            else{$paystack_payment = 0;}

            if(!empty($this->input->post('flutterwave_payment'))){$flutterwave_payment = $this->input->post('flutterwave_payment', true);}
            else{$flutterwave_payment = 0;}

            if(!empty($this->input->post('mercado_payment'))){$mercado_payment = $this->input->post('mercado_payment', true);}
            else{$mercado_payment = 0;}

            if(!empty($this->input->post('enable_iyzico'))){$enable_iyzico = $this->input->post('enable_iyzico', true);}
            else{$enable_iyzico = 0;}
            
            if(!empty($this->input->post('enable_offline_payment'))){$enable_offline_payment = $this->input->post('enable_offline_payment', true);}
            else{$enable_offline_payment = 0;}

            $country = $this->admin_model->get_by_id($this->input->post('country'), 'country');

            $data = array(
                'country' => 0,
                'currency' => 'USD',
                'offline_details' => $this->input->post('offline_details'),
                'paypal_mode' => $this->input->post('paypal_mode', true),
                'paypal_email' => $this->input->post('paypal_email', true),
                'publish_key' => $this->input->post('publish_key', true),
                'secret_key' => $this->input->post('secret_key', true),
                'razorpay_key_id' => $this->input->post('razorpay_key_id', true),
                'razorpay_key_secret' => $this->input->post('razorpay_key_secret', true),
                'paystack_secret_key' => $this->input->post('paystack_secret_key', true),
                'paystack_public_key' => $this->input->post('paystack_public_key', true),
                'paystack_payment' => $paystack_payment,
                'paypal_payment' => $paypal_payment,
                'stripe_payment' => $stripe_payment,
                'razorpay_payment' => $razorpay_payment, 
                'flutterwave_payment' => $flutterwave_payment,
                'enable_offline_payment' => $enable_offline_payment,
                'flutterwave_public_key' => $this->input->post('flutterwave_public_key', true),
                'flutterwave_secret_key' => $this->input->post('flutterwave_secret_key', true), 
                'mercado_payment' => $mercado_payment,
                'mercado_api_key' => $this->input->post('mercado_api_key', true),
                'mercado_token' => $this->input->post('mercado_token', true),
                'mercado_currency' => $this->input->post('mercado_currency', true),
                'enable_iyzico' => $this->input->post('enable_iyzico', true),
                'iyzico_api_key' => $this->input->post('iyzico_api_key', true),
                'iyzico_secret_key' => $this->input->post('iyzico_secret_key', true),
                'iyzico_mode' => $this->input->post('iyzico_mode', true)
            );
            $data = $this->security->xss_clean($data);
            $this->admin_model->edit_option($data, user()->id, 'users');
            $this->session->set_flashdata('msg', 'Updated Successfully'); 
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function offline_payment_customer($amp_id)
    {   
        if($_POST)
        {   
            $appointment = $this->admin_model->get_appointment_by_id($amp_id);
            $uid = random_string('numeric',5);
            
            $check_coupon = check_coupon($appointment->id, $appointment->service_id, $appointment->business_id);
            if ($check_coupon != FALSE):
                if (!empty($check_coupon)):
                    $price = $appointment->price;
                    $discount = $check_coupon->discount;
                    $amount = $price - ($price * ($discount / 100));
                    $discount_amount = $price - $totalCost;
                else:
                    $price = $appointment->price;
                    $discount = 0;
                    $discount_amount = 0;
                    $amount = $price;
                endif;
            else:
                $amount = $appointment->price;
            endif;

            //calculate service extra
            if(!empty($appointment->service_extra)):
                $service_extra = explode(',', $appointment->service_extra);
                $total_extra = 0;
                foreach ($service_extra as $value) {
                    $extra_price = get_by_id($value,'service_extra')->price;
                    $total_extra += $extra_price;
                }
                $amount = $amount + $total_extra;
            endif;

            // calculate service tax
            $company = $this->admin_model->get_business_uid($appointment->business_id);
            if ($company->tax_type != 0):
                if ($company->tax_type == 1 && $company->tax_amount > 0):
                    $amount = str_replace(',','', get_tax($amount,  $company->tax_amount));
                endif;

                $service = $this->admin_model->get_by_id($appointment->service_id, 'services');
                if ($company->tax_type == 2 && $service->tax > 0):
                    $amount = str_replace(',','', get_tax($amount,  $service->tax));
                endif;
            endif;
        

            $file_name = 'proof_'.random_string('numeric',6).'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (empty($_FILES['file']['name'])) {
                $this->session->set_flashdata('msg', trans('inserted-successfully')); 
                redirect($_SERVER['HTTP_REFERER']); exit();
            } else {
                $file_name = $file_name;
            }

            if (!empty($_FILES['file']['name'])) {
                $config['upload_path']          = './uploads/files'; //file save path
                $config['allowed_types']        = 'pdf|gif|jpg|png|JPG|GIF|PNG|jpeg|JPEG';
                $config['max_size']             = 10000;
                $config['file_name'] = $file_name;


                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('file')){
                    $error = array('error' => $this->upload->display_errors());
                }else{
                    $data = array('upload_data' => $this->upload->data());
                }
            }

            if ($company->card_fee != 0) {
                $amount = $amount + $company->card_fee;
            }
            
            $pay_data = array(
                'user_id' => $appointment->user_id,
                'customer_id' => $appointment->customer_id,
                'appointment_id' => $appointment->id,
                'puid' => $uid,
                'status' => 'pending',
                'amount' => $amount,
                'payment_method' => 'offline',
                'proof' => $file_name,
                'created_at' => my_date_now()
            );
            $pay_data = $this->security->xss_clean($pay_data);
            $this->admin_model->insert($pay_data, 'payment_user');

            
            $this->session->set_flashdata('msg', trans('inserted-successfully')); 
            redirect(base_url('customer/appointments'));

            
        }      
        
    }

    public function offline_event_payment($event_booking_id){

        if($_POST){
            $uid = random_string('numeric',5);
            $event_booking = $this->admin_model->get_by_id($event_booking_id,'event_booking');
            $event = $this->admin_model->get_by_id($event_booking->event_id,'events');
            $company = $this->admin_model->get_business_uid($event_booking->business_id);

            $amount = $event_booking->total_price;


            $file_name = 'proof_'.random_string('numeric',6).'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (empty($_FILES['file']['name'])) {
                $this->session->set_flashdata('msg', trans('inserted-successfully')); 
                redirect($_SERVER['HTTP_REFERER']); exit();
            } else {
                $file_name = $file_name;
            }

            if (!empty($_FILES['file']['name'])) {
                $config['upload_path']          = './uploads/files'; //file save path
                $config['allowed_types']        = 'pdf|gif|jpg|png|JPG|GIF|PNG|jpeg|JPEG';
                $config['max_size']             = 10000;
                $config['file_name'] = $file_name;


                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('file')){
                    $error = array('error' => $this->upload->display_errors());
                }else{
                    $data = array('upload_data' => $this->upload->data());
                }
            }


            $pay_data = array(
                'user_id' => $event_booking->user_id,
                'customer_id' => $event_booking->customer_id,
                'event_id' => $event->id,
                'event_booking_id' => $event_booking_id,
                'puid' => $uid,
                'status' => 'pending',
                'amount' => $amount,
                'payment_method' => 'offline',
                'proof' => $file_name,
                'created_at' => my_date_now()
            );
            $pay_data = $this->security->xss_clean($pay_data);
            $this->admin_model->insert($pay_data, 'payment_user_event');
            
            $this->session->set_flashdata('msg', trans('inserted-successfully')); 
            redirect(base_url('customer/events'));
        }
    }

    public function approve_offline_customer($id) 
    {
        $payment = $this->admin_model->get_by_id($id, 'payment_user');
    
        $data = array(
            'status' => 'verified'
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id, 'payment_user');

        if (settings()->confirm_notify == 1) {
            $this->send_booking_notifications($payment->appointment_id);
        }

        $this->session->set_flashdata('msg','Updated Successfully'); 
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function approve_offline_event_customer($id) 
    {
        $data = array(
            'status' => 'verified'
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id, 'payment_user_event');
        $this->session->set_flashdata('msg','Updated Successfully'); 
        redirect($_SERVER['HTTP_REFERER']);
    }


    // service payment
    public function record_payment($amp_id)
    {   
        $appointment = $this->admin_model->get_appointment_by_id($amp_id);
        $uid = random_string('numeric',5);
        
        $check_coupon = check_coupon($appointment->id, $appointment->service_id, $appointment->business_id);
        if ($check_coupon != FALSE):
            if (!empty($check_coupon)):
                $price = $appointment->price;
                $discount = $check_coupon->discount;
                $amount = $price - ($price * ($discount / 100));
                $discount_amount = $price - $totalCost;
            else:
                $price = $appointment->price;
                $discount = 0;
                $discount_amount = 0;
                $amount = $price;
            endif;
        else:
            $amount = $appointment->price;
        endif;

        //calculate service extra
        if(!empty($appointment->service_extra)):
            $service_extra = explode(',', $appointment->service_extra);
            $total_extra = 0;
            foreach ($service_extra as $value) {
                $extra_price = get_by_id($value,'service_extra')->price;
                $total_extra += $extra_price;
            }
            $amount = $amount + $total_extra;
        endif;

        if ($this->input->post('is_deposit') == 1) {
            $totalCost = get_appointment_price($appointment, $this->business);
            $amp_payment = appointment_payment_details($appointment->id);
        
            $pay_data = array(
                'status' => 'verified',
                'amount' => $totalCost,
                'pay_later' => '0',
                'payment_method' => 'offline',
                'created_at' => my_date_now()
            );
            $this->common_model->edit_option($pay_data, $amp_payment->id, 'payment_user');
        }else{

            $pay_data = array(
                'user_id' => $appointment->user_id,
                'customer_id' => $appointment->customer_id,
                'appointment_id' => $appointment->id,
                'puid' => $uid,
                'status' => 'verified',
                'amount' => $amount,
                'payment_method' => 'offline',
                'created_at' => my_date_now()
            );
            $pay_data = $this->security->xss_clean($pay_data);
            $response = $this->common_model->insert($pay_data, 'payment_user');
        }

        $this->session->set_flashdata('msg', trans('inserted-successfully')); 
        redirect($_SERVER['HTTP_REFERER']);
    }











    //** ------ customer Payments ------ **//

    public function customer($amp_id){
        $data = array();
        $data['appointment'] = $this->admin_model->get_by_id($amp_id, 'appointments');
        $data['appointment_id'] = $data['appointment']->id;
        $data['user'] = $this->admin_model->get_by_id($data['appointment']->user_id, 'users');
        $data['main_content'] = $this->load->view('admin/user/patient_payment', $data, TRUE);
        $this->load->view('admin/index', $data);
    }

    //iyzico payment
    public function iyzico_appointment_payment()
    {

        //error_reporting(-1);
        //ini_set('display_errors', 1);

        $this->load->library('iyzico');
        
        $id = $this->input->post('appointment_id');
        $appointment = $this->common_model->get_appointment($id);
        $user = $this->admin_model->get_by_id($appointment->user_id, 'users');
        $company = $this->admin_model->get_business_uid($appointment->business_id);
        $currency = get_currency_by_country($company->country)->currency_code;

        // calculate total cost
        $amount = get_appointment_price($appointment, $company);
        
        if (settings()->enable_wallet == 1) {
            $currency = settings()->currency_code;
            $apiKey = settings()->iyzico_api_key;
            $secretKey = settings()->iyzico_secret_key;

            if (settings()->iyzico_mode == 'sandbox') {
                $baseUrl = 'https://sandbox-api.iyzipay.com';
            }else{
                $baseUrl = 'https://api.iyzipay.com';
            }
        }else{
            $currency = $currency;
            $apiKey = $user->iyzico_api_key;
            $secretKey = $user->iyzico_secret_key;

            if ($user->iyzico_mode == 'sandbox') {
                $baseUrl = 'https://sandbox-api.iyzipay.com';
            }else{
                $baseUrl = 'https://api.iyzipay.com';
            }
        }

        $city = 'Istanbul';
        $country = 'Turkey';
        

        

        $paymentData = [
            'conversationId' => substr(random_string('alnum', 5).mt_rand(), 0, 9),
            'price' => $amount,
            'paidPrice' => $amount,
            'basketId' => 'BASKET123',
            'cardHolderName' => $this->input->post('card_holder'),
            'cardNumber' => $this->input->post('card_number'),
            'expireMonth' => $this->input->post('expire_month'),
            'expireYear' => $this->input->post('expire_year'),
            'cvc' => $this->input->post('cvc'),
            'buyerId' => 'BY'.substr(random_string('alnum', 5).mt_rand(), 0, 3),
            'buyerName' => $appointment->customer_name,
            'buyerSurname' => $appointment->customer_name,
            'buyerPhone' => $appointment->customer_phone,
            'buyerEmail' => $appointment->customer_email,
            'buyerIdentityNumber' => substr(random_string('alnum', 5).mt_rand(), 0, 11),
            'buyerAddress' => '123 Main Street',
            'buyerIp' => $this->input->ip_address(),
            'buyerCity' => $city,
            'buyerCountry' => $country,
            'currency' => $currency,
            'apiKey' => $apiKey,
            'secretKey' => $secretKey,
            'baseUrl' => $baseUrl
        ];

        $paymentResponse = $this->iyzico->createPayment($paymentData);

        
        if ($paymentResponse->getStatus() == 'success') {
            redirect(base_url('admin/payment/payment_success/'.$appointment->id.'/iyzico'));
        } else {
            $this->session->set_flashdata('error', $paymentResponse->getErrorMessage());
            redirect($_SERVER['HTTP_REFERER']);
            //echo "Payment failed! Reason: " . $paymentResponse->getErrorMessage();
        }
    }


    public function stripe_appointment_payment()
    {
        
        $id = $this->input->post('appointment_id');
        $appointment = $this->common_model->get_appointment($id);
        $user = $this->admin_model->get_by_id($appointment->user_id, 'users');
        $company = $this->admin_model->get_business_uid($appointment->business_id);
        $currency = get_currency_by_country($company->country)->currency_code;

        // calculate total cost
        $amount = get_appointment_price($appointment, $company);

        
        if (settings()->enable_wallet == 1) {
            $secret_key = settings()->secret_key;
        }else{
            $secret_key = $user->secret_key;
        }

        require_once('application/libraries/stripe-php/init.php');
        \Stripe\Stripe::setApiKey($secret_key);
        
        try {

            $customer = \Stripe\Customer::create(array(
                'name' => $appointment->customer_name,
                'email' => $appointment->customer_email,
                'source'  => $this->input->post('stripeToken')
            ));

            $charge = \Stripe\Charge::create ([
                "customer" => $customer,
                "amount" => $amount*100,
                "currency" => $currency,
                "description" => "Service payment ".get_settings()->site_name 
            ]);
            $chargeJson = $charge->jsonSerialize();
            
            $amount                  = $chargeJson['amount']/100;
            $balance_transaction     = $chargeJson['balance_transaction'];
            $currency                = $chargeJson['currency'];
            $status                  = $chargeJson['status'];
            $payment = 'success';
        }catch(Exception $e) { 
            $error = $e->getMessage(); 
            $this->session->set_flashdata('error', $error);
            $payment = 'failed';
        }

        if($payment == 'success'):  
            redirect(base_url('admin/payment/payment_success/'.$appointment->id.'/stripe'));
        else:
            redirect(base_url('customer/payment_msg/failed/'.$appointment->id));
        endif;
    }


    //payment success
    public function payment_success($amp_id, $payment_method='')
    {   

        if (settings()->type != 'live') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $appointment = $this->common_model->get_appointment($amp_id);
        $user = $this->admin_model->get_by_id($appointment->user_id, 'users');
        $company = $this->admin_model->get_business_uid($appointment->business_id);

        // calculate total cost
        $amount = get_appointment_price($appointment, $company);
        $payment_status = 'verified';

        $pay_later = '0';
        if (!empty($this->session->userdata('deposit')) && $this->session->userdata('deposit') == true) {
            $amount = $this->session->userdata('deposit_amount');
            $pay_later = get_appointment_price($appointment, $company) - $amount;
        }


        $uid = random_string('numeric',5);
       
        if (isset($payment_method) && $payment_method == 'stripe') {
            $payment_method = 'stripe';
        }else if(isset($payment_method) && $payment_method == 'razorpay'){
            $payment_method = 'razorpay';
        }else if(isset($payment_method) && $payment_method == 'paystack'){
            $payment_method = 'paystack';
        }else if(isset($payment_method) && $payment_method == 'flutterwave'){
            $payment_method = 'flutterwave';
        }else if(isset($payment_method) && $payment_method == 'mercadopago'){
            $payment_method = 'mercadopago';
        }else {
            $payment_method = 'paypal';
        }

        if (settings()->enable_wallet == 1) {
            $type = 'wallet';
            $total_amount = get_commission($amount, settings()->commission_rate);
            $commission_amount = get_commission_rate($amount, settings()->commission_rate);
            $commission_rate = settings()->commission_rate;
        }else{
            $type = 'user';
            $total_amount = '0.00';
            $commission_amount = '0.00';
            $commission_rate = 0;
        }

        $pay_data = array(
            'user_id' => $user->id,
            'customer_id' => $appointment->customer_id,
            'appointment_id' => $appointment->id,
            'puid' => $uid,
            'status' => $payment_status,
            'amount' => $amount,
            'total_amount' => $total_amount,
            'commission_amount' => $commission_amount,
            'commission_rate' => $commission_rate,
            'payment_method' => $payment_method,
            'pay_later' => $pay_later,
            'type' => $type,
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
        $response = $this->common_model->insert($pay_data, 'payment_user');

        if ($response) {
            
            if (settings()->enable_wallet == 1) {
                $balance = $total_amount * 100;
                $user_data = array(
                    'balance' => $balance + $user->balance,
                    'total_sales' => $user->total_sales + 1
                );
                $this->common_model->edit_option($user_data, $user->id, 'users');
            }

            if (settings()->confirm_notify == 1) {
                $this->send_booking_notifications($appointment->id);
            }


            redirect(base_url('customer/payment_msg/success/'.$appointment->id));
        }
    }

    //payment cancel
    public function payment_cancel($amp_id='')
    {   
        redirect(base_url('customer/payment_msg/failed/'.$amp_id));
    }



    public function send_booking_notifications($appointment_id)
    {   
        // error_reporting(-1);
        // ini_set('display_errors', 1);

        $appointment = $this->common_model->get_booking_by_id($appointment_id);
        //echo "<pre>"; print_r($appointment); exit();

        if ($appointment) {

            $booking_number_text = $appointment->number;
            $service_id = $appointment->service_id;
            $customer_id = $appointment->customer_id;
            $location_id = $appointment->location_id;
            $staff_id = $appointment->staff_id;
            $date = $appointment->date;
            $time = $appointment->time;
            $convert_time = $time;


            $company = $this->admin_model->get_business_uid($appointment->business_id);
            $user = $this->common_model->get_by_id($company->user_id, 'users');
            $customer = $this->admin_model->get_by_id($customer_id, 'customers');
            $service = $this->admin_model->get_by_id($service_id, 'services');
            $location = $this->admin_model->get_by_id($location_id, 'locations');


            $this->session->set_userdata('wap_template', 'new-booking');
           
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

        //return;
        //echo "done";
            
    }


    //payment cancel
    public function offline_payment($amp_id)
    {   
        $appointment = $this->admin_model->get_by_id($amp_id, 'appointments');
        $user = $this->admin_model->get_by_id($appointment->user_id, 'users');
        $amount = evisit_settings($user->id)->price;
        $uid = random_string('numeric',5);
        $payment_method = 'offline';
        
        $pay_data = array(
            'user_id' => $user->id,
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'puid' => $uid,
            'status' => 'verified',
            'amount' => $amount,
            'payment_method' => $payment_method,
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
        $response = $this->common_model->insert($pay_data, 'payment_user');
        $this->session->set_flashdata('msg', trans('inserted-successfully')); 
        redirect($_SERVER['HTTP_REFERER']);
    }








    // event payment code

    public function stripe_event_payment()
    {
        
        $id = $this->input->post('booking_id');
        $event_booking = $this->common_model->get_by_id($id, 'event_booking');
        $user = $this->admin_model->get_by_id($event_booking->user_id, 'users');
        $company = $this->admin_model->get_business_uid($event_booking->business_id);
        $currency = get_currency_by_country($company->country)->currency_code;

        // calculate total cost
        $amount = $event_booking->total_price;
        $secret_key = $user->secret_key;

      
        require_once('application/libraries/stripe-php/init.php');
        \Stripe\Stripe::setApiKey($secret_key);
        
        try {
            $charge = \Stripe\Charge::create ([
                "amount" => $amount*100,
                "currency" => $currency,
                "source" => $this->input->post('stripeToken'),
                "description" => "Event payment ".get_settings()->site_name 
            ]);

            $chargeJson = $charge->jsonSerialize();
            
            $amount                  = $chargeJson['amount']/100;
            $balance_transaction     = $chargeJson['balance_transaction'];
            $currency                = $chargeJson['currency'];
            $status                  = $chargeJson['status'];
            $payment = 'success';
        }catch(Exception $e) { 
            $error = $e->getMessage(); 
            $this->session->set_flashdata('error', $error);
            $payment = 'failed';
        }

        if($payment == 'success'):  
            redirect(base_url('admin/payment/event_payment_success/'.$event_booking->id.'/stripe'));
        else:
            redirect(base_url('customer/payment_msg/failed/'.$event_booking->id));
        endif;
    }



    //payment success
    public function event_payment_success($event_booking_id, $payment_method='')
    {   

        if (settings()->type != 'live') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $booking = $this->common_model->get_by_id($event_booking_id, 'event_booking');
        $user = $this->admin_model->get_by_id($booking->user_id, 'users');
        $company = $this->admin_model->get_business_uid($booking->business_id);

        // calculate total cost
        $amount = $booking->total_price;
     
        $uid = random_string('numeric',5);
       
        if (isset($payment_method) && $payment_method == 'stripe') {
            $payment_method = 'stripe';
        }else if(isset($payment_method) && $payment_method == 'razorpay'){
            $payment_method = 'razorpay';
        }else if(isset($payment_method) && $payment_method == 'paystack'){
            $payment_method = 'paystack';
        }else if(isset($payment_method) && $payment_method == 'flutterwave'){
            $payment_method = 'flutterwave';
        }else if(isset($payment_method) && $payment_method == 'mercadopago'){
            $payment_method = 'mercadopago';
        }else if(isset($payment_method) && $payment_method == 'offline'){
            $payment_method = 'offline';
        }else {
            $payment_method = 'paypal';
        }


        $pay_data = array(
            'user_id' => $user->id,
            'customer_id' => $booking->customer_id,
            'event_id' => $booking->event_id,
            'event_booking_id' => $booking->id,
            'puid' => $uid,
            'status' => 'verified',
            'amount' => $amount,
            'payment_method' => $payment_method,
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
        $response = $this->common_model->insert($pay_data, 'payment_user_event');

        if ($this->session->userdata('role') == 'user') {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(base_url('customer/payment_msg/success/'.$booking->event_id));
        }
        
    }


    // order payment code


    public function stripe_order_payment()
    {
        $type = $this->input->post('type');
        $type_id = $this->input->post('type_id');
        $customer_email = $this->input->post('customer_email');

        
        $order = $this->common_model->get_by_id($type_id, 'product_orders');
        $business_id = $order->business_id;
        $amount = $order->total_price;
        $customer_id = $order->customer_id;
        $table_name = 'product_orders';

        $user = $this->admin_model->get_by_id($this->session->userdata('user_id'), 'users');
        $company = $this->admin_model->get_business_uid($business_id);
        $currency = get_currency_by_country($company->country)->currency_code;
        $secret_key = $user->secret_key;
        

        require_once('application/libraries/stripe-php/init.php');
        \Stripe\Stripe::setApiKey($secret_key);
        
        try {
            $charge = \Stripe\Charge::create ([
                "amount" => $amount*100,
                "currency" => $currency,
                "source" => $this->input->post('stripeToken'),
                "description" => "Product payment ".$company->name 
            ]);
            $chargeJson = $charge->jsonSerialize();
            
            $amount                  = $chargeJson['amount']/100;
            $balance_transaction     = $chargeJson['balance_transaction'];
            $currency                = $chargeJson['currency'];
            $status                  = $chargeJson['status'];
            $payment = 'success';
        }catch(Exception $e) { 
            $error = $e->getMessage(); 
            $this->session->set_flashdata('error', $error);
            $payment = 'failed';
        }

        if($payment == 'success'):
            $odata = array(
                'payment_status' => 1
            );
            $odata = $this->security->xss_clean($odata);
            $this->common_model->edit_option($odata, $type_id, $table_name);

            redirect(base_url('admin/payment/order_payment_success/'.$type_id.'/stripe'));
        else:
            redirect(base_url('customer/payment_msg/failed/'.$type_id));
        endif;
    }

    public function order_payment_success($type_id, $payment_method='')
    {   

        if (settings()->type != 'live') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        
        $order = $this->common_model->get_by_id($type_id, 'product_orders');
        $amount = $order->total_price;
        $customer_id = $order->customer_id;
        $table_name = 'product_orders';

        $user = $this->admin_model->get_by_id($this->session->userdata('user_id'), 'users');
        

        $uid = random_string('numeric',5);
       
        if (isset($payment_method) && $payment_method == 'stripe') {
            $payment_method = 'stripe';
        }else if(isset($payment_method) && $payment_method == 'razorpay'){
            $payment_method = 'razorpay';
        }else if(isset($payment_method) && $payment_method == 'paystack'){
            $payment_method = 'paystack';
        }else if(isset($payment_method) && $payment_method == 'flutterwave'){
            $payment_method = 'flutterwave';
        }else {
            $payment_method = 'paypal';
        }


        $total_amount = '0.00';
        $commission_amount = '0.00';
        $commission_rate = '0';
        

        $pay_data = array(
            'user_id' => $user->id,
            'customer_id' => $customer_id,
            'order_id' => $type_id,
            'puid' => $uid,
            'status' => 'verified',
            'amount' => $amount,
            'total_amount' => $total_amount,
            'commission_amount' => $commission_amount,
            'commission_rate' => $commission_rate,
            'payment_method' => $payment_method,
            'type' => 'user',
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
         //echo "<pre>"; print_r($pay_data); exit();
        $response = $this->common_model->insert($pay_data, 'product_payment_user');

        if ($response) {
            $odata = array(
                'payment_status' => 1
            );
            $odata = $this->security->xss_clean($odata);
            $this->common_model->edit_option($odata, $type_id, $table_name);
            redirect(base_url('customer/product_payment_msg/success'));
        }
    }


    public function success_msg(){
        $data = array();
        $data['success_msg'] = 'Success';
        $data['main_content'] = $this->load->view('admin/user/payment_user_msg',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

}
	

