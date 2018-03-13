<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package	CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author	Adam Whitney
 * @link	http://outergalactic.org/
*/
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
class Mahasiswa extends REST_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = "tbmahasiswa";
        $this->tablePk = "nobp";
        $this->fullTextSearch = 'nobp,nm_mahasiswa';
        $this->load->library('form_validation');
        $this->validationRules = [
			"queryString" => [
				[
					[
						"field" => "per_page",
						"label" => "Per Page",
						"rules" => "is_natural"
					],
					[
						"field" => "page",
						"label" => "Page",
						"rules" => "is_natural"
					],
					[
						"field" => "sort",
						"label" => "Sort",
						"rules" => "regex_match[/[a-zA-Z0-9]{0,20}\|(asc|desc)/]"
					],
					[
						"field" => "search",
						"label" => "Search",
						"rules" => "max_length[150]"
					]
				]
			]
        ];
    }
    
    function index_get($id = '')
    {
		$res = [
			"status_code" => 200,
			"errors" => null,
			"data"=>new stdclass(),
			"pagination"=>new stdclass()
		];
		$per_page = $this->query('per_page');
		$page = $this->query('page');
		$sort = $this->query('sort');
		$search = $this->query('search');
		$formValidation = $this->form_validation;
		$formValidation->set_data($this->query());
		$formValidation->set_rules('per_page','Per page','is_natural');
		$formValidation->set_rules('page','Page','is_natural');
		$formValidation->set_rules('sort','Sort','regex_match[/[a-zA-Z0-9]{0,20}\|(asc|desc)/]');
		$formValidation->set_rules('search','Search','max_length[150]');
		if($formValidation->run() == FALSE){
			$res['status_code'] = 422;
			$res['errors'] = $formValidation->error_array();
			$this->response($res, 422);
		}else{
			$data = new stdclass();
			$data->pagination = new stdclass();
			
			if(isset($search) || !empty($search)) {
				$query = $this->db->select()->where('MATCH ('.$this->fullTextSearch.') AGAINST ("'.$search.'")', NULL, FALSE);
			}
			else {
				$query = $this->db->select();
			}
			$queryCount = clone $query; // Clone query sql untuk dipakai menghitung jumlah record
			$totalPage = count($queryCount->get($this->table)->result());
			
			$data->pagination->total = $totalPage;
			$data->pagination->next_page_url = null;
			$data->pagination->prev_page_url = null;
			$data->pagination->per_page = null;
			$data->pagination->current_page = 1;
			$data->pagination->from = null;
			$data->pagination->to = null;
			$data->pagination->last_page = null;
			
			if(!empty($id)){
				$data->data = $query->where($this->tablePk, $id)->get($this->table)->result();
			}else{			
				if(isset($sort)) {
						$sortTmp = explode('|', $sort);
						$sortType = substr($sortTmp[1],0,4);
						$query->order_by($sortTmp[0], $sortType);
					}
					
				if(!isset($per_page) && !isset($page)){
					$data->data = $query->get($this->table)->result();
				}else{
					if(isset($per_page) && !isset($page)) {
						$page = 1;
						$per_page = (int)$per_page;
					}
					else if(!isset($per_page) && isset($page)){
						$per_page = 10;
						$page = (int)$page;
					}
					$offset = (int)$per_page * ((int)$page - 1);
					$data->data = $query->get($this->table, $per_page, $offset)->result();
					$data->pagination->total = $totalPage;
					$lastPage = ceil($totalPage/$per_page);
				
					$queryStringPrev = new stdclass();
					$queryStringPrev->per_page = $per_page;
					$queryStringPrev->page = $page;
					$queryStringPrev->sort = $sort;
					$queryStringPrev->search = $search;
					
					$queryStringNext = clone $queryStringPrev;
					
					$queryStringNext->page = $page + 1 > $lastPage ? null : $page+1;
					$queryStringPrev->page = $page - 1 == 0 ? null : $page-1;
					
					$data->pagination->next_page_url = $queryStringNext->page == null ? null : strtok($_SERVER["REQUEST_URI"],'?').'?'.http_build_query($queryStringNext);
					$data->pagination->prev_page_url = $queryStringPrev->page == null ? null : strtok($_SERVER["REQUEST_URI"],'?').'?'.http_build_query($queryStringPrev);
					$data->pagination->per_page = (int)$per_page;
					$data->pagination->current_page = (int)$page;
					$data->pagination->from = (($page-1)*$per_page)+1;
					$data->pagination->to = $page*$per_page;
					$data->pagination->last_page = $lastPage;
					$res['data'] = $data->data;
					$res['pagination'] = $data->pagination;
				}
			}
			$this->response($res, 200);
			}
    }
    
    function index_post()
    {
		$data = $this->_post_args;
		$res = [
			"status_code" => 200,
			"errors" => null
		];
		$formValidation = $this->form_validation;
		$formValidation->set_data($data);
		$formValidation->set_rules('nobp','NOBP','required|max_length[15]|min_length[14]');
		$formValidation->set_rules('nm_mahasiswa','Nama Mahasiswa','required|max_length[150]');
		if($formValidation->run() == FALSE){
			$res['status_code'] = 422;
			$res['errors'] = $formValidation->error_array();
			$this->response($res, 422);
		}else{
			if($this->db->insert($this->table, $data)) $this->response($res, 200);
			else {
				$res['status_code'] = 500;
				$this->response(500);
			}
		}
    }
    
    public function index_put($id)
    {
		$res = [
			"status_code" => 200,
			"errors" => null
		];
		$data = $this->_put_args;
		$dataTmp = [
			"nm_mahasiswa"=>$data['nm_mahasiswa']
		];
		$formValidation = $this->form_validation;
		$formValidation->set_data($dataTmp);
		$formValidation->set_rules('nm_mahasiswa','Nama Mahasiswa','required|max_length[150]');
		if($formValidation->run() == FALSE){
			$res['status_code'] = 422;
			$res['errors'] = $formValidation->error_array();
			$this->response($res, 422);
		}else{
			if($this->db->where($this->tablePk, $id)->update($this->table, $dataTmp)) $this->response($res, 200);
			else {
				$res['status_code'] = 500;
				$this->response(500);
			}
		}
    }
        
    function index_delete($id)
    {
    	$delete = [
			$this->tablePk => $id
    	];
    	if($this->db->delete($this->table, $delete)) $this->response(200);
		else $this->response(500);
    }
}
