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
    }
    
    function index_get($id = '')
    {
		$per_page = $this->query('per_page');
		$page = $this->query('page');
		$sort = $this->query('sort');
		$data = new stdclass();
		$data->pagination = new stdclass();
		
		$query = $this->db;
		
		$totalPage = $this->db->count_all($this->table);
		$data->pagination->total = $totalPage;
		
		if(!empty($id)){
			$data->data = $query->where($this->tablePk, $id)->get($this->table)->result();
		}else{
			if(isset($sort)) {
					$sortTmp = explode('|', $sort);
					$sortType = substr($sortTmp[1],0,4);
					$query->order_by($sortTmp[0], $sortType);
				}
			if(!isset($per_page) && !isset($page)){
				$data->data = $query->get($this->table);
			}else{
				if(isset($per_page) && !isset($page)) $page = 1;
				else if(!isset($per_page) && isset($page)) $per_page = 10;
				$offset = (int)$per_page * ((int)$page - 1);
				$data->data = $query->get($this->table, $per_page, $offset)->result();
				$lastPage = ceil($totalPage/$per_page);
			
				$queryStringPrev = new stdclass();
				$queryStringPrev->per_page = $per_page;
				$queryStringPrev->page = $page;
				$queryStringPrev->sort = $sort;
				
				$queryStringNext = clone $queryStringPrev;
				
				$queryStringNext->page = $page + 1 > $lastPage ? null : $page+1;
				$queryStringPrev->page = $page - 1 == 0 ? null : $page-1;
				
				$data->pagination->next_page_url = $queryStringNext == null ? null : strtok($_SERVER["REQUEST_URI"],'?').'?'.http_build_query($queryStringNext);
				$data->pagination->prev_page_url = $queryStringPrev == null ? null : strtok($_SERVER["REQUEST_URI"],'?').'?'.http_build_query($queryStringPrev);
				$data->pagination->per_page = $per_page;
				$data->pagination->current_page = $page;
				$data->pagination->from = ($page-1)*$per_page;
				$data->pagination->to = $page*$per_page;
				$data->pagination->last_page = $lastPage;
			}
		}
		$this->response($data, 200);
    }
    
    function index_post()
    {
		$v = $this->_post_args;
		$cek = $this->db->select($this->tablePk)->from($this->table)->where($this->tablePk, $v['nidn'])->result();
		if(count($cek) != 0) {
			$error = new stdclass();
			$error->error = "NIDN sudah ada";
			$this->response($error, 409);
		}else {
			$this->db->insert($this->table, $v);
			$this->response(200);
		}
    }
    
    public function index_put()
    {
		$data = $this->_put_args;
		try {
			//$id = $this->widgets_model->updateWidget($data);
			$id = $data['id']; // test code
			//throw new Exception('Invalid request data', 400); // test code
		} catch (Exception $e) {
			// Here the model can throw exceptions like the following:
			// * For invalid input data: new Exception('Invalid request data', 400)
			// * For a conflict when attempting to create, like a resubmit: new Exception('Widget already exists', 409)
			$this->response(array('error' => $e->getMessage()), $e->getCode());
		}
		if ($id) {
			$widget = array('id' => $data['id'], 'name' => $data['name']); // test code
			//$widget = $this->widgets_model->getWidget($id);
			$this->response($widget, 200); // 200 being the HTTP response code
		} else
			$this->response(array('error' => 'Widget could not be found'), 404);
    }
        
    function index_delete($id = '')
    {
    	
    	// Example data for testing.
    	$widgets = array(
    			1 => array('id' => 1, 'name' => 'sprocket'),
    			2 => array('id' => 2, 'name' => 'gear'),
    			3 => array('id' => 3, 'name' => 'nut')
    	);
    	
    	if (!$id) { $id = $this->get('id'); }
    	if (!$id)
    	{
    		$this->response(array('error' => 'An ID must be supplied to delete a widget'), 400);
    	}
        //$widget = $this->widgets_model->getWidget($id);
    	$widget = @$widgets[$id]; // test code
    	if($widget) {
    		try {
    			//$this->widgets_model->deleteWidget($id);
    			//throw new Exception('Forbidden', 403); // test code
    		} catch (Exception $e) {
    			// Here the model can throw exceptions like the following:
    			// * Client is not authorized: new Exception('Forbidden', 403)
    			$this->response(array('error' => $e->getMessage()), $e->getCode());
    		}
    		$this->response($widget, 200); // 200 being the HTTP response code
    	} else
    		$this->response(array('error' => 'Widget could not be found'), 404);
    }
}
