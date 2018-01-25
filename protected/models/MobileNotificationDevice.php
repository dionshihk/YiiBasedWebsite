<?php

/**
 * This is the model class for table "mobile_notification_device".
 *
 * The followings are the available columns in table 'mobile_notification_device':
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property integer $is_debug
 * @property string $reg_time
 * @property integer $device_type
 * @property string $param
 */
class MobileNotificationDevice extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MobileNotificationDevice the static model class
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
		return 'mobile_notification_device';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, token, reg_time, device_type', 'required'),
			array('is_debug, device_type', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>10),
			array('token', 'length', 'max'=>100),
			array('param', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, token, is_debug, reg_time, device_type, param', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'user_id' => 'User',
			'token' => 'Token',
			'is_debug' => 'Is Debug',
			'reg_time' => 'Reg Time',
			'device_type' => 'Device Type',
			'param' => 'Param',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('is_debug',$this->is_debug);
		$criteria->compare('reg_time',$this->reg_time,true);
		$criteria->compare('device_type',$this->device_type);
		$criteria->compare('param',$this->param,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}