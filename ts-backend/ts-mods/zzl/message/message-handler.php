<?php

class MessageHandler
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

        $sql = "CREATE TABLE mydb.msgs ( msg_id INT NOT NULL AUTO_INCREMENT ,  msg_from_u_id INT NULL DEFAULT NULL ,
            msg_to_u_id INT NOT NULL ,  msg_type TEXT NOT NULL ,  msg_text TEXT NULL DEFAULT NULL ,
                msg_pic LONGBLOB NULL DEFAULT NULL ,    PRIMARY KEY  (msg_id))";
        $conn->query($sql);
    }
    public static function handle_post_msg($u_id, $u_token, $msg_type, $msg_content)
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

        $sql = "SELECT COLUMNS FROM msg";
        $result = $conn->query($sql);
        echo $result;

        if ($msg_type === "text") {
            $msg_content = addslashes($msg_content);
            $sql = "INSERT INTO msgs (msg_id, msg_from_u_id, msg_to_u_id, msg_type, msg_text, msg_pic) 
                VALUES (NULL, NULL, '$u_id', '$msg_type', '$msg_content', NULL)";
            if ($conn->query($sql) === TRUE) {
                echo "successfully";
                echo "<br/>";
            } else {
                echo "错误: " . $conn->error;
            }
        } else if ($msg_type === "picture") {
            $sql = "INSERT INTO msgs (msg_id, msg_from_u_id, msg_to_u_id, msg_type, msg_text, msg_pic)
                VALUES (NULL, NULL, '$u_id', '$msg_type', NULL, $msg_content)";
            if ($conn->query($sql) === TRUE) {
                echo "successfully";
                echo "<br/>";
            } else {
                echo "错误: " . $conn->error;
            }
        }
    }

    public static function handle_get_msg($u_id, $u_token)
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

        //while(TRUE)
        {
            $sql = "SELECT * FROM msgs WHERE msg_to_u_id=$u_id";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $msg['msg_id'] = $row['msg_id'];
                $msg['msg_type'] = $row['msg_type'];
                if ($msg['msg_type'] === "text") {
                    $msg['msg_content'] = $row['msg_text'];
                    $str = get_rand_str(40);
                    echo $str . "<br>";
                    echo json_encode($msg) . "<br>";
                    echo $str . "<br>";
                    echo "<br>";
                } else if ($msg['msg_type'] === "picture") {
                    $str = get_rand_str(40);
                    echo $str . "<br>";
                    echo json_encode($msg) . "<br>";
                    echo $str . "<br>";
                    echo $row['msg_pic'] . "<br>";
                    echo $str . "<br>";
                    echo "<br>";
                }
            }
            $sql = "DELETE FROM msgs WHERE msg_to_u_id=$u_id";
            $result = $conn->query($sql);
            //sleep(5);
        }
    }
}

function get_rand_str($length)
{
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str) - 1;
    $randstr = '';
    for ($i = 0; $i < $length; $i++) {
        $num = mt_rand(0, $len);
        $randstr .= $str[$num];
    }
    return $randstr;
}

TsRoute::hook(
    'handle_post_msg',
    array(
        'u_id' => TS_ROUTE_KEY_POST,
        'u_token' => TS_ROUTE_KEY_POST,
        'msg_type' => TS_ROUTE_KEY_POST,
        'msg_content' => TS_ROUTE_KEY_POST,
    ),
    array('MessageHandler', 'handle_post_msg'),
);

TsRoute::hook(
    'handle_get_msg',
    array(
        'u_id' => TS_ROUTE_KEY_POST,
        'u_token' => TS_ROUTE_KEY_POST,
    ),
    array('MessageHandler', 'handle_get_msg'),
);

/* End of /message-handler.php */
