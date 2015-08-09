<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property integer $id
 * @property string $email
 * @property string $sent_date
 * @property integer $status
 */
class Email extends \yii\db\ActiveRecord
{

    public $subject;
    public $body;
    public $selection;
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['status'], 'integer'],
            [['email'], 'email'],
            [['selection'], 'safe'],
            [['sent_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'sent_date' => 'Дата отправки',
            'status' => 'Статус',
        ];
    }

    public function changeStatus()
    {
        $query = Email::findOne($this->id);
        if ($query->status == 0)
        {
            return 'Не отправлялось';
        } elseif ($query->status == 1)
        {
            return 'Отправлено';
        } else
        {
            return 'Ошибка отправки';
        }
    }


}
