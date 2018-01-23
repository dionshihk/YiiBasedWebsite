<?php

class UserIdentity extends CUserIdentity
{
	private $_id;
	
    public function authenticate()
    {
        $recordId = Tools::authUser($this->username, $this->password);
        
        if($recordId === 0)
        {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        else 
        {
         	$this->errorCode = self::ERROR_NONE;
         	$this->_id = $recordId;
        }
      
        return !$this->errorCode;
    }
 
  	public function getId()
  	{
  		return (int)$this->_id;
  	}
}
?>