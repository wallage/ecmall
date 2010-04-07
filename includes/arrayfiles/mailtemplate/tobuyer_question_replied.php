<?php
return array (
  'version' => '1.0',
  'subject' => '{$site_name}提醒:{$user.user_name}您的商品咨询已得到回复',
  'content' => '<p>尊敬的用户:</p>
<p style="padding-left: 30px;">您好, 您在 {$site_name} 中的{$goods_name}咨询已得到回复，请点击下面的链接查看：</p>
<p style="padding-left: 30px;"><a href="{$site_url}/index.php?app=goods&act=qa&id={$id}&amp;ques_id={$ques_id}&amp;new=yes">{$site_url}/index.php?app=goods&act=qa&id={$id}&amp;ques_id={$ques_id}&amp;new=yes</a></p>
<p style="padding-left: 30px;"> 如果以上链接无法点击，请将它拷贝到浏览器(例如IE)的地址栏中。</p>
<p style="text-align: right;">{$site_name}</p>
<p style="text-align: right;">{$mail_send_time}</p>',
);
?>