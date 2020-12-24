<?php
    class CommentHandler{
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
        
        //get-s-cms
        public function get_merchant_comment($u_id, $u_token, $s_id, $cm_from, $cm_to){
            $sql_comment = "SELECT cm_id, cm_u_id, cm_p_id, cm_rating, cm_content, cm_anonym
                            FROM comments
                            LIMIT cm_from, cm_to"
            $result_comment = $conn->query();
            $row_comment = $result_comment->fetch_assoc();
            $cm_id = $row_comment['cm_id'];
            $cm_u_id = $row_comment['cm_u_id'];
            $cm_p_id = $row_comment['cm_p_id'];
            $cm_rating = $row_comment['cm_rating'];
            $cm_content = $row_comment['cm_content'];
            $cm_anonym = $row_comment['cm_anonym'];

            $sql_user = "SELECT u_name, u_icon
                        FROM users
                        WHERE u_id = $cm_u_id"

            $result_user = $conn->query();
            $row_user = $result_user->fetch_assoc();
            $u_name = $row_user['u_name'];
            $u_icon = $row_user['u_icon'];

            $s_cms = array(
                'cm_id' => $cm_id;
                'cm_u_id' => $cm_u_id;
                'u_name' => $u_name;
                'u_icon' => $u_icon;
                'cm_p_ids' => $cm_p_id;
                'cm_rating' => $cm_rating;
                'cm_content' => $cm_content;
                'cm_anonym' => $cm_anonym;
            )

            echo json_encode($s_cms);
        }

        public function post_comment($u_id, $u_token, $cm_rating, $cm_content, $cm_anonym){
            $sql_order = "SELECT o_p_id
                        FROM orders
                        WHERE $u_id = o_u_id"//通过u_id找o_p_id
            $result_order = $conn->query();
            $row_order = $result_order->fetch_assoc();
            $p_id = $row_order['o_p_id'];

            $cm_id = (string)$u_id."".(string)time();

            $sql_comment = "INSERT INTO comments (cm_id, cm_u_id, cm_p_id, cm_rating, cm_content, cm_anonym)
                            VALUES ($cm_id, $u_id, $p_id, $cm_rating, $cm_content, $cm_anonym)"
        }
    }

    TsRoute::hook(
        'get_merchant_comment',
        array(
            'cm_id' => TS_ROUTE_KEY_POST,
            'cm_u_id' => TS_ROUTE_KEY_POST,
            'cm_p_id' => TS_ROUTE_KEY_POST,
            'cm_rating' => TS_ROUTE_KEY_POST,
            'cm_content' => TS_ROUTE_KEY_POST,
            'cm_anonym' => TS_ROUTE_KEY_POST,
            'u_name' => TS_ROUTE_KEY_POST,
            'u_icon' => TS_ROUTE_KEY_POST
        ),
        array('CommentHandler','get_merchant_comment')
    )

    TsRoute::hook(
        'post_comment',
        array(
            'o_p_id'
        ),
        array('CommentHandler','post_comment')
    )
?>