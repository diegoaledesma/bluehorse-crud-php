<?php

/**
 * Tools
 *
 * @author     Diego Ledesma <dledesma@aimpasistemas.com>
 * @copyright  2019 Aimpa Sistemas
 */
class Tools
{
    /**
     *
     * @var array
     *
     */
    public $arrMatch = [
        'alpha' => [
            'php'   => '/[^ a-zA-ZñÑáéíóúÁÉÍÓÚ]/',
            'js'    => '/[^ a-zA-ZñÑáéíóúÁÉÍÓÚ]/g'
        ],
        'alphanumeric' => [
            'php'   => '/[^ 0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]/',
            'js'    => '/[^ 0-9a-zA-ZñÑáéíóúÁÉÍÓÚ]/g',
        ],
        'alphanumeric_sc' => [
            'php'   => '/[^ 0-9a-zA-ZñÑáéíóúÁÉÍÓÚ.,_@%&+\/:<>|()=-]/',
            'js'    => '/[^ 0-9a-zA-ZñÑáéíóúÁÉÍÓÚ\.,_@%&+\/:<>|()=-]/g',
        ],
        'int' => [
            'php'   => '/[^0-9]/',
            'js'    => '/[^0-9]/g'
        ],
        'float' => [
            'php'   => '/[^0-9.]/',
            'js'    => '/[^0-9\.]/g'
        ],
        'email' => [
            'php'   => '/[^0-9a-z._@%&+-]/',
            'js'    => '/[^0-9a-z\._@%&+-]/g'
        ],
        'password' => [
            'php'   => '/[^0-9a-zA-Z.,_@%&+-]/',
            'js'    => '/[^0-9a-zA-Z\.,_@%&+-]/g'
        ],
        'tel'   => [
            'php'   => '/[^ 0-9()+-]/',
            'js'    => '/[^ 0-9()+\-]/g'
        ]
    ];

    /**
     * Limpia de caracteres inválidos una variable.
     *
     * @param string $target
     * @param string $type
     * @return string|null
     */
    function filter($target, $type)
    {
        $ret = null;
        if (! is_null($target)) {
            switch ($type) {
                case 'alpha':
                    $match = $this->arrMatch['alpha']['php'];
                    break;
                case 'alphanumeric':
                    $match = $this->arrMatch['alphanumeric']['php'];
                    break;
                case 'alphanumeric_sc':
                    $match = $this->arrMatch['alphanumeric_sc']['php'];
                    break;
                case 'int':
                    $match = $this->arrMatch['int']['php'];
                    break;
                case 'float':
                    $match = $this->arrMatch['float']['php'];
                    break;
                case 'email':
                    $match = $this->arrMatch['email']['php'];
                    break;
                case 'password':
                    $match = $this->arrMatch['password']['php'];
                    break;
                 case 'tel':
                    $match = $this->arrMatch['tel']['php'];
                    break;
                default:
                    $match = $this->arrMatch['alpha']['php'];
            }
            $ret = htmlspecialchars(preg_replace($match, '', trim($target)));
        }
        return $ret;
    }

    /**
     * Devuelve un string hasheado.
     *
     * @param string $string
     * @param string $type
     * @return string
     */
    public function passwordMethod($string, $type = null): string
    {
        if (is_null($type)) {
            $type = 'sha256';
        }
        return hash($type, $string);
    }

    /**
     * Genera un select html.
     *
     * @param string $name Nombre del Select
     * @param string $post Valor enviado.
     * @param array $options
     * @return string
     */
    public function select(string $name, $post, array $options): string
    {
        $d = '<select name="' . $name . '" class="form-control">';
        foreach ($options as $k => $v) {
            $h = null;
            $s = null;
            if ($k == $post) {
                $s = ' selected';
            }
            if ($k == '') {
                $h = ' hidden';
            }
            $d .= '<option value="' . $k . '"' . $s . $h . '>' . $v . '</option>';
        }
        $d .= '</select>';
        return $d;
    }
}
