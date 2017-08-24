<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\fancypack\viewhelpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\helpers\GearHtmlHelper;
use gear\arch\security\GearAntiForgeryTokenManager;
use gear\fancypack\viewhelpers\section\GearHtmlSections;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<generals>*/
GearHtmlHelper::setStaticExtensionMethods([
    'renderSection' => [GearHtmlSections::class, 'renderSection']/*function ($sectionName) {
        GearHtmlSections::renderSection($sectionName);
    }*/,
    'beginSection' => [GearHtmlSections::class, 'beginSection']/*function ($sectionName) {
        GearHtmlSections::beginSection($sectionName);
    }*/,
    'endSection' => [GearHtmlSections::class, 'endSection']/*function ($sectionName = null) {
        GearHtmlSections::endSection($sectionName);
    }*/,
    'sectionExists' => [GearHtmlSections::class, 'sectionExists']/*function ($sectionName = null) {
        GearHtmlSections::sectionExists($sectionName);
    }*/,



    'renderScript' => function () {
        GearHtmlSections::renderSection('Scripts');
    },
    'beginScript' => function () {
        GearHtmlSections::beginSection('Scripts');
    },
    'endScript' => function () {
        GearHtmlSections::endSection('Scripts');
    },

    'renderStyle' => function () {
        GearHtmlSections::renderSection('Styles');
    },
    'beginStyle' => function () {
        GearHtmlSections::beginSection('Styles');
    },
    'endStyle' => function () {
        GearHtmlSections::endSection('Styles');
    },

    'renderHtml' => function () {
        GearHtmlSections::renderSection('Html');
    },
    'beginHtml' => function () {
        GearHtmlSections::beginSection('Html');
    },
    'endHtml' => function () {
        GearHtmlSections::endSection('Html');
    },
]);

GearHtmlHelper::setMemberExtensionMethods([
    'antiForgeryToken' => function ($obj, $createNew = true) {
        /** @var GearHtmlHelper $obj */
        return GearAntiForgeryTokenManager::getAntiForgeryToken($createNew);
    },
    'validationMessageFor' => function ($obj, $name) {
        /** @var GearHtmlHelper $obj */

        $controller = $obj->getController();
        $validationMessages = $controller->getViewData(Gear_ValidationMessages);
        if ($validationMessages != null) {
            return isset($validationMessages[$name])
                ? $validationMessages[$name]
                : '';
        }
        return '';
    },

    'valueOf' => function ($obj, $name) {
        /** @var GearHtmlHelper $obj */
        global $Model;
        if ($Model != null ) {
            return $Model->$name;
        }
        return $obj->getContext()->getRequest()->getValue($name, '');
    }
]);
/*</generals>*/
?>