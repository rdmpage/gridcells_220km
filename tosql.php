<?php

require_once('pg.php');

$filename = "gridcells_220km.geojson";

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
	
	if (preg_match('/^\{\s*"type"/', $line))
	{
		$json = preg_replace('/,$/', '', $line);
		
		//echo $json . "\n";
		
		$obj = json_decode($json);
		//print_r($obj);
		
		echo $obj->properties->Id . "\n";
		
		$sql = 'INSERT INTO gridcells220km (id, json, cell) VALUES(' 
			. $obj->properties->Id
			. ',' . "'" . str_replace("'", "''", $json) . "'"
			. ',' . 'ST_GeomFromGeoJSON(' . "'" . str_replace("'", "''", json_encode($obj->geometry)) . "'" . ')'
			. ')';
		$sql .= " ON CONFLICT DO NOTHING;";
		
			
		$result = pg_query($db, $sql);
	}	
}	

?>

