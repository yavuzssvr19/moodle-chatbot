<?php

defined('MOODLE_INTERNAL') || die();
// Moodle yetki sistemine yeni hak tanımlar: 
$capabilities = [
    'local/chatbot:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
        ],
    ],
];
