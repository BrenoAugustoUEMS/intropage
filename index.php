<?php
// Página inicial do plugin local_intropage.

require_once(__DIR__ . '/../../config.php');

require_login(); // Exige que o usuário esteja logado.

$context = context_system::instance();
require_capability('local/intropage:view', $context); // Verifica a permissão.

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/intropage/index.php'));
$PAGE->set_title(get_string('intropage', 'local_intropage'));
$PAGE->set_heading(get_string('intropage', 'local_intropage'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('intropage', 'local_intropage'));

// Conteúdo da página.
echo '<p>Welcome to the Intro Page plugin!</p>';

echo $OUTPUT->footer();
