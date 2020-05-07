<?php

namespace BlueHorse\Core;
use BlueHorse\Browser as Browser;
use BlueHorse\Editor as Editor;
use BlueHorse\Detailer as Detailer;
session_start();

/**
 * BlueHorse
 *
 * BlueHorse es un pequeño Framework CRUD Create, Read, Update and Delete).
 *
 * @author     Diego Ledesma <dledesma@aimpasistemas.com>
 * @copyright  2019 Aimpa Sistemas
 * @license    https://aimpasistemas.com/license/bh.txt
 * @version    Release: 0.1
 * @link       https://aimpasistemas.com/developers.php
 */
class Core
{
    public $table;
    public $key;
    public $addLabel;
    public $var;
    public $exitDetailer;
    public $redirectStore;
    public $numRows;
    public $phpSelf;
    public $mode            = 'browser';
    public $action          = null;
    public $reference       = null;
    public $lreference      = null;
    public $cols            = 1;
    public $page            = 1;
    public $perPage         = 25;
    public $views           = [];
    public $fields          = [];
    public $arrQuery        = [];
    public $msgFooterEditor = null;
    public $msgFooterDetailer = null;
    public $pageLinks       = 10;
    public $canAccess       = true;
    public $canEdit         = true;
    public $canDelete       = true;
    public $canAdd          = true;
    public $canXAccess      = true;
    public $canXEdit        = true;
    public $canXDelete      = true;
    public $canXAdd         = true;
    public $showButtonList  = true;
    public $showExit        = true;
    public $showDetailer    = true;

    /**
     * $_SESSION['update'] = hash('sha256', 'update.' . $this->table);
     */
    public $createSessionUpdate = false;

    /**
     * $_SESSION['store'] = hash('sha256', 'store.' . $this->table);
     */
    public $createSessionStore  = false;

    /**
     * $_SESSION['destroy'] = hash('sha256', 'destroy.' . $this->table);
     */
    public $createSessionDestroy = false;
    public $showFilter      = true;
    public $showPagination  = true;
    public $whereBrowser    = [];
    public $orderBy         = null;

    function __construct() {
        require_once 'DB.php';
        $this->dbi = new \DB();

        $this->phpSelf = $_SERVER['PHP_SELF'];
        $emptyRedirect = FILE_APP . '?p=' . FILE . '&mode=browser';

        if (isset($_GET['reference'])) {
            if (! empty($_GET['reference'])) {
                $this->reference = self::clean($_GET['reference']);
            } else {
                self::redirect($emptyRedirect);
            }
        }

        if (isset($_GET['lreference'])) {
            if (! empty($_GET['lreference'])) {
                $this->lreference = self::clean($_GET['lreference']);
            } else {
                self::redirect($emptyRedirect);
            }
        }

        if (isset($_GET['action'])) {
            if (! empty($_GET['action'])) {
                $this->action = self::clean($_GET['action']);
            } else {
                self::redirect($emptyRedirect);
            }
        }

        if (isset($_GET['mode'])) {
            if (! empty($_GET['mode'])) {
                $this->mode  = self::clean($_GET['mode']);
            } else {
                self::redirect($emptyRedirect);
            }
        }

        if (isset($_GET['page']) && ! empty($_GET['page'])) {
            $this->page    = self::clean($_GET['page']);
        }
    }

    /**
     * Crea una instancia dependiendo del modo
     *
     * @return instance
     */
    public static function &getMode()
    {
        if (isset($_GET['mode']) && ! empty($_GET['mode'])) {

            $mode = self::clean($_GET['mode']);
            switch ($mode) {
                case 'editor':
                    require_once 'Editor.php';
                    $foo = new Editor\Editor();
                    break;
                case 'detailer':
                    require_once 'Detailer.php';
                    $foo = new Detailer\Detailer();
                    break;
                case 'browser':
                default:
                    require_once 'Browser.php';
                    $foo = new Browser\Browser();
                    break;
            }
        } else {
            require_once 'Browser.php';
            $foo = new Browser\Browser();
        }
        return $foo;
    }

    /**
     * Elimina carcteres no deseados
     *
     * @param string $string
     * @return string
     */
    public function clean($string)
    {
        return htmlspecialchars(preg_replace('/[^ A-Za-z0-9ñ-Ñ]/', '', trim($string)));
    }

    /**
     * Estiliza los labels por defecto.
     * Si se define la variable 'change', renombra los labels y los estiliza.
     *
     * @param string $field
     * @return string
     */
    public function label($field)
    {
        if (isset($this->change)
            && is_array($this->change)
            && array_key_exists($field, $this->change)) {
            return $this->change[$field];
        } else {
            return ucfirst(strtolower(preg_replace('/[_]/', ' ', trim($field))));
        }
    }

    /**
     * Estiliza los list labels por defecto.
     * Si se define la variable 'listChange', renombra los list labels y los estiliza.
     *
     * @param string $field
     * @return string
     */
    public function listLabel($field)
    {
        if (isset($this->listChange)
            && is_array($this->listChange)
            && array_key_exists($field, $this->listChange)) {
            return $this->listChange[$field];
        } else {
            return ucfirst(strtolower(preg_replace('/[_]/', ' ', trim($field))));
        }
    }

