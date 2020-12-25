<?php if (!defined('TS_DEV')) { header('HTTP/1.1 403 Forbidden'); die(); }
class MerchantHandler {
	private static function __gen_S() {
		static $__charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$__charset = str_shuffle($__charset);
		return substr($__charset, 0, 40);
	}

	//get-ss
	public static function get_merchant_outline_info($args){
		$s_from = (int) $args['s-from'];
		$s_to = (int) $args['s-to'];
		$left = $s_from - 1;
		$num = $s_to - $s_from + 1;
		$sql = "SELECT s_id, s_name, s_icon, s_deliv, s_price, s_rating, s_p_n FROM ts_stores LIMIT $left, $num";
		global $ts_db;
		$res = $ts_db->exec($sql);
		$ss = array();
		while ($res) {
			$row = $res->fetch_assoc();
			if (!$row)
				break;
			$s_id = $row['s_id'];
			$s_name = $row['s_name'];
			$s_icon = $row['s_icon'];
			$s_deliv = $row['s_deliv'];
			$s_price = $row['s_price'];
			$s_rating = $row['s_rating'];
			$s_p_n = $row['s_p_n'];
			$ss[] = array(
				's-id' => $s_id,
				's-name' => $s_name,
				's-icon' => $s_icon,
				's-deliv' => $s_deliv,
				's-price' => $s_price,
				's-rating' => $s_rating,
				's-p-n' => $s_p_n
			);
		}
		
		echo json_encode(array('ss' => $ss));
	}
	
	//get-s
	public static function get_merchant_info($args) {
		global $ts_db;
		$s_id = (int) $args['s-id'];
		$sql_store = "SELECT s_name, s_icon, s_deliv, s_price, s_rating, s_fee, s_price_min FROM ts_stores WHERE s_id = '" . $ts_db->escape($s_id) . " LIMIT 1'";
		$sql_products = "SELECT p_id, p_name, p_price FROM ts_products WHERE s_id='" . $ts_db->escape($s_id) . "'";

		$row_store = $ts_db->fetch1($sql_store);

		$s_name = $row_store['s_name'];
		$s_icon = $row_store['s_icon'];
		$s_deliv = $row_store['s_deliv'];
		$s_price = $row_store['s_price'];
		$s_rating = $row_store['s_rating'];
		$s_fee = $row_store['s_fee'];
		$s_price_min = $row_store['s_price_min'];

		$s_ps = array();
		$result_products = $ts_db->exec($sql_products);
		while ($result_products) {
			$row_products = $result_products->fetch_assoc();
			if (!$row_products)
				break;
			$p_id = $row_products['p_id'];
			$p_name = $row_products['p_name'];
			$p_price = $row_products['p_price'];

			/*$random_name = __gen_s();
			$location_array = array($random_name, '.jpg');
			$p_pic_location = implode('', $location_array);//文件的路径
			$p_pic_file = fopen($p_pic_location, "w");
			fwrite($p_pic_file,$p_pic);
			fclose($p_pic_file);*/

			$s_ps[] = array(
				'p_id' => $p_id,
				'p_name' => $p_name,
				'p_price' => $p_price
			);
		}
		

		$arr = array(//除了s_icon都在这个数组
			's-name' => $s_name,
			's-icon' => $s_icon,
			's-deliv' => $s_deliv,
			's-price' => $s_price,
			's-rating' => $s_rating,
			's-fee' => $s_fee,
			's-price-min' => $s_price_min,
			's-ps' => $s_ps
		);

		echo json_encode($arr);
	}
}

TsRoute::hook(
	'get-ss',
	array(
		'u-id' => TS_ROUTE_KEY_POST, 
		'u-token' => TS_ROUTE_KEY_POST, 
		's-from' => TS_ROUTE_KEY_POST, 
		's-to' => TS_ROUTE_KEY_POST, 
	),
	array('MerchantHandler','get_merchant_outline_info')
);

TsRoute::hook(
	'get-s',
	array(
		'u-id' => TS_ROUTE_KEY_POST, 
		'u-token' => TS_ROUTE_KEY_POST, 
		's-id' => TS_ROUTE_KEY_POST, 
	),
	array('MerchantHandler','get_merchant_info')
);
