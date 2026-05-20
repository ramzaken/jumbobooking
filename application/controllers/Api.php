<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function check_api_key($api_key)
    {

        if (isset($api_key) && ($api_key == 'bAg8cH5QiuPoq4FvxDnzJeYUp1OItGT')) {
            return true;
        } else {
            echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
            exit();
        }
    }

    public function get_user($id)
    {

        $headers = $this->input->request_headers();
        header('Content-Type: application/json');

        if (empty($headers['Api-Key']) || $headers['Api-Key'] != settings()->api_key) {
            echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
            exit();
        }

        $user = $this->get_by_id_email_user_name($id, 'users');

        unset($user->password);

        if (!empty($user)) {
            echo json_encode(array('status' => 'success', 'data' => $user));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }

    public function get_all_users()
    {

        $headers = $this->input->request_headers();
        header('Content-Type: application/json');

        if (empty($headers['Api-Key']) || $headers['Api-Key'] != settings()->api_key) {
            echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
            exit();
        }

        $users = $this->get_users();

        if (!empty($users)) {
            echo json_encode(array('status' => 'success', 'data' => $users));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }

    public function insert_user()
    {

        $headers = $this->input->request_headers();
        header('Content-Type: application/json');

        try {


            if (empty($headers['Api-Key']) || $headers['Api-Key'] != settings()->api_key) {
                echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
                exit();
            }

            if (!isset($headers['Api-Key'])) {
                echo json_encode(array('error' => 'Your api key doesnot exist or match ! try again !'));
                exit();
            } else {
                $data = json_decode($this->input->raw_input_stream, true);
                $name = $data['name'];
                $email = $data['email'];
                $package_id = $data['package'];
                $password = $data['password'];
                $user_name = $data['user_name'];

                if (empty($name) || empty($email) || empty($package_id) || empty($password) || empty($user_name)) {
                    echo json_encode(array('status' => 'error', 'error' => "mandatory field must be fill up name: $name email:$email package_id:$package_id user_name:$user_name pw:$password" ));
                } else {


                    $user = $this->get_by_id_email_user_name($email, 'users');

                    if ($user) {
                        echo json_encode(array('error' => 'email already used!'));
                        exit();
                    }

                    $user = $this->get_by_id_email_user_name($user_name, 'users');

                    if ($user) {
                        echo json_encode(array('error' => 'user_name already used!'));
                        exit();
                    }



                    $data['password'] = password_hash($password, PASSWORD_BCRYPT);
                    $data['referral_id'] = substr(random_string('alnum', 5) . mt_rand(), 0, 10);
                    $data['created_at'] = my_date_now();
                    $data['slug'] = $this->sanitize_user_name($data['user_name']);
                    $data['verify_code'] = random_string('numeric', 4);
                    $data['email_verified'] = 1;
                    $data['parent_id'] = 0;
                    $data['phone'] = $data['phone'] ?? '';

                    unset($data['package']);

                    $id = $this->common_model->insert($data, 'users');

                    $rand_uid = substr(random_string('numeric', 5) . mt_rand(), 0, 12);
                    $uid = ltrim($rand_uid, '0');

                    $company_data = array(
                        'uid' => $uid,
                        'user_id' => $id,
                        'name' => 'Your Business',
                        'email' => 'yourbusiness@gmail.com',
                        'slug' =>  $this->sanitize_user_name($uid),
                        'category' => 1,
                        'details' => 'Your business details',
                        'country' => 1,
                        'type' => 1,
                        'enable_location' => 0,
                        'enable_category' => 0,
                        'status' => 1,
                        'enable_staff' => 0,
                        'created_at' => my_date_now()
                    );

                    $this->common_model->insert($company_data, 'business');


                    $package = $this->admin_model->get_by_id($package_id, 'package');
                    if ($package) {
                        $payment_data = array(
                            'user_id' => $id,
                            'puid' => random_string('numeric', 5),
                            'package_id' => $package->id,
                            'billing_type' => 'monthly',
                            'amount' => 0,
                            'status' => 'verified',
                            'created_at' => my_date_now()
                        );

                        $this->admin_model->insert($payment_data, 'payment');
                    }


                    echo json_encode(array('status' => 'success', 'id' => $id));
                }
            }
        } catch (\Throwable $th) {
            echo json_encode(array('status' => 'error', 'error' => $th->getMessage()));
            exit();
        }
    }

    public function update_user($id)
    {
        header('Content-Type: application/json');
        $headers = $this->input->request_headers();

        if (empty($headers['Api-Key']) || $headers['Api-Key'] != settings()->api_key) {
            echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
            exit();
        }

        $data = json_decode($this->input->raw_input_stream, true);

        $user = $this->get_by_id_email_user_name( $data['email'], 'users');
        
        $package_id = $data['package'];
        unset($data['package']);

        $this->common_model->edit_option($data, $user->id, 'users');


        if (!empty($package_id)) {
            $payment = $this->admin_model->get_user_payment($user->id);

            $package = $this->admin_model->get_by_id($package_id, 'package');

            if ($package) {
                $payment_data = array(
                    'user_id' => $user->id,
                    'puid' => random_string('numeric', 5),
                    'package_id' => $package->id,
                    'billing_type' => 'monthly',
                    'amount' => 0,
                    'status' => 'verified',
                    'created_at' => my_date_now()
                );

                if (empty($payment)) {
                    $this->admin_model->insert($payment_data, 'payment');
                } else {
                    $this->admin_model->update_payment($payment_data, $user->id, 'payment');
                }
            }
        }


        echo json_encode(array('status' => 'success', 'id' => $id, 'package'=> $package, 'payment'=>$payment));
    }

    public function delete_user($id)
    {
        
        $id =  urldecode($id);
        $headers = $this->input->request_headers();
        header('Content-Type: application/json');

        if (empty($headers['Api-Key']) || $headers['Api-Key'] != settings()->api_key) {
            echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
            exit();
        }

        $user = $this->get_by_id_email_user_name($id, 'users');

        if (!empty($user)) {
            $this->common_model->delete($user->id, 'users');
            echo json_encode(array('status' => 'success', 'user' => $user));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }

    public function check_email($email)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getLink()
    {

        $headers = $this->input->request_headers();
        header('Content-Type: application/json');

        if (empty($headers['Api-Key']) || $headers['Api-Key'] != settings()->api_key) {
            echo json_encode(array('status' => 'error', 'error' => 'Invalid api key'));
            exit();
        }


        $data = json_decode($this->input->raw_input_stream, true);
        $email = $data['email'] ?? 'nothing';

        $privateKey = $headers['Api-Key'];
        $objDateTime = new DateTime('+1day');
        $url = base_url() . 'api/login_user/';

        $user = $this->get_by_id_email_user_name($email, 'users');

        if (!$user) {
            echo json_encode(array('status' => 'error', 'message' => 'Not found', 'data' => null));

            die();
        }


        $hash = hash('sha256', $privateKey . "autologin_supportboard" . $url . $email . $objDateTime->getTimestamp());

        $autoLoginUrl = http_build_query(array(
            'user' => $email,
            'timeLimit' => $objDateTime->getTimestamp(),
            'token' => $hash
        ));

        $response = [];
        $response['url_otn'] = $user->status == "1" ? $url . '?' . $autoLoginUrl : '/';
        $response['status'] = 'success';
        $response['user'] = $user;
        $response['message'] = 'user found';

        echo json_encode($response);
    }



    public function login_user()
    {
        //header('Content-Type: application/json');

        $token = trim($_GET['token']);

        $timeLimit = $_GET['timeLimit'];
        if (!isset($timeLimit)) {
            echo json_encode(array('status' => 'error', 'error' => 'invalid param'));
            die();
            //redirect(base_url('/'));
        }


        if ((int)$timeLimit < time()) {
            echo json_encode(array('status' => 'error', 'error' => 'Expired'));
            die();
            //redirect(base_url('/'));
        }

        $email = $_GET['user'] ?? null;
        if (!isset($email)) {
            echo json_encode(array('status' => 'error', 'error' => 'user is required'));
            die();
        }


        $privateKey = settings()->api_key;
        $url = base_url('api/login_user/');

        $hash = hash('sha256', $privateKey . "autologin_supportboard" . $url . $email . $_GET['timeLimit']);

        if ($hash != $token) {
            echo json_encode(array('status' => 'error', 'error' => 'Unauthorized'));
            die();
        }


        $user = $this->get_by_id_email_user_name($email, 'users');

        if (!$user) {
            echo json_encode(array('status' => 'error', 'error' => 'User email do not match or exist'));
            die();
        } else {
            $data = array(
                'id' => $user->id,
                'name' => $user->name,
                'slug' => $user->slug,
                'thumb' => $user->thumb,
                'email' => $user->email,
                'role' => $user->role,
                'parent'  => 0,
                'logged_in' => TRUE
            );
            $data = $this->security->xss_clean($data);
            $this->session->set_userdata($data);
            $url = base_url('admin/dashboard/user');
            redirect($url);
        }
    }


    function get_users()
    {
        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        $users = $query->result();
        foreach ($users as $user) {
            unset($user->password);
        }
        return $users;
    }

    function get_by_id_email_user_name($id)
    {
        $this->db->select();
        $this->db->from('users');
        
            // Si $id es un número entero
            if (ctype_digit($id)) {
                // Usamos el WHERE para 'id' y combinamos con 'email' o 'user_name' usando 'OR'
                $this->db->where('id', $id);
                $this->db->or_where('email', $id);
                $this->db->or_where('user_name', $id);
            } else {
                // Si no es entero, solo filtramos por 'email' o 'user_name'
                $this->db->where('email', $id);
                $this->db->or_where('user_name', $id);
            }
        
        //$this->db->where('id', $id);
        /*$this->db->where('email', $id);
        $this->db->or_where('user_name', $id);*/
        $query = $this->db->get();
        $query = $query->row();
        return $query;
    }

    function sanitize_user_name($user_name)
    {
        // Reemplazar espacios por guiones
        $user_name = str_replace(' ', '-', $user_name);
        // Reemplazar caracteres especiales por guiones
        $user_name = preg_replace('/[^A-Za-z0-9\-]/', '-', $user_name);
        // Reemplazar múltiples guiones consecutivos por uno solo
        $user_name = preg_replace('/-+/', '-', $user_name);
        // Eliminar guiones al inicio y al final
        $user_name = trim($user_name, '-');
        return $user_name;
    }



    //
    //*
    //*
    //*
    // call function 




    public function test_api()
    {

        $ch = curl_init();
        // Set the URL
        curl_setopt($ch, CURLOPT_URL, "https://aoxiosaas.com/api/get_user/3");
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Api-Key: ' . '402881ef74c626680174c761f9f0000b'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        echo "<pre>";
        print_r($response);

        // $url = "https://aoxiosaas.com/api/get_user/3";
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        // $headers = array("X-Auth-Token: $json_token");
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // $payload = ( array( "pin" => "7737", "accLevelIds" => "402881ef74c626680174c761f9f0000b", "name" => "Selim" ) );
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        // $response = curl_exec($ch);
        // curl_close($ch);

        // $response = json_decode($response);
        //echo "<pre>"; print_r($response);
    }

    public function api()
    {
        $apiKey = random_string('alnum', 8);
        $data = array(
            'name' => 'Apollo',
            'slug' => 'apollo',
            'user_name' => 'apollo',
            'email' => 'apollo123@gmail.com',
            'phone' => '8457457567',
            'thumb' => 'assets/images/no-photo-sm.png',
            'password' => hash_password(1234),
            'role' => 'user',
            'user_type' => 'registered',
            'trial_expire' => 'verified',
            'status' => 1,
            'parent_id' => 0,
            'paypal_payment' => 0,
            'stripe_payment' => 0,
            'verify_code' => '5456',
            'email_verified' => 0,
            'enable_appointment' => 1,
            'created_at' => my_date_now(),
            'referral_id' => random_string('alnum', 8),

        );

        $jsonData = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://aoxiosaas.com/api/insert_user");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'api_key: ' . $apiKey,
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        $response = json_decode($response);

        echo "<pre>";
        print_r($response);
    }



    public function update_api()
    {
        $apiKey = random_string('alnum', 8);


        $data = array(
            'name' => 'Selim update',
            'slug' => 'selim updare',
            'user_name' => 'selim',
            'email' => 'selim123update@gmail.com',
            'phone' => '65849756457',
            'thumb' => 'assets/images/no-photo-sm.png',
            'password' => hash_password(1234),
            'role' => 'user',
            'user_type' => 'registered',
            'trial_expire' => 'verified',
            'status' => 1,
            'parent_id' => 0,
            'paypal_payment' => 0,
            'stripe_payment' => 0,
            'verify_code' => '5456',
            'email_verified' => 0,
            'enable_appointment' => 1,
            'created_at' => my_date_now(),
            'referral_id' => random_string('alnum', 8),

        );

        $jsonData = json_encode($data);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://aoxiosaas.com/api/update_user/404");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'api_key: ' . $apiKey,
            'Content-Type: application/json'
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);



        $response = json_decode($response);

        echo "<pre>";
        print_r($response);
    }


    public function get_api()
    {
        $api_key = '547hgfh765';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://aoxiosaas.com/api/check_api_key");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Api_Key: ' . $api_key
        ));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);

        $response = json_decode($response);

        echo "<pre>";
        print_r($response);
    }
}
