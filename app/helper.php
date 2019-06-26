<?php
function _httpGet($url = "")
{

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);

  $output['data'] = curl_exec($ch);
  $output['code'] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
  curl_close($ch);

  return $output;
}

function telegramFunction($method,$data)
{
  $tg = env('TELEGRAM_URL');
  $token = env('TELEGRAM_TOKEN');
  $url = $tg.$token.'/'.$method;
//    var_dump($url);die;
  $ret = curlGet($url,'post',$data);
  return $ret;
}

// get/post方法
if(!function_exists('curlGet')){
  function curlGet($url,$method,$post_data = 0){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($method == 'post') {
      curl_setopt($ch, CURLOPT_POST, 1);

      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }elseif($method == 'get'){
      curl_setopt($ch, CURLOPT_HEADER, 0);
    }
    $output = curl_exec($ch);
    \Illuminate\Support\Facades\Log::info('请求地址:'.$url,[$output,curl_errno($ch),curl_error($ch)]);
    curl_close($ch);
    return $output;
  }
}