<?php
// Este arquivo faz parte do Moodle - http://moodle.org/
//
// O Moodle é um software livre: você pode redistribuí-lo e/ou modificá-lo
// nos termos da Licença Pública Geral GNU, conforme publicada pela Free Software Foundation,
// seja na versão 3 da Licença ou (a seu critério) qualquer versão posterior.
//
// O Moodle é distribuído na esperança de que seja útil, mas SEM QUALQUER GARANTIA;
// sem mesmo a garantia implícita de COMERCIABILIDADE ou ADEQUAÇÃO A UM DETERMINADO FIM.
// Consulte a Licença Pública Geral GNU para mais detalhes.
//
// Você deve ter recebido uma cópia da Licença Pública Geral GNU
// junto com o Moodle. Se não, veja <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * Lida com redirecionamentos para a página introdutória do curso.
 *
 * @package    local_intropage
 * @copyright  2024 Breno Augusto <brenoaugusto@uems.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 ou posterior
 */

/**
 * Lógica para redirecionar o usuário à página introdutória.
 *
 * @param int $courseid O ID do curso.
 * @return void
 */
function local_intropage_handle_redirection() {
    global $PAGE, $SESSION;

    // Verifica se estamos na página principal do curso.
    if ($PAGE->pagetype === 'course-view') {
        // Obtém o ID do curso da URL.
        $courseid = optional_param('id', 0, PARAM_INT);

        // Verifica se o ID do curso é válido.
        if ($courseid) {
            // Checa se o redirecionamento já foi feito nesta sessão.
            if (!empty($SESSION->intropage_done) && $SESSION->intropage_done === $courseid) {
                debugging("Redirecionamento já feito para o curso ID: $courseid. Nenhuma ação necessária.");
                return; // Não redireciona novamente.
            }

            // Marca na sessão que o redirecionamento foi feito.
            $SESSION->intropage_done = $courseid;

            // Redireciona para a página introdutória do curso.
            debugging("Redirecionando para a página introdutória do curso ID: $courseid.");
            redirect(new moodle_url('/local/intropage/index.php', ['courseid' => $courseid]));
        } else {
            debugging('ID do curso não encontrado na URL. Nenhum redirecionamento será feito.');
        }
    } else {
        debugging('Página não é do tipo course-view. Nenhum redirecionamento necessário.');
    }
}