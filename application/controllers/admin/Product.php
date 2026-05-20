<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
        //check auth
        if (!is_user()) {
            redirect(base_url());
        }
        
        if (check_feature_access('products') != TRUE) {
            redirect(base_url('admin/dashboard/user'));
        }
    }

    public function index($id='')
    {
        // error_reporting(-1);
        // ini_set('display_errors', 1);
        $data = array();
        $config['base_url'] = base_url('admin/product/'.$id);
        $total_row = $this->admin_model->get_product($id, $total=1, 0, 0);
        $config['total_rows'] = $total_row;
        $config['per_page'] = 5;
        $this->pagination->initialize($config);
        $page = $this->security->xss_clean($this->input->get('page'));
        if (empty($page)) {
            $page = 0;
        }
        if ($page != 0) {
            $page = $page - 1;
        }
        $data['page_title'] = 'Product';  
        $data['page'] = 'Products';    
        $data['product'] = FALSE;
        $data['products']=$this->admin_model->get_product($id='',$total=0, $config['per_page'], $page * $config['per_page']);
        $data['categories'] = $this->admin_model->get_product_categories(user()->id);
        
        $data['subcategories'] = $this->admin_model->get_product_subcategories(user()->id);

        //$data['limit'] = FALSE;
        //$total = get_total_value('products');
        // if (ckeck_plan_limit('products', $total) == FALSE) {
        //     $data['limit'] = TRUE;
        // }

        $data['main_content'] = $this->load->view('admin/user/product',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function load_sub_category($id) 
    {
        $data = array();
        $subcategories = $this->admin_model->get_subcategories_by_category($id); 
        if (empty($subcategories)) {
            echo '<option value="0">'.trans('no-data-found').'</option>';
        }else{
            foreach ($subcategories as $subcategory) { 
                echo '<option value="'.$subcategory->id.'">'.$subcategory->name.'</option>';
            }
        }
    }



    public function add()
    {   
        check_status();
        
        if($_POST)
        { 
            $id = $this->input->post('id', true);
            if (empty($this->input->post('old_price'))) {
                $old_price = '0.00';
            }else{
                $old_price = $this->input->post('old_price');
            }
            $data=array(
                'lang_id' => 1,
                'business_id' => $this->business->uid,
                'user_id' => user()->id,
                'category_id' => $this->input->post('category_id', true) ,
                'subcategory_id' => $this->input->post('subcategory_id', true) ,
                'title' => $this->input->post('title', true),
                'slug' => str_slug($this->input->post('slug', true)),
                'short_desc' => $this->input->post('short_desc', true),
                'details' => $this->input->post('details', true),
                'price' => $this->input->post('price', true),
                'old_price' => $old_price,
                'sku' => random_string('alnum', 8),
                'quantity' => $this->input->post('quantity', true),
                'stock_type' => $this->input->post('stock_type', true),
                'tags' => $this->input->post('tags', true),
                'meta_tags' => $this->input->post('meta_tags', true),
                'meta_desc' => $this->input->post('meta_desc', true),
                'attributes' => $this->input->post('attributes', true),
                'status' => $this->input->post('status', true),
            );
            $data = $this->security->xss_clean($data);

            if (!empty($id)) {
                $this->admin_model->edit_option($data, $id, 'products');
                $this->session->set_flashdata('msg', trans('updated-successfully')); 
            } else {
                $id = $this->admin_model->insert($data, 'products');
                $this->session->set_flashdata('msg', trans('inserted-successfully')); 
            }


            if(!empty($_FILES['images']['name'])){

                $filesCount = count($_FILES['images']['name']);
                for($i = 0; $i < $filesCount; $i++){

                    $_FILES['file']['name']     = $_FILES['images']['name'][$i];
                    $_FILES['file']['type']     = $_FILES['images']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
                    $_FILES['file']['error']    = $_FILES['images']['error'][$i];
                    $_FILES['file']['size']     = $_FILES['images']['size'][$i];
                    

                    $uploadPath = './uploads/medium/';
                    $uploadPath_thumb = './uploads/thumbnail/';
                    $config['upload_path'] = $uploadPath;
                    $config['upload_path_thumb'] = $uploadPath_thumb;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['remove_spaces'] = TRUE;
                    $config['encrypt_name'] = TRUE;
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    $this->load->library('image_lib');
                    
                    if($this->upload->do_upload('file')){
                        
                        $image_data =   $this->upload->data();

                        $configer =  array(
                          'image_library'   => 'gd2',
                          'source_image'    =>  $image_data['full_path'],
                          'new_image'       =>  $uploadPath,
                          'maintain_ratio'  =>  TRUE,
                          'width'           =>  1200,
                          'height'          =>  1200,
                        );
                        $this->image_lib->initialize($configer);
                        $this->image_lib->resize();
                        $this->image_lib->clear();

                        $configer =  array(
                          'image_library'   => 'gd2',
                          'source_image'    =>  $image_data['full_path'],
                          'new_image'       =>  $uploadPath_thumb,
                          'maintain_ratio'  =>  TRUE,
                          'width'           =>  450,
                          'height'          =>  300,
                        );
                        $this->image_lib->initialize($configer);
                        $this->image_lib->resize();
                        $this->image_lib->clear();
                        
                        $idata = array(
                            'image' => 'uploads/medium/'.$image_data['file_name'],
                            'thumb' => 'uploads/thumbnail/'.$image_data['file_name'],
                            'product_id' => $id,
                        );
                        $this->admin_model->insert($idata, 'product_images');

                    }

                }    
                   
            }else{
                echo "not found"; exit();
            }



            $downloadable = $this->input->post('downloadable_product');

            if(!empty($downloadable)){
                $new_name = "file_".strtolower(uniqid().'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $config['upload_path']          = './uploads/files'; //file save path
                $config['allowed_types']        = 'pdf';
                $config['max_size']             = 10000;
                $config['file_name'] = $new_name;


                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('file'))
                {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('msg', $error);
                    
                }else{

                    $data = array(
                        'file' => 'uploads/files/' . $new_name,
                    );
                    $data = $this->security->xss_clean($data);
                    $this->admin_model->edit_option($data,$id,'products');
                }
            }

            $this->session->set_flashdata('msg', trans('updated-successfully')); 
            redirect(base_url('admin/product'));

        }      
        
    }

    public function edit($id)
    {  
        $data = array();
        $data['page_title'] = 'Edit';  
        $data['page'] = 'Product';     
        $data['product'] = $this->admin_model->get_by_id($id, 'products');
        $data['product_images'] = $this->admin_model->get_product_images($id,);
        $data['categories'] = $this->admin_model->get_product_categories(user()->id);
        //echo "<pre>"; print_r($data['categories']); exit();
        $data['subcategories'] = $this->admin_model->get_product_subcategories(user()->id);

        $data['main_content'] = $this->load->view('admin/user/product',$data,TRUE);
        $this->load->view('admin/index',$data);
    }
    
    public function active($id) 
    {
        $data = array(
            'status' => 1
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'products');
        $this->session->set_flashdata('msg', trans('activate-successfully')); 
        redirect(base_url('admin/product'));
    }

    public function deactive($id) 
    {
        $data = array(
            'status' => 0
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'products');
        $this->session->set_flashdata('msg', trans('deactivate-successfully')); 
        redirect(base_url('admin/product'));
    }

    public function delete($id)
    {
        $this->admin_model->delete($id,'products');
        $this->admin_model->delete_product_img($id, 'product_images'); 
        echo json_encode(array('st' => 1));
    }

    public function delete_img($id)
    {
        $this->admin_model->delete($id,'product_images'); 
        echo json_encode(array('st' => 1));
    }

    public function download($id){
        $this->load->helper('download');
        $product= $this->admin_model->get_by_id($id, 'products');
        $file_name = basename($product->file);
        $data = file_get_contents($product->file);
        $name = $file_name;

        force_download($name, $data);
        $this->session->set_flashdata('msg', trans('download-successfully'));
    }







     public function category()
    {

        $data = array();
        $data['page_title'] = 'Category';
        $data['category'] = FALSE;
        $data['page'] = 'Products';  
        $data['categories'] = $this->admin_model->get_product_categories(user()->id);
        //echo "<pre>"; print_r($data['categories']); exit();
        $data['main_content'] = $this->load->view('admin/user/product_categories',$data,TRUE);
        $this->load->view('admin/index',$data);
    }



    public function category_add()
    {   
        check_status();
        
        if($_POST)
        {   
            $id = $this->input->post('id', true);

            $data=array(
                'lang_id' => 1,
                'user_id' => user()->id,
                'business_id' => $this->business->uid,
                'name' => $this->input->post('name', true),
                'slug' => str_slug(trim($this->input->post('name', true))),
                'details' => $this->input->post('details',true),
                'status' => $this->input->post('status',true)
            );
            $data = $this->security->xss_clean($data);
           
            if ($id != '') {
                $this->admin_model->edit_option($data, $id, 'product_category');
                $this->session->set_flashdata('msg', trans('updated-successfully')); 
            } else {
                $id = $this->admin_model->insert($data, 'product_category');
                $this->session->set_flashdata('msg', trans('inserted-successfully')); 
            }
            redirect(base_url('admin/product/category'));

        }      
        
    }

    public function category_edit($id)
    {  
        $data = array();
        
        $data['page_title'] = 'Edit Category';
        $data['page'] = 'Products';   
        $data['category'] = $this->admin_model->select_option($id, 'product_category');
        $data['main_content'] = $this->load->view('admin/user/product_categories',$data,TRUE);
        $this->load->view('admin/index',$data);
    }



    public function category_delete($id)
    {
        $this->admin_model->delete($id,'product_category'); 
        echo json_encode(array('st' => 1));
    }



     public function product_sub()
    {
        $data = array();
        $data['page_title'] = 'Subcategory';  
        $data['subcategory'] = FALSE;

        $data['page'] = 'Products';
        $data['categories'] = $this->admin_model->get_product_categories(user()->id);
        //echo "<pre>"; print_r($data['categories']); exit();
        $data['subcategories'] = $this->admin_model->get_product_subcategories(user()->id);
        $data['main_content'] = $this->load->view('admin/user/product_subcategories',$data,TRUE);
        $this->load->view('admin/index',$data); 
    }

    public function sub_add()
    {   
        check_status();
        
        if($_POST)
        {   
            $id = $this->input->post('id', true);

            $data=array(
                'lang_id' => 1,
                'business_id' => $this->business->uid,
                'user_id' => user()->id,
                'name' => $this->input->post('name', true),
                'slug' => str_slug(trim($this->input->post('name', true))),
                'parent_id' =>$this->input->post('category_id', true),
                'details' => $this->input->post('details', true),
                'status' => $this->input->post('status', true)
            );
            $data = $this->security->xss_clean($data);

           
            if ($id != '') {
                $this->admin_model->edit_option($data, $id, 'product_category');
                $this->session->set_flashdata('msg', trans('updated-successfully')); 
            } else {
                $id = $this->admin_model->insert($data, 'product_category');
                $this->session->set_flashdata('msg', trans('inserted-successfully')); 
            }
            redirect(base_url('admin/product/product_sub'));

        }      
        
    }

    public function sub_edit($id)
    {  
        $data = array();

        $data['page_title'] = 'Sub Edit';
        $data['page'] = 'Products';
        $data['categories'] = $this->admin_model->get_product_categories(user()->id);  
        $data['subcategory'] = $this->admin_model->select_option($id, 'product_category');
        $data['main_content'] = $this->load->view('admin/user/product_subcategories',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function sub_delete($id)
    {
        $this->admin_model->delete($id,'product_category'); 
        echo json_encode(array('st' => 1));
    }

    public function orders($id='')
    {
        
        $data = array();
        $data['page_title'] = 'Order';  
        $data['page'] = 'Products';    
        $data['product'] = FALSE;

        //initialize pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('admin/product/orders');
        $total_row = $this->admin_model->get_product_orders(1 , 0, 0);
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




        $data['product_orders'] = $this->admin_model->get_product_orders(0 , $config['per_page'], $page * $config['per_page']);
        $data['customers'] = $this->admin_model->get_order_customer();

        $data['main_content'] = $this->load->view('admin/user/orders',$data,TRUE);
        $this->load->view('admin/index',$data);
    }


    public function order_details($id)
    {
        $data = array();
        $data['page_title'] = 'Order Details';  
        $data['page'] = 'Products';    
        $data['product'] = FALSE;
        $data['orders'] = $this->admin_model->get_order($id);
        $data['main_content'] = $this->load->view('admin/user/order_details',$data,TRUE);
        $this->load->view('admin/index',$data);
    }

    public function order_delete($id)
    {
        $this->admin_model->delete($id,'product_orders'); 
        echo json_encode(array('st' => 1));
    }

    public function order_confirm($id) 
    {
        $data = array(
            'order_status' => 1,
            'confirm_date' => date('Y-m-d'),
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'product_orders');
        $this->session->set_flashdata('msg', trans('order-confirm-successfully'));




        $order=$this->admin_model->get_by_id($id,'product_orders');
        $orders=$this->admin_model->get_order_details_by_id($order->id);

        foreach ($orders as $order) {
            $product_quantity = get_by_id($order->product_id,'products')->quantity;
            $order_quantity = $order->qty;
            $stock = $product_quantity - $order_quantity ;
            $data = array(
                'quantity' =>$stock ,
            );
            $this->admin_model->update($data, $order->product_id,'products');
        }




        redirect(base_url('admin/product/orders'));
    }

    public function order_delivered($id) 
    {
        
        
        $data = array(
            'order_status' => 2,
            'delivered_date' => date('Y-m-d'),
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'product_orders');
        $this->session->set_flashdata('msg', trans('order-delivered-successfully')); 
        redirect(base_url('admin/product/orders'));
    }


    public function order_cancel($id) 
    {
        $data = array(
            'order_status' => 3,
        );
        $data = $this->security->xss_clean($data);
        $this->admin_model->update($data, $id,'product_orders');
        $this->session->set_flashdata('msg', trans('order-cancel-successfully')); 
        redirect(base_url('admin/product/orders'));
    }


     public function record_payment($order_id)
    {   
        $order = $this->admin_model->get_by_id($order_id,'product_orders');
        $uid = random_string('numeric',5);


        $total_amount = '0.00';
        $commission_amount = '0.00';
        $commission_rate = '0';
        

        $pay_data = array(
            'user_id' => user()->id,
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'puid' => $uid,
            'status' => 'verified',
            'amount' => $order->total_price,
            'total_amount' => $total_amount,
            'commission_amount' => $commission_amount,
            'commission_rate' => $commission_rate,
            'payment_method' => 'offline',
            'type' => 'user',
            'created_at' => my_date_now()
        );
        $pay_data = $this->security->xss_clean($pay_data);
         
        $response = $this->common_model->insert($pay_data, 'product_payment_user');

        $updata = array(
            'payment_status' => 1,
        );
        $this->admin_model->edit_option($updata,$order->id,'product_orders');
        //echo "<pre>"; print_r($updata); exit();
        $this->session->set_flashdata('msg', trans('inserted-successfully')); 
        redirect($_SERVER['HTTP_REFERER']);
    }

}

