<?php

class basket{
	//Class that deals with data storage and communication between classes
	function get($data){
		//Function that allows to get data from the basket
		if(isset($this->$data))
			return $this->$data;
		else return false;
	}
	function set($key, $data){
		$this->$key = $data;
	}
}

?>