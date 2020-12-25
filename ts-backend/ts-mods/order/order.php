<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }

/**
 * Order Module
 *
 * @package Tsinghelp/Modules/Order
 */

class OrderHandler {

/**
 * get new order
 */	
	public static function new_order($args) {
		$u_id = $args['u-id'];
		$u_token = $args['u-token'];
		$s_id = $args['s-id'];
		$o_p_ids = $args['o-p-ids'];
		$o_p_n = $args['o-p-n'];

		$o_id = (string)$u_id."".(string)time();
		//查询订单原价
		$length=count($o_p_ids);
		$o_raw_price = 0;
		for($x=0;$x<$length;$x++)
		{
			$sql = "SELECT p_price FROM ts_products WHERE p_id=\'" . $ts_db->escape($o_p_ids[$x]) . "\' AND p_s_id=\'" . $ts_db->escape($s_id) . "\'";
			$result = $ts_db->query($sql);
			while($row = $result->fetch_assoc()) 
			{
				$o_raw_price += $row['p_price'] * $o_p_n[$x];
			}
		}
		//查询店家名称
		$sql = "SELECT * FROM ts_stores WHERE s_id=\'" . $ts_db->escape($s_id) . "\'";
		$result = $ts_db->query($sql);
		$row = $result->fetch_assoc();
		$s_name = $row['s_name'];
		$discount = unserialize($row['discount']);
		$o_p_ids = json_encode($o_p_ids);
		$o_p_n = json_encode($o_p_n);
		//新建订单插入数据库
		$index = 0;
		$o_price = $o_raw_price;
		foreach($discount as $price=>$price_minus)
		{	
			if ($o_raw_price < $price)
			{
				break;
			}
			$index = $price;
		}
		if($index != 0)
		{
			$o_price = $o_raw_price - $discount[$index];
		}
		$sql = "INSERT INTO 
		ts_orders (o_id,o_u_id,o_s_id,o_s_name,o_p_ids,o_p_n,o_price,o_raw_price,o_confirm,o_paid)
		VALUES (\'" . $ts_db->escape($o_id) . "\',\'" . $ts_db->escape($u_id) . "\',\'" . $ts_db->escape($s_id) . "\',\'" . $ts_db->escape($s_name) . "\',\'" . $ts_db->escape($o_p_ids) . "\',\'" . $ts_db->escape($o_p_n) . "\',\'" . $ts_db->escape($o_price) . "\',\'" . $ts_db->escape($o_raw_price) . "\',0,0)";
		$ts_db->exec($sql); 
		echo json_encode(array('msg' => $o_id ? 'ok' : 'failed'));
	}

/**
 * get new cb
 */	
	//开始拼单
	public static function get_cb($args){
		$u_id = $args['u-id'];
		$u_token = $args['u-token'];
		$o_id = $args['o-id'];

		$S = GetRandStr(40);
		//查询该订单是否已创建拼单
		$sql = "SELECT * FROM ts_orders WHERE o_id=$o_id";
		$result = $ts_db->query($sql);
		$row = $result->fetch_assoc();
		$s_id = $row['o_s_id'];
		$s_name = $row['o_s_name'];
		$o_raw_price = $row['o_raw_price'];
		$o_price = $row['o_price'];
		if ($row['o_cb_id']!=NULL)  //已创建订单 查询是否拼单成功
		{	
			$o_price = $row['o_price'];
			$cb_id = $row['o_cb_id'];
			$sql = "SELECT * FROM ts_cbs WHERE cb_id=$cb_id";
			$result = $ts_db->query($sql);
			$row = $result->fetch_assoc();
			$cb_start_time = $row['cb_start_time'];
			$cb_status = $row['cb_status'];
			$cb_n = $row['cb_n'];
			$cb_pay = $row['cb_pay'];
			$cb_raw_price = $row['cb_raw_price'];
			$cb_price = $row['cb_price'];

			if ($cb_status==1)  //成功
			{
				$result = array(
					'cb_id' => $cb_id,
					'cb_n' => $cb_n,
					'o_price' => $o_price,
					'cb_pay' => $cb_pay
				);
				echo $S."\n";
				echo json_encode($result)."\n";
				echo $S."\n";
			}
			elseif($cb_status==2)  //超时
			{
				echo $S."\n";
				echo json_encode('Timeout')."\n";
				echo $S."\n";
			}
			elseif($cb_status==0)  //拼单中
			{
				if(time()-$cb_start_time>600)
				{
					$sql = "UPDATE ts_cbs SET cb_status=2 WHERE cb_id=$cb_id";
					$ts_db->query($sql);
					echo $S."\n";
					echo json_encode('Timeout')."\n";
					echo $S."\n";
				}
				else
				{
					echo $S."\n";
					echo json_encode('Waiting')."\n";
					echo $S."\n";
				}
			}
		}
		else   //未创建拼单
		{	
			//查询用户点云位置
			$sql = "SELECT * FROM ts_users WHERE u_id=$u_id";
			$result = $ts_db->query($sql);
			$row = $result->fetch_assoc();
			$u_coord_x = $row['u_coord_x'];
			$u_coord_y = $row['u_coord_y'];
			//查询最近聚集点
			$min_dis = 2;
			$min_id = 1;
			$sql = "SELECT * FROM grids";
			$result = $ts_db->query($sql);
			while($row = $result->fetch_assoc()) 
			{
				$dis = sqrt(pow(($row['g_coord_x']-$u_coord_x),2) +pow(($row['g_coord_y']-$u_coord_y),2));
				if($dis<$min_dis)
				{
					$min_dis = $dis; 
					$min_id = $row['g_id'];
				}
			}
			//查询店家信息
			$sql = "SELECT * FROM stores WHERE s_id=$s_id";
			$result = $ts_db->query($sql);
			$row = $result->fetch_assoc();
			$s_fee = $row['s_fee']; 
			$s_deliv = $row['s_deliv']; 
			$discount = unserialize($row['discount']);
			//查询有无可加入的拼单
			$sql = "SELECT * FROM cbs WHERE cb_s_id=$s_id and cb_status=0 and cb_g_id=$min_id";
			$result = $ts_db->query($sql);
			if ($result->num_rows > 0) 
			{   //加入拼单
				$row = $result->fetch_assoc();
				$cb_id = $row['cb_id'];
				$cb_o_ids = json_decode($row['cb_o_ids']);
				array_push($cb_o_ids,$o_id);
				$cb_o_ids = json_encode($cb_o_ids);
				$cb_n = $row['cb_n'] + 1;
				$cb_raw_price = $row['cb_raw_price'] + $o_raw_price;
				$cb_price = $cb_raw_price;
				$index = 0;
				foreach($discount as $price=>$price_minus)
				{	
					if ($cb_raw_price < $price)
					{
						break;
					}
					$index = $price;
				}
				if($index != 0)
				{
					$cb_price = $cb_raw_price - $discount[$index];
				}		
				//拼单是否成功
				if($cb_n>2)
				{
					$cb_status=1;
					$cb_done_time = time() + $s_deliv;
				}
				else
				{
					$cb_status=0;
					$cb_done_time = 0;
				}
				//更新拼单信息
				$sql = "UPDATE cbs SET 
				cb_n=$cb_n,cb_status=$cb_status,cb_raw_price=$cb_raw_price,cb_o_ids='$cb_o_ids',cb_done_time=$cb_done_time,
				cb_price=$cb_price WHERE cb_id=$cb_id";
				$ts_db->query($sql); 
				//更新该订单信息
				$sql = "UPDATE orders SET o_cb_id=$cb_id WHERE o_id=$o_id";
				$ts_db->query($sql); 
				//更新每个订单价格
				$ratio = $cb_price / $cb_raw_price;
				$sql = "SELECT * FROM orders WHERE o_cb_id=$cb_id";
				$result = $ts_db->query($sql);
				while($row = $result->fetch_assoc()) 
				{
					$o_id = $row['o_id'];
					$o_raw_price = $row['o_raw_price'];
					$o_price = $o_raw_price * $ratio;
					$sql = "UPDATE orders SET o_cb_n=$cb_n,o_price=$o_price WHERE o_id=$o_id";
					$ts_db->query($sql);
				}	
			} 
			else   //没有拼单 新建拼单
			{	
				$cb_o_ids = json_encode(array($o_id)); 
				$t = time();
				$sql = "INSERT INTO cbs (cb_id,cb_s_id,cb_s_name,cb_o_ids,cb_n,cb_status,cb_g_id,cb_raw_price,cb_price,cb_start_time,cb_done)
				VALUES ($o_id,$s_id,$s_name,'$cb_o_ids',1,0,$min_id,$o_raw_price,$o_price,$t,0)";
				$ts_db->query($sql);
				$sql = "UPDATE orders SET o_cb_id=$o_id,o_cb_n=1 WHERE o_id=$o_id";
				$ts_db->query($sql); 
			}
			echo $S."\n";
			echo json_encode('new cb created')."\n";
			echo $S."\n";
		}	
	}


/**
 * confirm paid
 */	
	public static function confirm_o_pay($args){
		$u_id = $args['u-id'];
		$u_token = $args['u-token'];
		$o_id = $args['o-id'];

		global $ts_db;
		$sql = "SELECT * FROM ts_orders WHERE o_id=\'" . $ts_db->escape($o_id) . "\' LIMIT 1";
		$row = $ts_db->fetch1($sql);
		$arr = array('msg' => '', 'paid' => false);
		if (!$row) {
			$arr['msg'] = 'no such order';
		} else {
			$arr['msg'] = 'found';
			$arr['paid'] = (bool) $row['o_paid'];
		}
		echo json_encode($arr);
	}

/**
 * get deliver information
 */	
	public static function get_cb_deliv($args){
		$u_id = $args['u-id'];
		$u_token = $args['u-token'];
		$cb_id = $args['cb-id'];

		$sql = "SELECT * FROM ts_cbs WHERE cb_id=\'" . $ts_db->escape($cb_id) . "\' LIMIT 1";
		$row = $ts_db->fetch1($sql);
		$cb_done_time = $row['cb_done_time'];
		$cb_done = $row['cb_done'];
		$cb_g_id = $row['cb_g_id'];

		$result = array(
			'cb_done_time' => $cb_done_time,
			'cb_done' => $cb_done,
			'cb_g_id' => $cb_g_id
		);
		
		echo json_encode($result);
	}

/**
 * get cb history
 */	
	public static function get_cb_history($args){
		$u_id = $args['u-id'];
		$u_token = $args['u-token'];
		$from = (int) $args['from'];
		$to = (int) $args['to'];

		$S = GetRandStr(40);
		$to = $to - $from + 1;
		$sql = "SELECT * FROM orders WHERE o_u_id=\'" . $ts_db->escape($u_id) . "\' LIMIT $from,$to";
		$result = $ts_db->query($sql);
		$cb_history = array();
		while($row = $result->fetch_assoc()) 
		{	
			$s_id = $row['o_s_id'];
			$s_name = $row['o_s_name'];
			$o_price = $row['o_price'];
			$o_cb_n = $row['o_cb_n'];
			$o_p_ids = json_decode($row['o_p_ids']);
			$o_p_n = json_decode($row['o_p_n']);
			$cb = array();
			$commodity_list = array();
			$length = count($o_p_ids);
			//$pics=array();
			//$pic_num=0;
			for($x=0;$x<$length;$x++)
			{
				$p_id = $o_p_ids[$x];
				$sql = "SELECT * FROM commodities WHERE p_s_id=\'" . $ts_db->escape($s_id) . "\' and p_id=\'" . $ts_db->escape($p_id) . "\'";
				$result2 = $ts_db->query($sql);
				$row2 = $result2->fetch_assoc();
				$p_name = $row2['p_name'];
				$p_price = $row2['p_price'];
				//$p_pic = $row2['p_pic'];
				//array_push($pics,$p_pic);
				//$pic_num += 1;
				$p_n = $o_p_n[$x];
				$commodity = array();
				$commodity['p_id'] = $p_id;
				$commodity['p_name'] = $p_name;
				$commodity['p_price'] = $p_price;
				$commodity['p_n'] = $p_n;
				array_push($commodity_list, $commodity);
			}
			$cb['cb_s_id'] = $s_id;
			$cb['s_name'] = $s_name;
			$cb['o_price'] = $o_price;
			$cb['cb_n'] = $o_cb_n;
			$cb['ps'] = $commodity_list;
			array_push($cb_history, $cb);
		}
		//$cb_history['pic_num'] = $pic_num;
		//echo $S."\n";
		echo json_encode($cb_history);//echo "\n";
		//for($x=0;$x<$pic_num;$x++){
		//	echo $S."\n";
		//	echo $pics[$x]."\n";
		//}
		//echo $S."\n";
	}
};

function GetRandStr($length){
	//字符组合
	$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$len = strlen($str)-1;
	$randstr = '';
	for ($i=0;$i<$length;$i++) 
	{
	 $num=mt_rand(0,$len);
	 $randstr .= $str[$num];
	}
	return $randstr;
}

TsRoute::hook('new-order',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		's-id' => TS_ROUTE_KEY_POST,
		'o-p-ids' => TS_ROUTE_KEY_POST,
		'o-p-n' => TS_ROUTE_KEY_POST,
	),
	array('OrderHandler', 'new_order'),
);
TsRoute::hook('get-cb',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		'o-id' => TS_ROUTE_KEY_POST,
	),
	array('OrderHandler', 'get_cb'),
);
TsRoute::hook('confirm-o-pay',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		'o-id' => TS_ROUTE_KEY_POST,
	),
	array('OrderHandler', 'confirm_o_pay'),
);
TsRoute::hook('get-cb-deliv',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		'cb-id' => TS_ROUTE_KEY_POST,
	),
	array('OrderHandler', 'get_cb_deliv'),
);
TsRoute::hook('get-cb-history',
	array(
		'u-id' => TS_ROUTE_KEY_POST,
		'u-token' => TS_ROUTE_KEY_POST,
		'from' => TS_ROUTE_KEY_POST,
		'to' => TS_ROUTE_KEY_POST,
	),
	array('OrderHandler', 'get_cb_history'),
);


/** End of /ts-mods/order/order.php */
