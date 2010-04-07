<?php

//---修改本文件请务必小心!并做好相应备份---
/*
配置说明:

SITE_URL        :   网站访问地址, 当您发生访问地址修改时修改, 必须带http://, 不要在末尾添加'/'

DB_CONFIG       :   数据库访问配置(协议://用户名:密码@数据库服务器地址:端口/数据库名)

DB_PREFIX       :   数据库表名前缀

LANG            :   字符集与语言

COOKIE_DOMAIN   :   网站Cookie作用域

COOKIE_PATH     :   网站Cookie作用路径

ECM_KEY         :   网站密钥

MALL_SITE_ID    :   网站ID, 不可修改

ENABLED_GZIP    :   GZIP开关,开启GZIP后将提升用户的访问速度, 相应地服务器的开销将增加.1为开启,0为关闭.

DEBUG_MODE      :   0: 生成缓存文件,不强制编译模板.1: 不生成缓存文件,不强制编译模板. 2: 生成缓存文件, 强制编译模板. 3: 不生成缓存文件, 强制编译模版. 4: 生成缓存, 编译模版但不生成编译文件. 5: 不生成缓存, 编译模版但不生成编译文件.

CACHE_SERVER    :   数据缓存服务器,可以是default(php文件缓存),也可以是memcache

MEMBER_TYPE     :   可选值: default(使用内置的用户系统),uc(使用UCenter做为用户系统), 也可以是任意的第三方系统, 前提是您做好了相关的扩展程序:)

ENABLED_SUBDOMAIN : 二级域名功能开关,0为关闭,1为开启,开启时必须配置SUBDOMAIN_SUFFIX.二级域名功能开启方法请查看安装包中docs目录下的二级域名配置相关文档.

SUBDOMAIN_SUFFIX : 二级域名后缀,例如:用户的二级域名将是"test.mall.example.com", 则您只需要在此填写"mall.example.com".
*/

return array (
  'SITE_URL' => 'http://127.0.0.1/myweb',
  'DB_CONFIG' => 'mysql://root:582020@localhost:3306/wallage_waimai',
  'DB_PREFIX' => 'wm_',
  'LANG' => 'sc-utf-8',
  'COOKIE_DOMAIN' => '',
  'COOKIE_PATH' => '/',
  'ECM_KEY' => 'f375a62e3f475e428aa720c9599c350d',
  'MALL_SITE_ID' => 'EMKEHBWSd6vDxnbS',
  'ENABLED_GZIP' => 0,
  'DEBUG_MODE' => 0,
  'CACHE_SERVER' => 'default',
  'MEMBER_TYPE' => 'default',
  'ENABLED_SUBDOMAIN' => 0,
  'SUBDOMAIN_SUFFIX' => '',
);

?>
