<?php

class DirtyWordTools
{
    public static function contains($c)
    {
        $c = strtolower($c);
        foreach(DirtyWord::$data as $v)
        {
            if(strpos($c, $v) !== false)
                return true;
        }

        return false;
    }

    public static function purify($c, $mask = "***")
    {
        $patterns = array();
        foreach(DirtyWord::$data as $v)
        {
            $patterns[] = '/'.$v.'/i';
        }

        return preg_replace($patterns, $mask, $c);
    }
}