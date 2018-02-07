  <!-- main container -->
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                        <h3>商品列表</h3>
                        <div class="span10 pull-right">
                            <a href="<?php echo yii\helpers\Url::to(['/product/add']) ?>" class="btn-flat success pull-right">
                                <span>&#43;</span>添加新商品</a></div>
                    </div>
                    <!-- Users table -->
                    <div class="row-fluid table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="span6 sortable">
                                        <span class="line"></span>商品名称</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>商品库存</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>商品单价</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否热卖</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否促销</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>促销价</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否上架</th>
                                    <th class="span2 sortable">
                                        <span class="line"></span>是否精品</th>
                                    <th class="span3 sortable align-right">
                                        <span class="line"></span>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- row -->
                                <?php foreach($model as $val): ?>
                                <tr class="first">
                                    <td>
                                        <img src="<?php echo 'http://'.$val['cover'].'-small_style' ?>" class="img-circle avatar hidden-phone" />
                                        <a href="#" class="name"><?php echo $val['name'] ?></a></td>
                                    <td><?php echo $val['num'] ?></td>
                                    <td><?php echo $val['price'] ?></td>
                                    <td><?php echo $val['ishot']==1?'热卖':'不热卖' ?></td>
                                    <td><?php echo $val['forsale']==1?'促销':'不促销' ?></td>
                                    <td><?php echo $val['saleprice'] ?></td>
                                    <td><?php echo $val['onsale']==1?'上架':'不上架' ?></td>
                                    <td><?php echo $val['isbest']==1?'精品':'不是精品' ?></td>
                                    <td class="align-right">
                                        <a href="<?php echo yii\helpers\Url::to(['product/mod','id'=>$val['productid']]) ?>">编辑</a>
                                        <a href="<?php echo yii\helpers\Url::to(['product/on','productid'=>$val['productid']]) ?>">上架</a>
                                        <a href="<?php echo yii\helpers\Url::to(['product/off','productid'=>$val['productid']]) ?>">下架</a>
                                        <a href="<?php echo yii\helpers\Url::to(['product/del','productid'=>$val['productid']]) ?>">删除</a></td>
                                </tr>

                               <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pull-right"></div>
                    <!-- end users table --></div>
            </div>
        </div>
        <!-- end main container -->