<?php
// Este arquivo adiciona configurações ao plugin na administração.

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_intropage', get_string('pluginname', 'local_intropage'));
    $ADMIN->add('localplugins', $settings);

    // Exemplo de configuração.
    $settings->add(new admin_setting_configtext(
        'local_intropage/example_setting',
        get_string('example', 'local_intropage'),
        get_string('example_desc', 'local_intropage'),
        'default value',
        PARAM_TEXT
    ));
}
