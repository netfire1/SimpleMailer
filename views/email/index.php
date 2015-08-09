<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\EmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список адресов';
$this->params['breadcrumbs'][] = 'Список адресов';
?>
<div class="email-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить email', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    
    
    
     <?php //Pjax::begin(['id'=>'myid']); ?>

        <?php $form = ActiveForm::begin(['action' => ['email/sendemail'], 'enableClientValidation' => true]); ?>
    
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '-'],
            'columns' => [
                ['class' => 'yii\grid\CheckboxColumn'],
                'email:email',
                'sent_date',
                ['value' => function($data){
	                return $data->changeStatus();
	            },
                            'label'=>'Статус', 'enableSorting' => true, ],
                            
                ['class' => 'yii\grid\ActionColumn',
                    //removes delete button
                    'template' => '{view}{create}',
                ],
            ],
        ]);
        ?>
        
        <?php //$form = ActiveForm::begin(['action' => ['email/sendemail']]); ?>


        <?php //ActiveForm::end();  ?>





        <div class="form-group">
            <?= $form->field($model, 'subject')->textInput(['maxlength' => 128, 'value' => 'test'])->label('Тема') ?>
            <?= $form->field($model, 'body')->textarea()->label('Сообщение') ?>
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>       
        </div>

        <?php ActiveForm::end(); ?>
<?php //Pjax::end(); ?>
    

</div>