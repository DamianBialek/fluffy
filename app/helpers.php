<?php
if(!function_exists("appConfig")) {
    /**
     * @param null|string $key
     * @return \App\Helpers\ApplicationConfigRepository|string
     */
    function appConfig($key = null) {
        if (is_null($key)) {
            return app('appConfig');
        }

        return app('appConfig')->get($key);
    }
}

function formatPrice($price)
{
    return number_format($price, 2, ",", " ").' zł';
}

function calcNetFromGross($priceTIncluded)
{
    return round($priceTIncluded / (1 + appConfig("tax") / 100), 2);
}

function getTaxAmount($priceTIncluded)
{
    return round($priceTIncluded * appConfig("tax") / (appConfig("tax") + 100), 2);
}
