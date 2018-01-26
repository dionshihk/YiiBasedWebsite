<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $nickname
 * @property string $email
 * @property integer $is_male
 * @property string $password
 * @property string $avatar_url
 * @property string $introduction
 * @property string $reg_time
 * @property string $last_login_time
 * @property integer $account_status
 * @property integer $admin_status
 * @property string $admin_access_param
 * @property string $extra_data
 * @property string $mobile_data
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nickname, email, password, avatar_url, reg_time, last_login_time', 'required'),
			array('is_male, account_status, admin_status', 'numerical', 'integerOnly'=>true),
			array('nickname', 'length', 'max'=>20),
			array('email', 'length', 'max'=>45),
			array('password', 'length', 'max'=>32),
			array('introduction, admin_access_param, extra_data, mobile_data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nickname, email, is_male, password, avatar_url, introduction, reg_time, last_login_time, account_status, admin_status, admin_access_param, extra_data, mobile_data', 'safe', 'on'=>'search'),
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
			'nickname' => 'Nickname',
			'email' => 'Email',
			'is_male' => 'Is Male',
			'password' => 'Password',
			'avatar_url' => 'Avatar Url',
			'introduction' => 'Introduction',
			'reg_time' => 'Reg Time',
			'last_login_time' => 'Last Login Time',
			'account_status' => 'Account Status',
			'admin_status' => 'Admin Status',
			'admin_access_param' => 'Admin Access Param',
			'extra_data' => 'Extra Data',
			'mobile_data' => 'Mobile Data',
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
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('is_male',$this->is_male);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('avatar_url',$this->avatar_url,true);
		$criteria->compare('introduction',$this->introduction,true);
		$criteria->compare('reg_time',$this->reg_time,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('account_status',$this->account_status);
		$criteria->compare('admin_status',$this->admin_status);
		$criteria->compare('admin_access_param',$this->admin_access_param,true);
		$criteria->compare('extra_data',$this->extra_data,true);
		$criteria->compare('mobile_data',$this->mobile_data,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}