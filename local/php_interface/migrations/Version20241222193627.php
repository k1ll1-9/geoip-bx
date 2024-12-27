<?php

namespace Sprint\Migration;


class Version20241222193627 extends Version
{
    protected $author = "admin";

    protected $description = "";

    protected $moduleVersion = "4.16.1";

    /**
     * @return void
     * @throws Exceptions\HelperException
     * создаем HL блок и добавляем необходимые свойства
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $HiblockID = $helper->Hlblock()->addHlblockIfNotExists(
            [
                'NAME' => 'Geosearch',
                'TABLE_NAME' => 'geosearch'
            ]
        );

        $arFields = [
            [
                'FIELD_NAME' => 'UF_IP',
                'USER_TYPE_ID' => 'string',
                'XML_ID' => 'uf_ip_xml_id',
                'EDIT_FORM_LABEL' => ['ru' => 'IP адрес'],
                'LIST_COLUMN_LABEL' => ['ru' => 'IP адрес'],
                'LIST_FILTER_LABEL' => ['ru' => 'IP адрес'],
                'SETTINGS' => ['SIZE' => 15, 'ROWS' => 1],
            ],
            [
                'FIELD_NAME' => 'UF_CITY',
                'USER_TYPE_ID' => 'string',
                'XML_ID' => 'uf_city_xml_id',
                'EDIT_FORM_LABEL' => ['ru' => 'Город'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Город'],
                'LIST_FILTER_LABEL' => ['ru' => 'Город'],
                'SETTINGS' => ['SIZE' => 15, 'ROWS' => 1],
            ],
            [
                'FIELD_NAME' => 'UF_COUNTRY',
                'USER_TYPE_ID' => 'string',
                'XML_ID' => 'uf_country_xml_id',
                'EDIT_FORM_LABEL' => ['ru' => 'Страна'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Страна'],
                'LIST_FILTER_LABEL' => ['ru' => 'Страна'],
                'SETTINGS' => ['SIZE' => 15, 'ROWS' => 1],
            ],
            [
                'FIELD_NAME' => 'UF_LAT',
                'USER_TYPE_ID' => 'double',
                'XML_ID' => 'uf_lat_xml_id',
                'EDIT_FORM_LABEL' => ['ru' => 'Широта'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Широта'],
                'LIST_FILTER_LABEL' => ['ru' => 'Широта'],
                'SETTINGS' => ['SIZE' => 10, 'ROWS' => 1,"PRECISION"=>5],
            ],
            [
                'FIELD_NAME' => 'UF_LON',
                'USER_TYPE_ID' => 'double',
                'XML_ID' => 'uf_lon_xml_id',
                'EDIT_FORM_LABEL' => ['ru' => 'Долгота'],
                'LIST_COLUMN_LABEL' => ['ru' => 'Долгота'],
                'LIST_FILTER_LABEL' => ['ru' => 'Долгота'],
                'SETTINGS' => ['SIZE' => 10, 'ROWS' => 1,"PRECISION"=>5],
            ],
        ];

        try {
            foreach ($arFields as $field) {
                $helper->Hlblock()->saveField('Geosearch',$field );
            }
        } catch (Exceptions\HelperException $e) {
            //если какое-либо свойство добавить не удалось, откатываемся
            $helper->Hlblock()->deleteHlblockIfExists('Geosearch');
        }
    }

    /**
     * @return void
     * @throws Exceptions\HelperException
     * удаляем HL блок
     */
    public function down()
    {
        $helper = $this->getHelperManager();
        $helper->Hlblock()->deleteHlblockIfExists('Geosearch');
    }
}
