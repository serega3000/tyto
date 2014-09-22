<?php

if(isset($_POST['data']))
{
	$filename = __DIR__."/data/data.json";
	
	if(file_exists($filename))
	{
		$time = date("Y.m.d H:i",filemtime($filename));		
		$save_file = __DIR__."/data/{$time}.json";
		copy($filename, $save_file);
	}
	
	file_put_contents($filename, $_POST['data']);
	echo "ok";
	exit;
}
echo "no data";


