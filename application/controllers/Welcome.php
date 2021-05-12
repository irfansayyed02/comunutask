<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("AdminModel","Admin");
		if(!isset($this->session->userdata['LoggedIn']))
        { 			
			$FlashData = array(
			"ErrorAlert" => "Please Login to access this page!"
			);			
			redirect(base_url().'Login');			
        }

        $sessionData = $this->session->userdata['LoggedIn'];
		
	}

	public function index()
	{
		$sessionData = $this->session->userdata['LoggedIn'];

		$data['userlist'] = $this->Admin->GetSingleTable('users');

		$this->load->view('welcome_message', $data,$sessionData);
	}

	

	
}

?>