    /**
     * FILTER VALIDATE EMAIL
     *
     * @param string $string
     * @return string
     */
    public function emailValidate($string)
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }

    public function addMsg($msg)
    {
        $_SESSION["msgs"] = $msg;
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    /**
     * Crea el html con datos procesados segun el 'mode'
     */
    public function createHtml($data, $title)
    {
        $includeView = 'view_' . $this->mode;
        $reference   = $this->reference;
        $srcModal    = null;

        if (isset($this->views)
            && is_array($this->views)
            && count($this->views) > 0) {
            if (array_key_exists($this->mode, $this->views)) {
                $includeView = $this->views[$this->mode];
            }
        }

        if (isset($this->drawList)) {
            $dataList       = $this->drawList;
        }

        if (isset($this->javascript)) {
            $javascript     = $this->javascript;
        }

        if (isset($_SESSION['srcModal'])) {
            $srcModal       = $_SESSION['srcModal'];
        }

        if (! is_null($this->msgFooterEditor)) {
            $msgFooterEditor       = $this->msgFooterEditor;
        }

        if (! is_null($this->msgFooterDetailer)) {
            $msgFooterDetailer     = $this->msgFooterDetailer;
        }

        if (isset($this->exitDetailer) && ! empty($this->exitDetailer)) {
            $exitDetailer   = $this->exitDetailer;
        } else {
            $exitDetailer   = $_SERVER['PHP_SELF'] . '?p=' . FILE . '&mode=browser';
        }

        if (isset($this->exitEditor) && ! empty($this->exitEditor)) {
            $exitEditor   = $this->exitEditor;
        } else {
            $exitEditor   = $_SERVER['PHP_SELF'] . '?p=' . FILE . '&mode=browser';
        }

        if (isset($this->addLabel) && ! empty($this->addLabel)) {
            $addLabel     = $this->addLabel;
        } else {
            $addLabel     = 'Nuevo';
        }

        $canAdd         = $this->canAdd;
        $canEdit        = $this->canEdit;
        $canDelete      = $this->canDelete;
        $showExit       = $this->showExit;
        $showDetailer   = $this->showDetailer;

        ob_start();
        include 'views/' . $includeView . '.php';
        $contents = ob_get_contents();
        ob_end_clean();
        exit ($contents);
    }

    /**
     * Crea un div class row.
     *
     * @param string $data
     * @return string
     */
    public function createRow($data)
    {
        return '<div class="row">' . $data . '</div>';
    }

    public function createForm($fields)
    {
        $draw    = '';
        $data    = [];
        $drawCol = '';
        $i       = 0;
        $j       = 0;

        $countFields = count($fields);

        $addRow = $this->cols == 1 ? ' row' : null;

        foreach ($fields as $field => $type) {
            $i++;
            $j++;
            $fn         = null;
            $defValue   = null;
            if (is_array($type) && count($type) > 0) {
                $data = array_key_exists('data', $type) ? $type['data'] : null;
                if (array_key_exists('fn', $type)) {
                    $fn = $type['fn'];
                }
                if (array_key_exists('def_value', $type)) {
                    $defValue = $type['def_value'];
                }
                if (array_key_exists('maxlength', $type)) {
                    $maxlength = $type['maxlength'];
                } else {
                    $maxlength = 50;
                }
                if (array_key_exists('filter', $type)) {
                    $filter = $type['filter'];
                } else {
                    $filter = 'alphanumeric_sc';
                }
                $type = $type['type'];
            }

            $drawCol .= '<div class="col-lg col-sm col-xs">';
            $drawCol .= '<div class="form-group' . $addRow . '">' . self::createElement($type, $field, $data, $fn, $defValue, $maxlength, $filter) . '</div>';
            $drawCol .= '</div>';

            if (($i % $this->cols) == 0) {
                $draw   .= self::createRow($drawCol);
                $i       = 0;
                $drawCol = '';
            }

            if ($j == $countFields && ($i % $this->cols) !== 0) {
                $drawCol .= '<div class="col-lg col-sm col-xs"><div class="form-group' . $addRow . '">&nbsp;</div></div>';
                $draw    .= self::createRow($drawCol);
                $drawCol  = '';
            }
        }

        return $draw;
    }

    /**
     * Crea input, select, textarea
     *
     * @param string $field
     * @return string
     */
    public function createElement($type, $field, $data, $fn, $defValue, $maxlength, $filter)
    {
        $value      = null;
        $inline     = false;
        $classLabel = null;
        $disabled   = null;

        if (! is_null($this->reference)) {
            $value = self::getValues($this->reference);
            $value = $value[$field];
        } else {
            $value = $defValue;
        }

        if (! isset($this->cols) || (isset($this->cols) && $this->cols == 1)) {
            $classLabel = ' class="col-sm-2 col-form-label"';
            $inline     = true;
        }

        if ($this->mode == 'detailer') {
            $disabled = ' disabled';
        }

        $draw = '<label for="' . $field . '"' . $classLabel . '>' . self::label($field) . '</label>';
        $draw .= $inline ? '<div class="col-sm-10">' : null;

        $required = isset($this->required) && in_array($field, $this->required) ? ' data-required="true"' : null;

        switch ($type) {
            case 'textarea':
                $draw .= '<textarea type="' . $type . '" name="' . $field . '" id="' . $field . '" data-id="' . $field . '" data-filter="' . $filter . '" class="form-control"' . $disabled . $required . '>' . $value . '</textarea>';
                break;
            case 'select':
                $showSelected = true;
                if (array_key_exists('selected', $data) && ! $data['selected']) {
                    $showSelected = false;
                }
                if (! is_null($fn)) {
                    $data = self::{$fn}($data);
                }
                $selected = is_null($value) ? ' selected' : null;
                $draw    .= '<select name="' . $field . '" id="' . $field . '" data-id="' . $field . '" class="form-control"' . $disabled . $required . '>';

                $draw    .= $showSelected ? '<option value=""' . $selected . '>Seleccione un valor</option>' : null;
                foreach ($data as $key => $val) {
                    $selected = $key == $value ? ' selected' : null;
                    $draw .= '<option value="' . $key . '"' . $selected . '>' . $val . '</option>';
                }
                $draw .= '</select>';
                break;
            case 'input':
            case 'hidden':
            case 'text':
            default:
                $draw .= '<input type="' . $type . '" name="' . $field . '" id="' . $field . '" data-id="' . $field . '" data-filter="' . $filter . '" class="form-control" maxlength="' . $maxlength . '" value="' . $value . '"' . $disabled . $required . ' autocomplete="OFF"/>';
                break;
            case 'date':
                $draw .= '<input type="' . $type . '" name="' . $field . '" id="' . $field . '" data-id="' . $field . '" class="form-control" value="' . $value . '"' . $disabled . $required . ' autocomplete="OFF"/>';
                break;
            case 'autosuggest':
                $data = $this->fieldsEditor[$field]['data'];
                $draw .= '<input type="text" name="' . $field . '" id="' . $field . '" data-id="' . $field . '" class="form-control" value="' . $value . '"' . $disabled . $required . ' autocomplete="OFF"/>';
                if ($this->mode != 'detailer') {
                    $draw .= "<script>";
                    $draw .= "$(function() {";
                    $draw .= "$('#$field').autosuggest({";
                    $draw .= "width: '100%',";
                    $draw .= "url: '" . $data['url'] . "'";
                    $draw .= "});";
                    $draw .= "});";
                    $draw .= "</script>";
                }
                if (! is_null($value)) {
                    $query = $this->dbi->query("SELECT * FROM " . $data['table'] . " WHERE " . $data['key'] . "= '$value'");
                    $result = $this->dbi->fetchArray($query);
                    $draw .= "<script>";
                    $draw .= "$(function() {";
                    if ($this->mode != 'detailer') {
                        $draw .= "$('#text_for_$field').val('" . $result[$data['field']] . "');";
                        $draw .= "$('#$field').val('" . $result[$data['key']] . "');";
                    } else {
                        $draw .= "$('#$field').val('" . $result[$data['field']] . "');";
                    }
                    $draw .= "});";
                    $draw .= "</script>";
                }
                break;
        }
        if (isset($this->description)
            && array_key_exists($field, $this->description)
            && $this->mode == 'editor') {
            $draw .= '<small id="emailHelp" class="form-text text-muted">' . $this->description[$field] . '</small>';
        }
        $draw .= $inline ? '</div>' : null;
        return $draw;
    }

    public function createFields($arrFields)
    {
        $useValue = false;

        if (isset($_GET['mode']) && ! empty($_GET['mode'])) {
            if (self::clean($_GET['mode']) == 'browser') {
                $useValue = true;
            }
        } elseif (! isset($_GET['mode'])) {
            $useValue = true;
        }

        if (is_array($arrFields) && count($arrFields) > 0) {
            foreach ($arrFields as $key => $value) {
                $this->fields[] = $useValue ? $value : $key;
            }
        } else {
            return false;
        }
    }

    public function getValues($key = null)
    {
        $query  = "SELECT `" . join('`,`', $this->fields) . "` ";
        $query .= "FROM `$this->table`";

        if (! is_null($key)) {
            $query .= " WHERE `$this->key` = '$key'";
        }

        $result = $this->dbi->query($query);
        return $this->dbi->fetchArray($result);
    }

    public function queryToArray($params)
    {
        $key        = $params['key'];
        $value      = $params['value'];
        $query      = $params['query'];
        $result     = $this->dbi->query($query);
        $arrData    = [];

        while ($data = $this->dbi->fetchArray($result)) {
            $arrData[$data[$key]] = $data[$value];
        }

        return $arrData;
    }
}