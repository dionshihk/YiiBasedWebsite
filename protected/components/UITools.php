<?php

class UITools
{

    public static function echoProductPaging($currentPage, $totalCount, $pageSize)
    {
        echo '<div class="pages">';
        $pageCount = ceil($totalCount / $pageSize);
        if($pageCount == 0) $pageCount = 1;

        $isFirstPage = ($currentPage <= 0);
        $isLastPage = ($currentPage >= $pageCount - 1);

        echo '<a class="op" href="javascript:reloadUriWithParam(\'page\','.($isFirstPage ? 0 : ($currentPage - 1)).')">«</a>';

        $pageEntries = array($currentPage - 1, $currentPage, $currentPage + 1);

        if($pageEntries[0] <= 3)
        {
            for($j = min(3, $pageEntries[0] - 1); $j >= 0 ; $j--) array_unshift($pageEntries, $j);
        }
        else
        {
            array_unshift($pageEntries, -999);
            for($j = 3; $j >= 0 ; $j--) array_unshift($pageEntries, $j);
        }

        $lastElement = $pageEntries[count($pageEntries) - 1];
        if($lastElement > $pageCount - 3)
        {
            for($j = max($lastElement + 1, $pageCount - 3); $j < $pageCount ; $j++) array_push($pageEntries, $j);
        }
        else
        {
            array_push($pageEntries, -999);
            for($j = $pageCount - 3; $j < $pageCount ; $j++) array_push($pageEntries, $j);
        }

        foreach($pageEntries as $p)
        {
            $p = intval($p);

            if($p == $currentPage)
            {
                echo '<span>'.($p + 1).'</span>';
            }
            else if($p == -999)
            {
                echo '<em>…</em>';
            }
            else if($p >= 0 && $p < $pageCount)
            {
                echo '<a href="javascript:reloadUriWithParam(\'page\','.$p.')">'.($p + 1).'</a>';
            }
        }

        echo '<a class="op" href="javascript:reloadUriWithParam(\'page\','.($isLastPage ? ($pageCount - 1) : ($currentPage + 1)).')">»</a>';
        echo '</div>';
    }

    public static function echoDatePicker($id, $name, $placeHolder = "",
                                          $classes = array(), $enableOldDateSelect = false,
                                          $value = "", $style = "", $editable = false)
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(UserConfig::$staticFileRoot.'/lib.datepicker/datepicker.js');
        $cs->registerCssFile(UserConfig::$staticFileRoot.'/lib.datepicker/datepicker.css');

        $classes[] = 'disable-drag';
        if(!$enableOldDateSelect)
        {
            $classes[] = 'disable-19700101-'.date("Ymd");
        }

        echo '<input maxlength="12" name="'.$name.'" value="'.$value.'" style="'.$style.
            '" placeholder="'.$placeHolder.'" type="text" id="'.$id.'" class="w16em dateformat-Y-ds-m-ds-d';

        foreach($classes as $c) { echo ' '.$c; }
        if(!$editable) echo '" readonly="readonly';

        echo '">';
    }

    public static function echoSharing()
    {
        echo '<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5641877bd9fb8908" async="async"></script>';
    }

    public static function echoLiveChat($user = null)
    {
        if(!Tools::isLocalVisit())
        {
            echo '<script type="text/javascript">window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=' .
                'd.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.' .
                '_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");$.src="//v2.zopim.com/?3SDhucb8fi1ECUfOTWmpFEmQDfleBX7E";z.t=+new Date;$.' .
                'type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");</script>';

            $chatSettings = array();
            $chatSettings['Language'] = Tools::getContextLangObject()->langCode;

            if($user)
            {
                $chatSettings['Name'] = $user->first_name.' '.$user->last_name;
                $chatSettings['Email'] = $user->email;
                $chatSettings['Notes'] = 'Mask Member ID: '.$user->uniq_id;
            }

            echo '<script>$zopim(function(){';
            foreach($chatSettings as $k=>$v)
            {
                echo '$zopim.livechat.set'.$k.'("'.$v.'");';
            }

            echo '});</script>';
        }
    }
} 