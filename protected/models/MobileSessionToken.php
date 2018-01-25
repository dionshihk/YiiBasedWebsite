<?php

/**
 * This is the model class for table "mobile_session_token".
 *
 * The followings are the available columns in table 'mobile_session_token':
 * @property string $token
 * @property string $user_id
 * @property string $create_client
 * @property string $create_time
 * @property string $expire_time
 */
class MobileSessionToken extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MobileSessionToken the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mobile_session_token';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('token, user_id, create_client, create_time, expire_time', 'required'),
			array('token', 'length', 'max'=>12),
			array('user_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('token, user_id, create_client, create_time, expire_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'token' => 'Token',
			'user_id' => 'User',
			'create_client' => 'Create Client',
			'create_time' => 'Create Time',
			'expire_time' => 'Expire Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('token',$this->token,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('create_client',$this->create_client,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('expire_time',$this->expire_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}