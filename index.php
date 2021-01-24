<?php
include 'inccon.php';

//Neue Technologie erstellen?
if(isset($_REQUEST['newtech']) && $_REQUEST['newtech']==1){
	$sql="INSERT INTO de_tech_data SET tech_name='neue Technologie', tech_level=1;";
	mysqli_query($GLOBALS['dbi'], $sql);
	
	header('Location: index.php');
	exit;	
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8"/>
    <title>DE Techtree-Editor</title>
	<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<link href="js/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php


//alle Technologien aus der DB auslesen
$db_daten=mysqli_query($GLOBALS['dbi'], "SELECT * FROM de_tech_data ORDER BY tech_level ASC, tech_sort_id ASC");
//printf("Error: %s\n", mysqli_error($GLOBALS['dbi']));
//die Technlogien durchgehen und entsprechend ausgeben
$tech_output=array();
$bauzeit_gesamt=0;
while($row = mysqli_fetch_array($db_daten)){
	//Kosten
	$kosten ='<span style="color: #00AA00;">';
	$einzelkosten=explode(';', $row['tech_build_cost']);
	foreach ($einzelkosten as $value) {
		/*
		if($kosten!='<span style="color: #00AA00;">'){
			$kosten.='<br>';
		}*/
		$kosten.='<br>';
		$kosten.=$value;
	}
	
	
	
	$kosten.='</span>';
	//Bauzeit
	$bauzeit='<br><span style="color: #0000FF;">BZ: '.gmdate("H:i:s", $row['tech_build_time']).'</span>';
	$bauzeit_gesamt+=$row['tech_build_time'];
	
	//Ausgabe
	$tech_field='<div style="border: 1px solid #000000; width: 200px; margin-bottom: 10px;" onclick="change_data('.$row['tech_id'].')">
		<span style="font-weight:bold;">tech_id: '.$row['tech_id'].'</span> (SID: '.$row['tech_sort_id'].')<br>'
		.str_replace(';','<br>',$row['tech_name']).
		$kosten.
		$bauzeit.
			
		'</div>';
	
	//echo $tech_field;
	
	if(isset($tech_output[$row['tech_level']])){
		$tech_output[$row['tech_level']].=$tech_field;
	}else{
		$tech_output[$row['tech_level']]=$tech_field;
	}
		
}

//print_r($tech_output);
echo '<table><tr style="vertical-align: top;">';
for($i=0;$i<=100;$i++){
	if(!empty($tech_output[$i])){
		echo '<td style="border: 1px solid #888888;">'.$i.'<br>';
		echo $tech_output[$i];
		echo '</td>';
	}
}
echo '</tr></table>';

echo '<br>Zeit:  2.153.700(alt) / '.number_format($bauzeit_gesamt,0, ",", ".").' (neu)';
//echo '<br>Kosten in M:  10.791.000(alt) / '.number_format($baukosten_in_m_gesamt,0, ",", ".").' (neu)';


echo '<br><br><a href="index.php?newtech=1" class="btn">neue Technologie</a>';

?>

<div id="data_editor_popup" title="Eingabemaske"><div id="data_editor" style="background-color: #EEEEEE;">Daten werden geladen...</div></div>
	
	
<script type="text/javascript">
$( document ).ready(function() {
	$("#data_editor_popup").dialog({close: function(event, ui) { close_edit_popup() },autoOpen: false, width: 1200, modal: true, position: { my: "top", at: "top"}});
	$("#data_editor_popup").dialog("destroy");
	$("#data_editor_popup").dialog({close: function(event, ui) { close_edit_popup() },autoOpen: false, width: 1200, modal: true, position: { my: "top", at: "top"}});
});

var need_refresh_after_popup_close=0;
function close_edit_popup(){
	/*
	if(need_refresh_after_popup_close==1){
		lnk("command=stammdaten_mitarbeiter","#content");
	}
	*/
   location.reload(true); 
}

function change_data(id){
	//open popup
	$("#data_editor_popup").dialog("open");
	//reset popup-content
	$("#data_editor").html("Daten werden geladen...");
	//load popup-content
	lnk("command=edit&id="+id,"#data_editor");
}

function editor_submit(id){
	var inputdata = $("#editor_area :input").serialize();
	lnk('command=edit&save=1&id='+id+'&'+inputdata, '#data_editor');
}

function lnk(parameter, target){
	$.getJSON('tt_ajax.php?'+parameter,
	function(data){
		$(target).html(data[0].output);
	});
}
</script>
</body>
</html>