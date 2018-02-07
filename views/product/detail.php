<?php
    use yii\bootstrap\ActiveForm;
    $this->title = "商品详情";
?>
<!-- ============================================================= HEADER : END ============================================================= -->		
<script type="text/javascript" src="assets/2e194b6e/jquery.min.js"></script>
<div id="single-product">
    <div class="container">

         <div class="no-margin col-xs-12 col-sm-6 col-md-5 gallery-holder">
    <div class="product-item-holder size-big single-product-gallery small-gallery">

        <div id="owl-single-product">
            <div class="single-product-gallery-item" id="slide1">
            <a data-rel="prettyphoto" href="<?php echo 'http://'.$product['cover'] ?>-big_style">
                <img class="img-responsive" alt="" src="<?php echo 'http://'.$product['cover'] ?>-big_style"  />
                </a>
            </div><!-- /.single-product-gallery-item -->
            <?php $i = 2 ?>
            <?php foreach((array)json_decode($product['pics'], true) as $k=>$pic): ?>
            <div class="single-product-gallery-item" id="slide<?php echo $i ?>">
                <a data-rel="prettyphoto" href="<?php echo 'http://'.$pic ?>-big_style">
                    <img class="img-responsive" alt="" style="display:none" src="<?php echo 'http://'.$pic ?>-big_style" />
                </a>
            </div><!-- /.single-product-gallery-item -->
            <?php $i++ ?>
            <?php endforeach; ?>
        </div><!-- /.single-product-slider -->


        <div class="single-product-gallery-thumbs gallery-thumbs">

            <div id="owl-single-product-thumbnails">
            <?php $i = 2 ?>
            <?php foreach((array)json_decode($product['pics'], true) as $k=>$pic): ?>
                <a class="horizontal-thumb" data-target="#owl-single-product" data-slide="<?php echo $i-1 ?>" href="#slide<?php echo $i ?>">
                    <img width="67" alt="" src="<?php echo 'http://'.$pic ?>-small_style"/>
                </a>
            <?php $i++; ?>
            <?php endforeach; ?>
            </div><!-- /#owl-single-product-thumbnails -->

            <div class="nav-holder left hidden-xs">
                <a class="prev-btn slider-prev" data-target="#owl-single-product-thumbnails" href="#prev"></a>
            </div><!-- /.nav-holder -->
            
            <div class="nav-holder right hidden-xs">
                <a class="next-btn slider-next" data-target="#owl-single-product-thumbnails" href="#next"></a>
            </div><!-- /.nav-holder -->

        </div><!-- /.gallery-thumbs -->

    </div><!-- /.single-product-gallery -->
</div><!-- /.gallery-holder -->        
        <div class="no-margin col-xs-12 col-sm-7 body-holder">
    <div class="body">
        <!--<div class="star-holder inline"><div class="star" data-score="4"></div></div>-->
        <div style="margin-top:30px"></div>
        <div class="title"><a href="#"><?php echo $product['title'] ?></a></div>
        <div class="availability" style="font-size:15px;margin:0;line-height:30px"><label>库存:</label><span class="available">  <?php echo $product['num'] ?></span></div>
        <!--<div class="excerpt">
        <p><?php echo $product['description'] ?></p>
        </div>-->
        
        <div class="prices">
        <?php if ($product['forsale']): ?>
        <div class="price-current">￥<?php echo $product['saleprice'] ?></div>
        <div class="price-prev">￥<?php echo $product['price'] ?></div>
        <?php else: ?>
        <div class="price-current">￥<?php echo $product['price'] ?></div>
        <?php endif; ?>
        </div>

        <div class="qnt-holder">
            <?php $form = ActiveForm::begin([
                'action' => yii\helpers\Url::to(['cart/add']),
            ]) ?>
            <div class="le-quantity">
                <a class="minus" href="#reduce"></a>
                <input name="productnum" readonly="readonly" id="cartid" type="text" value="1" />
                <a class="plus" href="#add"></a>
            </div>
            <input type="hidden" name="price" value="<?php echo $product['forsale'] == '1'?$product['saleprice']:$product['price'] ?>">
            <input type="hidden" name="productid" value="<?php echo $product['productid'] ?>">
            <input type='submit' id="addto-cart" class="le-button huge" value="加入购物车">
            <?php ActiveForm::end(); ?>
        </div><!-- /.qnt-holder -->
    </div><!-- /.body -->

</div><!-- /.body-holder -->
    </div><!-- /.container -->
</div><!-- /.single-product -->

