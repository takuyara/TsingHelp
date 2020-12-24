<?php
    class MerchantHandler {
        global $conn;
        
        private function __gen_S() {
            static $__charset = array(

                'a','b','c','d','e','f','g','h','i','j','k','l','m',

                'n','o','p','q','r','s','t','u','v','w','x','y','z',

                'A','B','C','D','E','F','G','H','I','J','K','L','M',

                'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',

                '0','1','2','3','4','5','6','7','8','9'

            );
            return array_rand($__charset,40);
        }

        //get-ss
        public function get_merchant_outline_info($u_id, $u_token, $s_from, $s_to){
            $sql = "SELECT s_name, s_icon, s_deliv, s_price, s_rating, s_p_n
                    FROM stores
                    LIMIT $s_from - 1, $s_to - $s_from + 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $s_name = $row['s_name'];
            $s_icon = $row['s_icon'];
            $s_deliv = $row['s_deliv'];
            $s_price = $row['s_price'];
            $s_rating = $row['s_rating'];
            $s_p_n = $row['s_p_n'];
            
            $ss = array(
                    's_name' => $s_name,
                    's_icon' => $s_icon,
                    's_deliv' => $s_deliv,
                    's_price' => $s_price,
                    's_rating' => $s_rating,
                    's_p_n' => $s_p_n
            );
        
            echo json_encode($ss);
        }
        
        //get-s
        public function get_merchant_info($u_id, $u_token, $s_id){
            $sql_store = "SELECT s_name, s_icon, s_deliv, s_price, s_rating, s_fee, s_price_min
                    FROM stores
                    WHERE s_id = $s_id";
            $sql_user = "SELECT u_s_fav 
                    FROM users 
                    WHERE u_id = $u_id";
            $sql_commodity = "SELECT p_id, p_name, p_pic, p_price 
                    FROM commodities 
                    WHERE p_s_id = $s_id"

            $result_store = $conn->query($sql_store);
            $result_user = $conn->query($sql_user);
            $result_commodity = $conn->query($sql_commodity);

            $row_store = $result_store->fetch_assoc();
            $s_name = $row_store['s_name'];
            $s_icon = $row_store['s_icon'];
            $s_deliv = $row_store['s_deliv'];
            $s_price = $row_store['s_price'];
            $s_rating = $row_store['s_rating'];
            $s_fee = $row_store['s_fee'];
            $s_price_min = $row_store['s_price_min'];

            $row_user = $result_user->fetch_assoc();
            $u_s_fav = $row_user['u_s_fav'];
            
            $row_commodity = $result_commodity->fetch_assoc();
            $p_id = $row_commodity['p_id'];
            $p_pic = $row_commodity['p_pic'];
            $p_name = $row_commodity['p_name'];
            $p_price = $row_commodity['p_price'];
            
            $random_name = __gen_s();
            $location_array = array($random_name, '.jpg');
            $p_pic_location = implode('', $location_array);//文件的路径
            $p_pic_file = fopen($p_pic_location, "w");
            fwrite($p_pic_file,$p_pic);
            fclose($p_pic_file);

            $s_ps = array(
                'p_id' => $p_id;
                'p_pic' => $p_pic_location;
                'p_name' => $p_name;
                'p_price' => $p_price;
            );

            $arr = array(//除了s_icon都在这个数组
                's_name' => $s_name; 
                's_deliv' => $s_deliv;
                's_price' => $s_price;
                's_rating' => $s_rating;
                's_fee' => $s_fee;
                's_price_min' => $s_price_min;
                'u_s_fav' => $u_s_fav;
                's_ps' => $s_ps;
            );

            $S = __gen_S();
            echo $S, "\n", json_encode($arr), "\n", $s_icon, "\n", $S, "\n";
        }
    }

    TsRoute::hook(
        'get_merchant_outline_info',
        array(
            's_name' => TS_ROUTE_KEY_POST, 
            's_icon' => TS_ROUTE_KEY_POST, 
            's_deliv' => TS_ROUTE_KEY_POST, 
            's_price' => TS_ROUTE_KEY_POST, 
            's_rating' => TS_ROUTE_KEY_POST, 
            's_p_n' => TS_ROUTE_KEY_POST
        ),
        array('MerchantHandler','get_merchant_outline_info')
    )

    TsRoute::hook(
        'get_merchant_info',
        array(
            's_name' => TS_ROUTE_KEY_POST, 
            's_icon' => TS_ROUTE_KEY_POST, 
            's_deliv' => TS_ROUTE_KEY_POST, 
            's_price' => TS_ROUTE_KEY_POST, 
            's_rating' => TS_ROUTE_KEY_POST, 
            's_fee' => TS_ROUTE_KEY_POST,
            's_price_min' => TS_ROUTE_KEY_POST,
            'u_s_fav' => TS_ROUTE_KEY_POST,
            'p_id' => TS_ROUTE_KEY_POST, 
            'p_name' => TS_ROUTE_KEY_POST, 
            'p_pic' => TS_ROUTE_KEY_POST,
            'p_price' => TS_ROUTE_KEY_POST
        ),
        array('MerchantHandler','get_merchant_info')
    )
?>