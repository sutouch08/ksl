<?php
class Send_to_wms extends CI_Controller
{
  public $title = 'Resend data';
	public $menu_code = '';
	public $menu_group_code = '';
  public $orders;
  public $error = "";
  public $wms;

  public function __construct()
  {
    parent::__construct();
    $this->wms = $this->load->database('wms', TRUE);
    $this->load->library('wms_order_api');

    $this->orders = array(
      'WO-230218958',
      'WO-230218960',
      'WO-230218961',
      'WO-230218962',
      'WO-230218963',
      'WO-230218964',
      'WO-230218965',
      'WO-230218966',
      'WO-230218967',
      'WO-230218968',
      'WO-230218969',
      'WO-230218970',
      'WO-230218971',
      'WO-230218972',
      'WO-230218973',
      'WO-230218974',
      'WO-230218975',
      'WO-230218976',
      'WO-230218977',
      'WO-230218978',
      'WO-230218979',
      'WO-230218980',
      'WO-230218981',
      'WO-230218983',
      'WO-230218984',
      'WO-230218986',
      'WO-230218987',
      'WO-230218988',
      'WO-230218989',
      'WO-230218990',
      'WO-230218991',
      'WO-230218992',
      'WO-230218994',
      'WO-230218995',
      'WO-230218996',
      'WO-230218997',
      'WO-230218999',
      'WO-230219000',
      'WO-230219001',
      'WO-230219002',
      'WO-230219003',
      'WO-230219004',
      'WO-230219005',
      'WO-230219006',
      'WO-230219007',
      'WO-230219009',
      'WO-230219010',
      'WO-230219011',
      'WO-230219012',
      'WO-230219013',
      'WO-230219014',
      'WO-230219015',
      'WO-230219016',
      'WO-230219018',
      'WO-230219019',
      'WO-230219020',
      'WO-230219022',
      'WO-230219023',
      'WO-230219024',
      'WO-230219025',
      'WO-230219026',
      'WO-230219027',
      'WO-230219028',
      'WO-230219029',
      'WO-230219030',
      'WO-230219031',
      'WO-230219032',
      'WO-230219033',
      'WO-230219034',
      'WO-230219035',
      'WO-230219036',
      'WO-230219037',
      'WO-230219038',
      'WO-230219039',
      'WO-230219040',
      'WO-230219041',
      'WO-230219043',
      'WO-230219044',
      'WO-230219045',
      'WO-230219046',
      'WO-230219047',
      'WO-230219048',
      'WO-230219049',
      'WO-230219050',
      'WO-230219051',
      'WO-230219052',
      'WO-230219053',
      'WO-230219055',
      'WO-230219056',
      'WO-230219057',
      'WO-230219058',
      'WO-230219059',
      'WO-230219061',
      'WO-230219063',
      'WO-230219065',
      'WO-230219066',
      'WO-230219068',
      'WO-230219072',
      'WO-230219074',
      'WO-230219153',
      'WO-230219154',
      'WO-230219155',
      'WO-230219156',
      'WO-230219157',
      'WO-230219158',
      'WO-230219159',
      'WO-230219160',
      'WO-230219161',
      'WO-230219162',
      'WO-230219163',
      'WO-230219164',
      'WO-230219165',
      'WO-230219166',
      'WO-230219167',
      'WO-230219168',
      'WO-230219169',
      'WO-230219170',
      'WO-230219171',
      'WO-230219172',
      'WO-230219173',
      'WO-230219174',
      'WO-230219175',
      'WO-230219177',
      'WO-230219178',
      'WO-230219180',
      'WO-230219182',
      'WO-230219183',
      'WO-230219185',
      'WO-230219186',
      'WO-230219187',
      'WO-230219188',
      'WO-230219191',
      'WO-230219192',
      'WO-230219193',
      'WO-230219194',
      'WO-230219195',
      'WO-230219196',
      'WO-230219197',
      'WO-230219199',
      'WO-230219200',
      'WO-230219201',
      'WO-230219202',
      'WO-230219203',
      'WO-230219204',
      'WO-230219205',
      'WO-230219206',
      'WO-230219208',
      'WO-230219209',
      'WO-230219210',
      'WO-230219211',
      'WO-230219212',
      'WO-230219213',
      'WO-230219216',
      'WO-230219217',
      'WO-230219218',
      'WO-230219219',
      'WO-230219220',
      'WO-230219221',
      'WO-230219222',
      'WO-230219224',
      'WO-230219225',
      'WO-230219226',
      'WO-230219227',
      'WO-230219229',
      'WO-230219230',
      'WO-230219232',
      'WO-230219233',
      'WO-230219234',
      'WO-230219235',
      'WO-230219236',
      'WO-230219237',
      'WO-230219238',
      'WO-230219239',
      'WO-230219240',
      'WO-230219241',
      'WO-230219242',
      'WO-230219243',
      'WO-230219244',
      'WO-230219246',
      'WO-230219247',
      'WO-230219249',
      'WO-230219251',
      'WO-230219252',
      'WO-230219254',
      'WO-230219255',
      'WO-230219257',
      'WO-230219258',
      'WO-230219259',
      'WO-230219260',
      'WO-230219261',
      'WO-230219263',
      'WO-230219265',
      'WO-230219267',
      'WO-230219271',
      'WO-230219277',
      'WO-230219466',
      'WO-230220121',
      'WO-230220179',
      'WO-230220183',
      'WO-230220217'
    );
  }


  public function go()
  {
    $count = 0;
    $success = 0;
    $err = 0;
    if(! empty($this->orders))
    {
      foreach($this->orders as $code)
      {
        $count++;

        $ex = $this->wms_order_api->export_order($code);
        if( ! $ex)
        {
          $err++;
          $this->error .= $code .' : '.$this->wms_order_api->error.'<br/>';
        }
        else
        {
          $success++;
        }
      }
    }

    echo "Orders : {$count} orders, Success: {$success} failed : {$err} <br/>{$this->error}";
  }
}

 ?>
