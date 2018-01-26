<?php

class LangTools
{
    private $langDict = null;
    private $languageFile = null;

    public function __construct()
    {
        $this->languageFile = 'default';
        $this->langDict = require(Tools::getAbsUrl(UserConfig::$adminConfigRoot.'i18n/'.$this->languageFile.'.php'));
    }

    public function contains($key)
    {
        return isset($this->langDict[$key]);
    }

    public function presentText($key, $replaceText = [], $enableMissLog = true)
    {
        if(!$key) return '';

        if(isset($this->langDict[$key]))
        {
            $result = $this->langDict[$key];
            return LangTools::replaceInline($result, $replaceText);
        }
        else
        {
            if($enableMissLog) Tools::log("Dictionary miss: <i>".$key." | ".$this->languageFile."</i>", "error");
            return '';
        }
    }

    //$replaceText: array to replace {1} {2} ...
    public static function replaceInline($result, $replaceText)
    {
        if($replaceText && is_array($replaceText))
        {
            foreach($replaceText as $k => $singlePart)
            {
                $result = str_replace('{'.($k + 1).'}', strval($singlePart), $result);
            }
        }

        return $result;
    }
} 