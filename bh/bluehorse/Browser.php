<?php

namespace BlueHorse\Browser;
use BlueHorse\Core as Core;

class Browser extends Core\Core
{
    function __construct()
    {
        parent::__construct();
    }

    public function begin()
    {
        $this->dbi->connect();

        self::createFields($this->fieldsBrowser);

        if (! is_null($this->action)) {

            switch ($this->action) {
                case 'clean':
                    self::addMsg('Filtro Eliminado.');
                    break;
            }
        }

        $this->consult  = "SELECT `$this->key`, `" . join('`,`', $this->fields) . "`";
        $this->consult .= " FROM `$this->table`";

        if (is_array($this->whereBrowser) && count($this->whereBrowser) > 0) {
            $addWhere = [];
            foreach ($this->whereBrowser as $key => $val) {
                $addWhere[] = "`$key` $val";
            }
            $this->consult .= " WHERE " . join(' AND ', $addWhere);
        }

        if (isset($_POST) && count($_POST) > 0) {
            $this->preConsult = [];
            $i = 0;
            foreach ($this->fields as $field) {
                if (isset($_POST[$field]) && ! empty($_POST[$field])) {
                    $this->preConsult[] = "`$field` LIKE '%" . self::clean($_POST[$field]) . "%'";
                    $i++;
                }
            }
            if ($i > 0) {
                $pos = stripos($this->consult, 'WHERE');
                $conditional = $pos !== false ? "AND" : "WHERE";
                $this->consult .= " " . $conditional . join(' AND ', $this->preConsult);
            }
        }

        if (! is_null($this->orderBy)) {
            $this->consult .= " ORDER BY " . $this->orderBy;
        }
        #exit ($this->consult);

        self::splitResults();

        $this->results  = $this->dbi->query($this->consult);

        $this->numRows  = $this->dbi->numRows($this->results);

        #$draw = '<div class="col-lg col-sm col-xs">';
        $draw = '<form method="POST" action="' . FILE_APP . '?p=' . FILE . '&mode=browser" id="browser">';
        $draw .= '<table class="table table-hover">';
        $draw .= '<thead class="thead-light">';
        $draw .= self::drawHead();
        $draw .= '</thead>';
        $draw .= '<tbody>';
        $draw .= self::drawRow();
        $draw .= '</tbody>';
        $draw .= '</table>';
        $draw .= '</form>';
        #$draw .= '</div>';
        if ($this->numRows === 0) {
            $draw .= '<div class="row">';
            $draw .= '<div class="col">';
            $draw .= 'No hay datos para mostrar.';
            $draw .= '</div>';
            $draw .= '</div>';
        }
        if ($this->showPagination) {
            $draw .= '<div class="row">';
            $draw .= '<div class="col">';
            $draw .= self::displayLinks();
            $draw .= '</div>';
            $draw .= '<div class="col text-right text-secondary">';
            $draw .= self::displayCount();
            $draw .= '</div>';
            $draw .= '</div>';
        }

        self::createHtml($draw, $this->titleBrowser);
    }

    public function drawHead()
    {
        $draw = '<tr>';
        foreach ($this->fields as $field) {
            $draw .= '<th scope="col" style="border-bottom: 0;">' . self::label($field) . '</th>';
        }
        $draw .= '<th scope="col" style="border-bottom: 0;">&nbsp;</th>';
        $draw .= '</tr>';
        if ($this->showFilter) {
            $draw .= '<tr>';
            foreach ($this->fields as $field) {
                $value = isset($_POST[$field]) ? self::clean($_POST[$field]) : null;
                $draw .= '<th scope="col" style="border-top: 0;padding-top: 0;">';
                if (array_key_exists($field, $this->fieldsEditor) && $this->fieldsEditor[$field]['type'] == 'select') {

                    $selected = is_null($value) ? ' selected' : null;
                    $draw    .= '<select name="' . $field . '" class="form-control">';

                    if (array_key_exists('fn', $this->fieldsEditor[$field]) &&
                        $this->fieldsEditor[$field]['fn'] == 'queryToArray') {

                        $arrData = self::queryToArray($this->fieldsEditor[$field]['data']);

                    } else {

                        $arrData = $this->fieldsEditor[$field]['data'];

                    }
                    $draw    .= '<option value=""' . $selected . '>*</option>';
                    foreach ($arrData as $key => $val) {
                        $selected = $key == $value ? ' selected' : null;
                        $draw .= '<option value="' . $key . '"' . $selected . '>' . $val . '</option>';
                    }

                    $draw .= '</select>';

                } else {
                    $draw .= '<input class="form-control" name="' . $field . '" value="' . $value . '" autocomplete="off">';
                }
                $draw .= '</th>';
            }
            $draw .= '<th scope="col" style="border-top: 0;padding-top: 0;text-align:right;">';
            $draw .= '<div class="btn-group" role="group" aria-label="Browser" style="height: 38px;">';
            $draw .= '<button type="submit" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Buscar"><i class="material-icons">search</i></button>';
            $draw .= '<a href="' . FILE_APP . '?p=' . FILE . '&mode=browser&action=clean" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Quitar Filtro"><i class="material-icons">delete_sweep</i></a></th>';
            $draw .= '</div>';
            $draw .= '</tr>';
        }

        return $draw;
    }

