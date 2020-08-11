<?php
	# convert text based display date for movements to date range so can be used for sorting - should then be able to get ride of filter date and text display date
	
	require_once("../../setup.php");
	require_once(__CA_MODELS_DIR__."/ca_movements.php");
	require_once(__CA_LIB_DIR__."/Utils/DataMigrationUtils.php");
	
	$o_db = new Db();
# --- get notes element_id
	$q_movements = $o_db->query("SELECT DISTINCT m.movement_id 
					FROM ca_movements m");
	
	print "num movements/acquisitions: ".$q_movements->numRows()."\n";
	$vn_c = 0;
	if($q_movements->numRows()){
		while($q_movements->nextRow()){
			$t_movement = new ca_movements($q_movements->get("movement_id"));
			# --- get the text display date - set in new daterange field
			if($vs_tmp = $t_movement->get("ca_movements.Acquisition_Date")){
				print $t_movement->get("ca_movements.movement_id");
				$t_movement->setMode(ACCESS_WRITE);
				$t_movement->addAttribute(array("Acquisition_Daterange" => $vs_tmp, 'locale_id' => 1), "Acquisition_Daterange");
				$t_movement->update();
				DataMigrationUtils::postError($t_movement, "[ERROR: While setting Acquisition_Date. for movement record id:".$t_movement->get("ca_movements.movement_id"));			
								
				$vn_c++;
			}
			
		}
	}
	print "\n".$vn_c." acquisition/movement dates converted";

?>
