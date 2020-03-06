<?php
	require_once("../../setup.php");
	require_once(__CA_LIB_DIR__."/Logging/KLogger/KLogger.php");
	$log = caGetImportLogger(['logLevel' => 'DEBUG', 'logDirectory' => '.']);
	$files = caGetDirectoryContentsAsList("/thunderbolt/KressCollectionImages_20200220");
	
	$file_map = [];
	foreach($files as $f) {
		$fp = _stripName($f);
		$file_map[$fp] = $f;
	}
	
	
	$qr = ca_objects::findAsSearchResult('*');
	
	while($qr->nextHit()) {
		$m = array_shift($qr->get('ca_objects.media', ['returnWithStructure' => true]));
	
		if(is_array($m)) {
			foreach($m as $attr_id => $d) {
				$f = $d['media_filename'];
				$fproc = _stripName($f);
				if ($p = $file_map[$fproc]) {
					if (ca_object_representations::mediaExists($p)) { continue; }
					print "[$attr_id] IMPORT $p\n";
					$o = $qr->getInstance();
					$t_rep = $o->addRepresentation($p, 'front', 1, 0, 1, true, [], ['original_filename' => pathInfo($p, PATHINFO_FILENAME), 'returnRepresentation' => true]);
					
					DataMigrationUtils::postError($o, "While adding $p");
					$t_rep->addAttribute(['credit_line' => $d['media_credit_line']], 'credit_line');
					$t_rep->update();
					DataMigrationUtils::postError($o, "While setting credit line for ".$d['media_credit_line']);
					
					// $o->editAttribute($attr_id, 'media', array_merge($d, ['media_media' => $p]), null, ['original_filename' => $f]);
// 					$o->update();
// 					DataMigrationUtils::postError($o, "While adding $p");
				} else {
 					$log->logError("[NO MATCH FOR {$idno}] {$f}");
 				}
			}
		}
		
		// if (!($fname = trim($qr->get('ca_objects.Object_ImageFilename'))))  { continue; }
// 		$idno = $qr->get('ca_objects.idno');
// 		
// 		if ($p = $file_map[_stripName($fname)]) {
// 			if (ca_object_representations::mediaExists($p)) { continue; }
// 			print "IMPORT $p\n";
// 			$o = $qr->getInstance();
// 			$o->addRepresentation($p, 'front', 1, 0, 1, true);
// 			DataMigrationUtils::postError($o, "While adding $p");
// 		} else {
// 			$log->logError("[NO MATCH FOR {$idno}] {$fname}");
// 		}

		

	}
	
	
	function _stripName($f) {
		return strtolower(preg_replace("![^A-Za-z0-9]+!", "", pathinfo($f, PATHINFO_FILENAME)));
	}