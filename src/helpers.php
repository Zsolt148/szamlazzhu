<?php

if (! function_exists('szamlazzhu')) {
    /**
     * @param string|null $type
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
