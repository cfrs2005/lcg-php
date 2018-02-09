# lcg-php

莱茨狗 黑产服务端代码

目前看来  莱茨狗 开发速度较慢，不如外面的山寨

应该是团队rd 按自己的想法做的，目前看来 进度慢，简单的完善交易流程都有没有，投入有限，而且没什么可玩的。



还不如安心的去刷2个公信宝。 好歹1天也几块钱

推广注册链接：

https://blockcity.gxshares.org/#/?referUser=giTNP2MOZZPqUGTCVt10355330406 

尚未支持提现，不建议绑定 支付宝之类的 授权信息

可交易平台

https://big.one




# 增加品种提示

增加查询分页数量 ，增加查询类型

````
// sort 查询类型类型    
// 支持  "CREATETIME_DESC","CREATETIME_ASC","AMOUNT_ASC","AMOUNT_DESC"
define('SORT_TYPE','CREATETIME_DESC');

//目前只能到20
define('SIZE_PAGE',20)

````

# ocr -showapi 

增加验证码 识别率 超过 99% 

https://www.showapi.com/api/sku/184
注册付费 蛮贵的 我用1分钱 刷到一只狗


````
vim dog.php
define('SHOW_API_ID', '');
define('SHOW_API_SECRET', '');
define('ORC_FUNC', 'showApiOcr');

````

# ocr - baidu

采用百度ocr识别验证码,其实效果不好.

访问  https://console.bce.baidu.com  找到 文字识别 申请应用即可

申请地址
https://console.bce.baidu.com/ai/?_=1517904818639#/ai/ocr/overview/index

创建即可

500次/天免费


````
vim dog.php

//api相关内容
define('APP_ID', '');
define('API_KEY', '');
define('SECRET_KEY', '');
define('ORC_FUNC', 'idlOcr');
//配置
cookie="";
````

# 执行 php >= 5.6
````
php dog.php

2018-02-06 17:22:45	黑产 百度 pet-chain
2018-02-06 17:22:45	当前价格最大限制为:	1000
2018-02-06 17:22:45	1858369951695906444	价格:	2170.00
2018-02-06 17:22:45	1855391890091024066	价格:	2179.00
2018-02-06 17:22:45	1855391649572851410	价格:	2179.99
2018-02-06 17:22:45	1855390515701487655	价格:	2185.00
2018-02-06 17:22:45	1855391924450754966	价格:	2185.00
2018-02-06 17:22:45	1855391305975467756	价格:	2186.00
2018-02-06 17:22:45	1855445422563413857	价格:	2187.99
2018-02-06 17:22:45	1855391890091020576	价格:	2188.00
2018-02-06 17:22:45	1855391924450757306	价格:	2188.00
````


# 新版本
````
看心情

````