<?php

use \Bitrix\Main\Loader;
use \Bitrix\Iblock\ElementTable;
use \Bitrix\Iblock\ElementPropertyTable;

class ListingReview extends CBitrixComponent
{
    public $propertyIdReviews = 25;

    public function getReviewsId() {
        $result = ElementPropertyTable::getList(array(
            'select' => array('ID', '*'),
            'filter' => array('IBLOCK_ELEMENT_ID' => $this->arParams['ID'], 'IBLOCK_PROPERTY_ID' => $this->propertyIdReviews)
        ))->fetchAll();

        return $result;
    }

    public function getReviews() {
        $reviews = $this->getReviewsId();
        $reviewsInfo = [];

        foreach ($reviews as $review) {
            $reviewsInfo['ID'][] = $review['VALUE'];
        }

        $res = ElementTable::getList(array(
            'select' => array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_TEXT'),
            'filter' => array('ID' => $reviewsInfo['ID'])
        ));

        while ($arItem = $res->fetch()) {
            $propRes = \Bitrix\Iblock\ElementPropertyTable::getList(array(
                "select" => array("ID", "*"),
                "filter" => array("IBLOCK_ELEMENT_ID" => $arItem["ID"]),
            ));
            while($prop = $propRes->Fetch())
            {
                $arItem["PROPERTIES"][$prop["IBLOCK_PROPERTY_ID"]] = $prop;
            }

            $this->arResult[] = $arItem;
        }
    }

    public function executeComponent()
    {

        $this->getReviews();

        if(Loader::includeModule('reviews.d7')) {
            $this->includeComponentTemplate();
        }
    }
}