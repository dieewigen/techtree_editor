<?php
include 'inccon.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>DE Techtree-Generator</title>
	<meta charset="utf-8"/>
</head>
<body>

<?php

//die Tabelle leeren
$sql='TRUNCATE TABLE de_tech_data;';
echo $sql;
$db_daten=mysqli_query($GLOBALS['dbi'], $sql);

//die alten Techtrees auslesen und zusammenf�gen
//alle Rassen durchgehen
$td=array();
for($i=1;$i<=5;$i++){
	$db_daten=mysqli_query($GLOBALS['dbi'], "SELECT * FROM de_tech_data$i ORDER BY tech_id ASC");
	//printf("Error: %s\n", mysqli_error($GLOBALS['dbi']));
	//die Technlogien durchgehen und entsprechend ausgeben
	
	while($row = mysqli_fetch_array($db_daten)){
		$td[$i][$row['tech_id']]=$row;
	}
}

//aus den Daten den neuen Techtree bauen
//print_r($tech_data);
for($tech_id=0;$tech_id<120;$tech_id++){
	//echo $td[1][$tech_id]['tech_name'];

	if(!empty($td[1][$tech_id]['tech_name'])){
		
		//name
		$tech_name=$td[1][$tech_id]['tech_name'].';'.$td[2][$tech_id]['tech_name'].';'.$td[3][$tech_id]['tech_name'].';'.$td[4][$tech_id]['tech_name'].';'.$td[5][$tech_id]['tech_name'];
		
		//baukosten
		$tech_build_cost='';
		for($r=1;$r<=5;$r++){
			if($td[1][$tech_id]['restyp0'.$r]>0){
				if(!empty($tech_build_cost)){
					$tech_build_cost.=';';
				}
				$tech_build_cost.='R'.$r.'x'.$td[1][$tech_id]['restyp0'.$r];
			}
		}
		
		//vorbedingungen
		if(!empty($td[1][$tech_id]['tech_vor'])){
			$tech_vor='T'.str_replace(";",";T",$td[1][$tech_id]['tech_vor']);
		}else{
			$tech_vor='';
		}
		
		//beschreibung
		$tech_desc=$td[1][$tech_id]['des'].';'.$td[2][$tech_id]['des'].';'.$td[3][$tech_id]['des'].';'.$td[4][$tech_id]['des'].';'.$td[5][$tech_id]['des'];
		
		//tech_typ
		if($tech_id<=30){
			$tech_typ=0;
		}else{
			$tech_typ=1;
		}
		//tech_level
		$tech_level=1;
		//bauzeit
		$tech_build_time=$td[1][$tech_id]['tech_ticks']*60*15;

		$sql="INSERT INTO de_tech_data SET 
			tech_id='$tech_id',
			tech_name='$tech_name',
			tech_build_cost='$tech_build_cost',
			tech_vor='$tech_vor',
			tech_desc='$tech_desc',
			tech_typ='$tech_typ',
			tech_level='$tech_level',
			tech_build_time='$tech_build_time'
			";
		
		$sql=str_replace("�", "&#180;", $sql);
		$sql=html_entity_decode($sql, ENT_HTML401, 'UTF-8');
		//$sql=utf8_decode($sql);
		echo '<br>'.$sql;
		$db_daten=mysqli_query($GLOBALS['dbi'], $sql);
	}
}

?>