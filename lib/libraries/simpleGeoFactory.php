<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */

class lib_libraries_simpleGeoFactory
{
    public static $geo_inst;

    public static function get_inst()
    {
        if(!isset(self::$geo_inst))
        {
            self::$geo_inst = new Services_SimpleGeo('CBfSSXTJWPJTDggCfLGynhD3wYsdmGWT', 'XTgC4NDvUHEJ82pXsnshwTXNUUkAvEMY');
        }

        return self::$geo_inst;
    }
}