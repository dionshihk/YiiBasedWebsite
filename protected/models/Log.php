<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property string $id
 * @property string $user_id
 * @property string $user_name
 * @property string $user_agent
 * @property string $url
 * @property string $ip
 * @property string $log
 */
class Log extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Log the static model class
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
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_agent, url, ip, log', 'required'),
			array('user_id', 'length', 'max'=>10),
			array('ip', 'length', 'max'=>30),
			array('user_name', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, user_name, user_agent, url, ip, log', 'safe', 'on'=>'search'),
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
			'user_name' => 'User Name',
			'user_agent' => 'User Agent',
			'url' => 'Url',
			'ip' => 'Ip',
			'log' => 'Log',
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
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('log',$this->log,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}