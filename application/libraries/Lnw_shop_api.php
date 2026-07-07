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
    $this->ci = &get_instance();
    $this->ci->load->model('rest/V1/lnw_shop_api_logs_model');

    $this->api = is_true(getConfig('LNW_SHOP_API'));
    $this->token = getConfig('LNW_SHOP_API_CREDENTIAL');
    $this->url = getConfig('LNW_SHOP_API_ENDPOINT');
    $this->test = is_true(getConfig('LNW_SHOP_API_TEST'));
    $this->logs_json = is_true(getConfig('LNW_SHOP_LOG_JSON'));
  }


  public function addStock(array $ds = array(), $ref = '', $docType = 'GR')
  {
    if (! $this->api)
    {
      $this->error = 'LNW SHOP API is not enabled';
      return FALSE;
    }

    $type = $docType;
    $action = 'add-stock';
    $sc = TRUE;

    if (! empty($ds) && is_array($ds) && ! empty($ref))
    {
      $req = array(
        'product_sku' => $ds['product_sku'],
        'stock' => intval($ds['stock']),
        'reference_no' => $ds['reference_no'],
        'detail' => isset($ds['detail']) ? $ds['detail'] : NULL
      );

      $path = '/product/add_stock';
      $url = rtrim($this->url, '/') . $path;
      $method = 'POST';
      $headers = array(
        "X-API-KEY: {$this->token}",
        "Content-Type:application/json"
      );

      $json = json_encode($req);

      if (! $this->test)
      {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (! empty($error))
        {
          $sc = FALSE;
          $this->error = $error;
        }

        $res = json_decode($response);

        if (! empty($res) && ! empty($res->status))
        {
          if ($res->status != 'success')
          {
            $sc = FALSE;
            $this->error = $res->error_code . ' : ' . $res->error_message;
          }

          if ($this->logs_json)
          {
            $logs = array(
              'trans_id' => genUid(),
              'type' => $type,
              'api_path' => $path,
              'code' => $ds['product_sku'],
              'ref' => $ref,
              'action' => $action,
              'status' => $res->status == 'success' ? 'success' : 'failed',
              'message' => $res->status == 'success' ? NULL : $this->error,
              'request_json' => $json,
              'response_json' => $response
            );

            $this->ci->lnw_shop_api_logs_model->add_logs($logs);
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = 'Invalid response from LNW SHOP API';
        }
      }
      else
      {
        if ($this->logs_json)
        {
          $logs = array(
            'trans_id' => genUid(),
            'type' => $type,
            'api_path' => $path,
            'code' => $ds['product_sku'],
            'ref' => $ref,
            'action' => $action,
            'status' => 'test',
            'message' => NULL,
            'request_json' => $json,
            'response_json' => NULL
          );

          $this->ci->lnw_shop_api_logs_model->add_logs($logs);
        }
      }
    }
    else
    {
      $sc =  FALSE;
      $this->error = 'Invalid item code or quantity or reference';
    }

    return $sc;
  }


  public function addStockBatch(array $ds = array(), $ref = '', $type = 'GR')
  {
    if (! $this->api)
    {
      $this->error = 'LNW SHOP API is not enabled';
      return FALSE;
    }

    $action = 'add-stock-batch';
    $sc = TRUE;

    if (! empty($ds) && is_array($ds))
    {
      $req = array('products' => array());

      foreach ($ds as $item)
      {
        $req['products'][] = array(
          'product_sku' => $item->product_sku,
          'stock' => intval($item->stock),
          'reference_no' => $item->reference_no,
          'detail' => isset($item->detail) ? $item->detail : NULL
        );
      }

      $path = '/product/add_stock_batch';
      $url = rtrim($this->url, '/') . $path;
      $method = 'POST';
      $headers = array(
        "X-API-KEY: {$this->token}",
        "Content-Type:application/json"
      );

      $json = json_encode($req);

      if (! $this->test)
      {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (! empty($error))
        {
          $sc = FALSE;
          $this->error = $error;
        }

        $res = json_decode($response);

        if (! empty($res) && ! empty($res->status))
        {
          if ($res->status != 'success')
          {
            $sc = FALSE;
            $this->error = $res->error_code . ' : ' . $res->error_message;
          }

          if ($this->logs_json)
          {
            $logs = array(
              'trans_id' => genUid(),
              'type' => $type,
              'api_path' => $path,
              'code' => $ref,
              'ref' => $ref,
              'action' => $action,
              'status' => $res->status == 'success' ? 'success' : 'failed',
              'message' => $res->status == 'success' ? NULL : $this->error,
              'request_json' => $json,
              'response_json' => $response
            );

            $this->ci->lnw_shop_api_logs_model->add_logs($logs);
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = 'Invalid response from LNW SHOP API';
        }
      }
      else
      {
        if ($this->logs_json)
        {
          $logs = array(
            'trans_id' => genUid(),
            'type' => $type,
            'api_path' => $path,
            'code' => $ref,
            'ref' => $ref,
            'action' => $action,
            'status' => 'test',
            'message' => NULL,
            'request_json' => $json,
            'response_json' => NULL
          );

          $this->ci->lnw_shop_api_logs_model->add_logs($logs);
        }
      }
    }
    else
    {
      $sc =  FALSE;
      $this->error = 'Invalid data';
    }

    return $sc;
  }


  public function cancelStockBatch(array $ds = array(), $ref = '', $type = 'GR')
  {
    if (! $this->api)
    {
      $this->error = 'LNW SHOP API is not enabled';
      return FALSE;
    }

    $action = 'cancel-stock-batch';
    $sc = TRUE;

    if (! empty($ds) && is_array($ds))
    {
      $req = array('products' => array());

      foreach ($ds as $item)
      {
        $req['products'][] = array(
          'product_sku' => $item->product_sku,
          'stock' => intval($item->stock),
          'reference_no' => $item->reference_no,
          'detail' => isset($item->detail) ? $item->detail : NULL
        );
      }

      $path = '/product/add_stock_batch';
      $url = rtrim($this->url, '/') . $path;
      $method = 'POST';
      $headers = array(
        "X-API-KEY: {$this->token}",
        "Content-Type:application/json"
      );

      $json = json_encode($req);

      if (! $this->test)
      {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (! empty($error))
        {
          $sc = FALSE;
          $this->error = $error;
        }

        $res = json_decode($response);

        if (! empty($res) && ! empty($res->status))
        {
          if ($res->status != 'success')
          {
            $sc = FALSE;
            $this->error = $res->error_code . ' : ' . $res->error_message;
          }

          if ($this->logs_json)
          {
            foreach ($ds as $item)
            {
              $logs = array(
                'trans_id' => genUid(),
                'type' => $type,
                'api_path' => $path,
                'code' => $ref,
                'ref' => $ref,
                'action' => $action,
                'status' => $res->status == 'success' ? 'success' : 'failed',
                'message' => $res->status == 'success' ? NULL : $this->error,
                'request_json' => $json,
                'response_json' => $response
              );

              $this->ci->lnw_shop_api_logs_model->add_logs($logs);
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = 'Invalid response from LNW SHOP API';
        }
      }
      else
      {
        if ($this->logs_json)
        {
          $logs = array(
            'trans_id' => genUid(),
            'type' => $type,
            'api_path' => $path,
            'code' => $ref,
            'ref' => $ref,
            'action' => $action,
            'status' => 'test',
            'message' => NULL,
            'request_json' => $json,
            'response_json' => NULL
          );

          $this->ci->lnw_shop_api_logs_model->add_logs($logs);
        }
      }
    }
    else
    {
      $sc =  FALSE;
      $this->error = 'Invalid data';
    }

    return $sc;
  }
} //--- end class
