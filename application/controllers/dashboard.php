<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Barang_Model');
        $this->load->model('Pengiriman_Model');
        $this->load->model('Pelanggan_Model');
        $this->load->model('Kurir_Model');
    }

	public function index()
	{
		$data['title'] = "DASHBOARD";
		$data['layout'] = "dashboard";
		$data['barang_count'] = $this->Barang_Model->get_count();
        $data['detail_pengiriman_count'] = $this->Pengiriman_Model->get_count();
        $data['pelanggan_count'] = $this->Pelanggan_Model->get_count();
        $data['kurir_count'] = $this->Kurir_Model->get_count();
		
		$this->load->view('template',$data);
	}
}
