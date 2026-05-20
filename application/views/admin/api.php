<style type="text/css">
.grey {
  background: #f2f4f6;
  color: #6c6a6a;
  font-weight: 500;
}
</style>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/default.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.5.0/styles/atom-one-dark.min.css">
<script>hljs.highlightAll();</script>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->
  <?php $this->load->view('admin/include/breadcrumb'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">

            <div class="card list_area">
              <div class="card-header">
                <h3 class="card-title pt-2">Api Documentations</h3>
              </div>

              <div class="row">
                  <div class="col">
                    <span class="api_btns btn-block py-3 btn btn-outline-dark get_user"><i class="bi bi-person-up"></i> Get User</span>
                  </div>
                  <div class="col">
                    <span class="api_btns btn-block py-3 btn btn-outline-dark create_user"><i class="bi bi-person-add"></i> Create User</span>
                  </div>
                  <div class="col">
                    <span class="api_btns btn-block py-3 btn btn-outline-dark update_user"><i class="bi bi-person-fill-check"></i> Update User</span>
                  </div>
                  <div class="col">
                    <span class="api_btns btn-block py-3 btn btn-outline-dark delete_user"><i class="bi bi-person-x"></i> Delete User</span>
                  </div>
                  <div class="col">
                    <span class="api_btns btn-block py-3 btn btn-outline-dark login_user"><i class="bi bi-person-lock"></i> Login</span>
                  </div>
              </div>

              <div class="card-body mt-3 api_area hide">

                <div class="get_user_area hide mt-3">
                  
                  <h6>Endpoint</h6>
                  <div class="p-3 grey mb-3 rounded">
                    <p class="mb-0"><span class="badge badge-success-soft py-2 px-3 mr-2">GET</span> <?php echo base_url('api/get_user/{user_id}') ?></p>
                  </div>

                  <h6>Example</h6>
                  <div class="grey">
<pre class="p-0"><code class="language rounded mb-0">
// -- curl --request GET <br>
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "<?php echo base_url('api/get_user/{user_id}') ?>");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Api-Key: ' . 'your_api_key',
    'Content-Type: application/json'
));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
echo $response;
</code></pre>
                  </div>
                  
                  <div class="mt-4">
                    <h6 class="mb-2">Response</h6>
<div class="mt-3">
<pre class="p-0"><code class="language-php rounded">
  {
  "status": "success",
  "data": {
    "id": "3",
    "parent_id": "0",
    "auth_type": null,
    "auth_id": null,
    "device_1": null,
    "device_2": null,
    "name": "Supreme",
    "slug": "mypathology",
    "balance": "0",
    "total_sales": "0",
    "email": "mayanktgs@gmail.com",
    "user_name": "mypathology",
    "password": "$2y$10$UcI.JEfJCjlYj4oXoMYyXe12tuiHgzcO.VX2i9kiV6EgbnthMkmly",
    "role": "user",
    "referral_id": "RCnMw53225",
    "referral_earn": "0",
    "account_type": null,
    "user_type": "registered",
    "trial_expire": "1971-01-01",
    "phone": "+919131582479",
    "address": null,
    "email_verified": "0",
    "verify_code": "5046",
    "phone_verified": "0",
    "sms_count": "0",
    "image": null,
    "thumb": "assets/images/no-photo-sm.png",
    "paypal_payment": "0",
    "stripe_payment": "0",
    "paypal_email": null,
    "publish_key": null,
    "secret_key": null,
    "paystack_payment": "0",
    "paystack_secret_key": null,
    "paystack_public_key": null,
    "razorpay_payment": "0",
    "razorpay_key_id": null,
    "razorpay_key_secret": null,
    "mercado_payment": "0",
    "mercado_currency": null,
    "mercado_api_key": null,
    "mercado_token": null,
    "flutterwave_payment": "0",
    "flutterwave_public_key": null,
    "flutterwave_secret_key": null,
    "offline_details": null,
    "enable_offline_payment": "0",
    "status": "1",
    "country": null,
    "currency": "USD",
    "paypal_mode": "live",
    "about_me": null,
    "available_days": null,
    "google_analytics": null,
    "enable_appointment": "1",
    "enable_rating": "1",
    "twillo_account_sid": null,
    "twillo_auth_token": null,
    "twillo_number": null,
    "enable_sms_notify": "0",
    "enable_sms_alert": "0",
    "remember_me_token": null,
    "date_format": "d M Y",
    "created_at": "2023-10-22 08:21:42"
  }
}
</code></pre>
</div>

                  </div>
                </div>






                <div class="create_user_area hide mt-3">
                  <h6>Endpoint</h6>
                  <div class="p-3 grey mb-3 rounded">
                    <p class="mb-0"><span class="badge badge-primary-soft py-2 px-3 mr-2">POST</span> <?php echo base_url('api/insert_user') ?></p>
                  </div>

                  <h6>Example</h6>
                  <div class="p-0 mb-3">
                   
