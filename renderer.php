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
    public function render_course_intro($course) {

        // Busca o nome da categoria associada ao curso.
        $categoryname = local_intropage_get_category_name($course->category);

        // Busca as datas de autoinscrição.
        $enroldates = local_intropage_get_autoenrol_dates($course->id);

        // Busca os números do campo ODS.
        $ods_numbers = local_intropage_get_ods_field($course->id);

        // Busca a URL do campo Edital.
        $edital_url = local_intropage_get_edital_url($course->id);


        // Prepara os dados para o template.
        $data = [
            'fullname' => $course->fullname,
            'summary' => format_text($course->summary),
            'enrolstart' => $enroldates['enrolstart'],
            'enrolend' => $enroldates['enrolend'],
            'categoryname' => $categoryname,
            'ods_numbers' => $ods_numbers,
            'edital_url' => $edital_url,
            'pluginbaseurl' => (new moodle_url('/local/intropage'))->out(),
        ];

        return $this->render_from_template('local_intropage/course_intro', $data);
    }
}
