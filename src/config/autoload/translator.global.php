<?php

return array(
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../../module/Application/language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
);