<?php 
class Calculated_rate
{
	static function Error_massege($massege)
	{
		return $massege;
	}
	static function Get_rate_list($api_id,$valute)
	{
		$url = 'https://open.er-api.com/v6/latest/';
		$ch = curl_init($url . $valute . '?apikey=' . $api_id);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($ch);

		if ($response === false) {
		    Calculated_rate::Error_massege(curl_error($ch));
		} else {
		    return $response;
		}
	}
	static function Calculated($post)
	{
		$api_key = 'dbf7a33839c440b6b302f3e55d030bec';
		$valute = Calculated_rate::Find_valute($post);
		$rate_list = Calculated_rate::Get_rate_list($api_key,$valute);
		$post = json_decode($post);
		return var_dump($rate_list);
	}
	static function Find_valute($post)
	{
		$post = json_decode($post);
		if($post->checkoutCurrency === false) {
			Calculated_rate::Error_massege('bad checkoutCurrency');
		} else {
			return $post->checkoutCurrency;
		}
		
	}
}
$post = '{
  "items": {
    "42": {
      "currency": "EUR",
      "price": 49.99,
      "quantity": 1
    },
    "55": {
      "currency": "USD",
      "price": 12,
      "quantity": 3
    }
  },
  "checkoutCurrency": "EUR" 
}
';
Calculated_rate::Calculated($post);

 ?>