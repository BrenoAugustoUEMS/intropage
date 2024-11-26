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

        // Conta o número de usuários inscritos.
        $enrolledusers = count_enrolled_users($context);

        // Formata a data de início do curso.
        $startdate = userdate($course->startdate);

        // Busca os campos personalizados para este curso.
        $customfield_handler = \core_customfield\handler::get_handler('core_course', 'course');
        $customfields_data = $customfield_handler->get_instance_data($course->id, true);

        $difficulty = null; // Valor padrão se o campo não existir.

        // Itera sobre os campos personalizados para encontrar o "difficulty".
        foreach ($customfields_data as $field) {
            if ($field->get_field()->get('shortname') === 'difficulty') {
                $valueid = $field->get('value'); // Obtém o ID do valor (número).
                
                // Obtém o texto legível associado ao ID.
                $options = $field->get_field()->get_options(); // Retorna todas as opções do menu suspenso.
                $difficulty = $options[$valueid] ?? null; // Busca o texto correspondente ao ID.
                break;
            }
        }

        // Prepara os dados para o template.
        $data = [
            'fullname' => $course->fullname,
            'summary' => format_text($course->summary),
            'startdate' => $startdate,
            'enrolledusers' => $enrolledusers,
            'difficulty' => $difficulty, // Adiciona o nível de dificuldade ao template.
        ];

        return $this->render_from_template('local_intropage/course_intro', $data);
    }
}
