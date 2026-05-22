<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


class Home extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
       
        if (settings()->enable_frontend == 0) {
            redirect(base_url('login'));
        }
    }


    public function index()
    {
       

        if(!empty($_GET['ref'])){
           $this->session->set_userdata('ref',$_GET['ref']); 
        }

        $data = array();
        $data['page'] = 'Front';
        $data['page_title'] = 'Home';
        $data['menu'] = TRUE;
        $data['features'] = $this->common_model->select_by_language('product_services');
        $data['workflows'] = $this->common_model->select_by_language('workflows');
        $data['testimonials'] = $this->common_model->select_testimonial_by_language();
        $data['posts'] = $this->common_model->get_home_blog_posts();
        $data['brands'] = $this->common_model->get_site_brands();
        $data['faqs'] = $this->common_model->select_by_language('faqs');
        $data['count_countries'] = $this->common_model->get_countries_count();
        $data['count_bookings'] = $this->common_model->get_bookings_count();
        
        if (settings()->home_layout == 3) {
            $data['main_content'] = $this->load->view('theme3/home3', $data, TRUE);
        }else{
            $data['main_content'] = $this->load->view('theme/home'.settings()->home_layout, $data, TRUE);
        }

        $this->load->view('index', $data);
    }

   
    public function switch_lang($language = "")
    {   
        $language = ($language != "") ? $language : "english";
        $site_lang = array('site_lang' => $language);
        $this->session->set_userdata($site_lang);
        redirect($_SERVER['HTTP_REFERER']);
    }


    //features
    public function features()
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['page_title'] = 'Features';
        $data['menu'] = TRUE;
        $data['features'] = $this->common_model->select_by_language('features');
        
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/features';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/features';
        }

        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //companies
    public function users()
    {   
        if (settings()->enable_users == 0){
            redirect(base_url());
        }

        $data = array();
        $data['page'] = 'Front';
        
        //initialize pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('home/users');
        $total_row = $this->common_model->get_all_business(1 , 0, 0);
        $config['total_rows'] = $total_row;
        $config['per_page'] = 9;
        $this->pagination->initialize($config);
        $page = $this->security->xss_clean($this->input->get('page'));
        if (empty($page)) {
            $page = 0;
        }
        if ($page != 0) {
            $page = $page - 1;
        }

        $data['page_title'] = 'Companies';
        $data['menu'] = TRUE;
        $data['companies'] = $this->common_model->get_all_business(0 , $config['per_page'], $page * $config['per_page']);
        $data['countries'] = $this->admin_model->select_asc('country');
        $data['locations'] = $this->common_model->get_all_locations();
        $data['categories'] = $this->admin_model->select_by_status('categories');
        
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/users';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/users';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }



    //pricing
    public function pricing()
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['page_title'] = 'Pricing';
        $data['menu'] = TRUE;
        $data['packages'] = $this->admin_model->get_package_features();
        $data['features'] = $this->admin_model->get_features();
        //echo "<pre>"; print_r($data['features']); exit();
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/price';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/price';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //faqs
    public function faqs()
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['page_title'] = 'Faqs';
        $data['menu'] = TRUE;
        $data['faqs'] = $this->common_model->select_by_language('faqs');
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/faqs';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/faqs';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

 
    //purchase page
    public function purchase($payment_id)
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['menu'] = TRUE;
        $data['payment'] = $this->common_model->get_payment($payment_id);
        $data['payment_id'] = $payment_id;  
        $data['package'] = $this->common_model->get_package_by_slug($data['payment']->package);
        $this->load->view('purchase', $data);
    }

    

    //send contact message
    public function send_message()
    {     
        if ($_POST) {
            $data = array(
                'name' => $this->input->post('name', true),
                'business_id' => 0,
                'user_id' => 0,
                'email' => $this->input->post('email', true),
                'message' => $this->input->post('message', true),
                'created_at' => my_date_now()
            );
            $data = $this->security->xss_clean($data);
            
            //check reCAPTCHA status
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('error', trans('recaptcha-is-required')); 
            } else {
                $this->common_model->insert($data, 'contacts');
                $this->session->set_flashdata('msg', trans('send-successfully'));

                //send message


                $subject = get_email_by_slug('contact-submit-admin')->subject;
                $body = get_email_by_slug('contact-submit-admin')->body;
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
                $response = $this->email_model->send_email(settings()->admin_email, $subject, $msg);


           
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

  
    public function contact()
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['menu'] = TRUE;
        $data['page_title'] = 'Contact';
        $data['settings'] = $this->common_model->get('settings');
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/contact';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/contact';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //show pages
    public function page($slug)
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['page_title'] = 'Pages';
        $data['menu'] = TRUE;
        $data['pages'] = $this->common_model->get_single_page($slug);
        if (empty($data['page'])) {
            redirect(base_url());
        }
        $data['page_name'] = $data['page']->title;
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/page';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/page';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //show pages
    public function terms()
    {   
        $data = array();
        $data['page'] = 'Front';
        $data['page_title'] = 'Terms of Service';
        $data['menu'] = TRUE;
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/terms';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/terms';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //blogs
    public function blogs()
    {   
        $data = array();
        $data['page'] = 'Front';
        //initialize pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('blogs');
        $total_row = $this->common_model->get_site_blog_posts(1 , 0, 0,0);
        $config['total_rows'] = $total_row;
        $config['per_page'] = 9;
        $this->pagination->initialize($config);
        $page = $this->security->xss_clean($this->input->get('page'));
        if (empty($page)) {
            $page = 0;
        }
        if ($page != 0) {
            $page = $page - 1;
        }
        
        $data['page_title'] = 'Blogs';
        $data['menu'] = TRUE;
        $data['posts'] = $this->common_model->get_site_blog_posts(0 , $config['per_page'], $page * $config['per_page'],0);
        $data['categories'] = $this->common_model->get_blog_categories();
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/blog_posts';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/blog_posts';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //category
    public function category($slug)
    {   
        $data = array();
        $data['page'] = 'Front';
        $slug = $this->security->xss_clean($slug);
        $category = $this->common_model->get_category_by_slug($slug);
        
        if (empty($category)) {
            redirect(base_url('blog'));
        }

        //initialize pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('category/'.$slug);
        $total_row = $this->common_model->get_category_posts(1 , 0, 0, $category->id);
        $config['total_rows'] = $total_row;
        $config['per_page'] = 9;
        $this->pagination->initialize($config);
        $page = $this->security->xss_clean($this->input->get('page'));
        if (empty($page)) {
            $page = 0;
        }
        if ($page != 0) {
            $page = $page - 1;
        }
        
        $data['page_title'] = 'Category Posts';
        $data['menu'] = TRUE;
        $data['title'] = $category->name;
        $data['posts'] = $this->common_model->get_category_posts(0, $config['per_page'], $page * $config['per_page'], $category->id);
        $data['categories'] = $this->common_model->get_blog_categories();
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/blog_posts';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/blog_posts';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }

    //post details
    public function post_details($slug)
    {   

        $data = array();
        $data['page'] = 'Front';
        $slug = $this->security->xss_clean($slug);
        $data['page_title'] = 'Post details';
        $data['menu'] = TRUE;
        $data['post'] = $this->common_model->get_post_details($slug);

        if (empty($data['post'])) {
            redirect(base_url());
        }
        $category_id = $data['post']->category_id;
        $post_id = $data['post']->id;
        $data['post_id'] = $post_id;

        $data['comments'] = $this->common_model->get_comments_by_post($data['post']->id);
        $data['total_comment'] = count($data['comments']);
        //echo "<pre>"; print_r($data['post']); exit();
        $data['tags'] = $this->common_model->get_post_tags($post_id);
        if (settings()->home_layout == 1 || settings()->home_layout == 2) {
            $view_file = 'theme/single_post';
        }else{
            $view_file = 'theme'.settings()->home_layout.'/single_post';
        }
        $data['main_content'] = $this->load->view($view_file, $data, TRUE);
        $this->load->view('index', $data);
    }


    //send comment
    public function send_comment($post_id)
    {     
        if ($_POST) {
            $data = array(
                'post_id' => $post_id,
                'name' => $this->input->post('name', true),
                'email' => $this->input->post('email', true),
                'message' => $this->input->post('message', true),
                'created_at' => my_date_now()
            );
            $data = $this->security->xss_clean($data);
            $this->common_model->insert($data, 'comments');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public function demo()
    {  
        $this->load->view('demo');
    }

    // not found page
    public function error_404()
    {
        $data['page_title'] = "Error 404";
        $data['description'] = "Error 404";
        $data['keywords'] = "error,404";
        $data['main_content'] = $this->load->view('theme/error_404', $data, TRUE);
        $this->load->view('index', $data);
    }

    public function test_break_slots()
    {

        $start = '10:00';
        $end = '20:00';
        $break_start = '14:00';
        $break_end = '15:30';
        $slot_duration_minutes = '120';


        $slots = [];

        $current      = new DateTime($start);
        $work_end     = new DateTime($end);
        $break_start  = new DateTime($break_start);
        $break_end    = new DateTime($break_end);

        while ($current < $work_end) {
            $slot_end = clone $current;
            $slot_end->modify("+{$slot_duration_minutes} minutes");

            // Stop if slot goes beyond working hour
            if ($slot_end > $work_end) break;

            // If overlaps with break, jump current time to break_end
            if ($slot_end > $break_start && $current < $break_end) {
                $current = clone $break_end;
                continue;
            }

            // Valid slot
            $slots[] = [
                'start' => $current->format("H:i"),
                'end'   => $slot_end->format("H:i"),
                'label' => $current->format("H:i") . " - " . $slot_end->format("H:i"),
            ];

            // Move to next slot
            $current->modify("+{$slot_duration_minutes} minutes");
        }

        echo "<pre>"; print_r($slots); exit();
    }

}