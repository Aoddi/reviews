<?php
IncludeModuleLangFile(__FILE__);

use \Bitrix\Main\ModuleManager;

Class reviews_d7 extends CModule
{
    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "version.php");

        $this->MODULE_ID = "reviews.d7";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = "Отзывы D7";
        $this->MODULE_DESCRIPTION = "Модуль отзывов к товарам с пре-модерацией";

        $this->PARTNER_NAME = "Феоктистов Артём";
        $this->PARTNER_URI = "https://github.com/Aoddi/reviews";
    }

    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallEvents();
        $this->InstallFiles();

        ModuleManager::registerModule($this->MODULE_ID);
        return true;
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $this->UnInstallDB();

        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    function InstallDB()
    {
        return true;
    }

    function UnInstallDB()
    {
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/reviews.d7/install/components", $_SERVER["DOCUMENT_ROOT"]."/local/components/reviews.d7", true, true);

        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }
}