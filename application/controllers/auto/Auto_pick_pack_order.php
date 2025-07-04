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


  //--- set state to 8 จัดส่งแล้ว
  public function process_delivery()
  {
    $sc = TRUE;
    $code = trim($this->input->post('order_code'));
    $order =  $this->orders_model->get($code);

    if($order->role == 'T' OR $order->role == 'Q')
    {
      $this->load->model('inventory/transform_model');
    }

    if($order->role == 'L')
    {
      $this->load->model('inventory/lend_model');
    }

    $this->db->trans_begin();

    $date_add = getConfig('ORDER_SOLD_DATE') == 'D' ? $order->date_add : (empty($order->shipped_date) ? now() : $order->shipped_date);
    //--- change state
    $this->orders_model->change_state($order->code, 8);

    //--- add state event
    $arr = array(
      'order_code' => $order->code,
      'state' => 8,
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

      if($sc === FALSE)
      {
        break;
      }

      $ds = $this->orders_model->get_order_detail($order->code, $rs->product_code);

      if( ! empty($ds))
      {
        $item = $this->products_model->get($rs->product_code);

        if(!empty($item))
        {
          $qty = $rs->qty;

          foreach($ds as $ro)
          {
            if($qty > 0)
            {
              $orderQty = $qty >= $ro->qty ? $ro->qty : $qty;
              $qty = $qty - $orderQty;

              //--- add prepare
              if($sc === TRUE)
              {
                $prepare = array(
                  'order_code' => $order->code,
                  'product_code' => $item->code,
                  'zone_code' => $this->zone_code,
                  'qty' => $orderQty,
                  'user' => $this->user,
                  'order_detail_id' => $ro->id
                );

                if(! $this->prepare_model->add($prepare))
                {
                  $sc = FALSE;
                  $this->error = "Insert Prepare failed {$order->code} : {$item->code}";
                }
              }

              //---- insert Qc
              if($sc === TRUE)
              {
                $qc = array(
                  'order_code' => $order->code,
                  'product_code' => $item->code,
                  'qty' => $orderQty,
                  'box_id' => NULL,
                  'user' => $this->user,
                  'order_detail_id' => $ro->id
                );

                if(!$this->qc_model->add($qc))
                {
                  $sc = FALSE;
                  $this->error = "Insert Qc data failed : {$order->code} : {$rs->product_code}";
                }
              }

              $sell_price = ($ro->qty > 0) ? round($ro->total_amount/$ro->qty, 2) : $ro->price;
              $discount_amount = ($ro->qty > 0) ? round($ro->discount_amount/$ro->qty, 2) : 0;
              $id_policy = empty($ro->id_rule) ? NULL : $this->discount_rule_model->get_policy_id($ro->id_rule);

              //--- ข้อมูลสำหรับบันทึกยอดขาย
              $arr = array(
                'reference' => $order->code,
                'role' => $order->role,
                'payment_code'   => $order->payment_code,
                'channels_code'  => $order->channels_code,
                'product_code'  => $ro->product_code,
                'product_name'  => $ro->product_name,
                'product_style' => $ro->style_code,
                'cost'  => $ro->cost,
                'price'  => $ro->price,
                'sell'  => $sell_price,
                'qty'   => $orderQty,
                'discount_label'  => discountLabel($ro->discount1, $ro->discount2, $ro->discount3),
                'discount_amount' => ($discount_amount * $orderQty),
                'total_amount'   => ($sell_price * $orderQty),
                'total_cost'   => ($ro->cost * $orderQty),
                'margin'  =>  ($sell_price * $orderQty) - ($ro->cost * $orderQty),
                'id_policy'   => $id_policy,
                'id_rule'     => $ro->id_rule,
                'customer_code' => $order->customer_code,
                'customer_ref' => $order->customer_ref,
                'sale_code'   => $order->sale_code,
                'user' => $order->user,
                'date_add'  => $date_add,
                'zone_code' => $this->zone_code,
                'warehouse_code'  => $this->warehouse_code,
                'update_user' => $this->user,
                'budget_code' => $order->budget_code,
                'is_count' => 1,
                'empID' => $order->empID,
                'empName' => $order->empName,
                'approver' => $order->approver,
                'order_detail_id' => $ro->id
              );

              //--- 3. บันทึกยอดขาย
              if(! $this->delivery_order_model->sold($arr))
              {
                $sc = FALSE;
                $this->error = "Insert sale data failed : {$order->code} : {$ro->product_code}";
                break;
              }

              if($sc === TRUE)
              {
                //--- 2. update movement
                $arr = array(
                  'reference' => $order->code,
                  'warehouse_code' => $this->warehouse_code,
                  'zone_code' => $this->zone_code,
                  'product_code' => $ro->product_code,
                  'move_in' => 0,
                  'move_out' => $orderQty,
                  'date_add' => $date_add
                );

                if(! $this->movement_model->add($arr))
                {
                  $sc = FALSE;
                  $this->error = "Insert Movement failed";
                  break;
                }
              }
            } //--- end if qty > 0
          } //--- end foreach $ds
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invalid SKU : {$rs->product_code}";
          break;
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Update failed : No Item Code '{$rs->product_code}' In Order List";
        break;
      }

      //------ ส่วนนี้สำหรับโอนเข้าคลังระหว่างทำ
      //------ หากเป็นออเดอร์เบิกแปรสภาพ
      if($order->role == 'T' OR $order->role == 'Q')
      {
        //--- ตัวเลขที่มีการเปิดบิล
        $sold_qty = $rs->qty;

        if( ! empty($ds))
        {
          foreach($ds as $ro)
          {
            //--- ยอดสินค้าที่มีการเชื่อมโยงไว้ในตาราง tbl_order_transform_detail (เอาไว้โอนเข้าคลังระหว่างทำ รอรับเข้า)
            //--- ถ้ามีการเชื่อมโยงไว้ ยอดต้องมากกว่า 0 ถ้ายอดเป็น 0 แสดงว่าไม่ได้เชื่อมโยงไว้
            $trans_list = $this->transform_model->get_transform_product($ro->id);

            if(!empty($trans_list))
            {
              //--- ถ้าไม่มีการเชื่อมโยงไว้
              foreach($trans_list as $ts)
              {
                //--- ถ้าจำนวนที่เชื่อมโยงไว้ น้อยกว่า หรือ เท่ากับ จำนวนที่ตรวจได้ (ไม่เกินที่สั่งไป)
                //--- แสดงว่าได้ของครบตามที่ผูกไว้ ให้ใช้ตัวเลขที่ผูกไว้ได้เลย
                //--- แต่ถ้าได้จำนวนที่ผูกไว้มากกว่าที่ตรวจได้ แสดงว่า ได้สินค้าไม่ครบ ให้ใช้จำนวนที่ตรวจได้แทน
                $move_qty = $ts->order_qty <= $sold_qty ? $ts->order_qty : $sold_qty;

                if( $move_qty > 0)
                {
                  //--- update ยอดเปิดบิลใน tbl_order_transform_detail field sold_qty
                  if($this->transform_model->update_sold_qty($ts->id, $move_qty))
                  {
                    $sold_qty -= $move_qty;
                  }
                  else
                  {
                    $sc = FALSE;
                    $this->error = 'ปรับปรุงยอดรายการค้างรับไม่สำเร็จ';
                    break;
                  }
                }
              }
            }
          }
        }
      }

      //--- if lend
      if($order->role == 'L')
      {
        //--- ตัวเลขที่มีการเปิดบิล
        $sold_qty = $rs->qty;

        $arr = array(
          'order_code' => $order->code,
          'product_code' => $ds->product_code,
          'product_name' => $ds->product_name,
          'qty' => $sold_qty,
          'empID' => $order->empID
        );

        if($this->lend_model->add_detail($arr) === FALSE)
        {
          $sc = FALSE;
          $this->error = 'เพิ่มรายการค้างรับไม่สำเร็จ';
        }
      }
    } //---- end count detaail


    $uncount_details = $this->orders_model->get_order_uncount_details($order->code);

    if(!empty($uncount_details))
    {
      foreach($uncount_details as $ds)
      {
        $sell_price = ($ds->qty > 0) ? round($ds->total_amount/$ds->qty, 2) : $ds->price;
        $discount_amount = ($ds->qty > 0) ? round($ds->discount_amount/$ds->qty, 2) : 0;
        $id_policy = empty($ds->id_rule) ? NULL : $this->discount_rule_model->get_policy_id($ds->id_rule);

        //--- ข้อมูลสำหรับบันทึกยอดขาย
        $arr = array(
          'reference' => $order->code,
          'role'   => $order->role,
          'payment_code'   => $order->payment_code,
          'channels_code'  => $order->channels_code,
          'product_code'  => $ds->product_code,
          'product_name'  => $ds->product_name,
          'product_style' => $ds->style_code,
          'cost'  => $ds->cost,
          'price'  => $ds->price,
          'sell'  => $sell_price,
          'qty'   => $ds->qty,
          'discount_label'  => discountLabel($ds->discount1, $ds->discount2, $ds->discount3),
          'discount_amount' => ($discount_amount * $ds->qty),
          'total_amount'   => ($sell_price * $ds->qty),
          'total_cost'   => ($ds->cost * $ds->qty),
          'margin'  =>  ($sell_price * $ds->qty) - ($ds->cost * $ds->qty),
          'id_policy'   => $id_policy,
          'id_rule'     => $ds->id_rule,
          'customer_code' => $order->customer_code,
          'customer_ref' => $order->customer_ref,
          'sale_code'   => $order->sale_code,
          'user' => $order->user,
          'date_add'  => $date_add,
          'zone_code' => NULL,
          'warehouse_code'  => NULL,
          'update_user' => $this->user,
          'budget_code' => $order->budget_code,
          'is_count' => 0,
          'empID' => $order->empID,
          'empName' => $order->empName,
          'approver' => $order->approver,
          'order_detail_id' => $ds->id
        );

        //--- 3. บันทึกยอดขาย
        if(! $this->delivery_order_model->sold($arr))
        {
          $sc = FALSE;
          $this->error = "Insert sale data failed : {$order->code} : {$ds->product_code}";
          break;
        }
      } //--- end foreach non count
    } //--- end if ! empty non count detail

    if($sc === TRUE)
    {
      $this->orders_model->update($order->code, array('shipped_date' => $date_add)); //--- update shipped
    }

    if($sc === TRUE)
    {
      $this->db->trans_commit();
    }
    else
    {
      $this->db->trans_rollback();
    }

    if($sc === TRUE)
    {
      if( ! $this->do_export($order->code))
      {
        $sc = FALSE;
        $this->error = "บันทึกขายสำเร็จ แต่ส่งข้อมูลเช้า SAP ไม่สำเร็จ";
      }
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

  private function export_order($code)
	{
		$sc = TRUE;
		$this->load->library('export');
		if(! $this->export->export_order($code))
		{
			$sc = FALSE;
			$this->error = trim($this->export->error);
		}

		return $sc;
	}


	private function export_transfer_order($code)
	{
		$sc = TRUE;
		$this->load->library('export');
		if(! $this->export->export_transfer_order($code))
		{
			$sc = FALSE;
			$this->error = trim($this->export->error);
		}

		return $sc;
	}


	private function export_transfer_draft($code)
	{
		$sc = TRUE;
		$this->load->library('export');
		if(! $this->export->export_transfer_draft($code))
		{
			$sc = FALSE;
			$this->error = trim($this->export->error);
		}

		return $sc;
	}


	private function export_transform($code)
	{
		$sc = TRUE;
		$this->load->library('export');
		if(! $this->export->export_transform($code))
		{
			$sc = FALSE;
			$this->error = trim($this->export->error);
		}

		return $sc;
	}


	//--- manual export by client
	public function do_export($code)
	{
		$order = $this->orders_model->get($code);
		$sc = TRUE;
		if(!empty($order))
		{
			switch($order->role)
			{
				case 'C' : //--- Consign (SO)
					$sc = $this->export_order($code);
					break;

				case 'L' : //--- Lend
					$sc = $this->export_transfer_order($code);
					break;

				case 'N' : //--- Consign (TR)
					$sc = $this->export_transfer_draft($code);
					break;

				case 'P' : //--- Sponsor
					$sc = $this->export_order($code);
					break;

				case 'Q' : //--- Transform for stock
					$sc = $this->export_transform($code);
					break;

				case 'S' : //--- Sale order
					$sc = $this->export_order($code);
					break;

				case 'T' : //--- Transform for sell
					$sc = $this->export_transform($code);
					break;

				case 'U' : //--- Support
					$sc = $this->export_order($code);
					break;

				default : ///--- sale order
					$sc = $this->export_order($code);
					break;
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "ไม่พบเลขที่เอกสาร {$code}";
		}

		return $sc;
	}

} //--- end class
 ?>
