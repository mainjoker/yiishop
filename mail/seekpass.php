<p>尊敬的<?php echo $adminuser?></p>

<p>您的找回密码的链接如下</p>
<?php $url= yii::$app->urlManager->createAbsoluteUrl(['admin/manage/mailchangepass','tamp'=>$time,
'adminuser'=>$adminuser,'token'=>$token])?>
<p><a href="<php echo $url>"><?php echo $url; ?></a></p>

<p>该链接5分钟内有效</p>


