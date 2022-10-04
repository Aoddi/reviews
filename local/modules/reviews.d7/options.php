<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\ElementPropertyTable;


Loc::loadMessages(__FILE__);


$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialchars($request['mid'] != '' ? $request['mid'] : $request['id']);

Loader::includeModule($module_id);
Loader::includeModule('iblock');

$reviews = [];
$statuses = [];
$data = [];

$res = ElementTable::getList(array(
    'select' => array('ID', 'NAME', 'DETAIL_TEXT'),
    'filter' => array('IBLOCK_ID' => 4),
));

while ($arItem = $res->fetch()) {
    $propRes = ElementPropertyTable::getList(array(
        "select" => array("ID", "*"),
        "filter" => array("IBLOCK_ELEMENT_ID" => $arItem["ID"]),
    ));
    while($prop = $propRes->Fetch())
    {
        $arItem["PROPERTIES"][$prop["IBLOCK_PROPERTY_ID"]] = $prop;
    }

    $reviews[] = $arItem;
}

$propertyEnums = CIBlockPropertyEnum::GetList([], ['CODE' => 'STATUS']);
while($enumFields = $propertyEnums->GetNext())
{
    $statuses[] = $enumFields;
}
?>
<?php
$tabControl = new CAdminTabControl(
    'tabControl',
    ''
);

$tabControl->begin();

?>
    <form action="<?= $APPLICATION->getCurPage(); ?>?mid=<?= $module_id; ?>&lang=<?= LANGUAGE_ID; ?>" name="formReviews" method="post">
    <table style="width: 100%; padding: 40px">
        <thead style="font-weight: bold">
            <tr>
                <td>ID</td>
                <td>Автор</td>
                <td>Текст отзыва</td>
                <td>Оценка</td>
                <td>Статус</td>
            </tr>
        </thead>
        <?php foreach ($reviews as $review): ?>
        <tr>
            <input type="hidden" name="review-id[]" value="<?= $review['ID'] ?>">
            <input type="hidden" name="estimation[]" value="<?= $review['PROPERTIES']['26']['VALUE'] ?>">
            <td><?= $review['ID'] ?></td>
            <td><?= $review['NAME'] ?></td>
            <td><?= $review['DETAIL_TEXT'] ?></td>
            <td><?= $review['PROPERTIES']['26']['VALUE'] ?></td>
            <td>
                <select name="status[]">
                    <?php foreach ($statuses as $status): ?>
                        <?php if($status['ID'] === $review['PROPERTIES']['27']['VALUE']): ?>
                            <option value="<?=$status['ID']?>"><?=$status['VALUE']?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach ($statuses as $status): ?>
                        <?php if($status['ID'] !== $review['PROPERTIES']['27']['VALUE']): ?>
                            <option value="<?=$status['ID']?>"><?=$status['VALUE']?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
        <input type="submit" name="apply"
               value="<?= Loc::GetMessage('SCROLLUP_OPTIONS_INPUT_APPLY'); ?>" style="margin: 0 0 40px 40px" class="adm-btn-save"/>
    </form>



<?php
$tabControl->end();

if ($request->isPost()) {
    $reviewsData = [];

    foreach ($_POST['review-id'] as $key => $reviewId) {
        $reviewsData[$key]['ID'] = $reviewId;
        $reviewsData[$key]['STATUS'] = $_POST['status'][$key];
        $reviewsData[$key]['ESTIMATION'] = $_POST['estimation'][$key];
    }

    foreach ($reviewsData as $item) {
        $el = new CIBlockElement;

        $prop = [
            'STATUS' => $item['STATUS'],
            'ESTIMATION' => $item['ESTIMATION'],
        ];

        $arLoadProductArray = array(
            "PROPERTY_VALUES" => $prop,
        );

        $PRODUCT_ID = $item['ID'];
        $res = $el->Update($PRODUCT_ID, $arLoadProductArray);
    }

    LocalRedirect($APPLICATION->getCurPage() . '?mid=' . $module_id . '&lang=' . LANGUAGE_ID);
}