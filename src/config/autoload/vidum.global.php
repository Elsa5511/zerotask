<?php
$dbTranslations = require 'config/vidum.translatable.php';

return array(
    'vidum' => array(
        'base' => array(
            'inactivity_timeout' => 30,
            'mail' => array(
                'smtp' => false, /* if you want to use smtp options change to true */                
                'from' => array('email' => 'support@vidum.no', 'name' => 'Vidum Systems'),
            ),
            'attachments' => array(
                'path' => 'data/attachment'
            )
        ),
        'image' => array(
            'width' => 520
        ),
        'check_reg_number_by_equipment' => true,
        'serial_number_unique' => true,
        'admin-email' => "test@vidum-admin.com",
        'password_expiration' => array(
            'enabled' => false,
            'expire_in' => 3, //months
            'warn_before' => 14 //days
        ),
        'features' => array(
            'instances' => array(
                'name' => 'Instances',
                'route' => array(
                    'controller' => 'equipment-instance',
                    'action' => 'index'
                )
            ),
            'documentation' => array(
                'name' => 'Documentation',
                'route' => array(
                    'controller' => 'documentation',
                    'action' => 'index',
                )
            ),
            'ladoc-documentation' => array(
                'name' => 'Documentation',
                'route' => array(
                    'controller' => 'ladoc-documentation',
                    'action' => 'index',
                )
            ),
            'training' => array(
                'name' => 'Training',
                'route' => array(
                    'controller' => 'training',
                    'action' => 'index'
                )
            ),
            'exercise' => array(
                'name' => 'Exercises',
                'route' => array(
                    'controller' => 'exercise',
                    'action' => 'index'
                )
            ),
            'exam' => array(
                'name' => 'Exams',
                'route' => array(
                    'controller' => 'exam',
                    'action' => 'index'
                )
            ),
            'certification' => array(
                'name' => 'Certification',
                'route' => array(
                    'controller' => 'certification',
                    'action' => 'index'
                )
            ),
            'attachments' => array(
                'name' => 'Attachments',
                'route' => array(
                    'controller' => 'equipment',
                    'action' => 'attachment-index'
                )
            ),
            'best_practice' => array(
                'name' => 'Best practices',
                'route' => array(
                    'controller' => 'best-practice',
                    'action' => 'index'
                )
            ),
        ),
        'translatable' => $dbTranslations
    )
);


