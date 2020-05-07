# BlueHorse

BlueHorse es un pequeño Framework CRUD Create, Read, Update and Delete).

### Requisitos
- PHP 7

### Ejemplo de uso
```
<?php
require_once FILE_HEADER;

$foo->table = 'lib_usuarios';
$foo->key   = 'usuario_id';

$foo->titleEditor   = 'Definición de usuarios';
$foo->titleDetailer = 'Definición de usuarios';
$foo->titleBrowser  = 'Definición de usuarios';
$foo->cols          = 1;
$foo->canEdit       = true;
$foo->canDelete     = false;
$foo->canAdd        = false;

$foo->fieldsBrowser = ['usuario_nombres','usuario_apellidos','usuario_email','modificado'];

$foo->fieldsEditor = [
    'usuario_nombres'   => [
        'type'  => 'text'
    ],
    'usuario_apellidos' => [
        'type'  => 'text'
    ],
    'usuario_email'     => [
        'type'  => 'text'
    ],
    'usuario_password'  => [
        'type'  => 'password'
    ],
    'activo'            => [
        'type' => 'select',
        'data' => [
            'S' => 'SI',
            'N' => 'NO'
        ]
    ]
];

$foo->fieldsDetailer = $foo->fieldsEditor;

$foo->change = [
    'usuario_nombres'    => 'Nombres',
    'usuario_apellidos'  => 'Apellidos',
    'usuario_email'     => 'E-mail',
    'usuario_password'  => 'Contraseña',
    'modificado'        => 'Último ingreso'
];

$foo->sha256    = ['usuario_password'];
$foo->lowercase = ['usuario_email'];

$foo->begin();
```
