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
 * Página de introdução de cursos.
 *
 * @package    local_intropage
 * @copyright  2024 Breno Augusto <brenoaugusto@uems.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// Arquivo: local/intropage/index.php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

// Obtém o parâmetro "courseid" da URL.
$courseid = required_param('courseid', PARAM_INT);

// Obtém os dados do curso.
$course = local_intropage_get_course($courseid); // Função no lib.php

// Configura o contexto do curso.
$context = context_course::instance($courseid);

// Configura a página.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/intropage/index.php', ['courseid' => $courseid]));
$PAGE->set_title("Introdução a {$course->fullname}");
$PAGE->set_pagelayout('base'); // Layout simples para a página.
$PAGE->add_body_class('local-intropage-intro'); // Injeta Classe no body para isolar estilos nessa página
$PAGE->requires->css('/local/intropage/styles.css');

// Obtém o renderer do plugin.
$output = $PAGE->get_renderer('local_intropage');

// Renderiza a introdução do curso usando o template.
$coursehtml = $output->render_course_intro($course);

// Exibe a página.
echo $OUTPUT->header();
echo $coursehtml;
echo $OUTPUT->footer();
