<?php

//数据库创建和查询时   '-' 全用的'_'

//users
用户token：u-token
用户ID：u-id
用户名：u-name
用户学号：u-stud
用户学生卡照片：u-card
用户头像：u-icon
用户支付宝账号：u-alipay
用户是否已认证：u-authent
用户密码哈希：u-pwd
用户身份（普通用户/客服）：u-role
用户是否收藏某个店家：u-s-fav
用户相对坐标：u-coord
$sql = "INSERT INTO users (u_id,u_authent,u_coord_x,u_coord_y) VALUES (3,0,0.3,0.3)";
$sql = "CREATE TABLE users (
    u_token VARCHAR(30),
    u_id VARCHAR(30),
    u_name VARCHAR(30),
    u_stud VARCHAR(30),
    u_card VARCHAR(30),
    u_icon VARCHAR(30),
    u_alipay VARCHAR(30),
    u_authent bit(1),
    u_pwd VARCHAR(30),
    u_role VARCHAR(30),
    u_s_fav bit(1),
    u_coord_x FLOAT(24),
    u_coord_y FLOAT(24)
)";

//stores
店家ID：s-id
店家标志：s-icon
店家名称：s-name
店家配送时间：s-deliv
店家人均价格：s-price
店家评分：s-rating
店家商品数量：s-p-n
店家配送费：s-fee
店家起送费：s-price-min
店家第几条到第几条：s-from、s-to
$discount = serialize(array(30=>8,40=>15));  //满减用一个关联列表，序列化后存入数据库
$sql = "INSERT INTO stores (s_id,s_deliv,s_fee,s_discount) VALUES (2,400,20,$discount)";
$sql = "CREATE TABLE stores (
	s_id INT(30),
	s_icon VARCHAR(30),
	s_name VARCHAR(30),
	s_deliv INT(30),
	s_price VARCHAR(30),
	s_rating VARCHAR(30),
	s_p_n INT(30),
	s_fee INT(30),
	s_price_min INT(30),
	s_from INT(30),
	s_to INT(30),
	discount VARCHAR(80)
)";

//commodities 
商品ID：p-id
商品店家ID：p-s-id
商品店家名称：p-s-name
商品名：p-name
商品图片：p-pic
商品价格：p-price
$sql = "INSERT INTO commodities (p_id,p_s_id,p_s_name,p_name,p_pic,p_price) VALUES (2,1,'1','2','2',15)";
$sql = "CREATE TABLE commodities (
	p_id INT(30),
	p_s_id VARCHAR(30),
	p_s_name VARCHAR(30),
	p_name VARCHAR(30),
	p_pic VARBINARY(200),
	p_price VARCHAR(30)
)";

//orders
订单ID：o-id
订单用户ID：o-u-id
订单商家ID：o-s-id
订单商家名称：o-s-name
订单每种商品ID：o-p-id
订单每种商品的数量：o-p-n
订单对应的拼单ID：o-cb-id 
订单对应的拼单人数：o_cb_n
订单所需支付金额（拼单后）：o-price
订单原价：o-raw-price
订单用户是否确认支付：o-confirm
订单客服确认是否已支付：o-paid
$sql = "CREATE TABLE orders (
	o_id VARCHAR(30),
	o_u_id VARCHAR(30),
	o_s_id VARCHAR(30),
	o_s_name VARCHAR(30),
	o_p_ids VARCHAR(80),
	o_p_n VARCHAR(80),
	o_cb_id VARCHAR(30),
	o_cb_n INT(6),
	o_price FLOAT(24),
	o_raw_price FLOAT(24),
	o_confirm bit(1),
	o_paid bit(1),
)";

//cbs
拼单ID：cb-id
拼单店家ID：cb-s-id
拼单店家名称：cb-s-name
拼单的订单ID：cb-o-ids（用的时候应是复数）
拼单的订单数：cb-n
拼单是否成功：cb-status
拼单记录第几条到第几条：cb-from、cb-to
拼单的支付二维码：cb-pay
拼单的预期送达时间点：cb-done-time
拼单是否送达：cb-done
拼单送达的目的地聚集点ID：cb-g-id
$sql = "CREATE TABLE cbs (
	cb_id VARCHAR(30),
	cb_s_id VARCHAR(30),
    cb_s_name VARCHAR(30),
	cb_o_ids VARCHAR(80),
    cb_n INT(6),
    cb_status INT(2),
    cb_from INT(6),
    cb_to INT(6),
    cb_pay VARBINARY(200),
    cb_done_time INT(30),
    cb_done bit(1),
    cb_g_id INT(6),
	cb_start_time INT(20),
	cb_raw_price INT(20),
	cb_price INT(20)
)";

//grids
聚集点ID：g-id
聚集点相对坐标：g-coord
$sql = "INSERT INTO grids (g_id,g_coord_x,g_coord_y) VALUES (2,0,0)";
$sql = "CREATE TABLE grids (
    g_id INT(6),
    g_coord_x  FLOAT(24),
    g_coord_y  FLOAT(24)
)";


?>