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

/**
 * Namespace.
 */

namespace PBDKN\Efgco4\Resources\contao\widgets;

use PBDKN\Efgco4\Resources\contao\classes\EfgLog;

/**
 * Class EfgLookupOptionWizard.
 *
 * Provide methods to handle form field lookup option
 *
 * @copyright  Thomas Kuhn 2007-2014
 */
class EfgLookupOptionWizard extends \Contao\Widget
{
    /**
     * Submit user input.
     *
     * @var bool
     */
    protected $blnSubmitInput = true;

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * DB Tables and fields.
     *
     * @var array
     */
    protected $arrDbStruct = [];

    /**
     * DB Tables to ignore.
     *
     * @var array
     */
    protected $arrIgnoreTables = [];

    /**
     * DB Fields to ignore.
     *
     * @var array
     */
    protected $arrIgnoreFields = [];

    /**
     * Add specific attributes.
     *
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue): void
    {
        switch ($strKey) {
            case 'value':
                $this->varValue = deserialize($varValue);
                break;

            case 'mandatory':
                $this->arrConfiguration['mandatory'] = $varValue ? true : false;
                break;

            default:
                parent::__set($strKey, $varValue);
                break;
        }
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        EfgLog::EfgwriteLog(debfull, __METHOD__, __LINE__, '-> ');
        $this->arrIgnoreTables = [
            'tl_formdata',
            'tl_formdata_details',
            'tl_cache',
            'tl_extension',
            'tl_layout',
            'tl_log',
            'tl_lock',
            'tl_repository_installs',
            'tl_repository_instfiles',
            'tl_search',
            'tl_search_index',
            'tl_session',
            'tl_style',
            'tl_style_sheet',
            'tl_undo',
            'tl_version',
        ];
        $this->arrIgnoreFields = [
            /* 'id', */
            'pid', 'tstamp', 'sorting',
        ];

        // get all tables
        $arrTables = \Database::getInstance()->listTables();

        foreach ($arrTables as $strTable) {
            if (!\in_array($strTable, $this->arrIgnoreTables, true)) {
                $arrFields = \Database::getInstance()->listFields($strTable);

                foreach ($arrFields as $arrField) {
                    if (!\in_array($arrField['name'], $this->arrIgnoreFields, true) && 'index' !== $arrField['type']) {
                        $this->arrDbStruct[$strTable][] = $arrField['name'];
                    }
                }
            }
        }

        unset($arrTables, $arrFields);

        // get all forms marked to store data
        $objForms = \Database::getInstance()->prepare('SELECT id,title,formID,alias FROM tl_form WHERE storeFormdata=?')->execute('1');
        if ($objForms->numRows) {
            while ($objForms->next()) {
                $varKey = 'fd_'.((!empty($objForms->alias)) ? $objForms->alias : str_replace('-', '_', standardize($objForms->title)));

                if (!\in_array($varKey, $this->arrIgnoreTables, true)) {
                    $objFields = \Database::getInstance()->prepare("SELECT DISTINCT ff.name FROM tl_form_field ff, tl_form f WHERE (ff.pid=f.id) AND ff.name != '' AND f.id=?")->execute($objForms->id);
                    if ($objFields->numRows) {
                        $this->arrDbStruct[$varKey][] = 'form';
                        $this->arrDbStruct[$varKey][] = 'published';
                        while ($objFields->next()) {
                            $this->arrDbStruct[$varKey][] = $objFields->name;
                        }
                    }
                }
            }
        }
        unset($arrTables, $arrFields);

        ksort($this->arrDbStruct);

        // Make sure there is at least an empty array
        if (!\is_array($this->varValue) || !$this->varValue['lookup_field']) {
            $this->varValue = [['']];
        }

        $strSelectedTable = '';
        if (isset($this->varValue['lookup_field']) && \strlen($this->varValue['lookup_field'])) {
            $strSelectedTable = substr($this->varValue['lookup_field'], 0, strpos($this->varValue['lookup_field'], '.'));
        }

        $return = '';

        EfgLog::EfgwriteLog(debsfull, __METHOD__, __LINE__, 'Begin table strSelectedTable '.$strSelectedTable);
        // Begin table

        // table field used as option label
        $return .= '<div class="w50"><h3><label for="'.$this->strId.'_lookup_field">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_field'][0].'</label></h3>
				<select name="'.$this->strId.'[lookup_field]" id="'.$this->strId.'_lookup_field" class="tl_select tl_chosen" onchange="Backend.autoSubmit(\'tl_form_field\');" onfocus="Backend.getScrollOffset();">';
        //EfgLog::EfgwriteLog(debsfull, __METHOD__, __LINE__, "return 1\n".$return);
        foreach ($this->arrDbStruct as $strTable => $arrFields) {
            $return .= '<optgroup label="'.$strTable.'">';
            foreach ($arrFields as $strField) {
                if ('id' === $strField) {
                    continue;
                }
                $strSelected = ($this->varValue['lookup_field'] === $strTable.'.'.$strField) ? ' selected ' : '';
                $return .= '<option value="'.$strTable.'.'.$strField.'"'.$strSelected.'>'.$strTable.'.'.$strField.'</option>';
            }
            $return .= '</optgroup>';
        }
        $return .= '</select>';
        //EfgLog::EfgwriteLog(debsfull, __METHOD__, __LINE__, "return 2\n".$return);
        $return .= '
			<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_field'][1].'</p></div>';

        // table field used as option value
        if ('' !== $strSelectedTable && 'fd_' !== substr($strSelectedTable, 0, 3)) {
            $return .= '<div class="w50"><h3><label for="'.$this->strId.'_lookup_val_field">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_val_field'][0].'</label></h3>';
            $return .= '<select name="'.$this->strId.'[lookup_val_field]" id="'.$this->strId.'_lookup_val_field" class="tl_select tl_chosen">';
            foreach ($this->arrDbStruct as $strTable => $arrFields) {
                if ($strSelectedTable === $strTable) {
                    foreach ($arrFields as $strField) {
                        $strSelected = ($this->varValue['lookup_val_field'] === $strTable.'.'.$strField) ? ' selected ' : '';
                        $return .= '<option value="'.$strTable.'.'.$strField.'"'.$strSelected.'>'.$strTable.'.'.$strField.'</option>';
                    }
                }
            }
            $return .= '</select>';
            $return .= '
				<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_val_field'][1].'</p></div>';
        }

        // condition
        $return .= '<div class="w50 clr"><h3><label for="'.$this->strId.'_lookup_where">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_where'][0].'</label></h3>
				<input type="text" name="'.$this->strId.'[lookup_where]" id="'.$this->strId.'_lookup_where" value="'.$this->varValue['lookup_where'].'" class="tl_text"'.$this->strTagEnding;
        $return .= '
			<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_where'][1].'</p></div>';

        // order
        $return .= '<div class="w50"><h3><label for="'.$this->strId.'_lookup_sort">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_sort'][0].'</label></h3>
				<input type="text" name="'.$this->strId.'[lookup_sort]" id="'.$this->strId.'_lookup_sort" value="'.$this->varValue['lookup_sort'].'" class="tl_text"'.$this->strTagEnding;
        $return .= '
			<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG'][$this->strTable]['lookup_sort'][1].'</p></div>';

        return $return;
    }
}
