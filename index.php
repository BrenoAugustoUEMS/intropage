<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Exibe a página de introdução do curso
 *
 * @package    local_intropage
 * @copyright  2024 Breno Augusto <brenoaugusto@uems.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(__DIR__ . '/../../config.php');
 
 // Obtém o parâmetro "courseid" da URL.
 $courseid = required_param('courseid', PARAM_INT);
 
 // Verifica se o usuário está logado e tem permissão para visualizar o curso.
 $context = context_course::instance($courseid);
 
 // Obtém os dados do curso.
 $course = $DB->get_record('course', ['id' => $courseid], 'id, fullname, summary, category', MUST_EXIST);
 
 // Configura a página.
 $PAGE->requires->css('/local/intropage/styles.css');
 $PAGE->set_context($context);
 $PAGE->set_url(new moodle_url('/local/intropage/index.php', ['courseid' => $courseid]));
 $PAGE->set_title("Introdução a {$course->fullname}");
 
 
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