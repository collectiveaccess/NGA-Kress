######Run Using sudo bash import_script_kress.sh



echo "Importing name authorities..."
bin/caUtils import-data --format=CSV  --mapping=NA01 --source=02_NameAuthorities_edited.csv --log-level=ERR --log=import --direct 


echo "Importing object acquisition join..."
bin/caUtils import-data --format=CSV  --mapping=object_acquisition --source=05_AcquisitionObjectDetail.csv --log-level=ERR --log=import --direct 


echo "Importing artwork..."
bin/caUtils import-data --format=CSV  --mapping=AO01 --source=01_ArtObjects.csv --log-level=ERR --log=import --direct 


echo "Importing documents..."
bin/caUtils import-data --format=CSV  --mapping=document --source=03_Documents_edited.csv --log-level=ERR --log=import --direct 


echo "Importing distributions..."
bin/caUtils import-data --format=CSV  --mapping=DO01 --source=06_Distributions_ValuesOnly.csv --log-level=ERR --log=import --direct 


echo "Importing acquisitions..."
bin/caUtils import-data --format=CSV  --mapping=acquisition --source=04_Acquisitions_Group.csv --log-level=ERR --log=import --direct 




mysqldump -uroot kress >  /data/kress/admin/support/backup_prototype_3_artobjects.dump

cd /data/kress2/support/scripts
php fixItalics.php
php importMedia.php


cd /data/kress2/support/
bin/caUtils rebuild-search-index