<pre class="p-0"><code class="language-php rounded">
$password = password_hash('1234', PASSWORD_BCRYPT);
$data=array(
    'name' =>'John Doe',
    'slug' => 'john-doe',
    'user_name' => 'john-doe',
    'email' => 'john@gmail.com',
    'phone' =>'8457457567',
    'thumb' => 'assets/images/no-photo-sm.png',
    'password' => $password,
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
    'created_at' => date('Y-m-d H:i:s'),
    'referral_id' => 'random_8_digit'
);
$jsonData = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "<?php echo base_url('api/insert_user') ?>");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Api-Key: ' . $apiKey,
    'Content-Type: application/json'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
echo $response;
</code></pre>

                  </div>
                  <h6>Response</h6>
                  <div class="mt-3">
       
<pre class="p-0"><code class="language-php rounded">
{
  "status": "success",
  "id": "1"
}
</code>
</pre>
      
                  </div>
                </div>






                <div class="update_user_area hide mt-3">
                  <h6>Endpoint</h6>
                  <div class="p-3 grey mb-3 rounded">
                    <p class="mb-0"><span class="badge badge-primary-soft py-2 px-3 mr-2">POST</span> <?php echo base_url('api/update_user/{$user_id}') ?></p>
                  </div>

                  <h6>Example</h6>
                  <div class="p-0 mb-3">
                    <pre class="p-0"><code class="language-php rounded">
$password = password_hash('1234', PASSWORD_BCRYPT);
$data=array(
    'name' =>'John Doe',
    'slug' => 'john-doe',
    'user_name' => 'john-doe',
    'email' => 'john@gmail.com',
    'phone' =>'8457457567',
    'thumb' => 'assets/images/no-photo-sm.png',
    'password' => $password,
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
    'created_at' => date('Y-m-d H:i:s'),
    'referral_id' => 'random_8_digit'
);
$jsonData = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "<?php echo base_url('api/update_user/{$user_id}') ?>");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Api-Key: ' . $apiKey,
    'Content-Type: application/json'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
echo $response;
</code></pre>
                  </div>

                  <div class="mt-3">
                    <h6 class="mb-2">Response</h6>
                    <div class="mt-3">
               
<pre class="p-0"><code class="language-php rounded">
{
  "status": "success"
}
</code>
</pre>
                   
                  </div>
                  </div>
                </div>






                <div class="delete_user_area hide mt-3">
                 <h6>Endpoint</h6>
               
                  <div class="p-3 grey mb-3 rounded">
                    <p class="mb-0"><span class="badge badge-success-soft py-2 px-3 mr-2">GET</span> <?php echo base_url('api/delete_user/{user_id}') ?></p>
                  </div>

                  <h6>Example</h6>
                  <div class="p-0 grey mb-3">
                   <pre class="p-0"><code class="language rounded mb-0">
// -- curl --request GET <br>
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "<?php echo base_url('api/delete_user/{user_id}') ?>");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Api-Key: ' . 'your_api_key',
    'Content-Type: application/json'
));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
echo $response;
</code></pre>
                  </div>

                  <div class="mt-3">
                    <h6 class="mb-2">Response</h6>
<pre class="p-0"><code class="language-php rounded">
{
  "status": "success"
}
</code>
</pre>
                  </div>
                </div>

                <div class="login_area hide mt-3">
                 <h6>Endpoint</h6>
                  <div class="p-3 grey mb-3 rounded">
                    <p class="mb-0"><span class="badge badge-success-soft py-2 px-3 mr-2">href</span> <?php echo base_url('api/login_user?email={user_email}&key='.settings()->api_key) ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