    public function drawRow()
    {
        $draw = '';

        if ($this->dbi->numRows($this->results) > 0) {
            while ($data = $this->dbi->fetchArray($this->results)) {
                $draw .= '<tr>';
                foreach ($this->fields as $field) {
                    $value = $data[$field];
                    if (array_key_exists($field, $this->fieldsEditor) && is_array($this->fieldsEditor[$field])) {
                        if ($value != '') {
                            if (array_key_exists('fn', $this->fieldsEditor[$field])) {
                                $arrValue = self::{$this->fieldsEditor[$field]['fn']}($this->fieldsEditor[$field]['data']);
                                $value = $arrValue[$value];
                            } elseif (array_key_exists('data', $this->fieldsEditor[$field])) {
                                $value = $this->fieldsEditor[$field]['data'][$value];
                            }
                        }
                    }
                    $draw .= '<td>' . $value . '</td>';
                }
                $urlEditor   = FILE_APP . '?p=' . FILE . '&mode=editor&reference=' . $data[$this->key];
                $urlDetailer = FILE_APP . '?p=' . FILE . '&mode=detailer&reference=' . $data[$this->key];
                $urlDestroy  = FILE_APP . '?p=' . FILE . '&mode=editor&action=' . md5('destroy') . '&reference=' . $data[$this->key];
                $draw .= '<td style="text-align:right;width:150px;">';
                $draw .= '<div class="btn-group btn-browser">';
                $draw .= '<button type="button" class="btn btn-link dropdown-toggle text-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></button>';
                $draw .= '<div class="dropdown-menu dropdown-menu-right">';
                if ($this->canEdit) {
                    $draw .= '<a href="' . $urlEditor . '" class="dropdown-item"><div><i class="material-icons">edit</i></div> Edición</a>';
                }
                $draw .= '<a href="' . $urlDetailer . '" class="dropdown-item"><div><i class="material-icons">description</i></div> Detalle</a>';
                if ($this->canDelete) {
                    $draw .= '<a href="javascript:void();" data-id="delete" data-href="' . $urlDestroy . '" class="dropdown-item"><div><i class="material-icons">delete</i></div> Eliminar</a>';
                }
                $draw .= '</div>';
                $draw .= '</div>';
                $draw .= '</td>';
                $draw .= '</tr>';
            }
        }
        return $draw;
    }

    private function splitResults()
    {
        if (empty($this->page)) {
            $this->page = 1;
        }
        $posTo   = strlen($this->consult);
        $posFrom = strpos($this->consult, 'FROM', 0);

        $arrClause = [' GROUP BY', ' HAVING', ' ORDER BY', ' LIMIT', ' PROCEDURE'];

        foreach ($arrClause as $clause) {
            $pos = strpos($this->consult, $clause, $posFrom);
            if ($pos < $posTo && $pos != false) {
                $posTo = $pos;
            }
        }

        $sql    = "SELECT COUNT(*) AS `total` " . substr($this->consult, $posFrom, ($posTo - $posFrom));
        $result = $this->dbi->query($sql);
        $count  = $this->dbi->fetchObject($result);

        $this->numRows   = $count->total;
        $this->totalRows = $count->total;
        $offset          = ($this->perPage * ($this->page - 1));

        if ($offset < $this->numRows) {
            $limit = " LIMIT $offset, $this->perPage";
        } else {
            $limit = " LIMIT $this->perPage";
        }

        $this->consult .= $limit;
    }

