<?php

require_once FILE_HEADER;

$foo->table = 'usuarios';
$foo->key   = 'usuario_id';

$foo->titleEditor   = 'Definición de usuarios';
$foo->titleDetailer = 'Definición de usuarios';
$foo->titleBrowser  = 'Definición de usuarios';
$foo->cols          = 1;
$foo->canEdit       = true;
$foo->canDelete     = true;
$foo->canAdd        = true;

$foo->fieldsBrowser = ['usuario_nombre','usuario_apellido','usuario_email','modificado'];

$foo->fieldsEditor = [
	'usuario_nombre'   => [
		'type'  => 'text'
	],
	'usuario_apellido' => [
		'type'  => 'text'
	],
	'usuario_email'     => [
		'type'  => 'text'
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
	'usuario_nombre'   => 'Nombres',
	'usuario_apellido' => 'Apellidos',
	'usuario_email'    => 'E-mail',
	'modificado'       => 'Última modificación'
];

$foo->lowercase = ['usuario_email'];

$foo->begin();
