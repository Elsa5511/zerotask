<?php
$config = require 'config/autoload/translator.global.php';
if(file_exists('config/autoload/acl.local.php')) {
    $guestConfig = require 'config/autoload/acl.local.php';
}
$options = (array)$config['translator'];
$translator = \Zend\I18n\Translator\Translator::factory($options);

$auth = new \Zend\Authentication\AuthenticationService();
if ($auth->hasIdentity()) {
    $user = $auth->getIdentity();
    if (is_a($user, 'Application\Entity\User')) {
        $translator->setLocale($user->getLanguage()->getIsocode());
    }
} elseif (isset($guestConfig) and isset($guestConfig['guest_locale'])) {
    /* For guest access - se acl.local.php.dist */
    $translator->setLocale($guestConfig['guest_locale']);
}

if (!function_exists("featureEquipmentPages")) {

    function featureEquipmentPages($translator) {
        return array(
            'id' => 'equipment-detail',
            'route' => 'base/wildcard',
            'controller' => 'equipment',
            'action' => 'detail',
            'pages' => array(
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'equipment-instance',
                    'action' => 'index',
                    'label' => $translator->translate('Equipment instances'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance',
                            'action' => 'add',
                            'label' => $translator->translate('Add equipment instance'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance',
                            'action' => 'edit',
                            'label' => $translator->translate('Edit equipment instance'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance',
                            'action' => 'copy',
                            'label' => $translator->translate('Add equipment instance'),
                        ),
                        array(
                            'id' => 'equipment-instance-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance',
                            'action' => 'detail',
                            'label' => $translator->translate('Details'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'periodic-control',
                                    'action' => 'index',
                                    'label' => $translator->translate('Periodic control')
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'visual-control',
                                    'action' => 'index',
                                    'label' => $translator->translate('Visual control')
                                )
                            )
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'check-in-and-out',
                            'action' => 'checkin',
                            'label' => $translator->translate('Check-in'),
                        ),
                    ),
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'equipment-instance-container',
                    'action' => 'index',
                    'label' => $translator->translate('Equipment instances'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance-container',
                            'action' => 'add',
                            'label' => $translator->translate('Add equipment instance'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance-container',
                            'action' => 'edit',
                            'label' => $translator->translate('Edit equipment instance'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance-container',
                            'action' => 'copy',
                            'label' => $translator->translate('Add equipment instance'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance-container',
                            'action' => 'edit-many',
                            'label' => $translator->translate('Mass update'),
                        ),
                        array(
                            'id' => 'equipment-instance-container-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'equipment-instance-container',
                            'action' => 'detail',
                            'label' => $translator->translate('Details'),
                        ),
                    )
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'equipment',
                    'action' => 'attachment-index',
                    'label' => $translator->translate('Equipment attachments')
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'documentation',
                    'action' => 'index',
                    'label' => $translator->translate('Equipment documentation')
                ),
                array(
                    'id' => 'ladoc-documentation-display',
                    'route' => 'base/wildcard',
                    'controller' => 'ladoc-documentation',
                    'action' => 'display',
                    'label' => $translator->translate('Documentation'),
                    'pages' => array(
                        array(
                            'id' => 'ladoc-restraint-certified-document',
                            'route' => 'base/wildcard',
                            'controller' => 'ladoc-restraint-certified-document',
                            'action' => 'index',
                            'label' => $translator->translate('Listing documents'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'ladoc-restraint-certified-document',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add document'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'ladoc-restraint-certified-document',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit document'),
                                ),
                            )
                        )
                    )
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'ladoc-documentation',
                    'action' => 'create',
                    'label' => $translator->translate('Create documentation'),
                ),
                array(
                    'id' => 'ladoc-documentation',
                    'route' => 'base/wildcard',
                    'controller' => 'ladoc-documentation',
                    'action' => 'index',
                    'label' => $translator->translate('Documentation'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'load-basic-information',
                            'action' => 'add',
                            'label' => $translator->translate('Basic information'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'load-basic-information',
                            'action' => 'edit',
                            'label' => $translator->translate('Basic information'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'load-basic-information',
                            'action' => 'edit-wizard',
                            'label' => $translator->translate('Basic information'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-basic-information',
                            'action' => 'add',
                            'label' => $translator->translate('Basic information'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-basic-information',
                            'action' => 'edit',
                            'label' => $translator->translate('Basic information'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-basic-information',
                            'action' => 'edit-wizard',
                            'label' => $translator->translate('Basic information'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-weight-and-dimensions',
                            'action' => 'add',
                            'label' => $translator->translate('Weight and dimensions'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-weight-and-dimensions',
                            'action' => 'edit',
                            'label' => $translator->translate('Weight and dimensions'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-weight-and-dimensions',
                            'action' => 'edit-wizard',
                            'label' => $translator->translate('Weight and dimensions'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'load-weight-and-dimensions',
                            'action' => 'add',
                            'label' => $translator->translate('Weight and dimensions'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'load-weight-and-dimensions',
                            'action' => 'edit',
                            'label' => $translator->translate('Weight and dimensions'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'load-weight-and-dimensions',
                            'action' => 'edit-wizard',
                            'label' => $translator->translate('Weight and dimensions'),
                        ),
                        array(
                            'id' => 'carrier-lashing-equipment',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-lashing-equipment',
                            'action' => 'index',
                            'label' => $translator->translate('Lashing Equipments'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-lashing-equipment',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add lashing equipment'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-lashing-equipment',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit lashing equipment'),
                                ),
                            )
                        ),
                        array(
                            'id' => 'carrier-lashing-point',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-lashing-point',
                            'action' => 'index',
                            'label' => $translator->translate('Lashing points'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-lashing-point',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add lashing point'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-lashing-point',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit lashing point'),
                                ),
                            )
                        ),
                        array(
                            'id' => 'load-lashing-point',
                            'route' => 'base/wildcard',
                            'controller' => 'load-lashing-point',
                            'action' => 'index',
                            'label' => $translator->translate('Lashing points'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-lashing-point',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add lashing point'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-lashing-point',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit lashing point'),
                                ),
                            )
                        ),
                        array(
                            'id' => 'carrier-lifting-point',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-lifting-point',
                            'action' => 'index',
                            'label' => $translator->translate('Lifting points'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-lifting-point',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add lifting point'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-lifting-point',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit lifting point'),
                                ),
                            )
                        ),
                        array(
                            'id' => 'load-lifting-point',
                            'route' => 'base/wildcard',
                            'controller' => 'load-lifting-point',
                            'action' => 'index',
                            'label' => $translator->translate('Lifting points'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-lifting-point',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add lifting point'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-lifting-point',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit lifting point'),
                                ),
                            )
                        ),
                        array(
                            'id' => 'ladoc-documentation-attachment',
                            'route' => 'base/wildcard',
                            'controller' => 'ladoc-documentation-attachment',
                            'action' => 'index',
                            'label' => $translator->translate('Documentation attachments'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'ladoc-documentation-attachment',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add documentation attachment'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'ladoc-documentation-attachment',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit documentation attachment'),
                                ),
                            )
                        ),
                        array(
                            'id' => 'load-restraint-certified',
                            'route' => 'base/wildcard',
                            'controller' => 'load-restraint-certified',
                            'action' => 'index',
                            'label' => $translator->translate('Load restraint documentation for certified carriers'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-restraint-certified',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add load restraint documentation'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-restraint-certified',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit load restraint documentation'),
                                )
                            )
                        ),
                        array(
                            'id' => 'load-restraint-certified-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'load-restraint-certified',
                            'action' => 'detail',
                            'label' => $translator->translate('Detail'),
                        ),
                        array(
                            'id' => 'carrier-restraint-certified',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-restraint-certified',
                            'action' => 'index',
                            'label' => $translator->translate('Carrier restraint documentation for certified loads'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-restraint-certified',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add carrier restraint documentation'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-restraint-certified',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit carrier restraint documentation'),
                                )
                            )
                        ),
                        array(
                            'id' => 'carrier-restraint-certified-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-restraint-certified',
                            'action' => 'detail',
                            'label' => $translator->translate('Detail'),
                        ),
                        array(
                            'id' => 'load-restraint-non-certified',
                            'route' => 'base/wildcard',
                            'controller' => 'load-restraint-non-certified',
                            'action' => 'index',
                            'label' => $translator->translate('Load restraint documentation for non-certified carriers'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-restraint-non-certified',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add load restraint documentation'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'load-restraint-non-certified',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit load restraint documentation'),
                                )
                            )
                        ),
                        array(
                            'id' => 'load-restraint-non-certified-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'load-restraint-non-certified',
                            'action' => 'detail',
                            'label' => $translator->translate('Detail'),
                        ),
                        array(
                            'id' => 'carrier-restraint-non-certified',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-restraint-non-certified',
                            'action' => 'index',
                            'label' => $translator->translate('Carrier restraint documentation for non-certified loads'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-restraint-non-certified',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add carrier restraint documentation'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'carrier-restraint-non-certified',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit carrier restraint documentation'),
                                )
                            )
                        ),
                        array(
                            'id' => 'carrier-restraint-non-certified-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'carrier-restraint-non-certified',
                            'action' => 'detail',
                            'label' => $translator->translate('Detail'),
                        ),
                    )
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'training',
                    'action' => 'index',
                    'label' => $translator->translate('Equipment training')
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'certification',
                    'action' => 'index',
                    'label' => $translator->translate('Equipment certifications'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'certification',
                            'action' => 'add',
                            'label' => $translator->translate('Add certification')
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'certification',
                            'action' => 'edit',
                            'label' => $translator->translate('Edit certification')
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'certification',
                            'action' => 'user',
                            'label' => $translator->translate('User certifications')
                        ),
                    )
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'exercise',
                    'action' => 'index',
                    'label' => $translator->translate('Exercises'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'exercise',
                            'action' => 'add',
                            'label' => $translator->translate('Add exercise'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'exercise',
                            'action' => 'edit',
                            'label' => $translator->translate('Edit exercise'),
                        ),
                        array(
                            'id' => 'exercise-view',
                            'route' => 'base/wildcard',
                            'controller' => 'exercise',
                            'action' => 'detail',
                            'label' => $translator->translate('Exercise view'),
                            'pages' => array(
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'question',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add Question'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'question',
                                    'action' => 'edit',
                                    'label' => $translator->translate('Edit Question'),
                                )
                            )
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'exercise-attempt',
                            'action' => 'index',
                            'label' => $translator->translate('Exercise attempt'),
                        ),
                    )
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'exam',
                    'action' => 'index',
                    'label' => $translator->translate('Exams'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'exam',
                            'action' => 'add',
                            'label' => $translator->translate('Add exam'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'exam',
                            'action' => 'edit',
                            'label' => $translator->translate('Edit exam'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'exam-attempt',
                            'action' => 'index',
                            'label' => $translator->translate('Exam attempt'),
                        ),
                    )
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'best-practice',
                    'action' => 'index',
                    'label' => $translator->translate('Best practices'),
                    'pages' => array(
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'best-practice',
                            'action' => 'add',
                            'label' => $translator->translate('Add best practice'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'best-practice',
                            'action' => 'edit',
                            'label' => $translator->translate('Edit best practice'),
                        ),
                        array(
                            'id' => 'best-practice-detail',
                            'route' => 'base/wildcard',
                            'controller' => 'best-practice',
                            'action' => 'detail',
                            'label' => $translator->translate('Main page'),
                            'pages' => array(
                                array(
                                    'id' => 'revision-history',
                                    'route' => 'base/wildcard',
                                    'controller' => 'best-practice',
                                    'action' => 'revision-history',
                                    'label' => $translator->translate('Revision history'),
                                    'pages' => array(
                                        array(
                                            'route' => 'base/wildcard',
                                            'controller' => 'best-practice',
                                            'action' => 'old-revision-detail',
                                            'label' => $translator->translate('Main page'),
                                        ),
                                        array(
                                            'route' => 'base/wildcard',
                                            'controller' => 'best-practice',
                                            'action' => 'procedures-old-revision',
                                            'label' => $translator->translate('Procedures'),
                                        ),
                                        array(
                                            'route' => 'base/wildcard',
                                            'controller' => 'best-practice',
                                            'action' => 'user-manual-old-revision',
                                            'label' => $translator->translate('User Manual'),
                                        ),
                                        array(
                                            'route' => 'base/wildcard',
                                            'controller' => 'best-practice',
                                            'action' => 'additional-info-old-revision',
                                            'label' => $translator->translate('Additional Info'),
                                        ),
                                    )
                                ),
                            )
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'best-practice',
                            'action' => 'procedures',
                            'label' => $translator->translate('Procedures'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'best-practice',
                            'action' => 'user-manual',
                            'label' => $translator->translate('User manual'),
                        ),
                        array(
                            'route' => 'base/wildcard',
                            'controller' => 'best-practice',
                            'action' => 'additional-info',
                            'label' => $translator->translate('Additional info'),
                        ),
                    ),
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'equipment-instance-report',
                    'action' => 'expired-periodic-control-for-equipment',
                    'label' => $translator->translate('Instances expired on control date'),
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'equipment-instance-report',
                    'action' => 'expired-guarantee-for-equipment',
                    'label' => $translator->translate('Instances expired on guarantee date'),
                ),
                array(
                    'route' => 'base/wildcard',
                    'controller' => 'equipment-instance-report',
                    'action' => 'expired-lifetime-for-equipment',
                    'label' => $translator->translate('Instances expired on technical lifetime'),
                ),
            )
        );
    }

}

