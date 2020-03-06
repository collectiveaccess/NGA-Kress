<?php
	require_once("../../setup.php");
	
	$q = ca_objects::findAsSearchResult('*');
	
	while($q->nextHit()) {
		if(is_array($prov_list = array_shift($q->get('ca_objects.Object_Provenance', ['returnWithStructure' => true])))) {
			foreach($prov_list as $attr_id => $prov_info) {
				$prov_proc = preg_replace("!_([^_]+)_!", "<i>\\1</i>", $prov_info['Object_Provenance']);
				//print "PROV=$prov_proc\n\n";
				
				$o = $q->getInstance();
				$o->editAttribute($attr_id, 'Object_Provenance', ['Object_Provenance' => $prov_proc]);
				$o->update();
				DataMigrationUtils::postError($o, "While setting provenance");
			}
		}
	}