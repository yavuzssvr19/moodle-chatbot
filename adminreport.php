<?php
require_once(__DIR__ . '/../../config.php');
require_login(); // Kullanıcının giriş yapmasını zorunlu kılar.

if (!is_siteadmin()) { // Sadece site yöneticilerinin erişmesine izin verir. Diğer kullanıcılar için “Erişim reddedildi” hatası gösterilir.
    print_error('Erişim reddedildi');
}

$PAGE->set_url(new moodle_url('/local/chatbot/adminreport.php')); // sayfanın Moodle içinde tanımlı URL’sidir.
$PAGE->set_context(context_system::instance());
$PAGE->set_title("Chatbot Etkileşim Raporu");
$PAGE->set_heading("Yalnızca Yönetici - Chatbot Etkileşimleri");

echo $OUTPUT->header();
// Veritabanından Logları Alma işelmi: chatbot_logs tablosundan 
global $DB;

// Kullanıcı bilgisiyle birlikte chatbot loglarını al
$sql = "SELECT l.id, l.userid, l.question, l.answer, l.timecreated, u.firstname, u.lastname
        FROM {chatbot_logs} l
        JOIN {user} u ON l.userid = u.id
        ORDER BY l.timecreated DESC";

$logs = $DB->get_records_sql($sql);

echo '<table class="generaltable">
    <thead>
        <tr>
            <th>Kullanıcı</th>
            <th>Soru</th>
            <th>Cevap</th>
            <th>Tarih</th>
        </tr>
    </thead>
    <tbody>';

foreach ($logs as $log) {
    $date = date('Y-m-d H:i:s', $log->timecreated);
    $fullname = fullname((object)[
        'firstname' => $log->firstname,
        'lastname' => $log->lastname
    ]);
    
    echo "<tr>
            <td>{$fullname}</td>
            <td>{$log->question}</td>
            <td>{$log->answer}</td>
            <td>{$date}</td>
          </tr>";
}

echo '</tbody></table>';
echo $OUTPUT->footer(); // Moodle sayfa altbilgisini (footer) gösterir.
?>