return array(
    'default' => array(
        array(
            'label' => $translator->translate('Home'),
            'route' => 'home',
            'pages' => array(
                array(
                    'id' => 'equipment-home',
                    'route' => 'base',
                    'controller' => 'equipment',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'id' => 'equipment-category',
                            'route' => 'base/wildcard',
                            'controller' => 'equipment',
                            'action' => 'index',
                            'pages' => array(
                                featureEquipmentPages($translator),
                                array(
                                    'id' => 'category-page',
                                    'route' => 'base/wildcard',
                                    'controller' => 'page',
                                    'action' => 'index'
                                ),
                                array(
                                    'id' => 'category-page-search',
                                    'route' => 'base/wildcard',
                                    'controller' => 'page',
                                    'action' => 'simple-search',
                                    'label' => $translator->translate('Search Results'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'equipment-instance-report',
                                    'action' => 'expired-periodic-control-for-category',
                                    'label' => $translator->translate('Instances expired on control date'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'equipment-instance-report',
                                    'action' => 'expired-guarantee-for-category',
                                    'label' => $translator->translate('Instances expired on guarantee date'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'equipment-instance-report',
                                    'action' => 'expired-lifetime-for-category',
                                    'label' => $translator->translate('Instances expired on technical lifetime'),
                                ),
                                array(
                                    'route' => 'base/wildcard',
                                    'controller' => 'equipment-instance',
                                    'action' => 'edit-many',
                                    'label' => $translator->translate('Mass update'),
                                ),
                                array(
                                    'route' => 'base',
                                    'controller' => 'periodic-control',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add periodic control'),
                                ),
                                array(
                                    'route' => 'base',
                                    'controller' => 'check-in-and-out',
                                    'action' => 'checkout',
                                    'label' => $translator->translate('Check-out'),
                                ),
                                array(
                                    'route' => 'base',
                                    'controller' => 'visual-control',
                                    'action' => 'add',
                                    'label' => $translator->translate('Add visual control'),
                                ),
                            )
                        ),

                        array(
                            'route' => 'base',
                            'controller' => 'load-security',
                            'action' => 'index',
                            'use_route_match' => true,
                            'label' => $translator->translate('Load security'),
                            'pages' => array(
                                array(
                                    'route' => 'base',
                                    'controller' => 'load-security-attachment',
                                    'action' => 'index',
                                    'use_route_match' => true,
                                    'label' => $translator->translate('Load security attachment'),
                                    'pages' => array(
                                        array(
                                            'route' => 'base',
                                            'controller' => 'load-security-attachment',
                                            'action' => 'add',
                                            'label' => $translator->translate('Add load security attachment'),
                                        ),
                                        array(
                                            'route' => 'base/wildcard',
                                            'controller' => 'load-security-attachment',
                                            'action' => 'edit',
                                            'label' => $translator->translate('Edit load security attachment'),
                                        )
                                    )
                                )
                            )
                        ),
                        
                    )
                ),
                array(
                    'id' => 'equipment-search',
                    'route' => 'base',
                    'controller' => 'equipment',
                    'action' => 'do-search',
                    'use_route_match' => true
                ),
                array(
                    'id' => 'equipment-instance-search',
                    'route' => 'base',
                    'controller' => 'equipment-instance',
                    'action' => 'do-search',
                    'use_route_match' => true
                ),
                array(
                    'id' => 'equipment-instance-control-search',
                    'route' => 'base',
                    'controller' => 'equipment-instance',
                    'action' => 'do-control-search',
                    'use_route_match' => true
                ),
                array(
                    'label' => $translator->translate('Users'),
                    'route' => 'base',
                    'controller' => 'user',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'label' => $translator->translate('Add User'),
                            'route' => 'base',
                            'controller' => 'user',
                            'action' => 'add',
                        ),
                        array(
                            'label' => $translator->translate('Edit User'),
                            'route' => 'base/wildcard',
                            'controller' => 'user',
                            'action' => 'edit',
                        ),
                    ),
                ),
                array(
                    'label' => $translator->translate('Languages'),
                    'route' => 'base',
                    'controller' => 'language',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'label' => $translator->translate('Add Language'),
                            'route' => 'base',
                            'controller' => 'language',
                            'action' => 'add'
                        ),
                        array(
                            'label' => $translator->translate('Edit Language'),
                            'route' => 'base/wildcard',
                            'controller' => 'language',
                            'action' => 'edit'
                        )
                    )
                ),
                array(
                    'label' => $translator->translate('Organizations'),
                    'route' => 'base',
                    'controller' => 'organization',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'label' => $translator->translate('Add Organization'),
                            'route' => 'base',
                            'controller' => 'organization',
                            'action' => 'add'
                        ),
                        array(
                            'label' => $translator->translate('Edit Organization'),
                            'route' => 'base/wildcard',
                            'controller' => 'organization',
                            'action' => 'edit'
                        )
                    )
                ),
                array(
                    'label' => $translator->translate('Locations'),
                    'route' => 'base',
                    'controller' => 'location',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'label' => $translator->translate('Add Location'),
                            'route' => 'base',
                            'controller' => 'location',
                            'action' => 'add'
                        ),
                        array(
                            'label' => $translator->translate('Edit Location'),
                            'route' => 'base/wildcard',
                            'controller' => 'location',
                            'action' => 'edit'
                        )
                    )
                ),
                array(
                    'label' => $translator->translate('Roles'),
                    'route' => 'base',
                    'controller' => 'role',
                    'action' => 'index',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'label' => $translator->translate('Add Role'),
                            'route' => 'base',
                            'controller' => 'role',
                            'action' => 'add'
                        ),
                        array(
                            'label' => $translator->translate('Edit Role'),
                            'route' => 'base/wildcard',
                            'controller' => 'role',
                            'action' => 'edit'
                        )
                    )
                ),
                array(
                    'label' => $translator->translate('Categories'),
                    'route' => 'base',
                    'controller' => 'equipment-taxonomy',
                    'action' => 'admin-index',
                ),
                array(
                    'label' => $translator->translate('Equipment'),
                    'route' => 'base',
                    'controller' => 'equipment',
                    'action' => 'admin-index',
                ),
                array(
                    'label' => $translator->translate('Exam attempts'),
                    'route' => 'base',
                    'controller' => 'exam-attempt',
                    'action' => 'admin',
                    'use_route_match' => true,
                    'pages' => array(
                        array(
                            'label' => $translator->translate('Add exam attempt'),
                            'route' => 'base',
                            'controller' => 'exam-attempt',
                            'action' => 'add'
                        ),
                    )
                ),
                array(
                    'label' => $translator->translate('Certifications report'),
                    'route' => 'base',
                    'controller' => 'certification',
                    'action' => 'report',
                ),
                array(
                    'label' => $translator->translate('Exercise attempts report'),
                    'route' => 'base',
                    'controller' => 'exercise-attempt',
                    'action' => 'report',
                ),
                array(
                    'label' => $translator->translate('Exam attempts report'),
                    'route' => 'base',
                    'controller' => 'exam-attempt',
                    'action' => 'report',
                ),
                array(
                    'label' => $translator->translate('Instances expired on control date'),
                    'route' => 'base',
                    'controller' => 'equipment-instance-report',
                    'action' => 'expired-periodic-control',
                ),
                array(
                    'label' => $translator->translate('Instances expired on guarantee date'),
                    'route' => 'base',
                    'controller' => 'equipment-instance-report',
                    'action' => 'expired-guarantee',
                ),
                array(
                    'label' => $translator->translate('Instances expired on technical lifetime'),
                    'route' => 'base',
                    'controller' => 'equipment-instance-report',
                    'action' => 'expired-lifetime',
                ),
            )
        ),
    ),
);