<?php

use Twilio\Rest\Client;

class Sms_model extends CI_Model {

    public function send_user($to_number, $message, $user_id)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        $user = $this->common_model->get_by_id($user_id, 'users');
  
        // Your Account SID and Auth Token from twilio.com/console
        if (settings()->global_twilio == 1) {
            $sid = settings()->twillo_account_sid;
            $token = settings()->twillo_auth_token;
            $twillo_number = settings()->twillo_number;
        }else{
            $sid = $user->twillo_account_sid;
            $token = $user->twillo_auth_token;
            $twillo_number = $user->twillo_number;
        }

        if (!empty($sid) && !empty($token)) {
            $client = new Client($sid, $token);
            try{
                // Use the client to do fun stuff like send text messages!
                $message = $client->messages->create(
                    // the number you'd like to send the message to
                    $to_number,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => $twillo_number,
                        // the body of the text message you'd like to send
                        'body' => $message
                    ]
                );
                return 1;
            }
            catch(Exception $e){
                return 0;
            }
        }else{
            return 0;
        }
    }



    public function send_whatsapp_user($to_number, $message, $company)
    {
        if (settings()->global_wapp_msg == 1) {
            if (settings()->whatsapp_type == 'ultramsg') {
                $token = settings()->ultramsg_token;
                $instance_id = settings()->ultramsg_instance_id;
                $this->ultramsg($to_number, $message, $instance_id, $token);
            }else if (settings()->whatsapp_type == 'wappblaster') {
                $this->wappblaster($to_number, $message, settings()->wappblaster_key);
            } else {
                $token = settings()->wazfy_token;
                $instance_id = settings()->wazfy_instance_id;
                $this->wazfy($to_number, $message, $instance_id, $token);
            }
        }else{
            if (isset($company) && $company->whatsapp_type == 'ultramsg') {
                $token = $company->ultramsg_token;
                $instance_id = $company->ultramsg_instance_id;
                $this->ultramsg($to_number, $message, $instance_id, $token);
            }else if (isset($company) && $company->whatsapp_type == 'wappblaster') {
                $this->wappblaster($to_number, $message, $company->wappblaster_key);
            } else {
                $token = $company->wazfy_token;
                $instance_id = $company->wazfy_instance_id;
                $this->wazfy($to_number, $message, $instance_id, $token);
            }
        }
    }


    public function ultramsg($to_number, $message, $instance_id, $token){
        $params=array(
            'token' => $token,
            'to' => $to_number,
            'body' => $message
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.ultramsg.com/".$instance_id."/messages/chat",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => http_build_query($params),
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          //echo "Error #:" . $err;
            return false;
        } else {
            //echo $response;
            return true;
        }
    }

    public function wazfy($to_number, $message, $instance_id, $token){
        
        $apiUrl = "https://wazfy.com/api/send";
        $params = array(
            "number" => str_replace( '+', '', $to_number),
            "type" => "text",
            "message" => $message,
            "instance_id" => $instance_id,
            "access_token" => $token,
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($params),
          CURLOPT_HTTPHEADER => array(
            "content-type: application/json"
          ),
        ));

        if (curl_errno($curl)) {
            // $error_msg = curl_error($curl);
            // echo "<pre>";
            // print_r($error_msg);
        }
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $result = json_decode($response);

        if($result->status=="success"){
            return true;
        }else{
            return false;
            //echo "<pre>"; print_r($response);
        }
    }

    public function wappblaster($to_number, $message, $wappblaster_key){

        $service_id = $this->session->userdata('serviceId');
        $customer_id = $this->session->userdata('customerId');
        $customer = $this->admin_model->get_by_id($customer_id, 'customers');
        $service = $this->admin_model->get_by_id($service_id, 'services');
        $date = $this->session->userdata('date');
        $time = $this->session->userdata('time');
        $wap_template = $this->session->userdata('wap_template');
        $to_number = str_replace( '+', '', $to_number);

        if ($wap_template == 'bookingapprove') {
            $url = "https://webhooks.wappblaster.com/webhook/".$wappblaster_key."?number=$to_number&message=bookingapprove,".$customer->name.",".$service->name.",".$date.",".$time;
        }else if ($wap_template == 'bookingreject') {
            $url = "https://webhooks.wappblaster.com/webhook/".$wappblaster_key."?number=$to_number&message=bookingreject,".$customer->name.",".$service->name;
        }else if ($wap_template == 'bookingreminder') {
            $url = "https://webhooks.wappblaster.com/webhook/".$wappblaster_key."?number=$to_number&message=bookingreminder,".$customer->name.",".$service->name.",".$date.",".$time;
        }else {
            $url = "https://webhooks.wappblaster.com/webhook/".$wappblaster_key."?number=$to_number&message=new-booking,".$customer->name.",".$service->name.",".$date.",".$time;
        }


        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed (not recommended for production)

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return false;
            //log_message('error', 'cURL error: ' . curl_error($ch));
        } else {
            return true;
            //log_message('info', 'WhatsApp notification sent successfully. Response: ' . $response);
        }

        // Close cURL session
        curl_close($ch);
    }

    public function send($to_number, $message)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        
        // Your Account SID and Auth Token from twilio.com/console
        if (settings()->global_twilio == 1) {
            $sid = settings()->twillo_account_sid;
            $token = settings()->twillo_auth_token;
            $twillo_number = settings()->twillo_number;
        }else{
            $sid = user()->twillo_account_sid;
            $token = user()->twillo_auth_token;
            $twillo_number = user()->twillo_number;
        }

        
        $client = new Client($sid, $token);

        try{
            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
                // the number you'd like to send the message to
                $to_number,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $twillo_number,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return 1;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }


    public function send_cron($to_number, $message, $user_id)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        
        // Your Account SID and Auth Token from twilio.com/console
        if (settings()->global_twilio == 1) {
            $sid = settings()->twillo_account_sid;
            $token = settings()->twillo_auth_token;
            $twillo_number = settings()->twillo_number;
        }else{
            $user = get_by_id($user_id, 'users');
            $sid = $user->twillo_account_sid;
            $token = $user->twillo_auth_token;
            $twillo_number = $user->twillo_number;
        }

        if (!empty($sid) && !empty($token)) {
            $client = new Client($sid, $token);

            try{
                // Use the client to do fun stuff like send text messages!
                $message = $client->messages->create(
                    // the number you'd like to send the message to
                    $to_number,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => $twillo_number,
                        // the body of the text message you'd like to send
                        'body' => $message
                    ]
                );
                return 1;
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        }
    }


    

    public function send_admin($to_number, $message)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        
        // Your Account SID and Auth Token from twilio.com/console
        $sid = settings()->twillo_account_sid;
        $token = settings()->twillo_auth_token;
        $client = new Client($sid, $token);

        try{
            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
                // the number you'd like to send the message to
                $to_number,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => settings()->twillo_number,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return 1;
        }
        catch(Exception $e){
            return $e->getMessage();
        }

    }

}