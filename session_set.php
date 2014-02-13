<?php
include_once 'opmysql.php';
class MySessionHandler implements SessionHandlerInterface {
    /**
    * @access private
    * @var object 数据库连接
    */
    private $_dbLink;
    /**
    * @access private
    * @var string 保存session的表名
    */
    Private $_sessionTable;
    /**
    * @access private
    * @var string session名
    */
    private $_sessionName;
    /**
    * @const 过期时间
    */
   const SESSION_EXPIRE = 180;
    public function __construct($dbLink, $sessionTable) {
        if(!is_object($dbLink)) {
            return false;
        }
        $this->_dbLink = $dbLink;
        $this->_sessionTable = $sessionTable;
  }
    /**
    * 打开
    * @access public
    * @param string $session_save_path 保存session的路径
    * @param string $session_name session名
    * @return integer
    */
    public function open($session_save_path, $session_name) {
        $this->_sessionName = $session_name;
        return 0;
        $this->gc($maxlifetime);
    }
    /**
    * 关闭
    * @access public
    * @return integer
    */
    public function close() {
        return 0;
    }
    /**
    * 关闭session
    * @access public
    * @param string $session_id session ID
    * @return string
    */
    public function read($session_id) {
        $query = "SELECT value FROM {$this->_sessionTable} WHERE sid = '{$session_id}' AND UNIX_TIMESTAMP(expiration) + " . self::SESSION_EXPIRE . " > UNIX_TIMESTAMP(NOW())";
        $result = $this->_dbLink->getRowsNum($query);
        if(!isset($value) || empty($value)) {
            $value = "";
            return $value;
        }
        $this->_dbLink->uidRst("UPDATE {$this->_sessionTable} SET expiration = CURRENT_TIMESTAMP() WHERE sid = '{$session_id}'");
        $value = $result->fetch_array();
        $result->free();
        return $value['value'];
        $this->gc($maxlifetime);
    }
 
    /**
    * 写入session
    * @access public
    * @param string $session_id session ID
    * @param string $session_data session data
    * @return integer
    */
    public function write($session_id, $session_data) {
        $query = "SELECT value FROM {$this->_sessionTable} WHERE sid = '{$session_id}' AND UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE." > UNIX_TIMESTAMP(NOW())";
        $result = $this->_dbLink->getRowsRst($query);
        var_dump($result);
        if(!empty($result)) {
            $result = $this->_dbLink->uidRst("UPDATE {$this->_sessionTable} SET value = '{$session_data}' WHERE sid = '{$session_id}'");
            var_dump($result);
        }
        else{
            $result = $this->_dbLink->uidRst("INSERT INTO {$this->_sessionTable} (sid, value) VALUES ('{$session_id}', '{$session_data}')");
        }
        if($result){
            return 0;
        }
        else{
            return 1;
        }      
    }
    /**
    * 销魂session
    * @access public
    * @param string $session_id session ID
    * @return integer
    */
    public function destroy($session_id) {
        $result = $this->_dbLink->uidRst("DELETE FROM {$this->_sessionTable} WHERE  UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE." < UNIX_TIMESTAMP(NOW())");
        if($result){
            return 0;
        }
        else{
            return 1;
        }
    }
    /**
    * 垃圾回收
    * @access public
    * @param string $maxlifetime session 最长生存时间
    * @return integer
    */
    public function gc($maxlifetime) {
        $result = $this->_dbLink->uidRst("DELETE FROM {$this->_sessionTable} WHERE  UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE." < UNIX_TIMESTAMP(NOW())");
        if($result){
            return 0;
        }
        else{
            return 1;
        }
    }
}

$dblink=new db($$host="localhost",$name="root",$pwd="");
$sessionTable="sessioninfo";
$handler=new MySessionHandler($dblink,$sessionTable);
session_set_save_handler($handler);
session_start();
?>