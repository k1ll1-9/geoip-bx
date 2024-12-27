<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\ArgumentException,
    Bitrix\Main\LoaderException,
    Bitrix\Main\UI\Extension,
    Bitrix\Main\Web\HttpClient,
    Bitrix\Main\Engine\Contract\Controllerable,
    Bitrix\Highloadblock\HighloadBlockTable,
    Bitrix\Main\ObjectPropertyException,
    Bitrix\Main\SystemException,
    Bitrix\Main\Loader;

try {
    Extension::Load('ui.vue3');
} catch (LoaderException $e) {
    return false;
}

try {
    Extension::Load('ui.bootstrap4');
} catch (LoaderException $e) {
    return false;
}

try {
    Loader::includeModule("highloadblock");
} catch (LoaderException $e) {
    return false;
}

/**
 * TODO реализовать отправку email`а на почту при возникновении исключений
 */
class GeoSearch extends CBitrixComponent implements Controllerable
{
    /**
     * @return array
     */
    public function configureActions(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function listKeysSignedParameters()
    {
        return [
            'HL_IBLOCK_NAME'
        ];
    }

    /**
     * @param string $ip
     * @return array|string[]
     */
    public function fetchUserDataAction(string $ip): array
    {

        $ip = filter_var(trim($ip), FILTER_VALIDATE_IP);

        if (!$ip) {
            return [
                "error" => "Введен некорректный IP адрес"
            ];
        }

        $res = $this->getUserDataFromDB($ip);

        if (!$res) {
            $res = $this->getUserDataFromRemote($ip);

            if ($res['city'] === null || $res['country'] === null) {
                return [
                    "error" => "По введеному IP адресу данных не найдено."
                ];
            }
            $this->saveUserDataToDB($res);
        }
        return $res;
    }

    /**
     * @param string $ip
     * @return array|null
     */
    private function getUserDataFromDB(string $ip): array|false
    {
        try {
            $strEntityDataClass = HighloadBlockTable::compileEntity($this->arParams['HL_IBLOCK_NAME'])->getDataClass();
        } catch (SystemException $e) {
            return [
                "error" => "Произошла ошибка. Повторите запрос позднее."
            ];
        }

        try {
            $res = $strEntityDataClass::getList([
                'select' => [
                    'UF_IP',
                    'UF_CITY',
                    'UF_COUNTRY',
                    'UF_LAT',
                    'UF_LON'
                ],
                'filter' => [
                    '=UF_IP' => $ip
                ]
            ])->fetch();
        } catch (ObjectPropertyException|ArgumentException|SystemException $e) {
            return [
                "error" => "Произошла ошибка. Повторите запрос позднее."
            ];
        }
        return $res;
    }

    /**
     * @param string $ip
     * @return array
     */
    private function getUserDataFromRemote(string $ip): array
    {
        $httpClient = new HttpClient();
        $res = $httpClient->get('http://api.sypexgeo.net/json/' . $ip);
        return json_decode($res, true);
    }


    /**
     * @param array $data
     * @return string[]|null
     */
    private function saveUserDataToDB(array $data): ?array
    {
        try {
            $strEntityDataClass = HighloadBlockTable::compileEntity($this->arParams['HL_IBLOCK_NAME'])->getDataClass();
        } catch (SystemException $e) {
            return [
                "error" => "Произошла ошибка. Повторите запрос позднее."
            ];
        }

        try {
            $strEntityDataClass::add([
                'UF_IP' => $data['ip'],
                'UF_CITY' => $data['city']['name_ru'],
                'UF_COUNTRY' => $data['country']['name_ru'],
                'UF_LAT' => $data['city']['lat'],
                'UF_LON' => $data['city']['lon']
            ])->getId();
        } catch (Exception $e) {
            return [
                "error" => "Произошла ошибка. Повторите запрос позднее."
            ];
        }
        return null;
    }

    /**
     * @return void
     */
    public static function sendMailOnException(): void
    {

    }

    /**
     * @return void
     */
    public function executeComponent(): void
    {
        $this->includeComponentTemplate();
    }
}
