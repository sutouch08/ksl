<?php
class Lnw_shop_api
{
  private $url;
  private $token;
  private $api;
  protected $ci;
  public $error;
  public $logs_json = FALSE;
  public $test = FALSE;

  public function __construct()
  {
    $this->ci =& get_instance();
		$this->ci->load->model('rest/V1/lnw_shop_api_logs_model');
    $this->ci->load->model('orders/orders_model');

    $this->api = getWrxApiConfig();
  }



} //--- end class

?>
