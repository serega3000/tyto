<?php

$interface_info_data_file = __DIR__."/data/interface.json";

if( ! isset($_POST['interface_id']))
{
	echo "no interface id";
	exit;
}

$interface_id = $_POST['interface_id'];

if(file_exists($interface_info_data_file))
{
	$interface_data = (array)json_decode(file_get_contents($interface_info_data_file));
	$interface_time = $interface_data['time'];
	$last_interface_id = $interface_data['id'];

	if($interface_id != $last_interface_id && $interface_time > time() - 20)
	{
		echo "already opened in another window";
		exit;
	}
}

file_put_contents($interface_info_data_file, json_encode(array(
	"id" => $interface_id,
	"time" => time()
)));


if(isset($_POST['data']))
{		
	
	
	$filename = __DIR__."/data/data.json";
	
	if(file_exists($filename))
	{
		$old_data = file_get_contents($filename);
		if($old_data == $_POST['data'])
		{
			echo "same";
			exit;
		}
		
		$time = date("Y.m.d H:i",filemtime($filename));		
		$save_file = __DIR__."/data/{$time}.json";
		copy($filename, $save_file);				
	}
	
	file_put_contents($filename, $_POST['data']);
	echo "ok";
	exit;
}

echo "no data";


