<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Items extends PS_Controller
{
  public $menu_code = 'DBITEM';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'เพิ่ม/แก้ไข รายการสินค้า';
  public $error = '';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/items';

    //--- load model
    $this->load->model('masters/products_model');
    $this->load->model('masters/product_group_model');
		$this->load->model('masters/product_main_group_model');
    $this->load->model('masters/product_sub_group_model');
    $this->load->model('masters/product_kind_model');
    $this->load->model('masters/product_type_model');
    $this->load->model('masters/product_style_model');
    $this->load->model('masters/product_brand_model');
    $this->load->model('masters/product_collection_model');
    $this->load->model('masters/product_category_model');
    $this->load->model('masters/product_color_model');
    $this->load->model('masters/product_size_model');
    $this->load->model('masters/product_tab_model');
    $this->load->model('masters/product_image_model');

    //---- load helper    
    $this->load->helper('product_tab');
    $this->load->helper('product_brand');
    $this->load->helper('product_tab');
    $this->load->helper('product_kind');
    $this->load->helper('product_collection');
    $this->load->helper('product_type');
    $this->load->helper('product_group');
    $this->load->helper('product_category');
		$this->load->helper('product_main_group');
    $this->load->helper('product_sub_group');
    $this->load->helper('product_images');
    $this->load->helper('unit');

  }


  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'item_code', ''),
      'name' => get_filter('name', 'item_name', ''),
      'barcode' => get_filter('barcode', 'item_barcode', ''),
      'color' => get_filter('color', 'color' ,''),
      'size' => get_filter('size', 'size', ''),
      'group' => get_filter('group', 'group', 'all'),
      'sub_group' => get_filter('sub_group', 'sub_group', 'all'),
      'category'  => get_filter('category', 'category', 'all'),
      'kind' => get_filter('kind', 'kind', 'all'),
      'type' => get_filter('type', 'type', 'all'),
      'brand' => get_filter('brand', 'brand', 'all'),
      'collection' => get_filter('collection', 'collection', 'all'),
      'year' => get_filter('year', 'year', 'all'),
      'active' => get_filter('active', 'active', 'all')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->products_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$products = $this->products_model->get_list($filter, $perpage, $this->uri->segment($segment));
    $ds       = array();
    if(!empty($products))
    {
      foreach($products as $rs)
      {
        $rs->group   = $this->product_group_model->get_name($rs->group_code);
        $rs->main_group = $this->product_main_group_model->get_name($rs->main_group_code);
        $rs->sub_group = $this->product_sub_group_model->get_name($rs->sub_group_code);
        $rs->kind    = $this->product_kind_model->get_name($rs->kind_code);
        $rs->type    = $this->product_type_model->get_name($rs->type_code);
        $rs->category  = $this->product_category_model->get_name($rs->category_code);
        $rs->brand   = $this->product_brand_model->get_name($rs->brand_code);
        $rs->collection = $this->product_collection_model->get_name($rs->collection_code);
      }
    }

    $filter['data'] = $products;

		$this->pagination->initialize($init);
    $this->load->view('masters/product_items/items_list', $filter);
  }


  public function import_items()
  {
    $sc = TRUE;
    $file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
  	$path = $this->config->item('upload_path').'items/';
    $file	= 'uploadFile';
		$config = array(   // initial config for upload class
			"allowed_types" => "xlsx",
			"upload_path" => $path,
			"file_name"	=> "import_items",
			"max_size" => 5120,
			"overwrite" => TRUE
			);

			$this->load->library("upload", $config);

			if(! $this->upload->do_upload($file))
      {
        $sc = FALSE;
				$this->error = $this->upload->display_errors();
			}
      else
      {
        $this->load->library('excel');
        $this->load->library('api');

        $info = $this->upload->data();
        /// read file
				$excel = PHPExcel_IOFactory::load($info['full_path']);
				//get only the Cell Collection
        $collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

        $i = 1;
        $count = count($collection);
        $limit = intval(getConfig('IMPORT_ROWS_LIMIT'))+1;

        if($count <= $limit)
        {
          foreach($collection as $rs)
          {
            if($i == 1)
            {
              $headCol = array(
                'A' => 'Code',
                'B' => 'Name',
                'C' => 'Barcode',
                'D' => 'Model',
                'E' => 'Color',
                'F' => 'Size',
                'G' => 'Group',
								'H' => 'MainGroup',
                'I' => 'SubGroup',
                'J' => 'Category',
                'K' => 'Kind',
                'L' => 'Type',
                'M' => 'Brand',
                'N' => 'Collection',
                'O' => 'Year',
                'P' => 'Cost',
                'Q' => 'Price',
                'R' => 'Unit',
                'S' => 'CountStock',
                'T' => 'IsAPI'
              );

              foreach($headCol as $col => $field)
              {
                if($rs[$col] !== $field)
                {
                  $sc = FALSE;
                  $this->error = 'Column '.$col.' Should be '.$field;
                  break;
                }
              }

              if($sc === FALSE)
              {
                break;
              }

              $i++;
            }
            else if(!empty($rs['A']))
            {
              if($sc === FALSE)
              {
                break;
              }

              $code_pattern = '/[^a-zA-Z0-9_-]/';
              $rs['D'] = str_replace(array("\n", "\r"), '', $rs['D']); //--- เอาตัวขึ้นบรรทัดใหม่ออก

              $style = preg_replace($code_pattern, '', get_null(trim($rs['D'])));
              $old_style = get_null(trim($rs['U']));
              $color_code = get_null(trim($rs['E']));
              $size_code = get_null(trim($rs['F']));
              $group_code = get_null(trim($rs['G']));
							$main_group_code = get_null(trim($rs['H']));
              $sub_group_code = get_null(trim($rs['I']));
              $category_code = get_null(trim($rs['J']));
              $kind_code = get_null(trim($rs['K']));
              $type_code = get_null(trim($rs['L']));
              $brand_code = get_null(trim($rs['M']));
              $collection_code = get_null(trim($rs['N']));
              $year = empty($rs['O']) ? '0000' : trim($rs['O']);

              if(!empty($color_code) && ! $this->product_color_model->is_exists($color_code))
              {
                $sc = FALSE;
                $this->error = "Color : {$color_code}  does not exists @line {$i}";
              }
              else if(!empty($size_code) && ! $this->product_size_model->is_exists($size_code))
              {
                $sc = FALSE;
                $this->error = "Size : {$size_code}  does not exists @line {$i}";
              }
              else if(!empty($group_code) && ! $this->product_group_model->is_exists($group_code))
              {
                $sc = FALSE;
                $this->error = "Product Group : {$group_code}  does not exists @line {$i}";
              }
							else if(!empty($main_group_code) && ! $this->product_main_group_model->is_exists($main_group_code))
              {
                $sc = FALSE;
                $this->error = "Product Main Group : {$main_group_code}  does not exists @line {$i}";
              }
              else if(!empty($sub_group_code) && ! $this->product_sub_group_model->is_exists($sub_group_code))
              {
                $sc = FALSE;
                $this->error = "Product Sub Group : {$sub_group_code}  does not exists @line {$i}";
              }
              else if(!empty($category_code) && ! $this->product_category_model->is_exists($category_code))
              {
                $sc = FALSE;
                $this->error = "Product Category : {$category_code} does not exists @line {$i}";
              }
              else if(!empty($kind_code) && ! $this->product_kind_model->is_exists($kind_code))
              {
                $sc = FALSE;
                $this->error = "Product Kind : {$kind_code} does not exists @line {$i}";
              }
              else if(!empty($type_code) && ! $this->product_type_model->is_exists($type_code))
              {
                $sc = FALSE;
                $this->error = "Product Type : {$type_code} does not exists @line {$i}";
              }
              else if(!empty($brand_code) && ! $this->product_brand_model->is_exists($brand_code))
              {
                $sc = FALSE;
                $this->error = "Brand : {$brand_code} does not exists @line {$i}";
              }
              else if(! empty($collection_code) && ! $this->product_collection_model->is_exists($collection_code))
              {
                $sc = FALSE;
                $this->error = "Collection : {$collection_code} does not exists @line {$i}";
              }

              if($sc === FALSE)
              {
                break;
              }

              if(!empty($style))
              {
                if(! $this->product_style_model->is_exists($style) )
                {
                  $ds = array(
                    'code' => $style,
                    'name' => $style,
                    'group_code' => $group_code,
										'main_group_code' => $main_group_code,
                    'sub_group_code' => $sub_group_code,
                    'category_code' => $category_code,
                    'kind_code' => $kind_code,
                    'type_code' => $type_code,
                    'brand_code' => $brand_code,
                    'collection_code' => $collection_code,
                    'year' => $year,
                    'cost' => round(trim($rs['P']), 2),
                    'price' => round(trim($rs['Q']), 2),
                    'unit_code' => trim($rs['R']),
                    'count_stock' => trim($rs['S']) === 'N' ? 0:1,
                    'is_api' => trim($rs['T']) === 'N' ? 0 : 1,
                    'update_user' => $this->_user->uname,
                    'old_code' => $old_style
                  );

                  if($this->product_style_model->add($ds))
                  {
                    //---- export to SAP
                    $this->export_style($style);
                  }
                }
              }

              $rs['A'] = str_replace(array("\n", "\r"), '', $rs['A']); //--- เอาตัวขึ้นบรรทัดใหม่ออก
              $code = preg_replace($code_pattern, '', trim($rs['A']));
              $old_code = get_null(trim($rs['V']));
              $arr = array(
                'code' => $code,
                'name' => trim($rs['B']),
                'barcode' => get_null(trim($rs['C'])),
                'style_code' => get_null(trim($rs['D'])),
                'color_code' => get_null(trim($rs['E'])),
                'size_code' => get_null(trim($rs['F'])),
                'group_code' => get_null(trim($rs['G'])),
								'main_group_code' => get_null(trim($rs['H'])),
                'sub_group_code' => get_null(trim($rs['I'])),
                'category_code' => get_null(trim($rs['J'])),
                'kind_code' => get_null(trim($rs['K'])),
                'type_code' => get_null(trim($rs['L'])),
                'brand_code' => get_null(trim($rs['M'])),
                'collection_code' => get_null(trim($rs['N'])),
                'year' => trim($rs['O']),
                'cost' => round(trim($rs['P']), 2),
                'price' => round(trim($rs['Q']), 2),
                'unit_code' => empty(trim($rs['R'])) ? 'PCS' : trim($rs['R']),
                'count_stock' => trim($rs['S']) === 'N' ? 0:1,
                'is_api' => trim($rs['T']) === 'N' ? 0 : 1,
                'update_user' => $this->_user->uname,
                'old_style' => $old_style,
                'old_code' => $old_code
              );

              if($this->products_model->is_exists($code))
              {
                $is_done = $this->products_model->update($code, $arr);
              }
              else
              {
                $is_done = $this->products_model->add($arr);
              }

              if($is_done)
              {
                $this->do_export($code);
              }

              $i++;
            }
          } //-- end foreach
        }
        else
        {
          $sc = FALSE;
          $this->error = "จำนวนนำเข้าสูงสุดได้ไม่เกิน {$limit} บรรทัด";
        } //-- end if count limit

      } //--- end if else

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function add_new()
  {
    $this->load->view('masters/product_items/items_add_view');
  }


  public function add()
  {
    $sc = TRUE;
    $ds = json_decode($this->input->post('data'));

    if( ! empty($ds))
    {
      $code = trim($ds->code);

      if($this->products_model->is_exists($code))
      {
        $sc = FALSE;
        $this->error = "{$code} already exists";
      }

      if($sc === TRUE)
      {
        $user = $this->_user->uname;

        $arr = array(
          'code' => $code,
          'name' => trim($ds->name),
          'barcode' => get_null(trim($ds->barcode)),
          'style_code' => trim($ds->style),
          'color_code' => get_null($ds->color_code),
          'size_code' => get_null($ds->size_code),
          'group_code' => get_null($ds->group_code),
					'main_group_code' => get_null($ds->main_group_code),
          'sub_group_code' => get_null($ds->sub_group_code),
          'category_code' => get_null($ds->category_code),
          'kind_code' => get_null($ds->kind_code),
          'type_code' => get_null($ds->type_code),
          'brand_code' => get_null($ds->brand_code),
          'collection_code' => get_null($ds->collection_code),
          'year' => $ds->year,
          'cost' => round($ds->cost, 2),
          'price' => round($ds->price, 2),
          'unit_code' => $ds->unit_code,
          'count_stock' => $ds->count_stock == 0 ? 0 : 1,
          'can_sell' => $ds->can_sell == 0 ? 0 : 1,
          'active' => $ds->active == 0 ? 0 : 1,
          'is_api' => $ds->is_api == 0 ? 0 : 1,
          'update_user' => $user,
          'old_style' => get_null($ds->old_style),
          'old_code' => get_null($ds->old_code)
        );

        if( ! $this->products_model->add($arr))
        {
          $sc = FALSE;
          $this->error = "Failed to add new item";
        }
        else
        {
          $this->do_export($code);
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Missing required parameter";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function edit($id)
  {
    $item = $this->products_model->get_by_id($id);

    if(! empty($item))
    {
      $item->alt_codes = $this->products_model->get_alt_code($item->code);

      $this->load->view('masters/product_items/items_edit_view', $item);
    }
    else
    {
      $this->page_error();
    }
  }


  public function duplicate($id)
  {
    $item = $this->products_model->get_by_id($id);

    if( ! empty($item))
    {
      $this->load->view('masters/product_items/items_duplicate_view', $item);
    }
    else
    {
      $this->page_error();
    }
  }


	public function update()
  {
		$sc = TRUE;
		$ds = json_decode($this->input->post('data'));

		if(! empty($ds))
		{
			$code = $ds->code;

      $arr = array(
        'name' => trim($ds->name),
        'barcode' => get_null(trim($ds->barcode)),
        'style_code' => trim($ds->style),
        'color_code' => get_null($ds->color_code),
        'size_code' => get_null($ds->size_code),
        'group_code' => get_null($ds->group_code),
  			'main_group_code' => get_null($ds->main_group_code),
        'sub_group_code' => get_null($ds->sub_group_code),
        'category_code' => get_null($ds->category_code),
        'kind_code' => get_null($ds->kind_code),
        'type_code' => get_null($ds->type_code),
        'brand_code' => get_null($ds->brand_code),
        'collection_code' => get_null($ds->collection_code),
        'year' => $ds->year,
        'cost' => round($ds->cost, 2),
        'price' => round($ds->price, 2),
        'unit_code' => $ds->unit_code,
        'count_stock' => empty($ds->count_stock) ? 0 : 1,
        'can_sell' => empty($ds->can_sell) ? 0 : 1,
        'active' => empty($ds->active) ? 0 : 1,
        'is_api' => empty($ds->is_api) ? 0 : 1,
        'update_user' => $this->_user->uname,
        'old_style' => get_null($ds->old_style),
        'old_code' => get_null($ds->old_code)
      );

      if( ! $this->products_model->update($code, $arr))
      {
  			$sc = FALSE;
  			$this->error = "Update failed";
      }

      if($sc === TRUE)
      {
        if($this->products_model->remove_alt_codes($code))
        {
          if( ! empty($ds->alt_codes))
          {
            foreach($ds->alt_codes as $alt_code)
            {
              $arr = array(
                'alt_code' => trim($alt_code),
                'product_code' => $code
              );

              $this->products_model->add_alt_code($arr);
            }
          }
        }
      }
		}
		else
		{
			$sc = FALSE;
			$this->error = "Missing form data";
		}

		if($sc === TRUE)
		{
			$this->do_export($code);
		}

		echo $sc === TRUE ? 'success' : $this->error;
  }


  public function is_exists_code($code, $old_code = '')
  {
    if($this->products_model->is_exists($code, $old_code))
    {
      echo 'รหัสซ้ำ';
    }
    else
    {
      echo 'ok';
    }
  }



  public function toggle_can_sell($code)
  {
    $status = $this->products_model->get_status('can_sell', $code);
    $status = $status == 1 ? 0 : 1;

    if($this->products_model->set_status('can_sell', $code, $status))
    {
      echo $status;
    }
    else
    {
      echo 'fail';
    }
  }


  public function toggle_active($code)
  {
    $status = $this->products_model->get_status('active', $code);
    $status = $status == 1 ? 0 : 1;

    if($this->products_model->set_status('active', $code, $status))
    {
      echo $status;
    }
    else
    {
      echo 'fail';
    }
  }



  public function toggle_api($code)
  {
    $status = $this->products_model->get_status('is_api', $code);
    $status = $status == 1 ? 0 : 1;

    if($this->products_model->set_status('is_api', $code, $status))
    {
      echo $status;
    }
    else
    {
      echo 'fail';
    }
  }


  public function delete_item()
  {
    $sc = TRUE;
    $id = $this->input->get('id');
    $item = $this->products_model->get_by_id($id);

    if( ! empty($item))
    {
      if(! $this->products_model->has_transection($item->code))
      {
        if(! $this->products_model->delete_item_by_id($id))
        {
          $sc = FALSE;
          $this->error = "ลบรายการไม่สำเร็จ";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "ไม่สามารถลบ {$item->code} ได้ เนื่องจากสินค้ามี Transcetion เกิดขึ้นแล้ว";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Missing required parameter";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function do_export($code, $method = 'A')
  {
		$sc = TRUE;

    $item = $this->products_model->get($code);
    //--- เช็คข้อมูลในฐานข้อมูลจริง
    $exst = $this->products_model->is_sap_exists($item->code);

    $method = $exst === TRUE ? 'U' : $method;

    //--- เช็คข้อมูลในถังกลาง
    $middle = $this->products_model->get_un_import_middle($item->code);
    if(!empty($middle))
    {
      foreach($middle as $mid)
      {
        $this->products_model->drop_middle_item($mid->DocEntry);
      }
    }

    $ds = array(
      'ItemCode' => $item->code, //--- รหัสสินค้า
      'ItemName' => limitText($item->name, 97),//--- ชื่อสินค้า
      'FrgnName' => NULL,   //--- ชื่อสินค้าภาษาต่างประเทศ
      'ItmsGrpCod' => getConfig('ITEM_GROUP_CODE'),  //--- กลุ่มสินค้า (ต้องตรงกับ SAP)
      'VatGourpSa' => getConfig('SALE_VATE_CODE'), //--- รหัสกลุ่มภาษีขาย
      'CodeBars' => $item->barcode, //--- บาร์โค้ด
      'VATLiable' => 'Y', //--- มี vat หรือไม่
      'PrchseItem' => 'Y', //--- สินค้าสำหรับซื้อหรือไม่
      'SellItem' => 'Y', //--- สินค้าสำหรับขายหรือไม่
      'InvntItem' => $item->count_stock == 1 ? 'Y' : 'N', //--- นับสต้อกหรือไม่
      'SalUnitMsr' => $item->unit_code, //--- หน่วยขาย
      'BuyUnitMsr' => $item->unit_code, //--- หน่วยซื้อ
      'CntUnitMsr' => $item->unit_code,
      'VatGroupPu' => getConfig('PURCHASE_VAT_CODE'), //---- รหัสกลุ่มภาษีซื้อ (ต้องตรงกับ SAP)
      'ItemType' => 'I', //--- ประเภทของรายการ F=Fixed Assets, I=Items, L=Labor, T=Travel
      'InvntryUom' => $item->unit_code, //--- หน่วยในการนับสต็อก
      'validFor' => $item->active == 1 ? 'Y' : 'N',
      'U_MODEL' => $item->style_code,
      'U_COLOR' => $item->color_code,
      'U_SIZE' => $item->size_code,
      'U_GROUP' => $item->group_code,
			'U_MAINGROUP' => $item->main_group_code,
      'U_MAJOR' => $item->sub_group_code,
      'U_CATE' => $item->category_code,
      'U_SUBTYPE' => $item->kind_code,
      'U_TYPE' => $item->type_code,
      'U_BRAND' => $item->brand_code,
      'U_YEAR' => $item->year,
      'U_COST' => $item->cost,
      'U_PRICE' => $item->price,
      'U_OLDCODE' => $item->old_code,
      'F_E_Commerce' => $method,
      'F_E_CommerceDate' => sap_date(now(), TRUE)
    );

		if( ! $this->products_model->add_item($ds))
		{
      $sc = FALSE;
      $this->error = "Update Item failed";
		}

    return $sc;

  }



  public function export_style($style_code)
  {
    $style = $this->product_style_model->get($style_code);

    if(!empty($style))
    {
      $ext = $this->product_style_model->is_middle_exists($style_code);
      $flag = $ext === TRUE ? 'U' : 'A';
      $arr = array(
        'Code' => $style->code,
        'Name' => $style->name,
        'UpdateDate' => sap_date(now(), TRUE),
        'Flag' => $flag
      );
      return $this->product_style_model->add_sap_model($arr);
    }
    return FALSE;
  }


    public function download_template($token)
    {
      //--- load excel library
      $this->load->library('excel');

      $this->excel->setActiveSheetIndex(0);
      $this->excel->getActiveSheet()->setTitle('Items Master Template');

      //--- set report title header
      $this->excel->getActiveSheet()->setCellValue('A1', 'Code');
      $this->excel->getActiveSheet()->setCellValue('B1', 'Name');
      $this->excel->getActiveSheet()->setCellValue('C1', 'Barcode');
      $this->excel->getActiveSheet()->setCellValue('D1', 'Model');
      $this->excel->getActiveSheet()->setCellValue('E1', 'Color');
      $this->excel->getActiveSheet()->setCellValue('F1', 'Size');
      $this->excel->getActiveSheet()->setCellValue('G1', 'Group');
      $this->excel->getActiveSheet()->setCellValue('H1', 'MainGroup');
      $this->excel->getActiveSheet()->setCellValue('I1', 'SubGroup');
      $this->excel->getActiveSheet()->setCellValue('J1', 'Category');
      $this->excel->getActiveSheet()->setCellValue('K1', 'Kind');
      $this->excel->getActiveSheet()->setCellValue('L1', 'Type');
      $this->excel->getActiveSheet()->setCellValue('M1', 'Brand');
      $this->excel->getActiveSheet()->setCellValue('N1', 'Collection');
      $this->excel->getActiveSheet()->setCellValue('O1', 'Year');
      $this->excel->getActiveSheet()->setCellValue('P1', 'Cost');
      $this->excel->getActiveSheet()->setCellValue('Q1', 'Price');
      $this->excel->getActiveSheet()->setCellValue('R1', 'Unit');
      $this->excel->getActiveSheet()->setCellValue('S1', 'CountStock');
      $this->excel->getActiveSheet()->setCellValue('T1', 'IsAPI');
      $this->excel->getActiveSheet()->setCellValue('U1', 'OldModel');
      $this->excel->getActiveSheet()->setCellValue('V1', 'OldCode');


      setToken($token);

      $file_name = "Items_master_template.xlsx";
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
      header('Content-Disposition: attachment;filename="'.$file_name.'"');
      $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
      $writer->save('php://output');
    }

  public function clear_filter()
	{
    $filter = array('item_code','item_name','item_barcode','color', 'size','group','sub_group','category','kind','type','brand', 'collection','year', 'active');
    clear_filter($filter);
	}
}

?>
