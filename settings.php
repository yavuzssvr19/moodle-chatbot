<?php
// local/chatbot/settings.php
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    // Site administration → Reports
    $ADMIN->add(
        'reports', // “Reports” kategorisi
        new admin_externalpage(
            'local_chatbot_adminreport',                 // benzersiz ad
            get_string('chatbotreports', 'local_chatbot'), // başlık
            new moodle_url('/local/chatbot/adminreport.php'), // URL
            'moodle/site:config'                         // yetki kontrolü
        )
    );
}
