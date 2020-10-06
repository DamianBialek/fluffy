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
