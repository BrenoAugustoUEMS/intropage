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
