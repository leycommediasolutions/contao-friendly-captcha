<?php

$GLOBALS['TL_DCA']['tl_form_field']['palettes']['friendlycaptcha'] = '{type_legend},type,name,label;{fconfig_legend},friendlyCaptchaSiteKey,friendlyCaptchaTheme,friendlyCaptchaLang,friendlyCaptchaPuzzleEndpoint;{expert_legend:hide},class;{template_legend:hide},customTpl;{invisible_legend:hide},invisible';

$GLOBALS['TL_DCA']['tl_form_field']['fields']['friendlyCaptchaSiteKey'] = [
    'inputType' => 'text',
    'eval' => ['mandatory' => true, 'tl_class' => 'w50 clr'],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['friendlyCaptchaTheme'] = [
    'inputType' => 'select',
    'options' => ['auto', 'light', 'dark'],
    'reference' => &$GLOBALS['TL_LANG']['tl_form_field']['friendlyCaptchaThemeOptions'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['friendlyCaptchaLang'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_form_field']['fields']['friendlyCaptchaPuzzleEndpoint'] = [
    'inputType' => 'text',
    'eval' => ['rgxp' => 'url', 'decodeEntities' => true, 'tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''",
];
