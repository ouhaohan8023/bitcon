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