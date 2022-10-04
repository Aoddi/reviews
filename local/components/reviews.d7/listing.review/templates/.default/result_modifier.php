<?php

foreach ($arResult as $arItem) {
    if($arItem['PROPERTIES'][27]['VALUE'] == 19) {
        $arResult['DISPLAY_VALUE'][] = $arItem;
    }
}