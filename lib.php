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
 * Callback implementations for Intro Page
 *
 * @package    local_intropage
 * @copyright  2024 Breno Augusto <brenoaugusto@uems.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Busca o nome da categoria associada ao curso.
 *
 * @param int $categoryid O ID da categoria do curso.
 * @return string O nome da categoria ou "Categoria não encontrada".
 */
function local_intropage_get_category_name($categoryid) {
    global $DB;

    // Busca a categoria pelo ID.
    $category = $DB->get_record('course_categories', ['id' => $categoryid], 'id, name', IGNORE_MISSING);

    if ($category) {
        return $category->name; // Retorna o nome da categoria.
    } else {
        return 'Categoria não encontrada'; // Fallback.
    }
}


/**
 * Busca as datas de início e fim da autoinscrição de um curso.
 *
 * @param int $courseid O ID do curso.
 * @return array Um array contendo as datas 'enrolstart' e 'enrolend'.
 */
function local_intropage_get_autoenrol_dates($courseid) {
    global $DB;

    // Busca o método de autoinscrição.
    $enrolmethod = $DB->get_record('enrol', [
        'courseid' => $courseid,
        'enrol' => 'self'
    ], 'enrolstartdate, enrolenddate', IGNORE_MISSING);

    // Verifica se o método foi encontrado.
    if ($enrolmethod) {
        return [
            'enrolstart' => $enrolmethod->enrolstartdate
                ? userdate($enrolmethod->enrolstartdate, '%d/%m/%Y %H:%M')
                : 'Data não informada',
            'enrolend' => $enrolmethod->enrolenddate
                ? userdate($enrolmethod->enrolenddate, '%d/%m/%Y %H:%M')
                : 'Data não informada',
        ];
    } else {
        return [
            'enrolstart' => 'Data não informada',
            'enrolend' => 'Data não informada',
        ];
    }
}

/**
 * Obtém e processa o campo personalizado "ods" associado a um curso.
 * Extrai os números e retorna apenas os que estão no intervalo de 1 a 17.
 *
 * @param int $courseid O ID do curso.
 * @return array Um array contendo os números de 1 a 17 extraídos do campo "ods".
 */
function local_intropage_get_ods_field($courseid) {
    // Obtém o manipulador de campos personalizados para cursos.
    $customfield_handler = \core_customfield\handler::get_handler('core_course', 'course');

    // Obtém os dados personalizados para este curso.
    $customfields_data = $customfield_handler->get_instance_data($courseid, true);

    // Inicializa o array para armazenar os números extraídos.
    $ods_numbers = [];

    // Itera pelos campos para encontrar o campo "ods".
    foreach ($customfields_data as $data) {
        // Verifica se o campo tem o nome curto "ods".
        if ($data->get_field()->get('shortname') === 'ods') {
            // Obtém o valor do campo "ods" como string.
            $ods_value = $data->get_value();

            // Divide a string em partes usando a vírgula como delimitador.
            $ods_parts = explode(',', $ods_value);

            // Remove espaços extras e converte para inteiros.
            $ods_numbers = array_map('intval', array_map('trim', $ods_parts));

            // Filtra apenas os números dentro do intervalo de 1 a 17.
            $ods_numbers = array_filter($ods_numbers, function($num) {
                return $num >= 1 && $num <= 17;
            });

            break; // Sai do loop após encontrar e processar o campo "ods".
        }
    }

    return $ods_numbers;
}
