<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vueci extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->database();
		$data['app'] = new stdclass();
		$data['app']->data = new stdclass();
		$data['app']->name = 'vueci';
		$data['app']->data->base_url = "http://localhost/api/dosen";
		$data['app']->data->columns = json_decode('[{"name": "nm_dosen", "title": "Nama Dosen"},
			{"name": "nidn", "title": "NIDN"}]');
		$this->load->view('vueci', $data);
	}
	public function tabel(){
		$this->load->view('tabelmaster');
	}
}
