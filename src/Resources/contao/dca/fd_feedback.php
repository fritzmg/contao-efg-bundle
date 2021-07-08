<?php

declare(strict_types=1);

/*
 *
 *  Contao Open Source CMS
 *
 *  Copyright (c) 2005-2014 Leo Feyer
 *
 *  @package   Efg
 *  @author    Thomas Kuhn <mail@th-kuhn.de>
 *  @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *  @copyright Thomas Kuhn 2007-2014
 *
 *
 *  Porting EFG to Contao 4
 *  Based on EFG Contao 3 from Thomas Kuhn
 *
 *  @package   contao-efg-bundle
 *  @author    Peter Broghammer <mail@pb-contao@gmx.de>
 *  @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *  @copyright Peter Broghammer 2021-
 *
 *  Thomas Kuhn's Efg package has been completely converted to contao 4.9
 *  extended by insert_tag  {{efg_insert::formalias::aliasvalue::column(::format)}}
 *
 */

// This file is created when saving a form in form generator
// last created on 2021-04-11 15:38:35 by saving form ""

/*
 * Table tl_formdata defined by form ""
 */
$GLOBALS['TL_DCA']['tl_formdata'] = [
    // Config
    'config' => [
        'dataContainer' => 'Formdata',
        'ctable' => ['tl_formdata_details'],
        'closed' => true,
        'notEditable' => false,
        'enableVersioning' => false,
        'doNotCopyRecords' => true,
        'doNotDeleteRecords' => true,
        'switchToEdit' => true,
    ],
    // List
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['date DESC'],
            'flag' => 8,
            'panelLayout' => 'filter;search,sort,limit',
        ],
        'label' => [
            'fields' => ['date', 'form', 'alias', 'be_notes', 'PETERNAME', 'PETERWERT'],
            /*
            'format'                  => '<div class="fd_wrap">
    <div class="fd_head">%s<span>[%s]</span></div>
        <div class="fd_notes">%s</div>
    <div class="fd_row field_PETERNAME"><div class="fd_label">PeterName: </div><div class="fd_value">%s </div></div>
    <div class="fd_row field_PETERWERT"><div class="fd_label">PeterWert: </div><div class="fd_value">%s </div></div>
        </div>',
            */
            'label_callback' => ['tl_fd_feedback', 'getRowLabel'],
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_formdata']['edit'],
                'href' => 'act=edit',
                'button_callback' => ['FormdataBackend', 'callbackEditButton'],
                'icon' => 'bundles/contaoefgco4/icons/edit.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_formdata']['delete'],
                'href' => 'act=delete',
                'icon' => 'bundles/contaoefgco4/icons/delete.gif',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_formdata']['show'],
                'href' => 'act=show',
                'icon' => 'bundles/contaoefgco4/icons/show.gif',
            ],
        ],
    ],
    // Palettes
    'palettes' => [
        'default' => 'form,alias,date,ip,published,sorting;{confirmation_legend},confirmationSent,confirmationDate;{fdNotes_legend:hide},be_notes;{fdOwner_legend:hide},fd_member,fd_user,fd_member_group,fd_user_group;{fdDetails_legend},PETERNAME,PETERWERT',
    ],

    // Base fields in table tl_formdata
    'fields' => [
        'form' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['form'],
            'inputType' => 'select',
            'exclude' => false,
            'search' => true,
            'filter' => true,
            'sorting' => true,
            'options_callback' => ['tl_formdata', 'getFormsSelect'],
            'eval' => ['chosen' => true, 'tl_class' => 'w50'],
        ],
        'date' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['date'],
            'inputType' => 'text',
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'filter' => true,
            'flag' => 8,
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
        ],
        'ip' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['ip'],
            'inputType' => 'text',
            'exclude' => true,
            'search' => true,
            'sorting' => false,
            'filter' => false,
            'eval' => ['tl_class' => 'w50'],
        ],
        'fd_member' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['fd_member'],
            'exclude' => true,
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'options_callback' => ['tl_formdata', 'getMembersSelect'],
        ],
        'fd_user' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['fd_user'],
            'exclude' => true,
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'options_callback' => ['tl_formdata', 'getUsersSelect'],
        ],
        'fd_member_group' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['fd_member_group'],
            'exclude' => true,
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'options_callback' => ['tl_formdata', 'getMemberGroupsSelect'],
        ],
        'fd_user_group' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['fd_user_group'],
            'exclude' => true,
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'mandatory' => false, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'options_callback' => ['tl_formdata', 'getUserGroupsSelect'],
        ],
        'published' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['published'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12 cbx clr'],
            // 'default'                 => '1'
        ],
        'sorting' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['sorting'],
            'exclude' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'digit', 'maxlength' => 10, 'tl_class' => 'w50'],
        ],
        'alias' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['alias'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'alnum', 'unique' => true, 'spaceToUnderscore' => true, 'maxlength' => 64, 'tl_class' => 'w50'],
            'save_callback' => [
                ['tl_formdata', 'generateAlias'],
            ],
        ],
        'confirmationSent' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['confirmationSent'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50', 'doNotCopy' => true, 'isBoolean' => true],
        ],
        'confirmationDate' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['confirmationDate'],
            'exclude' => true,
            'filter' => true,
            'flag' => 8,
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
        ],
        'be_notes' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['be_notes'],
            'inputType' => 'textarea',
            'exclude' => true,
            'search' => true,
            'sorting' => false,
            'filter' => false,
            'eval' => ['rte' => 'tinyMCE', 'cols' => 80, 'rows' => 5, 'style' => 'height: 80px'],
        ],
        'import_source' => [
            'label' => &$GLOBALS['TL_LANG']['tl_formdata']['import_source'],
            'eval' => ['fieldType' => 'radio', 'files' => true, 'filesOnly' => true, 'extensions' => 'csv', 'class' => 'mandatory'],
        ],
    ],
    'tl_formdata' => [
        'baseFields' => ['id', 'sorting', 'tstamp', 'form', 'ip', 'date', 'fd_member', 'fd_user', 'fd_member_group', 'fd_user_group', 'published', 'alias', 'be_notes', 'confirmationSent', 'confirmationDate'],
        'detailFields' => ['PETERNAME', 'PETERWERT'],
    ],
];

