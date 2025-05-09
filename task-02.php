<?php

{
    $fieldsToUpdate = [
        'KartyaCim',
        'oregszik',
        'Mask_HW'
    ];

    if ($result) {
        //it could be function updateRecord($actualRecord, $dataToStoreCardAddress, $fieldsToUpdate);
        $actualRecord = $result[0];
        $dataToStoreCardAddress = $dataToStore['FulkartyaCim'];
        $updateClause = getUpdateClause($actualRecord, $dataToStoreCardAddress, $fieldsToUpdate);
        if (!empty($updateClause)) {
            $sql = "
				UPDATE `FulKartyaCim`
				SET " . implode(",", $updateClause) . "
				WHERE
					`Ful` = '" . $dataToStoreCardAddress['Ful'] . "'
			";
            $DataBase->SqlQuery($sql);
        }
    } else {
        //it could be function insertRecord($actualRecord, $dataToStoreCardAddress, $fieldsToUpdate);
        $fieldsToInsert = array_merge($fieldsToUpdate, 'Ful');
        $insertClause = getInsertClause($dataToStoreCardAddress, $fieldsToInsert);
        if (!empty($insertClause)) {
            $sql = "
			INSERT INTO
				`FulKartyaCim`
			SET " . implode(",", $fieldsToInsert) . "
				
		";
            $DataBase->SqlQuery($sql);
        }
    }
}


function getUpdateClause(array $actualRecord, array $dataToStoreCardAddress, array $fieldsToUpdate): array
{
    $updateClause = [];
    foreach ($fieldsToUpdate as $fieldToUpdate) {
        if ($actualRecord[$fieldToUpdate] !== $dataToStoreCardAddress[$fieldToUpdate]) {
            //of course, need to escape if not using db wrapper
            $updateClause = "`" . $fieldToUpdate . "` = '" . $dataToStoreCardAddress[$fieldToUpdate] . "'";
        }
    }
    return $updateClause;
}

function getInsertClause(array $dataToStoreCardAddress, array $fieldsToInsert): array
{
    $insertClause = [];
    foreach ($fieldsToInsert as $fieldToInsert) {
        //of course, need to escape if not using db wrapper
        $insertClause = "`" . $fieldToInsert . "` = '" . $dataToStoreCardAddress[$fieldToInsert] . "'";
    }
    return $insertClause;
}