<!-- ========================================= SINGLE PRODUCT TAB ========================================= -->
<section id="single-product-tab">
    <div class="container">
        <div class="tab-holder">
            
            <ul class="nav nav-tabs simple" >
                <li class="active"><a href="#descriptioniption" data-toggle="tab">商品详情</a></li>
            </ul><!-- /.nav-tabs -->

            <div class="tab-content">
                <div class="tab-pane active" id="descriptioniption">
                    <p><?php echo $product['description'] ?></p>
                </div><!-- /.tab-pane #descriptioniption -->

            </div><!-- /.tab-content -->

        </div><!-- /.tab-holder -->
    </div><!-- /.container -->
</section><!-- /#single-product-tab -->
<!-- ========================================= SINGLE PRODUCT TAB : END ========================================= -->
<!-- ========================================= RECENTLY VIEWED ========================================= -->

<section id="recently-reviewd" class="wow fadeInUp">
	<div class="container">
		<div class="carousel-holder hover">
			<div class="title-nav">
				<h2 class="h1">所有商品</h2>
				<div class="nav-holder">
					<a href="#prev" data-target="#owl-recently-viewed" class="slider-prev btn-prev fa fa-angle-left"></a>
					<a href="#next" data-target="#owl-recently-viewed" class="slider-next btn-next fa fa-angle-right"></a>
				</div>
			</div><!-- /.title-nav -->

			<div id="owl-recently-viewed" class="owl-carousel product-grid-holder">
            <?php foreach($data['all'] as $pro): ?>
                <div class="no-margin carousel-item product-item-holder size-small hover">
					<div class="product-item">
                        <?php if ($pro->ishot): ?>
                        <div class="ribbon red"><span>HOT</span></div> 
                        <?php endif; ?>
                        <?php if ($pro->forsale): ?>
                        <div class="ribbon green"><span>sale</span></div> 
                        <?php endif; ?>

						<div class="image">
                            <img alt="<?php echo $pro->title ?>" src="<?php echo 'http://'.$pro->cover ?>-middle_style" data-echo="<?php echo 'http://'.$pro->cover ?>-middle_style" />
						</div>
						<div class="body">
							<div class="title">
                            <a href="<?php echo yii\helpers\Url::to(['product/detail', 'productid' => $pro->productid]) ?>"><?php echo $pro->title ?></a>
							</div>
						</div>
						<div class="prices">
                        <div class="price-current text-right">￥<?php echo $pro->saleprice ?></div>
						</div>
						<div class="hover-area">
							<div class="add-cart-button">
                                <a href="<?php echo yii\helpers\Url::to(['cart/add', 'productid' => $pro->productid]) ?>" class="le-button">加入购物车</a>
							</div>
						</div>
					</div><!-- /.product-item -->
				</div><!-- /.product-item-holder -->
                <?php endforeach; ?>
			</div><!-- /#recently-carousel -->

		</div><!-- /.carousel-holder -->
	</div><!-- /.container -->
</section><!-- /#recently-reviewd -->
<!-- ========================================= RECENTLY VIEWED : END ========================================= -->		<!-- ============================================================= FOOTER ============================================================= -->

<script>
    $(".minus").click(function(){    
   var num = parseInt($("input[name=productnum]").val()) - 1;  
   if (parseInt($("input[name=productnum]").val()) <= 1) {    
       var num = 1;    
   } 
   $("input[name=productnum]").val(num);     
   /*if (parseInt($("input[name=productnum]").val()) <= 1) {    
       var num = 1;    
   }    
   var total = parseFloat($(".value.pull-right span").html());    
   var price = parseFloat($(".price span").html());   
   //changeNum(cartid, num); 
  
   var p = total - price;    
   if (p < 0) {    
       var p = "0";    
   }    
   $(".value.pull-right span").html(p + "");    
   $(".value.pull-right.ordertotal span").html(p + "");    */
});    
$(".plus").click(function(){ 
    var num = parseInt($("input[name=productnum]").val()) + 1;    
    $("#cartid").val(num);
 /*
   var cartid = $("input[name=productnum]").attr('id'); 
   var num = parseInt($("input[name=productnum]").val()) + 1;    
   var total = parseFloat($(".value.pull-right span").html());    
   var price = parseFloat($(".price span").html());    
   changeNum(cartid, num);    
   var p = total + price;    
   $(".value.pull-right span").html(p + "");    
   $(".value.pull-right.ordertotal span").html(p + ""); */   
});    
function changeNum(cartid, num)    
{    
   $.get('$url', {'productnum':num, 'cartid':cartid}, function(data){    
       location.reload();    
   });    
}    

</script>