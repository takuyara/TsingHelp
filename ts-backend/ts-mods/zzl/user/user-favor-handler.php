<?php

class UserFavorHandler
{
    public function __construct()
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
        $sql = "CREATE TABLE mydb.user_favor ( u_id INT NOT NULL ,  s_id INT NOT NULL ,    PRIMARY KEY  (u_id, s_id))";
        $conn->query($sql);
    }

    public static function handle_favor_store($u_id, $u_token, $s_id)
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

        $sql = "INSERT INTO user_favor (u_id, s_id) VALUES ($u_id, $s_id)";
        if ($conn->query($sql) === TRUE) {
            echo "successfully";
            echo "<br/>";
        } else {
            echo "错误: " . $conn->error;
        }
    }
}

TsRoute::hook(
    'handle_favor_store',
    array(
        'u_id' => TS_ROUTE_KEY_POST,
        'u_token' => TS_ROUTE_KEY_POST,
        's_id' => TS_ROUTE_KEY_POST,
    ),
    array('UserFavorHandler', 'handle_favor_store'),
);

/* End of /user-favor-handler.php */
