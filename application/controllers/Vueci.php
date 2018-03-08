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
					"url"=>"tabel",
					"icon"=>"fa fa-dashboard",
					"text"=>"Tabel"],
				[
					"url"=>"form",
					"icon"=>"fa fa-dashboard",
					"text"=>"Form"],
				[	
					"icon"=>"fa fa-dashboard",
					"text"=>"Home",
					"child"=>[
						[
							"url"=>"/about1",
							"icon"=>"fa fa-dashboard",
							"text"=>"About1"
						],
						[
							"url"=>"/about1",
							"icon"=>"fa fa-dashboard",
							"text"=>"About1"
						]
					]
				]
			];
	}
	public function tabel()
	{
		$this->load->database();
		$data['app'] = new stdclass();
		$data['app']->data = new stdclass();
		$data['app']->name = 'vueci';
		$data['app']->data->base_url = "http://localhost/api/dosen";
		$data['app']->data->url = "http://localhost/api/dosen";
		$data['app']->data->url_search = "http://localhost/api/cari/dosen";
		$data['app']->data->columns = [
			["name"=> "nm_dosen","title"=>"Nama Dosen","sortField"=>"nm_dosen"],
			["name"=> "nidn","title"=>"NIDN","sortField"=>"nidn"]];
		
		$data['app']->data->table = new stdclass();
		$data['app']->data->table->tableClass = 'table table-bordered table-striped dataTable';
		$data['app']->data->table->ascendingIcon = 'glyphicon glyphicon-chevron-up';
		$data['app']->data->table->descendingIcon = 'glyphicon glyphicon-chevron-down';
		$data['app']->data->table->handleIcon = 'sorting';

		$data['app']->data->pagination = new stdclass();
		$data['app']->data->pagination->wrapperClass = "pagination pagination-sm no-margin pull-right";
		$data['app']->data->pagination->activeClass = "btn-primary";
		$data['app']->data->pagination->disabledClass = "disabled";
		$data['app']->data->pagination->pageClass = "btn btn-border";
		$data['app']->data->pagination->linkClass = "btn btn-border";
		$data['app']->data->pagination->infoClass = "pull-left";
		$data['app']->data->pagination->icons = [
				      'first'=> "glyphicon glyphicon-fast-backward",
				      'prev'=> "glyphicon glyphicon-backward",
				      'next'=> "glyphicon glyphicon-forward",
				      'last'=> "glyphicon glyphicon-fast-forward"];
		$data['app']->data->paginationPath = "pagination";
		$data['app']->data->sortDefault = [["field"=>"nm_dosen","direction"=>"asc"]];
		$data['app']->data->perPage = 10;
		$data['app']->data->search = '';
		
		$data['menu'] = $this->menu;
		$this->load->view('admincontoh', $data);
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
}
