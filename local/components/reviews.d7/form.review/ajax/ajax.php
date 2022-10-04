<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main;

$APPLICATION->IncludeComponent(
    "reviews.d7:form.review",
    "",
    [
        "IBLOCK_ID" => 4,
    ],
    false
);