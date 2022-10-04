<?php

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Iblock\ElementPropertyTable;

class FormReviews extends CBitrixComponent
{
    /**
     * Функция для создания элемента ИБ
     */
    public function createElement()
    {

        if (CModule::IncludeModule('iblock')) {
            $el = new CIBlockElement;
            $data = (array)json_decode($_POST['ajax']);

            $prop = [
                "ESTIMATION" => htmlspecialchars($data['estimation']),
                "STATUS" => "18",
            ];

            $arLoadProductArray = [
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                "NAME" => htmlspecialchars($data['name']),
                "ACTIVE" => "Y",
                "DETAIL_TEXT" => htmlspecialchars($data['text']),
                "PROPERTY_VALUES" => $prop,
            ];

            if ($ELEMENT_ID = $el->Add($arLoadProductArray)) {
                $result['success'] = 'true';
                $result['ID'] = $ELEMENT_ID;
            } else {
                $result['error'] = $el->LAST_ERROR;
            }

            return $result;
        }
    }

    /**
     * Метод для получения всех id отзывов товара
     */

    public function getPropertyProduct()
    {
        $data = (array)json_decode($_POST['ajax']);

        $result[] = ElementPropertyTable::getList(array(
            'select' => array('VALUE'),
            'filter' => array('IBLOCK_ELEMENT_ID' => $data['product'], 'IBLOCK_PROPERTY_ID' => 25)
        ))->fetchAll();

        return $result[0];
    }

    /**
     * Метод для обновления привязки id отзыва к торвару
     */

    public function updatePropertyProduct()
    {
        $newReview = $this->createElement();
        $idReviews = $this->getPropertyProduct();
        $array = [];

        foreach ($idReviews as $key => $review) {
            $array[] = $review['VALUE'];
        }

        $array[] = $newReview['ID'];

        if (CModule::IncludeModule('iblock')) {
            $el = new CIBlockElement;
            $data = (array)json_decode($_POST['ajax']);

            $prop = [
                "REWIEWS" => $array,
            ];

            $arLoadProductArray = array(
                "PROPERTY_VALUES" => $prop,
            );

            $PRODUCT_ID = htmlspecialchars($data['product']);
            $res = $el->Update($PRODUCT_ID, $arLoadProductArray);
        }
    }

    /**
     * Функция для подключения шаблона
     */
    public function executeComponent()
    {
        if (isset($_POST['ajax'])) {
            $this->updatePropertyProduct();
        } else {
            $this->includeComponentTemplate();
        }
    }
}
