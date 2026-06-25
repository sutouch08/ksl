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


  public function import_order()
  {
    ini_set('max_execution_time', 1200);
    ini_set('memory_limit', '1000M');

    $sc = TRUE;

    $file = isset($_FILES['uploadFile']) ? $_FILES['uploadFile'] : FALSE;
    $path = $this->config->item('upload_path') . 'import_files/';
    $file  = 'uploadFile';
    $config = array(   // initial config for upload class
      "allowed_types" => "xlsx",
      "upload_path" => $path,
      "file_name"  => "import_file",
      "max_size" => 5120,
      "overwrite" => TRUE
    );

    $this->load->library("upload", $config);

    if (! $this->upload->do_upload($file))
    {
      $sc = FALSE;
      $this->error = $this->upload->display_errors();
    }
    else
    {
      $info = $this->upload->data();
      $this->load->library('excel');
      /// read file
      $excel = PHPExcel_IOFactory::load($info['full_path']);
      //get only the Cell Collection
      $collection  = $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

      if (! empty($collection))
      {
        $i = 1;
        $j = 0;
        $ds = [];
        $ro = [];

        foreach ($collection as $rs)
        {
          if ($i > 1)
          {
            $j++;
            $ro[] = array('code' => trim($rs['A']));

            if ($j == 1000)
            {
              $j = 0;
              $ds[] = $ro;
              $ro = [];
            }
          }

          $i++;
        }

        $ds[] = $ro;

        if (! empty($ds))
        {
          foreach ($ds as $rows)
          {
            if (! $this->insert($rows))
            {
              $sc = FALSE;
              $this->error = "Cannot insert data";
            }
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Cannot get data from import file : empty data collection";
      }
    }

    $this->_response($sc);
  }



  public function insert(array $ds = array())
  {
    if (! empty($ds))
    {
      return $this->db->insert_batch('auto_send_to_sap_order', $ds);
    }

    return FALSE;
  }


  public function clear_data()
  {
    $sc = TRUE;

    $qr = "TRUNCATE TABLE auto_send_to_sap_order";

    if (! $this->db->query($qr))
    {
      $sc = FALSE;
      $this->error = "Failed to clear data";
    }

    $this->_response($sc);
  }

  
  public function change_order_limit()
  {
    $sc = TRUE;
    $limit = $this->input->post('limit');

    if ($limit > 0)
    {
      $this->load->model('setting/config_model');

      if (! $this->config_model->update('AUTO_CHANGE_STATE_LIMIT', $limit))
      {
        $sc = FALSE;
        $this->error = "Failed to update config value";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "จำนวนต้องมากกว่า 0";
    }

    $this->_response($sc);
  }

} //--- end class
 ?>
