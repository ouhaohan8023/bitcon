<?php

use Illuminate\Database\Seeder;

class MoneySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('money')->insert([
            [
                'platform' => 'bian',
                'symbol' => 'BTC',
                'price' => '10000',
                'qty' => '1',
            ],
            [
                'platform' => 'bian',
                'symbol' => 'USDT',
                'price' => '1',
                'qty' => '10000',
            ],
            [
                'platform' => 'huobi',
                'symbol' => 'BTC',
                'price' => '10000',
                'qty' => '1',
            ],
            [
                'platform' => 'huobi',
                'symbol' => 'USDT',
                'price' => '1',
                'qty' => '10000',
            ]
    ]
    );
  }
}
