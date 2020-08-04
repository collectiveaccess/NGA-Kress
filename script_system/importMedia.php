<?php
	require_once("../../setup.php");
	
	$p = DelimitedDataParser::load("../01_ArtObjects.csv", ['delimiter' => ',']);
	
	$files = caGetDirectoryContentsAsList("../../import/KressCollectionImages_20200220");
	
	
	foreach($files as $f) {
		$files[pathinfo($f, PATHINFO_BASENAME)] = $f;
	}
	while($p->nextRow()) {
		$r = $p->getRow();
		
		$id = $r[0];
		$fnames = $r[33];
		
		if ($fnames) {
			if ($o = ca_objects::find(['idno' => $id], ['returnAs' => 'firstModelInstance'])) {
				if ($o->getRepresentationCount() == 0) {
					$va_file_names = explode(";", $fnames);
					$c = 1;
					foreach($va_file_names as $fname){
						$fname = trim($fname);
						if($files[$fname]) {
							print "$id => $files[$fname]\n";
							$o->addRepresentation($files[$fname], 'front', 1, 0, 1, ($c > 1) ? false : true);
							DataMigrationUtils::postError($o, "While adding media");
							$c++;
						}
					}
				}
			}
			
		}
	}
?>