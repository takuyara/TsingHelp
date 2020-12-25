<?php
//header('content-type:image/jpeg');
header('content-type:application/json');
class MapHandler
{

    var $_m_ver;//地图版本
    var $_m_anchor;//四角绝对坐标
    var $_m_upd;//地图是否更新
    var $_m_img;//地图图片
    

  function __construct() {
      $this->_m_anchor=array("westnorth"=>array(116.321,40.018),//西北
                            "eastnorth"=>array(116.344,40.018),      //东北
                            "eastsouth"=>array(116.344,39.9985),     //东南
                            "westsouth"=>array(116.321,39.9985) );   //西南四角绝对坐标
      $this->_m_ver="1.0";
      $filename='map.jpg';
      $handle=fopen($filename,'rb+'); //读写二进制，图片的可移植性
      $this->_m_img=fread($handle,filesize($filename));
      fclose($handle);
  }

  public static function handel_get_map_info(){                      //获取地图信息
    $_m_ver=1.0;
    $_m_anchor=array("westnorth"=>array(116.321,40.018),//西北
                            "eastnorth"=>array(116.344,40.018),      //东北
                            "eastsouth"=>array(116.344,39.9985),     //东南
                            "westsouth"=>array(116.321,39.9985) );   //西南四角绝对坐标
    $_m_img='map.jpg';
    echo $_m_ver,"\n" ;
    
    echo json_encode($_m_anchor),"\n";
    
    echo $_m_img;
  }
  

}

//MapHandler::handel_get_map_info();

?> 