<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="body-content" >
    <h1>FROOGU</h1>

    <div class="row">
        <div class="col-lg-12 text-center">
<?php $form = ActiveForm::begin(['id' => 'search-form']); ?>
            <?= $form->field($model, 'query') ?>
            <div class="form-group ">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button']) ?>
            </div>
                <?php ActiveForm::end(); ?>
        </div>
            <?php if (isset($result['result'])) {
                ?>
            <table class="table table-striped"><tr>
            <?php foreach ($tableHeaders as $th) { ?>

                        <th><?= $th ?></th>  

    <?php }
    ?>
                </tr>
                    <?php foreach ($result['result'] as $v) { ?>
                    <tr>
                    <?php
                    foreach ($tableHeaders as $th) { ?>
                      <?php if (isset($v[$th])) { ?>
                        <td><?= $v[$th] ?></td>
                      <?php
                       
                          }
                       }
                    }
                    ?>
                </tr>
            </table>

<?php } else { 
    
    //no result for now
    if($result){echo "<pre>".print_r($result, TRUE)."</pre>";
    
    
    } 
if($output){
    //var_dump($output);
    //echo "<pre>".print_r($output, TRUE)."</pre>";
    
} } ?>                    
    </div>
</div>
