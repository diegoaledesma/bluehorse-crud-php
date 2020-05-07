<?php

namespace BlueHorse\Editor;
use BlueHorse\Core as Core;

class Editor extends Core\Core
{
    function __construct()
    {
        parent::__construct();
    }

    public function begin()
    {
        $this->dbi->connect();

        self::createFields($this->fieldsEditor);

        if (! is_null($this->action)) {

            switch ($this->action) {
                case md5('store'):
                    self::store($_POST);
                    break;
                case md5('destroy'):
                    if (! is_null($this->reference)) {
                        self::destroy($this->reference);
                    }
                    break;
                case md5('update'):
                    if (! is_null($this->reference)) {
                        self::update($this->reference, $_POST);
                    }
                    break;
            }
        } else {
            $form = self::createForm($this->fieldsEditor);
            self::createHtml($form, $this->titleEditor);
        }
    }

    /* public function textTransform($style, $value)
    {
        switch ($style) {
            case 'lowercase':
                $value = strtolower($value);
                break;
            case 'capitalize':
                $value = ucwords(strtolower($value));
                break;
            case 'uppercase':
            default:
                $value = strtoupper($value);
                break;
        }
    } */

    private function store($data)
    {
        if (! $this->canAdd) {
            return false;
        }

        $tools    = new \Tools();

        foreach ($data as $name => $value) {
            if (isset($this->redirectStore)) {
                if (strpos($this->redirectStore, "{{ $name }}") !== false) {
                    $this->redirectStore = preg_replace("/{{ $name }}/", $value, $this->redirectStore);
                }
            }
            $value = $this->dbi->realEscapeString($value);
            if (self::emailValidate($value) != false) {
                $value = strtolower($value);
            } else {
                if (isset($this->sha256) && in_array($name, $this->sha256)) {
                    $value = hash('sha256', $value);
                } else {
                    if (isset($this->lowercase) && in_array($name, $this->lowercase)) {
                        $value = strtolower($value);
                    } else {
                        $value = strtoupper($value);
                    }
                }
            }
            if (array_key_exists($name, $this->fieldsEditor)) {
                $filterType = $this->fieldsEditor[$name]['filter'] ?? 'alphanumeric_sc';
                $value      = $tools->filter($value, $filterType);
                $this->arrQuery[] = "`$name` = '$value'";
            }
        }

        $query = "INSERT INTO `$this->table` SET " . join(', ', $this->arrQuery);

        if (! $this->dbi->query($query)) {
            $this->addMsg('No se pudo <strong>grabar</strong> el registro.');
        } else {
            $this->addMsg('Registro <strong>grabado</strong> con éxito.');
            if ($this->createSessionStore) {
                $_SESSION['store'] = hash('sha256', 'store.' . $this->table);
            }
        }

        if (isset($this->redirectStore)) {
            $this->redirect($this->redirectStore);
        } else {
            $this->redirect($_SERVER['PHP_SELF'] . '?p=' . FILE . '&mode=editor');
        }

    }

    private function update($key, $vars)
    {
        if (! $this->canEdit) {
            return false;
        }
        $consult  = "SELECT `" . join('`,`', $this->fields) . "` ";
        $consult .= "FROM `$this->table` WHERE `$this->key` = '$key'";
        $results  = $this->dbi->query($consult);
        $old      = $this->dbi->fetchArray($results);

        $tools    = new \Tools();

        if ($this->dbi->numRows($results) > 0) {

            foreach ($this->fields as $field) {
                if (isset($this->redirectUpdate)) {
                    if (strpos($this->redirectUpdate, "{{ $field }}") !== false) {
                        $this->redirectUpdate = preg_replace("/{{ $field }}/", $vars[$field], $this->redirectUpdate);
                    }
                }

                if ($old[$field] != $vars[$field]) {
                    $value = $this->dbi->realEscapeString($vars[$field]);
                    if ($value == '') {
                        $this->arrQuery[] = "`$field` = NULL";
                    } else {
                        if (self::emailValidate($value) != false) {
                            $value = strtolower($value);
                        } else {
                            if (isset($this->sha256) && in_array($field, $this->sha256)) {
                                $value = hash('sha256', $value);
                            } else {
                                if (isset($this->lowercase) && in_array($field, $this->lowercase)) {
                                    $value = strtolower($value);
                                } else {
                                    $value = strtoupper($value);
                                }
                            }
                        }
                        if (array_key_exists($field, $this->fieldsEditor)) {
                            $filterType = $this->fieldsEditor[$field]['filter'] ?? 'alphanumeric_sc';
                            $value      = $tools->filter($value, $filterType);
                            $this->arrQuery[] = "`$field` = '$value'";
                        }
                    }
                }
            }

            $query  = "UPDATE `$this->table` SET ";
            $query .= join(', ', $this->arrQuery) . " WHERE `$this->key` = '$key'";

            if (! $this->dbi->query($query)) {
                $this->addMsg('No se pudo <strong>actualizar</strong> el registro.');
            } else {
                $this->addMsg('Registro <strong>actualizado</strong> con éxito.');

                if ($this->createSessionUpdate) {
                    $_SESSION['update'] = hash('sha256', 'update.' . $this->table);
                }
            }
        } else {
            $this->addMsg('Imposible <strong>actualizar</strong>, el registro no existe.');
        }
        if (isset($this->redirectUpdate)) {
            $this->redirect($this->redirectUpdate);
        } elseif (isset($this->redirectUpdate, $this->showModal) && $this->showModal) {
            $_SESSION['srcModal'] = $this->redirectUpdate;
            $this->redirect($_SERVER['PHP_SELF'] . '?p=' . FILE . '&mode=editor&reference=' . $key);
        } else {
            $this->redirect($_SERVER['PHP_SELF'] . '?p=' . FILE . '&mode=editor&reference=' . $key);
        }
    }

    private function destroy($id)
    {
        if (! $this->canDelete) {
            return false;
        }
        $id      = $this->dbi->realEscapeString($id);
        $consult = "SELECT * FROM `$this->table` WHERE `$this->key` = '$id'";
        $results = $this->dbi->query($consult);
        $field   = $this->dbi->fetchArray($results);

        if ($this->dbi->numRows($results) > 0) {

            $query = "DELETE FROM `$this->table` WHERE `$this->key` = '$id'";

            if (! $this->dbi->query($query)) {
                $this->addMsg('No se pudo <strong>eliminar</strong> el registro.');
            } else {
                $this->addMsg('Registro <strong>eliminado</strong> con éxito.');
                if ($this->createSessionDestroy) {
                    $_SESSION['destroy'] = hash('sha256', 'destroy.' . $this->table);
                }
            }

        } else {
            $this->addMsg('Imposible <strong>eliminar</strong>, el registro no existe.');
        }

        if (isset($this->redirectDestroy)) {
            if (strpos($this->redirectDestroy, "{{ ") !== false) {
                $x = explode("{{ ", $this->redirectDestroy);
                $y = explode(" }}", $x[1]);
                $this->redirectDestroy = preg_replace("/{{ $y[0] }}/", $field[$y[0]], $this->redirectDestroy);
            }
            $this->redirect($this->redirectDestroy);
        } else {
            $this->redirect($_SERVER['PHP_SELF'] . '?p=' . FILE . '&mode=browser');
        }
    }
}