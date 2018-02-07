<?php 
    use yii\bootstrap\ActiveForm;
    use yii\Helpers\Html;
    use yii\Helpers;
    use yii\grid\GridView;
?>

        <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                        <h3>管理员列表</h3>
                        <div class="span10 pull-right">
                            <a href="<?php echo yii\helpers\Url::to(['adduser']) ?>" class="btn-flat success pull-right">
                                <span>&#43;</span>添加新管理员</a></div>
                    </div>
                    <!-- Users table -->
                    <div class="row-fluid table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="span2">
                                        <span class="line"></span>用户名</th>
                                    <th class="span2">
                                        <span class="line"></span>用户邮箱</th>
                                    <th class="span3">
                                        <span class="line"></span>最后登录时间</th>
                                    <th class="span3">
                                        <span class="line"></span>性别</th>
                                    <th class="span2">
                                        <span class="line"></span>添加时间</th>
                                    <th class="span2">
                                        <span class="line"></span>用户昵称</th>
                                    <th class="span2">
                                        <span class="line"></span>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($users as $val):?>

                                <!-- row -->
                                <tr class="first">
                                    <td><img src="<?php if (empty($val['headimg'])) {
                                        echo yii::$app->params['defaultValue']['avatar'];
                                    }else{
                                        echo $val['headimg'];
                                        }?>" alt="" class="img-circle avatar hidden-phone"/>
                                    <a href="#" class="name"><?php echo $val['username']?></a>
                                        <span class="subtext"></span>
                                    </td>
                                    <td><?php echo $val['useremail']?></td>
                                    <td><?php echo date('Y-m-d H:i:s' ,$val['logintime'])?></td>
                                    <td><?php if ($val['profile']->sex) {
                                       echo $val->profile->sex==1?'男':'保密';
                                        }else{
                                            echo '女';}?></td>
                                    <td><?php echo date('Y-m-d H:i:s' ,$val['createtime'])?></td>
                                    <td><?php echo $val->profile->nickname;?></td> 

                                    <td class="align-right godel">
                                        <a href="" dataid="<?php echo yii\Helpers\Url::to(['userdel','userid'=>$val['userid']]) ?>">删除</a></td>
                                </tr>
                            <?php endforeach;?>

                            </tbody>
                        </table>
                        <?php  if (yii::$app->session->hasFlash('info')) {
                            echo yii::$app->session->getFlash('info');
                        } ?>
                    </div>
                    <script>

                        $(".godel a").click(function(){
                            var a=confirm("您是否要删除该用户？");
                            if (a==true) {
                                var href=$(this).attr('dataid');
                                window.location.href=href;
                                return false;
                            }
                        })
                      
                       
                    </script>
                    <div class="pagination pull-right">

                        <?php echo yii\widgets\Linkpager::widget(['pagination'=>$pager,'prevPageLabel'=>"&#8249;",'nextPageLabel'=>'&#8250;']) ?>
                        <!-- <ul>
                            <li><a href="">&#8249</a></li>
                            <li><a class="active" href="">1</a></li>
                            <li><a href="">2</a></li>
                            <li><a href="">3</a></li>
                            <li><a href="">4</a></li>
                            <li><a href="">&#8250</a></li>
                        
                        </ul> -->

                    </div>
                    <!-- end users table --></div>
            </div>
        </div>
        <!-- end main container -->
       