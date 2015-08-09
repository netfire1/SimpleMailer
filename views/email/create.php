 <?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Email */

$this->title = 'Добавить email';
$this->params['breadcrumbs'][] = ['label' => 'Список адресов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
