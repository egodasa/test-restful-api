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
		$data['app']->data->columns = [
			["name"=> "nm_dosen","title"=>"Nama Dosen","sortField"=>"nm_dosen"],
			["name"=> "nidn","title"=>"NIDN","sortField"=>"nidn"]];
		
		$data['app']->data->table = new stdclass();
		$data['app']->data->table->tableClass = 'table table-striped table-bordered';
		$data['app']->data->table->ascendingIcon = 'glyphicon glyphicon-chevron-up';
		$data['app']->data->table->descendingIcon = 'glyphicon glyphicon-chevron-down';
		$data['app']->data->table->handleIcon = 'glyphicon glyphicon-menu-hamburger';

		$data['app']->data->pagination = new stdclass();
		$data['app']->data->pagination->wrapperClass = "pagination pull-right";
		$data['app']->data->pagination->activeClass = "btn-primary";
		$data['app']->data->pagination->disabledClass = "disabled";
		$data['app']->data->pagination->pageClass = "btn btn-border";
		$data['app']->data->pagination->linkClass = "btn btn-border";
		$data['app']->data->pagination->icons = [
				      'first'=> "",
				      'prev'=> "",
				      'next'=> "",
				      'last'=> ""];
		$data['app']->data->paginationPath = "pagination";
		$data['app']->data->sortDefault = [["field"=>"nm_dosen","direction"=>"asc"]];
		$data['app']->data->perPage = 10;
		$this->load->view('vueci', $data);
	}
	public function tabel(){
		$this->load->view('tabelmaster');
	}
}
