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
function local_intropage_get_category($categoryid) {
    global $DB;

    // Busca a categoria pelo ID.
    $category = $DB->get_record('course_categories', ['id' => $categoryid], 'id, name, path', IGNORE_MISSING);

    if ($category) {
        // Divide o caminho (path) para obter as IDs das categorias.
        $pathids = explode('/', trim($category->path, '/'));

        // Busca o nome de todas as categorias no caminho.
        $categories = [];
        foreach ($pathids as $catid) {
            $cat = $DB->get_record('course_categories', ['id' => $catid], 'name', IGNORE_MISSING);
            if ($cat) {
                $categories[] = $cat->name;
            }
        }

        // Retorna as categorias formatadas com " > " entre elas.
        return implode(' > ', $categories);
    }

    return 'Categoria não encontrada';
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
 * Obtém todos os campos personalizados de um curso.
 *
 * @param int $courseid O ID do curso.
 * @return array Um array associativo onde o nome curto do campo é a chave e o valor é o valor do campo.
 */
function local_intropage_get_all_custom_fields($courseid) {
    // Obtém o manipulador de campos personalizados para cursos.
    $customfield_handler = \core_customfield\handler::get_handler('core_course', 'course');

    // Obtém os dados personalizados para este curso.
    $customfields_data = $customfield_handler->get_instance_data($courseid, true);

    // Inicializa um array para armazenar os campos personalizados.
    $customfields = [];

    // Itera pelos campos personalizados e preenche o array com shortname => valor.
    foreach ($customfields_data as $data) {
        $shortname = $data->get_field()->get('shortname'); // Nome curto do campo.
        $value = $data->get_value(); // Valor do campo.
        $customfields[$shortname] = $value;
    }

    return $customfields;
}


/**
 * Obtém e processa o campo personalizado "ods" associado a um curso.
 * Extrai os números e retorna apenas os que estão no intervalo de 1 a 17.
 *
 * @param int $courseid O ID do curso.
 * @return array Um array contendo os números de 1 a 17 extraídos do campo "ods".
 */
function local_intropage_get_ods_field($courseid) {
    // Obtém todos os campos personalizados do curso.
    $customfields = local_intropage_get_all_custom_fields($courseid);

    // Verifica se o campo "ods" existe.
    if (isset($customfields['ods'])) {
        // Divide a string em partes usando a vírgula como delimitador.
        $ods_parts = explode(',', $customfields['ods']);

        // Remove espaços extras, converte para inteiros e filtra os números válidos (1-17).
        return array_filter(array_map('intval', array_map('trim', $ods_parts)), function($num) {
            return $num >= 1 && $num <= 17;
        });
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
    // Obtém todos os campos personalizados do curso.
    $customfields = local_intropage_get_all_custom_fields($courseid);

    // Retorna o valor do campo "edital_url", se existir.
    return $customfields['edital_url'] ?? null;
}

/**
 * Obtém o valor do campo personalizado "actions" de um curso.
 *
 * @param int $courseid O ID do curso.
 * @return string|null O valor do campo "actions" ou null se não estiver configurado.
 */
function local_intropage_get_actions_field($courseid) {
    $customfields = local_intropage_get_all_custom_fields($courseid);
    $customfields['actions'] = strip_tags($customfields['actions']);

    return $customfields['actions'] ?? null;
}

/**
 * Obtém o valor do campo personalizado "target" de um curso.
 *
 * @param int $courseid O ID do curso.
 * @return string|null O valor do campo "target" ou null se não estiver configurado.
 */
function local_intropage_get_target_field($courseid) {
    $customfields = local_intropage_get_all_custom_fields($courseid);
    $customfields['target'] = strip_tags($customfields['target']);

    return $customfields['target'] ?? null;
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

/**
 * Gera os dados do botão dinâmico de inscrição ou acesso ao curso.
 *
 * @param int $courseid O ID do curso.
 * @param int|null $userid O ID do usuário (opcional, padrão é o usuário atual).
 * @return array Contendo 'text' (texto do botão), 'icon' (ícone do botão), e 'url' (URL do botão).
 */
function local_intropage_get_enroll_button_data($courseid, $userid = null) {
    global $DB, $USER;

    // Usa o usuário atual se nenhum ID for passado.
    if ($userid === null) {
        $userid = $USER->id;
    }

    // Obtém o contexto do curso e o contexto da categoria.
    $context_course = context_course::instance($courseid);
    $course = $DB->get_record('course', ['id' => $courseid], 'category');
    $context_category = $course ? context_coursecat::instance($course->category) : null;

    // Verifica se o usuário está inscrito no curso.
    $is_enrolled = local_intropage_is_user_enrolled($courseid, $userid);

    // Verifica se o usuário é administrador ou gerente na categoria ou curso.
    $is_manager = (
        has_capability('moodle/course:update', $context_course, $userid) || // Capacidade de editar cursos.
        ($context_category && has_capability('moodle/category:manage', $context_category, $userid)) // Gerente da categoria.
    );

    // Caso o usuário esteja inscrito ou seja administrador/gerente, mostre "Acesse".
    if ($is_enrolled || $is_manager) {
        return [
            'text' => 'Acesse',
            'icon' => 'fa-solid fa-right-to-bracket',
            'url' => (new moodle_url('/course/view.php', ['id' => $courseid]))->out(),
        ];
    }

    // Busca o método de autoinscrição no banco de dados.
    $enrol = $DB->get_record('enrol', [
        'courseid' => $courseid,
        'enrol' => 'self', // Método de inscrição: autoinscrição.
    ], 'id, status', IGNORE_MISSING);

    if ($enrol->status==0) {
        // Redireciona para a página de inscrição do curso com o enrolid.
        return [
            'text' => 'Inscreva-se',
            'icon' => 'fa-solid fa-cloud-arrow-up',
            'url' => (new moodle_url('/enrol/index.php', [
                'id' => $courseid, // ID do curso.
                'enrolid' => $enrol->id, // ID da instância de autoinscrição.
            ]))->out(),
        ];
    }

    // Caso não haja autoinscrição configurada.
    return [
        'text' => 'Indisponível',
        'icon' => 'fa-solid fa-circle-xmark',
        'url' => null,
    ];
}
