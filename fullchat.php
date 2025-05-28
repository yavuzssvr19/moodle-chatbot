<?php

define('NO_MOODLE_COOKIES', true);
require_once(__DIR__ . '/../../config.php');
$PAGE->set_title("AI Chatbot");
$PAGE->set_heading("AI Destekli Chatbot");
$PAGE->set_pagelayout('embedded'); 
echo $OUTPUT->header();
?>
<!-- Başlık elle eklendi -->
<h2 style="text-align: center; margin-top: 20px;">AI Destekli Chatbot</h2>

<div id="chat-container" style="padding: 20px;">
    <!-- Chat UI burada olacak -->
</div>
<!-- FontAwesome ve stil -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
#chatbot-wrapper {
    max-width: 700px;
    margin: 40px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}

#chat-history {
    height: 400px;
    overflow-y: auto;
    border: 1px solid #ccc;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    background: #f9f9f9;
    display: flex;
    flex-direction: column;
}

.user-msg, .bot-msg {
    padding: 10px 15px;
    margin: 8px 0;
    border-radius: 12px;
    max-width: 80%;
    word-wrap: break-word;
    white-space: pre-line;
}

.user-msg {
    background: #d1e7dd;
    align-self: flex-end;
}

.bot-msg {
    background: #e2e3e5;
    align-self: flex-start;
}

#chat-input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    resize: none;
    margin-bottom: 10px;
}

#chat-send {
    background-color: #0d6efd;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
}

#chat-send:hover {
    background-color: #0b5ed7;
}

#loading-indicator {
    font-style: italic;
    color: #888;
    margin-left: 10px;
}

.dot {
    animation: blink 1.5s infinite;
    font-weight: bold;
}
.dot.one { animation-delay: 0s; }
.dot.two { animation-delay: 0.2s; }
.dot.three { animation-delay: 0.4s; }

@keyframes blink {
    0%, 80%, 100% { opacity: 0 }
    40% { opacity: 1 }
}
</style>


<div id="chatbot-wrapper">
    <div id="chat-history"></div>
    <div id="loading-indicator" style="display:none;">ChatBot yazıyor<span class="dot one">.</span><span class="dot two">.</span><span class="dot three">.</span></div>
    <textarea id="chat-input" rows="2" placeholder="Sorunuzu yazın..."></textarea>
    <button id="chat-send"><i class="fa fa-paper-plane"></i> Gönder</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#chat-send').click(async function() {
    const query = $('#chat-input').val().trim();
    if (!query) return;

    $('#chat-history').append(`<div class="user-msg"><b>Sen:</b> ${query}</div>`);
    $('#chat-input').val('');
    $('#loading-indicator').show();

    try {
        const response = await $.post('/moodle/local/chatbot/api.php', { query });
        $('#chat-history').append(`<div class="bot-msg"><b>ChatBot:</b> ${response}</div>`);
    } catch {
        $('#chat-history').append(`<div class="bot-msg"><b>Bot:</b> ❌ Hata oluştu.</div>`);
    }

    $('#loading-indicator').hide();
    $('#chat-history').scrollTop($('#chat-history')[0].scrollHeight);
});
</script>
<?php echo $OUTPUT->footer(); ?>

