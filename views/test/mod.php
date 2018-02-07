 <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="new-user">
                    <div class="row-fluid header">
                        <h3>添加新分类</h3></div>
                    <div class="row-fluid form-wrapper">
                        <!-- left column -->
                        <div class="span9 with-sidebar">
                            <div class="container">
                                <?php $form=yii\bootstrap\activeForm::begin(
                                    ['fieldConfig'=>[
                                        'template'=>'{input}{error}',
                                    ]]
                                ) ?>
                                <?php echo $form->field($model,'id')->textInput(['disabled'=>'true']) ?>

                                <?php echo $form->field($model,'name')->textInput(['placeholder'=>'请输入姓名']) ?>
                                <div> 
                                    <?php if (yii::$app->session->hasFlash('info')) {
                                        echo yii::$app->session->getFlash('info');
                                    } ?>
                                </div>
                               
                                <div class="span11 field-box actions">  
                                    <?php echo yii\helpers\Html::submitButton('添加',['class'=>'btn-glow primary']) ?>
                                    <span>OR</span>
                                    <?php echo yii\helpers\Html::resetButton('取消',['class'=>'reset']) ?>
                                <?php $form->end() ?>
                                </div>
                              
                            </div>
                        </div>
                        <!-- side right column -->
                        <div class="span3 form-sidebar pull-right">
                            <div class="alert alert-info hidden-tablet">
                                <i class="icon-lightbulb pull-left"></i>请在左侧表单当中填写要添加的分类，请选择好上级分类</div>
                            <h6>商城分类说明</h6>
                            <p>该分类为无限级分类</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end main container -->