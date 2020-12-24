<?php

class AuthentHandler
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

        $sql = "CREATE TABLE mydb.users_to_be_authent ( u_id INT NOT NULL ,  u_token TEXT NOT NULL ,  u_stud INT NOT NULL ,
            u_card LONGBLOB NOT NULL ,    PRIMARY KEY  (u_id))";
        $conn->query($sql);
    }
    public static function handle_authenticate($u_id, $u_token, $u_stud, $u_card)
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

        $sql = "INSERT INTO users_to_be_authent (u_id, u_token, u_stud, u_card) VALUES ('$u_id', '$u_token', '$u_stud', '$u_card')";
        if ($conn->query($sql) === TRUE) {
            echo "successfully";
            echo "<br/>";
        } else {
            echo "错误: " . $conn->error;
        }
    }
}

TsRoute::hook(
    'handle_authenticate',
    array(
        'u_id' => TS_ROUTE_KEY_POST,
        'u_token' => TS_ROUTE_KEY_POST,
        'u_stud' => TS_ROUTE_KEY_POST,
        'u_card' => TS_ROUTE_KEY_POST,
    ),
    array('AuthentHandler', 'handle_authenticate'),
);

/* End of /authent-handler.php */
