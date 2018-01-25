<?php
class Tools 
{
	public static function authUser($name, $pass)
	{
	    //Return 0 if fail to authenticate
        //$pass is encrypted here

        $fieldName = EmailTools::checkEmail($name) ? 'email' : 'nickname';
		$record = User::model()->findByAttributes(array($fieldName=>$name, 'password'=>$pass));
		
		if($record == null) { return 0; }
		else { return $record->id; }
	}

	public static function encryptPassword($t) { return md5($t); }
    public static function getAbsUrl($u) { return Yii::getPathOfAlias('webroot').$u; }
	public static function now($dateOnly = false) { return $dateOnly ? date("Y-m-d") : date("Y-m-d H:i:s"); }
	public static function datePart($d) { return substr($d, 0, 10); }
    public static function timePart($d) { return substr($d, 11); }
	public static function ip() { return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'; }
	public static function cut($t, $len, $postfix = ' ..') { return mb_strlen($t, 'utf-8') > $len ? mb_substr($t, 0, $len - 1, 'utf-8').$postfix : $t; }

    //Mode: 1 for local test, 2 for production
    public static function mode() { return isset(Yii::app()->params['runningMode']) ? intval(Yii::app()->params['runningMode']) : 1; }

    public static function currentUrl($autoGetSourceIfAjax = true)
    {
        $currentUrl = '/';
        if(Yii::app()->request->isAjaxRequest && $autoGetSourceIfAjax)
        {
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']) $currentUrl = $_SERVER['HTTP_REFERER'];
        }
        else
        {
            $currentUrl = Yii::app()->createAbsoluteUrl(Yii::app()->request->getUrl());
        }

        return $currentUrl;
    }

    public static function logException($exception, $specifiedUser = null)
    {
        $errorMessage = get_class($exception).': '.$exception->getMessage();
        Tools::log($errorMessage, 'error', $specifiedUser);
    }

	public static function log($t, $category = "info", $specificUser = null)
    {
        $user = $specificUser ? $specificUser : Tools::getUserFromContext();
        $url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $url .= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        $log = new Log();
        $log->time = Tools::now();
        $log->user_id = $user ? $user->id : 0;
        $log->user_name = $user ? $user->nickname : null;
        $log->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
        $log->url = $url ? $url : '-';
        $log->ip = Tools::ip();
        $log->log = '<div class="'.$category.'">'.$t.'</div>';
        $log->save();
    }

    public static function getSeoFriendlyUrl($name)
    {
        $name = trim(strtolower($name));
        $name = str_replace(array(' ', ',', "'", '"', '#', '?', '%', '(', ')', '&'), '-', $name);
        $name = str_replace('--', '-', $name);
        return $name;
    }

    public static function simplifyText($content, $maxLength = 0)
    {
        $c = strip_tags($content);
        $c = trim($c);
        $c = str_replace(array("\n", "\t", "\r", "\""), '', $c);

        if($maxLength > 0)
        {
            $c = Tools::cut($c, $maxLength);
        }

        return $c;
    }

    public static function getUserFromContext()
    {
        //EmailController does not apply here

        $controller = Yii::app()->controller;
        return (is_a($controller, 'BaseController') || is_a($controller, 'AdminBaseController'))
            ? $controller->user : null;
    }

    public static function normalizeName($text)
    {
        return ucwords(strtolower($text));
    }

    public static function isMobileDevice()
    {
        if(isset($_SERVER["HTTP_USER_AGENT"]))
        {
            return preg_match(
                "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
                $_SERVER["HTTP_USER_AGENT"]);
        }

        return false;
    }

    public static function shortNum($number)
    {
        $numberString = strval($number);
        $numberLength = strlen($numberString);

        if($numberLength <= 3) return $numberString;
        if($numberLength == 4) return number_format($number / 1000, 1).'K';
        if($numberLength <= 6) return intval($number / 1000).'K';
        return number_format($number / 1000000, 1).'M';
    }

    public static function isFileUpdatedAfter($fileRelPath, $givenTime)
    {
        $path = Tools::getAbsUrl($fileRelPath);
        if(!file_exists($path))
        {
            throw new Exception('File not exist while loading m-time: '.$fileRelPath);
        }

        $fileLastModifyTime = filemtime($path);
        $givenTime = CDateTimeParser::parse($givenTime, 'yyyy-M-d H:m:s');

        return $givenTime < $fileLastModifyTime;
    }

	public static function decodeHtmlText($t)
	{
		$t = str_replace('<br />', "", $t);
		$t = html_entity_decode($t);
		return $t;
	}

    public static function purifySqlContent($t)
    {
        if(!$t) return '';
        return str_replace(array('"', "'", "%", "\\"), "", $t);
    }

    public static function autoAddLink($text)
    {
        //Ref: https://stackoverflow.com/questions/13105960/replacing-text-link-as-link-with-preg-replace

        return preg_replace(
            '|(https?://([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i',
            '<a class="link" target="_blank" href="$1">$1</a>', $text);
    }

	public static function genCode($length = 10, $withChars = false)
	{
        $range = $withChars ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789';
        $rangeLength = strlen($range);
		$v = '';
		while(strlen($v) < $length)
		{
            $i = $range[rand(0, $rangeLength - 1)];
			$v .= $i;
		}
		return $v;
	}

	public static function asyncCallByCurl($action, $getParam)
    {
        $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : UserConfig::$defaultDomain.'.'.UserConfig::$domain;
        $fullUrl = UserConfig::$protocol.$serverName.$action.'?'.$getParam;
        $commandLine = 'curl -k -L "'.$fullUrl.'" >/dev/null &';

        exec($commandLine, $_, $retVal);
        if($retVal != 0)
        {
            Tools::log('Async request returns '.$retVal.': <i>'.$fullUrl.'</i>');
        }
    }
	
	public static function saveModel($model)
	{
		if(!$model->save()) throw new Exception(var_export($model->getErrors(), true));

		//Auto process sort-index filling
        if($model->hasAttribute('sort_index') && $model->sort_index == 0)
        {
            $model->sort_index = $model->id;
            $model->save();     //No recursive call!
        }
	}
	
	public static function processPostInput($formName = 'DataForm', $excludeKeys = array())
	{
	    return Tools::processObjectInput($_POST[$formName], $excludeKeys);
	}

	public static function processObjectInput($obj, $excludeKeys = array())
    {
        $formData = array();
        foreach($obj as $k => $v)
        {
            $formData[$k] = in_array($k, $excludeKeys) ? $v : Tools::processSingleInput($v);
        }

        return $formData;
    }

    public static function processSingleInput($v, $nlCount = 0)
    {
        //Replace only first $nlCount new line

        $v = htmlspecialchars($v, ENT_QUOTES);
        if($nlCount > 0) return preg_replace('/\\n/', "<br />", $v, $nlCount);
        else return nl2br($v);
    }

    public static function getFolderSize($folder, $outputFull = true)
    {
        //Work on Linux/Mac only
        //$folder is an absolute URL

        $cmd = 'du -sh '.$folder;
        $output = shell_exec($cmd);
        if($outputFull) return $output;
        else
        {
            $splitIndex = strpos($output, '/');
            if($splitIndex > 0)
            {
                $output = trim(substr($output, 0, $splitIndex - 1));
            }

            return $output;
        }
    }

    public static function createUpload($fieldName, $targetExtension = null)
    {
        //For single upload only

        try
        {
            if(!isset($_FILES[$fieldName])) throw new Exception('No ['.$fieldName.'] item found');
            if($_FILES[$fieldName]["error"] !== 0)
                throw new Exception('['.$fieldName.'] error code #'.$_FILES[$fieldName]["error"].
                    (isset($_FILES[$fieldName]["name"]) ? (': '.$_FILES[$fieldName]["name"]) : ''));

            if(!$targetExtension)
            {
                $pathInfo = pathinfo($_FILES[$fieldName]["name"]);
                $targetExtension = $pathInfo["extension"];
            }

            $relUrl = Tools::createFile($targetExtension);
            if(!move_uploaded_file($_FILES[$fieldName]["tmp_name"], Tools::getAbsUrl($relUrl)))
                throw new Exception('Fail to move ['.$_FILES[$fieldName]["tmp_name"].'] to ['.$relUrl.']');

            return $relUrl;
        }
        catch (Exception $ex)
        {
            Tools::log('Create single upload fail: <i>'.$ex->getMessage().'</i>');
            return null;
        }
    }

    public static function createFile($targetExtension, $data = null, $namePrefix = '')
    {
        //Saved to [uploadV2] folder, separated by date
        //If $data = null, it does not create the file actually, just create the folder structure
        //Return relative URL

        $uniqueName = uniqid($namePrefix ? ($namePrefix.'.') : '', true);
        $targetFolder = '/uploadV2/'.date("Y-m/d").'/';
        $absTargetFolder = Tools::getAbsUrl($targetFolder);
        if(!file_exists($absTargetFolder) && !mkdir($absTargetFolder, 0777, true))
            throw new Exception('Fail to create ['.$targetFolder.']');

        $relUrl = $targetFolder.$uniqueName.'.'.$targetExtension;
        $absUrl = Tools::getAbsUrl($relUrl);
        if($data)
        {
            if(!file_put_contents($absUrl, $data))
                throw new Exception('Fail to put contents ['.$relUrl.']');
        }

        return $relUrl;
    }

    public static function checkPhpSyntax($code)
    {
        //Return NULL if correct, or error string

        $tempFilePath = Tools::getAbsUrl(UserConfig::$adminConfigRoot.uniqid().'.phpx');
        $tempFileHandle = fopen($tempFilePath, 'w');
        fwrite($tempFileHandle, $code);
        $lastLineOutput = trim(exec('php -l '.$tempFilePath.' 2>&1', $output));
        $isSyntaxOk = strpos($lastLineOutput, 'No syntax errors') === 0;

        fclose($tempFileHandle);
        if(!unlink($tempFilePath))
        {
            Tools::log('Fail to remove: <i>'.$tempFilePath.'</i>, manual removal required', 'error');
        }

        if($isSyntaxOk)
        {
            return null;
        }
        else
        {
            if(count($output) > 1) array_pop($output);     //Last line output is useless
            $phpCompileOutput = implode("<br>", $output);
            $phpCompileOutput = str_replace('in '.$tempFilePath, '', $phpCompileOutput);
            if(!$phpCompileOutput) $phpCompileOutput = 'No Compiler Output';

            return $phpCompileOutput;
        }
    }

}
