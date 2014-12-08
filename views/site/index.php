<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="body-content" >
    <h1>FROOGU</h1>

    <div class="row">
        <div class="col-lg-12 text-center mail-search">
            <?php $form = ActiveForm::begin(['id' => 'search-form']); ?>
            <?= $form->field($model, 'query', [
  'template' => "\n{input}\n"
])->textInput(array('placeholder' => 'Enter a product name', 'class'=>'form-control search-bar')); ?>
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary submitButton', 'name' => 'search-button']) ?>
            <?= $form->field($model, 'query', [
  'template' => "<i class='fa fa-user cerror'>\n\n{hint}\n{error}</i>\n\n</i>"
])->textInput(array('placeholder' => 'Enter a product name', 'class'=>'form-control search-bar')); ?>
            <?php ActiveForm::end(); ?>
        </div>


        <?php if ($result && !is_string($result)) { 
      //  echo print_r($result ); exit();
?>
            <table class="table table-striped ctable"><tr>
                    <?php
                    $i = 0;
                    foreach ($tableHeaders as $k => $v) {
                        ?>
                    <th class="description-field"><?= $v ?></th>  

        <?php $i++;
    } ?>

                </tr>
                <?php
                reset($result);
                
                while (list($var1, $val1) = each($result)) {
                    reset($val1);
                    echo "<tr>";
                    while (list($var2, $val2) = each($val1)) {
                        foreach ($tableHeaders as $key => $value) {
                            if ($var2 == $key) {
                                echo '<td class=\'description-field\'>' . $val2 . "</td>";
                            }
                        }
                    }
                    echo "</tr>";
                }
                ?>






            </table>

    <?php
} else {

    if ($tableHeaders) {
        //var_dump($output);
        //echo "<pre>".print_r($tableHeaders, TRUE)."</pre>";
    }
    if ($result) {
        echo "<pre>" . print_r($result, TRUE) . "</pre>";
    }
}



?>                    
    </div>
</div>
