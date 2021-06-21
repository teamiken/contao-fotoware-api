<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['fotoware_category'] =
    '{type_legend},type,jumpTo,fotoware_category,fotoware_filter_exclude,customTpl'
;

$GLOBALS['TL_DCA']['tl_content']['palettes']['fotoware_reader'] =
    '{type_legend},type,jumpTo,customTpl'
;

$GLOBALS['TL_DCA']['tl_content']['palettes']['fotoware_bookmarks'] =
    '{type_legend},type,customTpl'
;

$GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['jumpTo'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('dcaPicker'=>true,'tl_class'=>'clr'),
    'sql'                     => "varchar(255) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['fotoware_category'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['fotoware_category'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'clr'),
    'sql'                     => "varchar(255) NOT NULL default ''",
);


$GLOBALS['TL_DCA']['tl_content']['fields']['fotoware_filter_exclude'] = array
(
    'label'                    => &$GLOBALS['TL_LANG']['tl_content']['fotoware_filter_exclude'],
    'exclude'                  => true,
    'inputType'                => 'multiColumnWizard',
    'eval'                     => array
    (
        'columnFields'           => array
        (
            'filterId'       => array
            (
                'label'              => 'ID (z.B. 306)',
                'exclude'            => true,
                'inputType'          => 'text',
                'eval'               => array
                (
                    'rgxp'    => 'digit'
                )
            ),
        )
    ),
    'sql'                     => "blob NULL"
);