// Detail fields in table tl_formdata_details
// 'PETERNAME'
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['label'] = ['PeterName', '[PETERNAME] PeterName'];
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['inputType'] = 'text';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['formfieldType'] = 'text';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['exclude'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['search'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['sorting'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['filter'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['ff_id'] = 25;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['f_id'] = 99;
// 'PETERWERT'
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['label'] = ['PeterWert', '[PETERWERT] PeterWert'];
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['inputType'] = 'text';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['formfieldType'] = 'text';
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['exclude'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['search'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['sorting'] = true;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['filter'] = false;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['ff_id'] = 26;
$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['f_id'] = 99;

/*
 * Class tl_fd_
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @copyright  Thomas Kuhn 2007-2014
 * @author     Thomas Kuhn <mail@th-kuhn.de>
 * @package    Efg
 */
/*
 * erweitert fuer contao 4
 * PBD verhinderung der doppeldefinition der class bei delete eines Eintrags aus der Tabelle
 */
if (!class_exists('tl_fd_feedback', false)) {
    class fd_feedback extends \Backend
    {
        /**
         * Database result.
         *
         * @var array
         */
        protected $arrData;

        public function __construct()
        {
            parent::__construct();
        }

        /*
        * Create list label for formdata item
        * This can be used to customize the backend list view for formdata
        */
        public function getRowLabel($arrRow)
        {
            $strRowLabel = '';

            $strKey = 'unpublished';

            $strRowLabel .= '<div class="fd_wrap">';
            $strRowLabel .= '<div class="fd_head">'.date($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']).'<span>['.$arrRow['form'].']</span><span>'.$arrRow['alias'].'</span></div>';
            $strRowLabel .= '<div class="fd_notes">'.$arrRow['be_notes'].'</div>';
            $strRowLabel .= '<div class="mark_links">';
            if (\strlen($arrRow['PETERNAME'])) {
                $strRowLabel .= '<div class="fd_row field_PETERNAME">';
                $strRowLabel .= '<div class="fd_label">'.$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERNAME']['label'][0].': </div>';
                $strRowLabel .= '<div class="fd_value">'.$arrRow['PETERNAME'].' </div>';
                $strRowLabel .= '</div>';
            }
            if (\strlen($arrRow['PETERWERT'])) {
                $strRowLabel .= '<div class="fd_row field_PETERWERT">';
                $strRowLabel .= '<div class="fd_label">'.$GLOBALS['TL_DCA']['tl_formdata']['fields']['PETERWERT']['label'][0].': </div>';
                $strRowLabel .= '<div class="fd_value">'.$arrRow['PETERWERT'].' </div>';
                $strRowLabel .= '</div>';
            }
            $strRowLabel .= '</div></div>';

            return $strRowLabel;
        }
    }
}
