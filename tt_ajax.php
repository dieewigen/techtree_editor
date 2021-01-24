<?php
include 'inccon.php';

if($_REQUEST['command']=='edit'){

	$output='';
	
	$tech_id=intval($_REQUEST['id']);

	//Datensatz speichern?
	if(isset($_REQUEST['save']) && $_REQUEST['save']==1){
		$errormsg='';

		$names=$_REQUEST['name_1'].';'.$_REQUEST['name_2'].';'.$_REQUEST['name_3'].';'.$_REQUEST['name_4'].';'.$_REQUEST['name_5'];
		
		$descs=$_REQUEST['desc_1'].';'.$_REQUEST['desc_2'].';'.$_REQUEST['desc_3'].';'.$_REQUEST['desc_4'].';'.$_REQUEST['desc_5'];
		
		//in der DB speichern
		$sql = "UPDATE `de_tech_data` SET "
		." tech_name='".$names."'"
		.",tech_build_cost='".$_REQUEST['tech_build_cost']."'"
		.",tech_vor='".$_REQUEST['tech_vor']."'"
		.",tech_desc='".$descs."'"
		.",tech_typ='".$_REQUEST['tech_typ']."'"
		.",tech_level='".$_REQUEST['tech_level']."'"
		.",tech_build_time='".$_REQUEST['tech_build_time']."'"
		.",tech_sort_id='".$_REQUEST['tech_sort_id']."'"
		." WHERE tech_id='".$tech_id."';"
			;
		//$output.=$sql;
		mysqli_query($dbi, $sql);
		$output.='<div style="color: #00AA00;">Der Datensatz wurde geupdatet.</div>';
	}
	
	//bestehende Technologie anzeigen
	$sql="SELECT * FROM `de_tech_data` WHERE tech_id='$tech_id'";
	//echo $sql;
	//$output.=$sql;
	$result=mysqli_query($dbi, $sql);
	$row = mysqli_fetch_array($result);
	
	$output.='<span id="editor_area">';
	
	//tech_id
	$output.='tech_id: '.$row['tech_id'];
	
	//tech_typ
	$output.=' - <span title="0 Geb&auml;ude, 1 Forschung">Typ</span>: <input id="tech_typ" name="tech_typ" value="'.$row['tech_typ'].'" style="width: 30px;">';
	
	//tech_level
	$output.=' - Level: <input id="tech_level" name="tech_level" value="'.$row['tech_level'].'" style="width: 30px;">';
	
	//tech_build_time
	$output.=' - <span title="Bauzeit in Sekunden">BZ</span>: <input id="tech_build_time" name="tech_build_time" value="'.$row['tech_build_time'].'" style="width: 100px;">';
	
	//tech_sort_id
	$output.=' - Sort-ID: <input id="tech_sort_id" name="tech_sort_id" value="'.$row['tech_sort_id'].'" style="width: 30px;">';
	
	//Namen
	$names=explode(';',$row['tech_name']);
	$output.='<br><br>Namen:';
	$output.='<br>
		<input id="name_1" name="name_1" value="'.$names[0].'">
		<input id="name_2" name="name_2" value="'.$names[1].'">
		<input id="name_3" name="name_3" value="'.$names[2].'">
		<input id="name_4" name="name_4" value="'.$names[3].'">
		<input id="name_5" name="name_5" value="'.$names[4].'">';
	
	//Baukosten
	$output.='<br><br>Baukosten:';
	$output.='<br><input id="tech_build_cost" name="tech_build_cost" value="'.$row['tech_build_cost'].'" style="width: 98%;">';
	
	//ben�tigte Technologien
	$output.='<br><br>ben&ouml;tigte Technologien:';
	$output.='<br><input id="tech_vor" name="tech_vor" value="'.$row['tech_vor'].'" style="width: 98%;">';
	
	//Beschreibung
	$output.='<br>Beschreibung:';
	$descs=explode(';',$row['tech_desc']);
	$output.='<br><textarea id="tech_desc" name="desc_1" style="width: 98%;">'.$descs[0].'</textarea>';
	$output.='<br><textarea id="tech_desc" name="desc_2" style="width: 98%;">'.$descs[1].'</textarea>';
	$output.='<br><textarea id="tech_desc" name="desc_3" style="width: 98%;">'.$descs[2].'</textarea>';
	$output.='<br><textarea id="tech_desc" name="desc_4" style="width: 98%;">'.$descs[3].'</textarea>';
	$output.='<br><textarea id="tech_desc" name="desc_5" style="width: 98%;">'.$descs[4].'</textarea>';
	
	//Speichern-Button
	$output.='<br><br><a onclick="editor_submit('.$row['tech_id'].')" class="btn">speichern</a>';
	
	
	$output.='</span>';//editor_area
	
	$data[] = array ('output' => $output);
	echo json_encode($data);	
	
}

?>