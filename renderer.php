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
 * TODO describe file renderer
 *
 * @package    local_intropage
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_intropage_renderer extends plugin_renderer_base {
    public function render_course_intro($course) {
        global $DB;

        // Prepara o contexto do curso.
        $context = context_course::instance($course->id);

        // Formata a data de início do curso.
        $startdate = userdate($course->startdate);

        // Busca os campos personalizados para este curso.
        $customfield_handler = \core_customfield\handler::get_handler('core_course', 'course');
        $customfields_data = $customfield_handler->get_instance_data($course->id, true);

        // Busca informações de autoinscrição do curso.
        $enrolmethod = $DB->get_record('enrol', [
            'courseid' => $course->id,
            'enrol' => 'self' // Filtra apenas pelo método de autoinscrição
        ], 'enrolstartdate, enrolenddate');

        // Verifica se há método de autoinscrição configurado.
        if ($enrolmethod) {
            // Converte as datas de timestamp para um formato legível ou define "Data não informada".
            $enrolstart = $enrolmethod->enrolstartdate
                ? userdate($enrolmethod->enrolstartdate, '%d/%m/%Y %H:%M')
                : 'Data não informada';

            $enrolend = $enrolmethod->enrolenddate
                ? userdate($enrolmethod->enrolenddate, '%d/%m/%Y %H:%M')
                : 'Data não informada';
        } else {
            // Caso não haja autoinscrição configurada.
            $enrolstart = 'Data não informada';
            $enrolend = 'Data não informada';
        }


        // Prepara os dados para o template.
        $data = [
            'fullname' => $course->fullname,
            'summary' => format_text($course->summary),
            'startdate' => $startdate,
            'enrolstart' => $enrolstart,
            'enrolend' => $enrolend,
        ];

        return $this->render_from_template('local_intropage/course_intro', $data);
    }
}
