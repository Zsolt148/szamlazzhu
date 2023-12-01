<?php

if (! function_exists('szamlazzhu')) {
    /**
     * @return mixed|\Zsolt148\Szamlazzhu\Szamlazzhu
     */
    function szamlazzhu(string $type = null)
    {

        if (is_null($type)) {
            return app('szamlazzhu');
        }

        return app('szamlazzhu')->{$type}();
    }
}