    private function displayLinks()
    {
        if ($this->totalRows > 0) {
            $this->numPages = intval($this->totalRows / $this->perPage);

            if ($this->totalRows % $this->perPage) {
                $this->numPages++;
            }

            function pageItemSpan($span, $type = '')
            {
                $addType    = $type != '' ? " $type" : '';
                $draw       = '<li class="page-item' . $addType . '">';
                $draw      .= '<span class="page-link">' . $span . '</span>';
                $draw      .= '</li>';
                return $draw;
            }

            $draw = '<nav aria-label="...">';
            $draw .= '<ul class="pagination">';

            if ($this->page > 1) {
                $draw .= '<li class="page-item">';
                $draw .= '<a class="page-link" href="' . FILE_APP . '?p=' . FILE . '&mode=browser&page=1">Inicio</a>';
                $draw .= '</li>';
            } else {
                $draw .= pageItemSpan('Inicio', 'disabled');
            }

            $curWindowNum = intval($this->page / $this->pageLinks);

            if ($this->page % $this->pageLinks) {
                $curWindowNum++;
            }

            $maxWindowNum = intval($this->numPages / $this->pageLinks);

            if ($this->numPages % $this->pageLinks) {
                $maxWindowNum++;
            }

            if ($curWindowNum > 1) {
                $draw .= '<li class="page-item">';
                $draw .= "<a class=\"page-link\" href=\"" . FILE_APP . "?p=" . FILE . "&mode=browser&page=" . (($curWindowNum - 1) * $this->pageLinks) . "\">...</a>";
                $draw .= '</li>';
            }

            for ($jumpToPage = 1 + (($curWindowNum - 1) * $this->pageLinks); ($jumpToPage <= ($curWindowNum * $this->pageLinks)) && ($jumpToPage <= $this->numPages); $jumpToPage++) {
                if ($jumpToPage == $this->page) {
                    $draw .= "<li class=\"page-item active\">";
                    $draw .= "<span class=\"page-link\" href=\"javascript:void(0)\">".$jumpToPage;
                    $draw .= '<span class="sr-only">(current)</span>';
                    $draw .= '</span>';
                    $draw .= '</li>';
                } else {
                    $draw .= "<li class=\"page-item\">";
                    $draw .= "<a class=\"page-link\" href=\"" . FILE_APP . "?p=" . FILE . "&mode=browser&page={$jumpToPage}\">" . $jumpToPage . "</a>";
                    $draw .= '</li>';
                }
            }
            if ($curWindowNum < $maxWindowNum) {
                $draw .= "<li class=\"page-item\">";
                $draw .= "<a class=\"page-link\" href=\"" . FILE_APP . "?p=" . FILE . "&mode=browser&page=" . (($curWindowNum) * $this->pageLinks + 1) . "\">...</a>";
                $draw .= '</li>';
            }

            if (($this->page < $this->numPages) && ($this->numPages != 1)) {
                $draw .= "<li class=\"page-item\">";
                $draw .= "<a class=\"page-link\" href=\"" . FILE_APP . "?p=" . FILE . "&mode=browser&page={$this->numPages}\">Fin</a>";
                $draw .= '</li>';
            } else {
                $draw .= pageItemSpan('Fin', 'disabled');
            }
            $draw .= '</ul>';
            $draw .= '</nav>';

            return $draw;
        }
    }

    private function displayCount()
    {
        if ($this->totalRows > 0) {
            $toNum   = $this->perPage * $this->page;
            $fromNum = ($this->perPage * ($this->page - 1)) + 1;

            if ($toNum > $this->totalRows) {
                $toNum = $this->totalRows;
            }

            $draw  = "Mostrando $fromNum-$toNum de <strong>$this->totalRows</strong> ";
            $draw .= "(Página <strong>$this->page</strong> / $this->numPages)";

            return $draw;
        }
    }
}