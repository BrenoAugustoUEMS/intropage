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
     /**
      * Renderiza a introdução do curso usando um template.
      *
      * @param stdClass $course Dados do curso (id, fullname, summary, startdate).
      * @return string O HTML renderizado.
      */
     public function render_course_intro($course) {
         global $DB;
 
         // Prepara os dados adicionais.
         $context = context_course::instance($course->id);
         $enrolledusers = count_enrolled_users($context); // Conta os usuários inscritos.
         $startdate = userdate($course->startdate); // Converte a data para o formato do usuário.
 
         // Prepara os dados para o template.
         $data = [
             'fullname' => $course->fullname, // Nome completo do curso.
             'summary' => format_text($course->summary), // Resumo formatado.
             'startdate' => $startdate, // Data de início formatada.
             'enrolledusers' => $enrolledusers, // Número de usuários inscritos.
         ];
 
         // Renderiza o template 'course_intro'.
         return $this->render_from_template('local_intropage/course_intro', $data);
     }
 }
