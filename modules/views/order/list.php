<?php
    $this->title = '订单列表';
    $this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['/admin/order/list']];
    $this->params['breadcrumbs'][] = $this->title;
    $this->registerCssFile('admin/css/compiled/user-list.css');
?>
    <!-- main container -->
        <style>
            .product_list {
                display: none;
            }
        </style>
        <div class="container-fluid" style="margin-left: 200px;">
            <div id="pad-wrapper" class="users-list">
                <div class="row-fluid header">
                    <h3>订单列表</h3>
                </div>

                <!-- Users table -->
                <div class="row-fluid table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="span2 sortable">
                                    <span class="line"></span>订单编号
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>下单人
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>收货地址
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>快递方式
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>订单总价
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>联系电话
                                </th>
                                <th class="span3 sortable">
                                    <span class="line"></span>订单状态
                                </th>
                                <th class="span2 sortable align-right">
                                    <span class="line"></span>操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <!-- row -->
                        <?php foreach($orders as $order): ?>
                        <tr class="first">
                            <td>
                                <?php echo $order->ordersn ?>
                            </td>
                            <td>
                                <?php echo $order->name?>
                            </td>
                            <td>
                                <?php echo $order->address ?>
                            </td>
                            <td>
                                <?php echo $order->express ?>          
                            </td>
                            <td>
                                <?php echo $order->count ?>
                            </td>
                            <td>
                                <?php echo $order->phone; ?>
                            </td>
                            <td><?php

                                if ($order->order_status==0) {
                                    //未付款
                                    echo '未付款';
                                }elseif($order->order_status==1){
                                    //已付款
                                   echo '已付款';
                                }elseif($order->order_status==2){
                                    //已完成
                                    echo '已完成';
                                }else{
                                    echo 'error';
                                }
                            
                            ?></td>
                            
                            <td class="align-right">
                                <?php if ($order->order_status == 1): ?>
                                    <a href="<?php echo yii\helpers\Url::to(['order/send', 'orderid' => $order->orderid]) ?>">发货</a>
                                <?php endif; ?>
                               <!--  <a href="<?php echo yii\helpers\Url::to(['order/detail', 'orderid' => $order->orderid]) ?>">查看</a> -->
                                <a href="" class="order_product">查看</a>
                            </td>
                        </tr>
    
                            <tr>
                                <th class="product_list">商品列表</th>
                            </tr>
                            <tr class="product_list">
                                <th>商品id</th>
                                <th>购买价格</th>
                                <th>商品数量</th>
                                <th>商品名称</th>
                            </tr>
                            <?php foreach ($order->orderinfo as $key => $val): ?>
                            <tr class="product_list">
                                <td><?php echo $val['orderinfoid'] ?></td>
                                <td><?php echo $val['price'] ?></td>
                                <td><?php echo $val['num'] ?></td>
                                <td><?php echo $val['product_name'] ?></td>
                            </tr> 
                            <?php endforeach ?>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="pagination pull-right">
                    <?php echo yii\widgets\LinkPager::widget([
                        'pagination' => $pager,
                        'prevPageLabel' => '&#8249;',
                        'nextPageLabel' => '&#8250;',
                    ]) ?>
                </div>
                <!-- end users table -->
            </div>
        </div>
    <!-- end main container -->
    <script>
    $('.order_product').click(function(){
        $(".product_list").slideToggle();
        return false;
    })
    </script>