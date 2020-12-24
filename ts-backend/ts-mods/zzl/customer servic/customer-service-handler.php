<?php

class CustomerServiceHandler
{
    public static function handle_upload_pay($u_id, $u_token, $cb_id, $cb_pay)
    {
        $servername = "localhost";
        $username = "root";
        $password = "123";
        $dbname = "myDB";

        // 创建连接
        $conn = new mysqli($servername, $username, $password, $dbname);
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }
        echo "连接成功\n";
        echo "<br />";

        $sql = "UPDATE cbs SET cb_pay='$cb_pay' WHERE cb_id='$cb_id'";
        if ($conn->query($sql) === TRUE) {
            echo "successfully";
            echo "<br/>";
        } else {
            echo "错误: " . $conn->error;
        }
    }
}

TsRoute::hook(
    'handle_upload_pay',
    array(
        'u_id' => TS_ROUTE_KEY_POST,
        'u_token' => TS_ROUTE_KEY_POST,
        'cb_id' => TS_ROUTE_KEY_POST,
        'cb_pay' => TS_ROUTE_KEY_POST,
    ),
    array('CustomerServiceHandler', 'handle_upload_pay'),
);

/* End of /customer-service-handler.php */
