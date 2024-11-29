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
 * Funções auxiliares para o plugin Intro Page
 *
 * @package    local_intropage
 * @copyright  2024 Breno Augusto <brenoaugusto@uems.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

//////////////////////////////////////////////////////////////
// Funções para buscar dados do curso e campos personalizados //
//////////////////////////////////////////////////////////////

/**
 * Obtém os dados básicos do curso.
 *
 * @param int $courseid O ID do curso.
 * @return stdClass Dados do curso.
 */
function local_intropage_get_course($courseid) {
    global $DB;

    return $DB->get_record('course', ['id' => $courseid], 'id, fullname, summary, category', MUST_EXIST);
}

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

    // Retorna o nome da categoria ou "Categoria não encontrada".
    return $category ? $category->name : 'Categoria não encontrada';
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

    // Retorna as datas formatadas ou "Data não informada" se não existirem.
    return [
        'enrolstart' => $enrolmethod && $enrolmethod->enrolstartdate
            ? userdate($enrolmethod->enrolstartdate, '%d/%m/%Y %H:%M')
            : 'Data não informada',
        'enrolend' => $enrolmethod && $enrolmethod->enrolenddate
            ? userdate($enrolmethod->enrolenddate, '%d/%m/%Y %H:%M')
            : 'Data não informada',
    ];
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

    // Itera pelos campos para encontrar o campo "ods".
    foreach ($customfields_data as $data) {
        if ($data->get_field()->get('shortname') === 'ods') {
            // Obtém o valor do campo "ods" como string.
            $ods_value = $data->get_value();

            // Divide a string em partes usando a vírgula como delimitador.
            $ods_parts = explode(',', $ods_value);

            // Remove espaços extras, converte para inteiros e filtra os números válidos (1-17).
            return array_filter(array_map('intval', array_map('trim', $ods_parts)), function($num) {
                return $num >= 1 && $num <= 17;
            });
        }
    }

    // Retorna um array vazio se o campo "ods" não for encontrado.
    return [];
}

/**
 * Obtém o valor do campo personalizado "edital_url" associado a um curso.
 *
 * @param int $courseid O ID do curso.
 * @return string|null A URL do edital ou NULL se o campo não estiver configurado.
 */
function local_intropage_get_edital_url($courseid) {
    // Obtém o manipulador de campos personalizados para cursos.
    $customfield_handler = \core_customfield\handler::get_handler('core_course', 'course');

    // Obtém os dados personalizados para este curso.
    $customfields_data = $customfield_handler->get_instance_data($courseid, true);

    // Itera pelos campos para encontrar o campo "edital_url".
    foreach ($customfields_data as $data) {
        if ($data->get_field()->get('shortname') === 'edital_url') {
            return $data->get_value(); // Retorna o valor do campo "edital_url".
        }
    }

    // Retorna NULL se o campo "edital_url" não for encontrado.
    return null;
}

////////////////////////////////////////////////////////
// Funções auxiliares para manipulação de inscrições //
////////////////////////////////////////////////////////

/**
 * Verifica se o usuário está inscrito no curso.
 *
 * @param int $courseid O ID do curso.
 * @param int|null $userid O ID do usuário (opcional, padrão é o usuário atual).
 * @return bool Retorna true se o usuário estiver inscrito, false caso contrário.
 */
function local_intropage_is_user_enrolled($courseid, $userid = null) {
    global $USER;

    // Usa o ID do usuário atual se nenhum for fornecido.
    if ($userid === null) {
        $userid = $USER->id;
    }

    // Obtém o contexto do curso.
    $context = context_course::instance($courseid);

    // Verifica se o usuário está inscrito no contexto do curso.
    return is_enrolled($context, $userid, '', true);
}
