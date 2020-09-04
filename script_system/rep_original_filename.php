<?php
	# assign original filename from embedded file media if not already set
	
	require_once("../../setup.php");
	require_once(__CA_MODELS_DIR__."/ca_object_representations.php");
	require_once(__CA_LIB_DIR__."/Utils/DataMigrationUtils.php");
	
	$o_db = new Db();
	# --- get objects without primary rep
	$q_reps = $o_db->query("SELECT r.representation_id, r.original_filename, r.media_metadata 
					FROM ca_object_representations r
					WHERE r.original_filename ='' AND r.deleted != 1");
	
	$vn_c = 0;
	if($q_reps->numRows()){
		print "reps without original_filename: ".$q_reps->numRows()."\n";
		$db = new Db();
		while($q_reps->nextRow()){
			if(!$q_reps->get("deleted") && !$q_reps->get("original_filename") && $q_reps->get("media_metadata")){
				$va_extracted_metadata = caSanitizeArray(caUnserializeForDatabase($q_reps->get("media_metadata")), array('removeNonCharacterData' => true));
				if($vs_orig_name_from_md = $va_extracted_metadata["EXIF"]["FILE"]["FileName"]){
					$vn_rep_id = $q_reps->get("representation_id");
					$db->query("UPDATE ca_object_representations SET original_filename = ? WHERE representation_id = ?", [$vs_orig_name_from_md, $vn_rep_id]);
					print "rep_id:".$vn_rep_id."; original_filename: ".$q_reps->get("original_filename")."; filename: ".$vs_orig_name_from_md."\n";
					$vn_c++;
				}							
			}
		}
		print "filenames updated: ".$vn_c."\n";
	}

?>
