<?php
// Este arquivo define as permissões do plugin.

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/intropage:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'guest' => CAP_ALLOW,
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],
];
