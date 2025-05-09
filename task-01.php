<?php

{
    $actualRecord = $resultSet[0];
    $updateRequired = FALSE;
    $sql = "";
    $doNotUpdateField = [];
    $doesNotForceUpdate = [];
    $typeIdField = 'TypeId';

    foreach ($dataToStore['AllFields'] as $fieldName => $value) {
        if ($fieldName === $typeIdField) {
            continue;
        }

        $currentValue = $actualRecord[$fieldName] ?? null;

        if ($value != $currentValue) {
            if (!in_array($fieldName, $doNotUpdateField)) {
                $updateFields[] = "`$fieldName` = '" . mysqli_real_escape_string($value) . "'";
            }

            if (isUpdateRequired()) {
                $updateRequired = true;
            }
        }

    }

    if (!empty($updateFields) && $updateRequired) {
        $typeId = (int)$dataToStore['AllFields'][$typeIdField];
        $sqlStatment = getSqlStatement($updateFields, $typeId);
        $DataBase->SqlQuery($sqlStatment); //should be also outsourced and wrap by try catch
    }
}

function isUpdateRequired($fieldName, $doesNotForceUpdate)
{
    if (!in_array($fieldName, $doesNotForceUpdate)) {
        return true;
    }
    return false;
}

function getSqlStatement($updateFields, int $typeId): string
{
    $setFields = implode(", ", $updateFields);
    $sqlStatement = "
            UPDATE `Records` 
            SET " . $setFields . "
            WHERE `TypeId` = '  " . $typeId . " '
            LIMIT 1
        ";
    return $sqlStatement;
}
