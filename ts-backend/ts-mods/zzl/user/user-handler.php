<?php

class UserHandler
{
    public static function handel_get_u_info($u_id, $u_token)
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

        $sql = "SELECT u_name, u_alipay, u_stud, u_authent FROM users WHERE u_id=$u_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        $str = get_rand_str(40);

        echo $str . "<br>";
        echo json_encode($row) . "<br>";
        echo $str . "<br>";

        $sql = "SELECT u_icon FROM users WHERE u_id=$u_id";
        $result = $conn->query($sql);
        echo $result->fetch_assoc()['u_icon'] . "<br>";
        echo $str . "<br>";

        echo "<br>";
    }
}

/* End of /user-handler.php */
