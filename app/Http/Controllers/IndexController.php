<?php

    namespace App\Http\Controllers;

    use App\Model\TrickerData;
    use Illuminate\Support\Facades\Log;

    class IndexController extends Controller
    {
        public function index()
        {
            $url = "https://api.binance.com";
            $path = "/api/v3/ticker/bookTicker?";//最优挂单接口
            $data['symbol'] = "BTCUSDT";
            $u = $url . $path . http_build_query($data);
//            $s = time();
            $res = $this->_httpGet($u);
//            $e = time();
//            dd($res, $u,$e-$s);
            if ($res['code'] == 200){
                $r = json_decode($res['data'],true);
                $data['platform'] = 'bian';
                $data['bid_price'] = $r['bidPrice'];
                $data['bid_qty'] = $r['bidQty'];
                $data['ask_price'] = $r['askPrice'];
                $data['ask_qty'] = $r['askQty'];
                TrickerData::create($data);
            } else {
                Log::info('httpError',$res);
//                echo 'false';
            }

        }

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
    }
