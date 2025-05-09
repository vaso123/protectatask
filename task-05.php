<?php

/**
 * I think, in the original class's design is not so good.
 * It breaks a lot of OOP priciples. First mentiond the Single responsibility
 * A device never will read an XML file, also not checking user persmissions, etc...
 * Also, the global config_values is a bad practice, since globel variables
 * could be overwritten by everyone, any time.
 *
 * It could be a serives instead. So I try to show you, how would I do it...
 *
 * Of course I do not implement all the classes, just try to show the big picture, I hope you saw from my previous
 * task, that I could code.
 *
 * I should see the whole context to improve this code more...
 *
 */


class Device
{
    //....
    public function getDevices()
    {
        return [];
    }
}

class User
{
    function getUserRights(int $userId): array
    {
        return [];
    }
}

interface xmlHandlerInterface
{

}

class xmlHandler implements xmlHandlerInterface
{

}

class deviceXmlHandler extends xmlHandler
{

}


class deviceDataXmlReportService
{

    /**
     * @var deviceXmlHandler
     */
    private deviceXmlHandler $deviceXmlHandler;

    /**
     * @var array
     */
    private array $configValues; //It should be also a configValues class with getConfigValue(miexed $key) function

    public function __construct(
        deviceXmlHandler $deviceXmlHandler,
        array            $configValues
    )
    {
        $this->deviceXmlHandler = $deviceXmlHandler;
        $this->configValues = $configValues;

    }


    public function getExtendCardDataWithXmlData(array $devicesList, User $user): array
    {
        if (!$this->hasUserPermission($user, $devicesList)) {
            return [$devicesList, null];
        }

        $extendedResults = array();
        $errorOrderCodes = array();

        foreach ($devicesList as $device) {
            if ($this->deviceHasOrderCode($device)) {
                $errorOrderCodes[] = $device['OrderCode'];
            } else {
                try {
                    $xml = $this->deviceXmlHandler($device['OrderCode']);
                    foreach ($xml->kartya as $card) {
                        $device['Kártya fül'] = $card->Title->__toString();
                        $device['Slot Geo'] = $card->Slot->__toString();
                        $device['Kártya Gyáriszám'] = '';
                        $extendedResults[] = $device;
                    }
                } catch (Exception $exception) {
                    $errorOrderCodes[] = $device['OrderCode'];
                }
            }
        }
        return [$extendedResults, $errorOrderCodes];
    }

    public function getExtendedXmlIfHasPermission(array $devicesList, User $user): array
    {
        if (!$this->hasUserPermission($user, $devicesList)) {
            return [$devicesList, null];
        }

        $extendedResults = array();
        $errorOrdercodes = array();

        foreach ($devicesList as $device) {
            if ($this->deviceHasOrderCode($device)) {
                $device['Típus'] = 'KR';
                $device['Méret'] = '';
                $device['Lemez'] = '';
                $device['Felszerelési mód'] = '';
            } else {
                try {
                    $xml = $this->deviceXmlHandler($device['OrderCode']);
                    $device['Típus'] = $this->deviceXmlHandler->getTypeFromOrderCodeToExport($device['OrderCode']);
                    $device['Méret'] = $xml->meret->__ToString();
                    $device['Lemez'] = $xml->fedel->__ToString();
                    $device['Felszerelési mód'] = $xml->felszerelesi_mod->__ToString();
                } catch (Exception $exception) {
                    $errorOrdercodes[] = $device['OrderCode'];
                }
            }
            $extendedResults[] = $device;
        }
        return [$extendedResults, $errorOrdercodes];
    }


    private function hasUserPermission(User $user, $deviceList)
    {
        if ($user->hasRightForOperation($user->getId(), [['ExtendExport']['accessPermission']])) {
            return true;
        }
        return false;
    }

    public function deviceHasOrderCode(array $device): bool
    {
        if (strpos($device['OrderCode'], $this->configValues['CardOrder']['Type']) !== false
            || strpos($device['OrderCode'], $this->configValues['CardOrder']['Config']) !== false) {
            return true;
        }
        return false;
    }
}
