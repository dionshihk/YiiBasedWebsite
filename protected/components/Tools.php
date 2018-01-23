<?php
class Tools 
{
	/**
	 * Return 0 if fail
	 */
	public static function authUser($name, $pass)
	{
        $fieldName = Tools::checkEmail($name) ? 'email' : 'nickname';
		$record = User::model()->findByAttributes(array($fieldName=>$name, 'password'=>md5($pass)));
		
		if($record == null) { return 0; }
		else { return $record->id; }
	}

    public static function getUserName($user) { return $user->name; }
	public static function checkEmail($email) { return preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/", $email) > 0; }
	public static function now($dateOnly = false) { return $dateOnly ? date("Y-m-d") : date("Y-m-d H:i:s"); }
	public static function datePart($d) { return substr($d, 0, 10); }
	public static function ip() { return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'; }
    public static function isLocalVisit($ip = null) { return substr($ip ? $ip : Tools::ip(), 0, 4) == '127.'; }
	public static function cut($t, $len) { return mb_strlen($t, 'utf-8') > $len ? mb_substr($t, 0, $len - 1, 'utf-8').' ...' : $t; }
	public static function log($t, $specificUser = null)
    {
        $controller = Yii::app()->controller;
        $user = $specificUser ? $specificUser : (is_a($controller, 'BaseController') ? $controller->user : null);

        $log = new Log();
        $log->time = Tools::now();
        $log->user_id = $user ? $user->id : 0;
        $log->name = $user ? Tools::getUserName($user) : 'Anonymous';
        $log->ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
        $log->gets = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Unknown';
        $log->ip = Tools::ip();
        $log->log = $t;
        $log->save();
    }

    public static function isAfterDate($d)
    {
        return time() > CDateTimeParser::parse($d.' 00:00:01', 'yyyy-M-d H:m:s');
    }

    public static function isBeforeDate($d)
    {
        return time() < CDateTimeParser::parse($d.' 23:59:59', 'yyyy-M-d H:m:s');
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

	public static function genCode($length = 10)
	{
		$v = '';
		while(strlen($v) < $length)
		{
			$i = rand(0, 9);
			$v .= $i;
		}
		return $v;
	}
	
	public static function saveModel($model, $friendlyTip = 'Please check your every input')
	{
		if(!$model->save())
		{
			throw new Exception(UserConfig::$isDebugging ? var_export($model->getErrors(), true) : $friendlyTip);
		}
	}
	
	public static function processPostInput($formName = 'DataForm', $excludeKeys = array())
	{
		$formData = array();
		foreach($_POST[$formName] as $k => $v)
		{
			$formData[$k] = in_array($k, $excludeKeys) ? $v : Tools::processSingleInput($v);
		}
			
		return $formData;
	}

    public static function processSingleInput($v)
    {
        return nl2br(htmlspecialchars($v, ENT_NOQUOTES));
    }

    public static function getFolderSize($folder, $outputFull = true)
    {
        //Work on Linux OS only
        //$folder is an absolute URL

        $cmd ='du -sh '.$folder;
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

    public static function makeUniqueFileName()
    {
        return date("Ymd").'-'.substr(md5(microtime()), 0, 6).'-'.Tools::genCode(8);
    }

	public static function saveFile($fieldName, $fixedExtension = null, $destFolder = '/uploadDefault/')
	{
        $uniqueName = Tools::makeUniqueFileName();

        if(isset($_SERVER['HTTP_CONTENT_DISPOSITION']) &&
            preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i', $_SERVER['HTTP_CONTENT_DISPOSITION'],
                $info))
        {
            $pathInfo = pathinfo(urldecode($info[2]));
            $relUrl = $destFolder.$uniqueName.".".
                ($fixedExtension == null ? $pathInfo["extension"] : $fixedExtension);
            Tools::save(Tools::getAbsUrl($relUrl), file_get_contents("php://input"));

            return $relUrl;
        }
        else if(isset($_FILES[$fieldName]) && $_FILES[$fieldName]["error"] == 0)
        {
            $pathInfo = pathinfo($_FILES[$fieldName]["name"]);
            $relUrl = $destFolder.$uniqueName.".".
                ($fixedExtension == null ? $pathInfo["extension"] : $fixedExtension);
            $absUrl = Tools::getAbsUrl($relUrl);
            move_uploaded_file($_FILES[$fieldName]["tmp_name"], $absUrl);

            return $relUrl;
        }
        else
        {
            return null;
        }
	}

    public static function save($url, $data)
    {
        //$url is absolute here
        $pathInfo = pathinfo($url);
        $folderName = $pathInfo['dirname'];
        if(!file_exists($folderName))
        {
            //Create folder first
            mkdir($folderName, 0777);
        }

        file_put_contents($url, $data);
        chmod($url, 0755);
    }

	public static function getAbsUrl($relUrl)
	{
		return Yii::getPathOfAlias('webroot').$relUrl;
	}
}

?>