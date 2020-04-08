<?php
	require_once("../setup.php");
	
	$p = DelimitedDataParser::load("01_ArtObjects.xlsx");
	
	$files = caGetDirectoryContentsAsList("../import");
	
	
	foreach($files as $f) {
		$files[pathinfo($f, PATHINFO_BASENAME)] = $f;
	}
	
	while($p->nextRow()) {
		$r = $p->getRow();
		
		$id = $r[0];
		$fname = $r[33];
		
		if ($fname) {
			if($files[$fname]) {
				if ($o = ca_objects::find(['idno' => $id], ['returnAs' => 'firstModelInstance'])) {
					if ($o->getRepresentationCount() == 0) {
						print "$id => $files[$fname]\n";
						$o->addRepresentation($files[$fname], 'front', 1, 0, 0, true);
						DataMigrationUtils::postError($o, "While adding media");
					}
				}
			}
			
		}
	}