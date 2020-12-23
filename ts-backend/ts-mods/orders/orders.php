<?php

//新建订单
function new_order($u_id,$u_token,$s_id,$o_p_ids,$o_p_n){
	global $conn;

	$sql = "SELECT * FROM users WHERE u_id=$u_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if($row['u_authent']==0)
	{	
		echo json_encode("Unauthenticated");
	}
	else
	{	
		$o_id = (string)$u_id."".(string)time();
		//查询价格
		$length=count($o_p_ids);
		$o_raw_price = 0;
		for($x=0;$x<$length;$x++)
		{
			$sql = "SELECT p_price FROM commodities WHERE p_id=$o_p_ids[$x] AND p_s_id=$s_id";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()) 
			{
				$o_raw_price += $row['p_price'] * $o_p_n[$x];
			}
		}
		//查询店家名称
		$sql = "SELECT * FROM stores WHERE s_id=$s_id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$s_name = $row['s_name'];
		$discount = unserialize($row['discount']);
		//encode
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
		orders (o_id,o_u_id,o_s_id,o_s_name,o_p_ids,o_p_n,o_price,o_raw_price,o_confirm,o_paid)
		VALUES ($o_id,$u_id,$s_id,$s_name,'$o_p_ids','$o_p_n',$o_price,$o_raw_price,0,0)";
		$conn->query($sql); 
		echo json_encode($o_id);
	} 	
}

//开始拼单
function get_cb($u_id,$u_token,$o_id){
	global $conn;

	//查询该订单是否已创建拼单
	$sql = "SELECT * FROM orders WHERE o_id=$o_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$s_id = $row['o_s_id'];
	$s_name = $row['o_s_name'];
	$o_raw_price = $row['o_raw_price'];
	$o_price = $row['o_price'];
	if ($row['o_cb_id']!=NULL) 
	{	
		$o_price = $row['o_price'];
		$cb_id = $row['o_cb_id'];
		$sql = "SELECT * FROM cbs WHERE cb_id=$cb_id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$cb_start_time = $row['cb_start_time'];
		$cb_status = $row['cb_status'];
		$cb_n = $row['cb_n'];
		$cb_pay = $row['cb_pay'];
		$cb_raw_price = $row['cb_raw_price'];
		$cb_price = $row['cb_price'];

		if ($cb_status==1)
		{
			$result = array(
				'cb_id' => $cb_id,
				'cb_n' => $cb_n,
				'o_price' => $o_price,
				'cb_pay' => $cb_pay
			);
			echo json_encode($result);
		}
		elseif($cb_status==2)
		{
			echo json_encode('Timeout');
		}
		elseif($cb_status==0)
		{
			if(time()-$cb_start_time>600)
			{
				$sql = "UPDATE cbs SET cb_status=2 WHERE cb_id=$cb_id";
				$conn->query($sql);
				echo json_encode('Timeout');
			}
			else
			{
				echo json_encode('Waiting');
			}
		}
	}
	else
	{	
		//查询用户点云位置
		$sql = "SELECT * FROM users WHERE u_id=$u_id";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$u_coord_x = $row['u_coord_x'];
		$u_coord_y = $row['u_coord_y'];
		//查询最近聚集点
		$min_dis = 2;
		$min_id = 1;
		$sql = "SELECT * FROM grids";
		$result = $conn->query($sql);
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
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$s_fee = $row['s_fee']; 
		$s_deliv = $row['s_deliv']; 
		$discount = unserialize($row['discount']);
		//查询有无已有拼单
		$sql = "SELECT * FROM cbs WHERE cb_s_id=$s_id and cb_status=0 and cb_g_id=$min_id";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) 
		{   //已有拼单
			$row = $result->fetch_assoc();
			$cb_id = $row['cb_id'];
			$cb_o_ids = json_decode($row['cb_o_ids']);
			array_push($cb_o_ids,$o_id);
			$cb_o_ids = json_encode($cb_o_ids);
			echo $cb_o_ids;
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
			$conn->query($sql); 
			echo $conn->error;
			//更新该订单信息
			$sql = "UPDATE orders SET o_cb_id=$cb_id WHERE o_id=$o_id";
			$conn->query($sql); 
			//更新每个订单价格
			$ratio = $cb_price / $cb_raw_price;
			$sql = "SELECT * FROM orders WHERE o_cb_id=$cb_id";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()) 
			{
				$o_id = $row['o_id'];
				$o_raw_price = $row['o_raw_price'];
				$o_price = $o_raw_price * $ratio;
				$sql = "UPDATE orders SET o_cb_n=$cb_n,o_price=$o_price WHERE o_id=$o_id";
				$conn->query($sql);
			}	
		} 
		else   //没有拼单 新建拼单
		{	
			$cb_o_ids = json_encode(array($o_id)); 
			$t = time();
			$sql = "INSERT INTO cbs (cb_id,cb_s_id,cb_s_name,cb_o_ids,cb_n,cb_status,cb_g_id,cb_raw_price,cb_price,cb_start_time,cb_done)
			VALUES ($o_id,$s_id,$s_name,'$cb_o_ids',1,0,$min_id,$o_raw_price,$o_price,$t,0)";
			$conn->query($sql);
			echo $conn->error;
			$sql = "UPDATE orders SET o_cb_id=$o_id,o_cb_n=1 WHERE o_id=$o_id";
			$conn->query($sql); 
		}
	}	
}

function confirm_o_pay($u_id,$u_token,$o_id){
	global $conn;

	$sql = "SELECT * FROM orders WHERE o_id=$o_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	echo json_encode($row['o_paid']);
}

function get_cb_deliv($u_id,$u_token,$cb_id){
	global $conn;

	$sql = "SELECT * FROM cbs WHERE cb_id=$cb_id";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
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

function get_cb_history($u_id,$u_token,$from,$to){
	global $conn;
	$to = $to - $from;
	$sql = "SELECT * FROM orders WHERE o_u_id=$u_id LIMIT $from,$to";
	$result = $conn->query($sql);
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
		$pics=array();
		$pic_num=0;
		for($x=0;$x<$length;$x++)
		{
			$p_id = $o_p_ids[$x];
			$sql = "SELECT * FROM commodities WHERE p_s_id=$s_id and p_id=$p_id";
			$result2 = $conn->query($sql);
			$row2 = $result2->fetch_assoc();
			$p_name = $row2['p_name'];
			$p_price = $row2['p_price'];
			$p_pic = $row2['p_pic'];
			array_push($pics,$p_pic);
			$pic_num += 1;
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
	echo json_encode($cb_history);
	for($x=0;$x<$pic_num;$x++){
		echo $pics[$x];
		echo "<br />";
	}
}


?>
