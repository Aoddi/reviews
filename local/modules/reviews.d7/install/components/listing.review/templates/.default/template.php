<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arResult */
?>
<ul>
    <?php foreach ($arResult['DISPLAY_VALUE'] as $arItem): ?>
    <li>
        <div class="">Оценка: <?=$arItem['PROPERTIES'][26]['VALUE']?></div>
        <div class="">Автор: <?=$arItem['NAME']?></div>
        <div class="">Сообщение: <?=$arItem['DETAIL_TEXT']?></div>
    </li>
    <?php endforeach; ?>
</ul>