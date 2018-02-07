<!-- main container -->
     <style>
           /* Icon coloring begin*/
          .icon-state-default {
         }
 
         .icon-state-success {
             color: #45b6af;
        }
 
         .icon-state-info {
             color: #89c4f4;
        }
 
        .icon-state-warning {
            color: #ecbc29;
         }

         .icon-state-danger {
             color: #f3565d;
         }
         /* Icon coloring end*/
     </style>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
     <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
     <script src="/assets/js/jstree/dist/jstree.min.js"></script> 
<div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper" class="users-list">
                    <div class="row-fluid header">
                        <h3>分类列表</h3>
                        <div class="span10 pull-right">
                            <a href="/index.php?r=admin%2Fcategory%2Fadd" class="btn-flat success pull-right">
                                <span>&#43;</span>添加新分类</a></div>
                    </div>
                    <!-- Users table -->
                    <div class="row-fluid table">
                        
              				    <div id="tree1"></div>
                                    
                            </tbody>
                        </table>
                        <?php if (yii::$app->session->hasFlash('info')) {
                            echo yii::$app->session->getFlash('info');
                        } ?>
                        <!--分页-->
                         <div class="pagination pull-right">
                        <?php echo yii\widgets\LinkPager::widget(['pagination'=>$pager,'prevPageLabel'=>"&#8249;",'nextPageLabel'=>'&#8250;']) ?>
                    </div>
                    </div>
                    <div class="pagination pull-right"></div>
                    <!-- end users table --></div>
            </div>
        </div>
        <!-- end main container -->

       <script>
    $(function(){
        $.get("<?php echo  yii\helpers\Url::to(['category/tree','page'=>$page,'per-page'=>$perpage]) ?>",null,function(data){
           var jsonData= data;
           inittree(jsonData);
        })
       function inittree(jsonData){
        //初始化jstree
        $("#tree1").jstree({
            "core": {
                "themes": {
                    "responsive": false
                },
                // so that create works
                "check_callback": true,
                //data为后台返回的数据,这里我先伪造一点数据
                'data': jsonData

                
            },
            //types表示文件类型,不同类型设置不同的样式 也就是icon的值
            "types": {
                "default": {
                    "icon": "fa fa-folder icon-state-warning icon-lg"
                },
                "file": {
                    "icon": "fa fa-file icon-state-warning icon-lg"
                }
            },
            //plugins 要使用的插件,jstree内部集成了一些插件比如 contextmenu:右键菜单
            "plugins": ["contextmenu", "dnd", "state", "types",'wholerow','search']
        });
       }
        $("#plugins10").jstree({
            "conditionalselect" : function (node, event) {
              return false;
            },
            "plugins" : [ "conditionalselect" ]
        });
    });
   
</script>
<script>
     
    
     var url='<?php echo yii\helpers\Url::to(['category/rename']) ?>';
     var csrfVal='<?php echo yii::$app->request->getCsrfToken(); ?>';
     var csrfParam='<?php echo yii::$app->request->csrfParam ?>';
     $("#tree1").on("rename_node.jstree",function(e,data){

        var postData={
            'id':data.node.id,
            'new':data.text,
            'old':data.old,
        };
        postData[csrfParam]=csrfVal;
        $.post(url,postData,function(res){
            //console.log(res);debugger;
            if (res==1) {
                alert('修改成功');
            }else{
                alert('修改失败');
            }
        })


        
    })
</script>


