<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Email;

/**
 * Description of EmailSend
 *
 * @author user
 */
class EmailSend extends Email {

    //public $subject;
    //public $body;
    //public $selection;

    public function rules() {
        return [
            [['subject'], 'required', 'message' => 'Укажите тему!'],
            [['body'], 'required', 'message' => 'Сообщение не может быть пустым!'],
            [['selection'], 'safe'],
            [['selection'], 'required'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**

     */
    public function send($id) {
        $query = Email::findOne($id);
        //var_dump($query->email);
        //var_dump($this->subject);$query = Email::findOne($id);
        //var_dump($this->body);
        //exit();

        return Yii::$app->mailer->compose()
                        ->setTo($query->email)
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setSubject($this->subject)
                        ->setTextBody($this->body)
                        ->send();
    }
    
    

}
