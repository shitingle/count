<?php 
Class db{
	private $host;
	private $name;
	private $pwd;
	private $result="";
	private $conn="";
	private $dbname="db_member";
	function __construct($host="",$name="",$pwd=""){
		$this->host=$host;
		$this->name=$name;
		$this->pwd=$pwd;
		$this->connect();
	}
	function connect(){
		$this->conn=mysql_connect($this->host,$this->name,$this->pwd);
	    mysql_select_db($this->dbname,$this->conn);
		mysql_query("set names gb2312");
	}
	function mysql_query_rst($sql){
		$this->result=mysql_query($sql,$this->conn);
	}
	function getRowsNum($sql){
		$this->mysql_query_rst($sql);
		return @mysql_num_rows($this->result);//返回受记录条数
	}
	function getRowsRst($sql){  //处理单条数组
		$this->mysql_query_rst($sql);
		$this->rowsRst=mysql_fetch_array($this->result);
		return $this->rowsRst;	
	}
	function getRowsArray($sql){ //处理多条数组
		$this->mysql_query_rst($sql);
		while($row=mysql_fetch_array($this->result)){
			$this->rowsArray[]=$row;
		}
	}
	function uidRst($sql){
		mysql_query($sql);
		$this->rowsNum=mysql_affected_rows();
		$rowsNum=mysql_affected_rows();//数据的增，删 ，查，改
		return $this->rowsNum;
	}
	function close_rst(){
		mysql_free_result($this->result);
		$this->msg="";
		$this->fieldNum=0;
		$this->rowsNum=0;
		$this->filesArray="";
		$this->rowsArray="";
	}
	function close_conn(){
		$this->close_rst();
		mysql_close($this->conn);
		$this->conn="";
	}
}

$conne=new db($host="localhost",$name="root",$pwd="");

?>