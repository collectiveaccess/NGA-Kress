######Run Using sudo bash import_script_kress.sh

#echo "Importing name authorities..."
#bin/caUtils import-data --format=CSV  --mapping=NA01 --source=02_NameAuthorities_edited.csv --log-level=ERR --log=import --direct 


#echo "Importing object acquisition join..."
#bin/caUtils import-data --format=CSV  --mapping=object_acquisition --source=05_AcquisitionObjectDetail.csv --log-level=ERR --log=import --direct 


#echo "Importing artwork..."
#bin/caUtils import-data --format=CSV  --mapping=AO01 --source=01_ArtObjects.csv --log-level=ERR --log=import --direct 

#mysqldump -uroot kress >  /data/kress/admin/support/backup_prototype_final_art_acqobj_ent.dump


#echo "Importing documents..."
#bin/caUtils import-data --format=CSV  --mapping=document --source=03_Documents_edited.csv --log-level=ERR --log=import --direct 

#mysqldump -uroot kress >  /data/kress/admin/support/backup_prototype_final_art_acqobj_ent_doc.dump

#echo "Importing distributions..."
#bin/caUtils import-data --format=CSV  --mapping=DO01 --source=06_Distributions.csv --log-level=ERR --log=import --direct 

#mysqldump -uroot kress >  /data/kress/admin/support/backup_prototype_final_art_acqobj_ent_doc_dist.dump

#echo "Importing acquisitions..."
#bin/caUtils import-data --format=CSV  --mapping=acquisition --source=04_Acquisitions_Group.csv --log-level=ERR --log=import --direct 


#mysqldump -uroot kress >  /data/kress/admin/support/backup_prototype_final.dump

cd /data/kressfinal/support/scripts
#php fixItalics.php
php importMedia.php
php importConditionMedia.php


cd /data/kressfinal/support/
#bin/caUtils apply-prepopulate-rules


#mysqldump -uroot kress >  /data/kressfinal/support/backup_prototype_final_w_media.dump

#Hand Edits:

#[2833] Failed to add value for Object_Date_Filter; values were Object_Date_Filter = 1550-1549; 1550-1649
#[951] Failed to add value for Object_Date_Filter; values were Object_Date_Filter = 1550-1549; 1550-1599
#[3455] Failed to add value for Object_Date_Filter; values were Object_Date_Filter = 1550-1549; 1550-1599
#[3359] Failed to add value for Object_Date_Filter; values were Object_Date_Filter = 1650-1399; 1350-1399
#[2833] Failed to add value for Object_Date_Filter; values were Object_Date_Filter = 1550-1549; 1550-1649
#[2481] Failed to add value for Object_Date_Filter; values were Object_Date_Filter = -50-99 0-99; 1st century B.C.-1st century A.D.

#[DOC02352] Failed to add value for Doc_DateFilter; values were Doc_DateFilter = 1981-1969; 1965-1969

#delete ACQ299 acquisition record

bin/caUtils rebuild-search-index

