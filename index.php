<?php
require_once(__DIR__ . '/../../config.php');

// Obtém o parâmetro "courseid" da URL.
$courseid = required_param('courseid', PARAM_INT);

// Verifica se o usuário está logado e tem permissão para visualizar o curso.
$context = context_course::instance($courseid);

// Obtém os dados do curso.
$course = $DB->get_record('course', ['id' => $courseid], 'id, fullname, summary, startdate', MUST_EXIST);

// Configura a página.
$PAGE->requires->css('/local/intropage/styles.css');
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/intropage/index.php', ['courseid' => $courseid]));
$PAGE->set_title("Introduction to {$course->fullname}");
$PAGE->set_heading("Introduction to {$course->fullname}");

// Tentativa de alterar o layout da página
$PAGE->set_pagelayout('base');

// Adiciona uma classe CSS personalizada ao <body>.
$PAGE->add_body_class('local-intropage-intro');

// Obtém o renderer do plugin.
$output = $PAGE->get_renderer('local_intropage');

// Renderiza a introdução do curso usando o template.
$coursehtml = $output->render_course_intro($course);

// Exibe a página.
echo $OUTPUT->header();
echo $coursehtml;
echo $OUTPUT->footer();
