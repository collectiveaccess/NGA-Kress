<?php
	# remove empty offer dates field: ca_loans.Distribution_OfferDate
	
	require_once("../../setup.php");
	require_once(__CA_MODELS_DIR__."/ca_loans.php");
	require_once(__CA_MODELS_DIR__."/ca_metadata_elements.php");
	require_once(__CA_LIB_DIR__."/Utils/DataMigrationUtils.php");
	
	$t_loans = new ca_loans();
	$vn_table_num = $t_loans->tableNum();
	
	$o_db = new Db();
# --- get notes element_id
	$t_element = new ca_metadata_elements(array("element_code" => "Distribution_OfferDate"));

	$q_loans = $o_db->query("SELECT DISTINCT l.loan_id 
					FROM ca_loans l 
					INNER JOIN ca_attributes as a ON a.row_id = l.loan_id
					WHERE a.element_id = ? AND a.table_num = ? AND l.deleted = 0", $t_element->get("element_id"), $vn_table_num);
	
	
		
	print "num rows with offer date: ".$q_loans->numRows()." - ";
	$vn_c = 0;
	if($q_loans->numRows()){
		while($q_loans->nextRow()){
			$t_loan = new ca_loans($q_loans->get("loan_id"));
			# --- find the blanks and remove
			if($va_tmp = $t_loan->get("ca_loans.Distribution_OfferDate", array("returnWithStructure" => true))){
				$va_tmp = array_pop($va_tmp);
				foreach($va_tmp as $vn_attribute_id => $va_values){
					if(trim($va_values["Distribution_OfferDate"]) == ""){
								print "[loan_id:".$t_loan->get("ca_loans.loan_id")." idno:".$t_loan->get("ca_loans.idno")." offerdate:'".$va_values["Distribution_OfferDate"]."']\n";	
								$t_loan->setMode(ACCESS_WRITE);
								$t_loan->removeAttribute($vn_attribute_id);
								$t_loan->update();
								$vn_c++;
					}
				}
			}
			
		}
	}
	print "\n".$vn_c." offer dates removed";

?>
