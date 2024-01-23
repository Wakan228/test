<?php

class CalculatedRate
{
    public static function calculated($post)
    {
        $apiKey = 'dbf7a33839c440b6b302f3e55d030bec';
        $valute = self::findValute($post);
        $rateList = self::getRateList($apiKey, $valute);
        $price = 0;
        $post = json_decode($post);
        $items = $post->items;
        
        foreach ($items as $key => $value) {
            $actualValute = self::findSimilarity($value->currency, $rateList);
            $price += (($value->price / $actualValute) * $value->quantity);
        }   
        
        return json_encode(['checkoutCurrency' => $valute, 'checkoutPrice' => number_format($price, 2)]);
    }

    private static function findValute($post)
    {
        $post = json_decode($post);

        if ($post->checkoutCurrency === false) {
            self::errorMessage('bad checkoutCurrency');
        } else {
            return $post->checkoutCurrency;
        }
    }

    private static function getRateList($apiKey, $valute)
    {
        $url = 'https://open.er-api.com/v6/latest/';
        $ch = curl_init($url . $valute . '?apikey=' . $apiKey);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        if ($response === false) {
            self::errorMessage(curl_error($ch));
        } else {
            return $response;
        }
    }

    private static function findSimilarity($valute, $rateList)
    {
        $rateList = json_decode($rateList, true);

        foreach ($rateList['rates'] as $key => $valueRates) {
            if ($key == $valute) {
                return $valueRates;
            }
        }
    }

    private static function errorMessage($message)
    {
        return $message;
    }
}

$examplePost = '{
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

var_dump(CalculatedRate::calculated($examplePost));
