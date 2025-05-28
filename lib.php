<?php

defined('MOODLE_INTERNAL') || die();

function local_chatbot_before_footer() {
    global $OUTPUT;
    echo $OUTPUT->render_from_template('local_chatbot/chatbutton', []);
}






