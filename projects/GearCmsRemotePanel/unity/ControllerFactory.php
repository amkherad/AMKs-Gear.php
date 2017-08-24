<?php

class ControllerFactory extends GearDefaultControllerFactory
{
    public function getControllerPath(
        $context,
        $config,
        $route,
        $mvcContext,
        $areaName,
        &$controllerName,
        $controllerRootPath,
        $controllerSuffix)
    {
        $result = parent::getControllerPath(
            $context,
            $config,
            $route,
            $mvcContext,
            $areaName,
            $controllerName,
            $controllerRootPath,
            $controllerSuffix);
        switch ($areaName) {
            case 'cp': $controllerName = "GearCmsRemotePanel\\areas\\cp\\controllers\\$controllerName"; break;
            //case 'panel': $controllerName = "GearCmsRemotePanel\\areas\\panel\\controllers\\$controllerName"; break;
            default:  $controllerName = "GearCmsRemotePanel\\controllers\\$controllerName"; break;
        }
        return $result;
    }
}