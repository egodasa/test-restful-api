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
	public function __construct(){
		parent::__construct();
		$this->menu = [
				[	
					"icon"=>"fa fa-dashboard",
					"text"=>"Data Master",
					"child"=>[
						[
							"url"=>"dosen",
							"icon"=>"fa fa-dashboard",
							"text"=>"Dosen"
						],
						[
							"url"=>"mahasiswa",
							"icon"=>"fa fa-dashboard",
							"text"=>"Mahasiswa"
						]
					]
				]
			];
	}
	public function dosen()
	{
		$this->load->database();
		$data['app'] = new stdclass();
		$data['app']->data = new stdclass();
		$data['app']->name = 'vueci';
		$data['menu'] = $this->menu;
		$this->load->view('dosen', $data);
	}
	public function tabelmaster(){
		$this->load->view('tabelmaster');
	}
	public function form(){
		$data['menu'] = $this->menu;
		$data['title'] = 'Contoh Form';
		$data['subTitle'] = 'Contoh Form menggunakan vue form generator';
		$data['app'] = new stdclass();
		$data['app']->name = "form";
		$this->load->view('formcontoh',$data);
	}
	public function mahasiswa()
	{
		$this->load->database();
		$data['app'] = new stdclass();
		$data['app']->data = new stdclass();
		$data['app']->name = 'mahasiswa';
		$data['menu'] = $this->menu;
		$this->load->view('mahasiswa', $data);
	}
	public function contohvue()
	{
		$this->load->view('contohvue');
	}
}
