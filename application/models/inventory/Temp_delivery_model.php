<?php
class Temp_delivery_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get($docEntry)
  {
    $rs = $this->mc->where('DocEntry', $docEntry)->get('ODLN');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }

  public function count_rows(array $ds = array())
  {
    if(!empty($ds['code']))
    {
      $this->mc->like('U_ECOMNO', $ds['code']);
    }

    if(!empty($ds['customer']))
    {
      $this->mc->group_start();
      $this->mc->like('CardCode', $ds['customer']);
      $this->mc->or_like('CardName', $ds['customer']);
      $this->mc->group_end();
    }

    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->mc->where('DocDate >=', from_date($ds['from_date']));
      $this->mc->where('DocDate <=', to_date($ds['to_date']));
    }

    if($ds['status'] != 'all')
    {
      if($ds['status'] === 'Y')
      {
        $this->mc->where('F_Sap', 'Y');
      }
      else if($ds['status'] === 'N')
      {
        $this->mc
        ->group_start()
        ->where('F_Sap IS NULL', NULL, FALSE)
        ->or_where('F_Sap', 'P')
        ->group_end();
        //$this->mc->where('F_Sap IS NULL', NULL, FALSE);
      }
      else if($ds['status'] === 'E')
      {
        $this->mc->where('F_Sap', 'N');
      }
    }

    return $this->mc->count_all_results('ODLN');
  }



  public function get_list(array $ds = array(), $perpage = NULL, $offset = 0)
  {
    $this->mc
    ->select('DocEntry, U_ECOMNO, DocDate, CardCode, CardName')
    ->select('F_E_Commerce, F_E_CommerceDate')
    ->select('F_Sap, F_SapDate, U_BOOKCODE')
    ->select('Message');

    if(!empty($ds['code']))
    {
      $this->mc->like('U_ECOMNO', $ds['code']);
    }

    if(!empty($ds['customer']))
    {
      $this->mc->group_start();
      $this->mc->like('CardCode', $ds['customer']);
      $this->mc->or_like('CardName', $ds['customer']);
      $this->mc->group_end();
    }

    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->mc->where('DocDate >=', from_date($ds['from_date']));
      $this->mc->where('DocDate <=', to_date($ds['to_date']));
    }

    if($ds['status'] != 'all')
    {
      if($ds['status'] === 'Y')
      {
        $this->mc->where('F_Sap', 'Y');
      }
      else if($ds['status'] === 'N')
      {
        $this->mc
        ->group_start()
        ->where('F_Sap IS NULL', NULL, FALSE)
        ->or_where('F_Sap', 'P')
        ->group_end();        
      }
      else if($ds['status'] === 'E')
      {
        $this->mc->where('F_Sap', 'N');
      }
    }

    $this->mc->order_by('DocDate', 'DESC')->order_by('U_ECOMNO', 'DESC');

    if(!empty($perpage))
    {
      $this->mc->limit($perpage, $offset);
    }

    $rs = $this->mc->get('ODLN');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_detail($docEntry)
  {
    $rs = $this->mc
    ->select('LineNum, U_ECOMNO, ItemCode, Dscription, DiscPrcnt, Quantity, PriceBefDi, BinCode')
    ->where('DocEntry', $docEntry)
    ->get('DLN1');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_error_list()
  {
    $rs = $this->mc
    ->select('DLN1.ItemCode, DLN1.BinCode')
    ->select_sum('DLN1.Quantity', 'Qty')
    ->from('DLN1')
    ->join('ODLN', 'DLN1.DocEntry = ODLN.DocEntry', 'left')
    ->where('ODLN.F_Sap', 'N')
    ->group_by('DLN1.ItemCode')
    ->group_by('DLN1.BINCode')
    ->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_stock_zone($zone_code, array $item_list = array())
  {
    if( ! empty($item_list))
    {
      $rs = $this->ms
      ->select('OIBQ.ItemCode, OBIN.BinCode')
      ->select_sum('OIBQ.OnHandQty')
      ->from('OBIN')
      ->join('OIBQ', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
      ->where('OBIN.BinCode', $zone_code)
      ->where_in('OIBQ.ItemCode', $item_list)
      ->group_by('OBIN.BinCode')
      ->group_by('OIBQ.ItemCode')
      ->get();

      if($rs->num_rows() > 0)
      {
        return $rs->result();
      }
    }

    return NULL;
  }


	public function delete_temp_details($id)
	{
		return $this->mc->where('DocEntry', $id)->delete('DLN1');
	}

	public function delete_temp($id)
	{
		return $this->mc->where('DocEntry', $id)->delete('ODLN');
	}

} //--- end model

?>
