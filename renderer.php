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
 * renderer
 *
 * @package    local_intropage
 * @copyright  2024 Breno Augusto <brenoaugusto@uems.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 defined('MOODLE_INTERNAL') || die();

 require_once(__DIR__ . '/lib.php'); // Importa as funções reutilizáveis.
 
 class local_intropage_renderer extends plugin_renderer_base {
     /**
      * Renderiza a introdução do curso.
      *
      * @param stdClass $course Dados do curso.
      * @return string HTML da introdução renderizada.
      */
     public function render_course_intro($course) {
         // 1. Busca o nome da categoria associada ao curso.
         $categoryname = local_intropage_get_category_name($course->category);
 
         // 2. Busca as datas de autoinscrição.
         $enroldates = local_intropage_get_autoenrol_dates($course->id);
 
         // 3. Busca os valores dos campos personalizados.
         $ods_numbers = local_intropage_get_ods_field($course->id);
         $edital_url = local_intropage_get_edital_url($course->id);
         $actions = local_intropage_get_actions_field($course->id);
         $target = local_intropage_get_target_field($course->id);

         // 4. Obtém os dados do botão de inscrição/acesso.
         $enroll_button = local_intropage_get_enroll_button_data($course->id);
 
         // 5. Prepara os dados para o template.
         $data = [
            'course' => [
                'fullname' => $course->fullname,
                'summary' => format_text($course->summary),
                'categoryname' => $categoryname,
            ],
            'enrol' => [
                'enrolstart' => $enroldates['enrolstart'],
                'enrolend' => $enroldates['enrolend'],
                'button' => [
                    'text' => $enroll_button['text'],
                    'icon' => $enroll_button['icon'],
                    'url' => $enroll_button['url'],
                ],
            ],
            'customfields' => [
                'ods' => $ods_numbers,
                'edital' => ['url' => $edital_url,],
                'actions' => $actions,
                'target' => $target,
            ],
            'plugin' => [
                'baseurl' => (new moodle_url('/local/intropage'))->out(),
            ],
        ];
 
         // Renderiza o conteúdo usando o template Mustache.
         return $this->render_from_template('local_intropage/course_intro', $data);
     }
 }
 