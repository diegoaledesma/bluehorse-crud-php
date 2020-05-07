<?php

namespace BlueHorse\Detailer;
use BlueHorse\Core as Core;

class Detailer extends Core\Core
{
    function __construct()
    {
        parent::__construct();
    }

    public function begin()
    {
        $this->dbi->connect();

        self::createFields($this->fieldsDetailer);

        $form = self::createForm($this->fieldsDetailer);

        if (isset($this->listKey, $this->listFields, $this->listTable, $this->listPage, $this->listTitle)) {
            $this->drawList = self::drawList();
        }

        self::createHtml($form, $this->titleDetailer);
    }

    public function drawList()
    {
        $this->consult  = "SELECT `" . join('`,`', $this->listFields) . "`";
        $this->consult .= " FROM `$this->listTable`";

        if (isset($this->listWhere) && $this->listWhere != '') {
            $this->consult .= " WHERE $this->listWhere";
        }

        $this->listResults  = $this->dbi->query($this->consult);
        $this->listNumRows  = $this->dbi->numRows($this->listResults);

        $draw = '<div class="row mb-2">';
        $draw .= '<div class="col-sm-12">';
        $draw .= '<h4 class="h4 font-weight-normal text-muted">' . $this->listTitle . '</h4>';
        $draw .= '</div>';
        $draw .= '</div>';
        $draw .= '<div class="card">';
        $draw .= '<div class="card-body">';
        $draw .= '<table class="table table-hover" data-id="list">';
        $draw .= '<thead class="thead-light">';
        $draw .= '<tr>';
        foreach ($this->listFields as $field) {
            $draw .= '<th scope="col">' . self::listLabel($field) . '</th>';
        }
        $draw .= '<th scope="col">&nbsp;</th>';
        $draw .= '</tr>';

        $draw .= '</thead>';
        $draw .= '<tbody>';

        if ($this->listNumRows > 0) {
            while ($data = $this->dbi->fetchArray($this->listResults)) {
                $draw .= '<tr>';
                foreach ($this->listFields as $field) {
                    $value = $data[$field];
                    if (array_key_exists($field, $this->listFields) && is_array($this->listFields[$field])) {
                        if ($value != '') {
                            if (array_key_exists('data', $this->listFields[$field])) {
                                $value = $this->listFields[$field]['data'][$value];
                            }
                        }
                    }
                    $draw .= '<td>' . $value . '</td>';
                }
                $urlEditor   = $this->listPage . '?mode=editor&reference=' . $data[$this->listKey] . '&lreference=' . $this->reference;
                $urlDetailer = $this->listPage . '?mode=detailer&reference=' . $data[$this->listKey] . '&lreference=' . $this->reference;
                $urlDestroy  = $this->listPage . '?mode=editor&action=' . md5('destroy') . '&reference=' . $data[$this->listKey];
                $draw .= '<td style="text-align:right;width:150px;">';
                if ($this->showButtonList) {
                    $draw .= '<div class="btn-group">';
                    $draw .= '<button type="button" class="btn btn-link dropdown-toggle text-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></button>';
                    $draw .= '<div class="dropdown-menu dropdown-menu-right">';
                    if ($this->canXEdit) {
                        $draw .= '<a href="' . $urlEditor . '" class="dropdown-item">Edición</a>';
                    }
                    if ($this->canXDetail) {
                        $draw .= '<a href="' . $urlDetailer . '" class="dropdown-item">Detalle</a>';
                    }
                    if ($this->canXDelete) {
                        $draw .= '<a data-href="' . $urlDestroy . '" class="dropdown-item" data-toggle="modal" data-target="#confirmModal">Eliminar</a>';
                    }
                    $draw .= '</div>';
                    $draw .= '</div>';
                }
                $draw .= '</td>';
                $draw .= '</tr>';
            }
        }
        $draw .= '</tbody>';
        $draw .= '</table>';
        if ($this->listNumRows == 0) {
            $draw .= '<div class="row">';
            $draw .= '<div class="col">';
            $draw .= '<p>No hay datos para mostrar.</p>';
            $draw .= '</div>';
            $draw .= '</div>';
        }
        if ($this->canXAdd) {
            $draw .= '<div class="row">';
            $draw .= '<div class="col">';
            $draw .= '<a href="' . $this->listAdd['href'] . '" class="btn btn-primary">' . $this->listAdd['name'] . '</a>';
            $draw .= '</div>';
            $draw .= '</div>';
        }
        $draw .= '</div>';
        $draw .= '</div>';
        return $draw;
    }
}