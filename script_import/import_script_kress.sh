######Run Using sudo bash import_script_kress.sh



echo "Importing name authorities..."
bin/caUtils import-data --format=XLSX  --mapping=NA01 --source=02_NameAuthorities_edited.xlsx --log-level=ERR --log=import --direct 
&1

echo "Importing object acquisition join..."
bin/caUtils import-data --format=XLSX  --mapping=object_acquisition --source=05_AcquisitionObjectDetail.xlsx --log-level=ERR --log=import --direct 
&1

echo "Importing artwork..."
bin/caUtils import-data --format=XLSX  --mapping=AO01 --source=01_ArtObjects.xlsx --log-level=ERR --log=import --direct 
&1

echo "Importing documents..."
bin/caUtils import-data --format=XLSX  --mapping=document --source=03_Documents_edited.xlsx --log-level=ERR --log=import --direct 
&1

echo "Importing distributions..."
bin/caUtils import-data --format=XLSX  --mapping=DO01 --source=06_Distrubutions_ValuesOnly.xlsx --log-level=ERR --log=import --direct 
&1

echo "Importing acquisitions..."
bin/caUtils import-data --format=XLSX  --mapping=acquisition --source=04_Acquisitions_Group.xlsx --log-level=ERR --log=import --direct 
&1

mysqldump -uroot kress2 >  /data/kress2/support/backup_prototype_2_2.dump

cd /data/kress2/support/scripts
php ImportMedia.php


cd /data/kress2/support/
bin/caUtils rebuild-search-index
