<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("AdminModel","Admin");
	}

	public function login()
	{
		if(isset($this->session->userdata['LoggedIn']))
		{
			redirect(base_url().'Dashboard');
		}
		
		$this->load->view('login');
	}

	public function userlogin()
	{
	    
		$this->form_validation->set_rules('useremail', 'useremail', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE) 
		{ 
			if(isset($this->session->userdata['LoggedIn']))
			{
				redirect(base_url().'Dashboard');
			}
			else
			{
				redirect(base_url().'Login');
			}
		} 
		else 
		{
			$LoginArray = array(
			'useremail' => $this->input->post('useremail'),
			'password' => MD5($this->input->post('password')),
			);
			
			$DoLogin = $this->Admin->DoLogin($LoginArray);
			
			if ($DoLogin) 
			{
				$SessionArray = array(
					'UserId' => $DoLogin['id'],
					'Useremail' => $DoLogin['useremail'],
					'Mobile' => $DoLogin['mobile'],
					'ProfilePic' => $DoLogin['profilepic'],
				);
				
				$this->session->set_userdata('LoggedIn', $SessionArray);

				redirect(base_url().'Dashboard');
				
			} 
			else 
			{
				$this->session->set_flashdata('ErrorAlert', 'Invalid Username OR Password');
				redirect(base_url().'Login');
			}
		}		
	}

	public function Logout() {
		$this->session->unset_userdata('LoggedIn');
		redirect(base_url().'Login');
	}

	public function register()
	{
		$this->load->view('register');
	}

	public function registeruser()
	{

		// print_r($this->input->post()); die();

		$email = $this->input->post('useremail');
		$mobile = $this->input->post('usermobile');
		$password = $this->input->post('password');

		$config['upload_path'] = './assets/doc/';
        $config['allowed_types'] = '*';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $this->upload->do_upload('file');
		$upload_data = $this->upload->data();
		$profile = $upload_data['file_name'];
        
		if(empty($profile)){
	    	$ResponseMessage = $this->session->set_flashdata('ErrorAlert', 'Somethinhg Went Wrong.!!!');
    		redirect(base_url().'Register');
		}
	    
		$Condition = "useremail = '".$email."'";
    	$UserExist = $this->Admin->CheckUserExist('users', $Condition);
    	
    	if($UserExist)
    	{
    		$ResponseMessage = $this->session->set_flashdata('ErrorAlert', 'User already exits.Please try with new email.!!!');
    		redirect(base_url().'Register');
    	}
    	else
    	{
    			$data = array(
					"useremail" => $email,
					"mobile" => $mobile,
					"password" => md5($password),
					"profilepic" => $profile,
					);
				
				$UserInsert = $this->Admin->InsertCommon('users', $data);
			
				if($UserInsert)
				{
					$ResponseMessage = $this->session->set_flashdata('SuccessAlert', 'User Successfully Added');
					redirect(base_url().'Login');
				}
				else
				{
					$ResponseMessage = $this->session->set_flashdata('ErrorAlert', 'Something Went Wrong!!');
				}
				redirect(base_url().'Register');
    	
    	}      
	}

}