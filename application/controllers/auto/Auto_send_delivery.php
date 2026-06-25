<?php
class Auto_send_delivery extends PS_Controller
{
  public $title = 'รายการที่รอส่งเข้า SAP';
	public $menu_code = '';
	public $menu_group_code = '';
	public $error;

  public function __construct()
  {
    parent::__construct();
    _check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;
    $this->home = base_url().'auto/auto_send_delivery';
		$this->load->library('export');
  }

  public function index()
  {
    $limit = getConfig('AUTO_CHANGE_STATE_LIMIT');
    $limit = empty($limit) ? 100 : $limit;

    $filter = array(
      'code' => get_filter('code', 'auto_code', ''),
      'status' => get_filter('status', 'auto_status', '0')
    );

    $rows = $this->count_all($filter);
    $data = $this->get_all($filter, $limit);     

    $filter['count'] = empty($data) ? 0 : count($data);
    $filter['all'] = $rows;
    $filter['limit'] = $limit;
    $filter['data'] = $data;    

    $this->load->view('auto/auto_send_to_sap', $filter);
  }  

  public function update_status()
  {
    $sc = TRUE;
    $code = $this->input->post('code');
    $status = $this->input->post('status');
    $message = $this->input->post('message');

    $ds = array(
      'status' => $status,
      'message' => $message
    );

    if (! $this->db->where('code', $code)->update('auto_send_to_sap_order', $ds))
    {
      $sc = FALSE;
      $this->error = "Update false";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }

  public function count_all($filter = array())
  {
    if (!empty($filter['code']))
    {
      $this->db->like('code', $filter['code']);
    }

    if (isset($filter['status']) && $filter['status'] != 'all')
    {
      $this->db->where('status', $filter['status']);
    }

    return $this->db->count_all_results('auto_send_to_sap_order');    
  }


  public function get_all($filter = array(), $limit = 100)
  {
    if (!empty($filter['code']))
    {
      $this->db->like('code', $filter['code']);
    }

    if (isset($filter['status']) && $filter['status'] != 'all')
    {
      $this->db->where('status', $filter['status']);
    }

    $rs = $this->db
      ->order_by('status', 'ASC')
      ->order_by('code', 'ASC')
      ->limit($limit)
      ->get('auto_send_to_sap_order');

    if ($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;    
  }

} //--- end class
 ?>
