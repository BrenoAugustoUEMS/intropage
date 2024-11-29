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
 
         // 3. Busca os números do campo ODS.
         $ods_numbers = local_intropage_get_ods_field($course->id);
 
         // 4. Busca a URL do campo Edital.
         $edital_url = local_intropage_get_edital_url($course->id);

         // 5. Obtém os dados do botão de inscrição/acesso.
         $enroll_button = local_intropage_get_enroll_button_data($course->id);
 
         // 7. Prepara os dados para o template.
         $data = [
             'fullname' => $course->fullname, // Nome completo do curso.
             'summary' => format_text($course->summary), // Resumo do curso.
             'enrolstart' => $enroldates['enrolstart'], // Data de início da autoinscrição.
             'enrolend' => $enroldates['enrolend'], // Data de término da autoinscrição.
             'categoryname' => $categoryname, // Nome da categoria do curso.
             'ods_numbers' => $ods_numbers, // Números do campo ODS.
             'edital_url' => $edital_url, // URL do edital.
             'enroll_button_text' => $enroll_button['text'], // Texto do botão.
             'enroll_button_icon' => $enroll_button['icon'], // Icone FontAwesome.
             'enroll_button_url' => $enroll_button['url'], // URL do botão.
             'pluginbaseurl' => (new moodle_url('/local/intropage'))->out(), // URL base do plugin.
         ];
 
         // Renderiza o conteúdo usando o template Mustache.
         return $this->render_from_template('local_intropage/course_intro', $data);
     }
 }
 