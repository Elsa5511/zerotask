<?php

namespace LadocDocumentation\Controller\Helper;

use Application\Controller\AbstractBaseController;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Entity\LadocRestraintCertifiedDocument;

class BreadcrumbCreator {
    public static function createBreadcrumbForDocumentationSubPage(AbstractBaseController $controller,
                                                                   LadocDocumentation $ladocDocumentation) {
        $applicationName = $controller->params()->fromRoute('application');
        $controller->setBreadcrumbForFeatureActions($ladocDocumentation->getEquipment(), 'ladoc-documentation');
        $navigationPage = $controller->getNavigationPage('ladoc-documentation');
        $navigationPage->setParams(
            array(
                'application' => $applicationName,
                'id' => $ladocDocumentation->getEquipment()->getEquipmentId()
            )
        );
    }

    public static function createBreadcrumbForDocumentationDisplay(AbstractBaseController $controller,
                                                                   LadocDocumentation $ladocDocumentation) {
        $applicationName = $controller->params()->fromRoute('application');
        $controller->setBreadcrumbForFeatureActions($ladocDocumentation->getEquipment(), 'ladoc-documentation');
        $navigationPage = $controller->getNavigationPage('ladoc-documentation-display');
        $navigationPage->setParams(
            array(
                'application' => $applicationName,
                'id' => $ladocDocumentation->getId()
            )
        );
    }

    public static function createBreadcrumbForRestraintCertifiedDocument(AbstractBaseController $controller,
                                                                   $restraintCertified, $type = 'load') {
        $applicationName = $controller->params()->fromRoute('application');
        if($type == 'load')
            $ladocDocumentation = $restraintCertified->getLoadDocumentation();
        else
            $ladocDocumentation = $restraintCertified->getCarrierDocumentation();

        BreadcrumbCreator::createBreadcrumbForDocumentationDisplay($controller, $ladocDocumentation);
        $navigationPage = $controller->getNavigationPage('ladoc-restraint-certified-document');
        $navigationPage->setParams(
            array(
                'application' => $applicationName,
                'restraint_certified_id' => $restraintCertified->getId(),
                'type' => $type
            )
        );
    }

    public static function createAddEditBreadcrumbForDocumentationSubPage($controller, $ladocDocumentation, $templateType = null) {
        BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($controller, $ladocDocumentation);
        $applicationName = $controller->params()->fromRoute('application');
        $navigationPage = $controller->getNavigationPage($controller->getControllerName());
        $params = array(
            'application' => $applicationName,
            'documentation_id' => $ladocDocumentation->getId()
        );
        if($templateType)
            $params['template_type'] = $templateType;
        $navigationPage->setParams($params);
    }

    public static function createDetailBreadcrumbForDocumentationSubPage($controller, $ladocDocumentation, $title) {
        BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($controller, $ladocDocumentation);
        $detailNavigationPage = $controller->getNavigationPage($controller->getControllerName() . '-detail');
        $detailNavigationPage->setLabel($title);
    }
}