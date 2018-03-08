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
class Dosen extends REST_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = "tbdosen";
        $this->tablePk = "nidn";
        $this->fullTextSearch = 'nidn,nm_dosen';
    }
    
    function index_get($cari = '')
    {
		$per_page = $this->query('per_page');
		$page = $this->query('page');
		$sort = $this->query('sort');
		$data = new stdclass();
		$data->pagination = new stdclass();
		
		$query = $this->db->select()->where('MATCH ('.$this->fullTextSearch.') AGAINST ("'.$cari.'")', NULL, FALSE);
		$data->pagination->next_page_url = null;
		$data->pagination->prev_page_url = null;
		$data->pagination->per_page = null;
		$data->pagination->current_page = 1;
		$data->pagination->from = null;
		$data->pagination->to = null;
		$data->pagination->last_page = null;
		
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
			$totalPage = count($data->data);
			$data->pagination->total = $totalPage;
			$lastPage = ceil($totalPage/$per_page);
		
			$queryStringPrev = new stdclass();
			$queryStringPrev->per_page = $per_page;
			$queryStringPrev->page = $page;
			$queryStringPrev->sort = $sort;
			
			$queryStringNext = clone $queryStringPrev;
			
			$queryStringNext->page = $page + 1 > $lastPage ? null : $page+1;
			$queryStringPrev->page = $page - 1 == 0 ? null : $page-1;
			
			$data->pagination->next_page_url = $queryStringNext->page == null ? null : strtok($_SERVER["REQUEST_URI"],'?').'?'.http_build_query($queryStringNext);
			$data->pagination->prev_page_url = $queryStringPrev->page == null ? null : strtok($_SERVER["REQUEST_URI"],'?').'?'.http_build_query($queryStringPrev);
			$data->pagination->per_page = (int)$per_page;
			$data->pagination->current_page = (int)$page;
			$data->pagination->from = ($page-1)*$per_page;
			$data->pagination->to = $page*$per_page;
			$data->pagination->last_page = $lastPage;
		}
		$this->response($data, 200);
    }
}
