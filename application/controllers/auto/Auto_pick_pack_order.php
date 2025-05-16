<?php
class Auto_pick_pack_order extends CI_Controller
{
  public $home;
	private $tb = "auto_process_order";
  public $title = "Auto pick pack order";
	public $user;
	public $warehouse_code;
	public $zone_code;
  public $isViewer = FALSE;
  public $notibars = FALSE;
  public $menu_code = NULL;
  public $menu_group_code = NULL;
  public $pm;

  public function __construct()
  {
    parent::__construct();

    $this->home = base_url().'auto/auto_pick_pack_order';
		$this->zone_code = getConfig('DEFAULT_ZONE'); //--- โซน เริ่มต้น
    $this->warehouse_code = getConfig('DEFAULT_WAREHOUSE');

		$this->load->model('orders/orders_model');
    $this->load->model('orders/order_state_model');
		$this->load->model('inventory/prepare_model');
		$this->load->model('inventory/buffer_model');
		$this->load->model('inventory/qc_model');
		$this->user = 'api@warrix';
    $this->pm = new stdClass();
    $this->pm->can_view = 1;
  }


  public function index()
  {
    $limit = getConfig('AUTO_CONFRIM_ORDER_LIMIT');

    $limit = empty($limit) ? 50 : $limit;

    $ds['data'] = NULL;
    $all = $this->db->where('status !=', 1)->count_all_results($this->tb);
    $rs = $this->db->where('status !=', 1)->limit($limit)->get($this->tb);

    $ds['count'] = $rs->num_rows();
    $ds['all'] = $all;
    $ds['data'] = $rs->result();

    $this->load->view('auto/auto_pick_pack_order', $ds);
  }


	//---- set state to 7 รอจัดส่ง
	public function process_pre_delivery()
	{
		$sc = TRUE;
    $code = trim($this->input->post('order_code'));

    $order =  $this->orders_model->get($code);

    if( ! empty($order))
    {
      if($order->state >= 3 && $order->state < 7)
      {
        $details = $this->orders_model->get_order_details($code);

        if( ! empty($details))
        {
          $this->db->trans_begin();

          $arr = array(
            'state' => 7,
            'warehouse_code' => $this->warehouse_code,
            'update_user' => $this->user
          );

          //--- change state
      		$this->orders_model->update($order->code, $arr);

          //--- add state event
      		$arr = array(
      			'order_code' => $order->code,
      			'state' => 7,
      			'update_user' => $this->user
      		);

      		$this->order_state_model->add_state($arr);

      		//--- drop current prepare
      		$this->prepare_model->drop_prepare($order->code);

      		//--- drop current buffer
      		$this->buffer_model->drop_buffer($order->code);

      		//--- drop current qc
      		$this->qc_model->drop_qc($order->code);

          foreach($details as $rs)
      		{
      			if($sc === FALSE) {	break; }

            if($rs->is_count)
            {
              //--- insert prepare
              if($sc === TRUE)
              {
                $prepare = array(
                  'order_code' => $order->code,
                  'product_code' => $rs->product_code,
                  'zone_code' => $this->zone_code,
                  'qty' => $rs->qty,
                  'user' => $this->user,
                  'order_detail_id' => $rs->id
                );

                if(! $this->prepare_model->add($prepare))
                {
                  $sc = FALSE;
                  $this->error = "Insert Prepare failed {$order->code} : {$rs->product_code}";
                }
              }

              //--- insert buffer
              if($sc === TRUE)
              {
                $buffer = array(
                  'order_code' => $order->code,
                  'product_code' => $rs->product_code,
                  'warehouse_code' => $this->warehouse_code,
                  'zone_code' => $this->zone_code,
                  'qty' => $rs->qty,
                  'user' => $this->user,
                  'order_detail_id' => $rs->id
                );

                if(! $this->buffer_model->add($buffer))
                {
                  $sc = FALSE;
                  $this->error = "Insert buffer failed : {$order->code} : {$rs->product_code}";
                }
              }

              //---- insert Qc
              if($sc === TRUE)
              {
                $qc = array(
                  'order_code' => $order->code,
                  'product_code' => $rs->product_code,
                  'qty' => $rs->qty,
                  'box_id' => NULL,
                  'user' => $this->user,
                  'order_detail_id' => $rs->id
                );

                if(!$this->qc_model->add($qc))
                {
                  $sc = FALSE;
                  $this->error = "Insert Qc data failed : {$order->code} : {$rs->product_code}";
                }
              }
            }
          } //--- end foreach

          if($sc === TRUE)
      		{
      			$this->db->trans_commit();
            $this->update_status($order->code, 1, NULL);
      		}
      		else
      		{
      			$this->db->trans_rollback();
            $this->update_status($order->code, 3, $this->error);
      		}
        }
        else
        {
          $sc = FALSE;
          $this->error = "No items found";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid order state";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Order not found";
    }

		echo $sc === TRUE ? 'success' : $this->error;
	}


  private function update_status($code, $status = 1, $message = NULL)
	{

    $arr = array(
      'status' => $status,
      'message' => $message
    );

    return $this->db->where('code', $code)->update($this->tb, $arr);
	}

} //--- end class
 ?>
