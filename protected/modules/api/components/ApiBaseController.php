<?php

class ApiBaseController extends CController
{
    /** @var User $user */
    public $user = null;

	public function init()
	{
        parent::init();

        //Load User from request (in test env, user can be retrieved by Web session)
        if(isset($_POST['sessionToken']))
        {
            $sessionCode = Tools::purifySqlContent($_POST['sessionToken']);
            $tokenObj = MobileSessionToken::model()->find('token = "'.$sessionCode.'" AND expire_time > NOW()');
            if($tokenObj)
            {
                $this->user = User::model()->findByPk($tokenObj->user_id);
                if($this->user && !$this->user->account_status == 2) $this->user = null;
                if($this->user)
                {
                    AdminTools::extendSessionTokenExpire($sessionCode);
                }
            }
        }
    }

    //Called when some parameter (GET/POST) not meet the requirements
    public function raiseBadRequestError($info = 'Unknown')
    {
        Tools::log('Mobile Bad Request: <i>'.$info.'</i>');
        throw new CHttpException(400);
    }

    public function assureLogin()
    {
        if(!$this->user)
        {
            throw new CHttpException(401);
        }
    }

    public function response($obj = null, $returnObj = null)
    {
        //$returnObj only works when $obj is Exception/String

        header('Content-Type: application/json');
        if($obj)
        {
            if(is_a($obj, 'Exception'))
            {
                Tools::logException($obj);
                if($returnObj)
                {
                    $this->responseDirectly($returnObj);
                }
            }
            else if(is_string($obj))
            {
                Tools::log('Mobile API info: <i>'.$obj.'</i>');
                if($returnObj)
                {
                    $this->responseDirectly($returnObj);
                }
            }
            else if(is_array($obj))
            {
                if(count($obj) > 0)
                {
                    $this->responseDirectly($obj);
                }
            }
        }

        echo '{}';
        Yii::app()->end();
    }

    private function responseDirectly($obj)
    {
        array_walk_recursive($obj, function(&$item, $key) {
            if($item === null) $item = '';
            if(is_numeric($item)) $item = strval($item);
            if(is_bool($item)) $item = $item ? '1' : '0';
        });

        echo json_encode($obj);
        Yii::app()->end();
    }

}