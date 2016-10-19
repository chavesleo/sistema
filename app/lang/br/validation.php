<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "O campo :attribute deve ser aceito.",
	"active_url"           => "O campo :attribute não é uma URL válida.",
	"after"                => "O campo :attribute dever ser uma data após :date.",
	"alpha"                => "O campo :attribute deve conter somente letras.",
	"alpha_dash"           => "O campo :attribute deve conter letras, números, e pontos.",
	"alpha_num"            => "O campo :attribute deve conter somente letras e números.",
	"array"                => "O campo :attribute deve ser uma lista.",
	"before"               => "O campo :attribute deve ser uma data anterior a :date.",
	"between"              => array(
		"numeric" => "O campo :attribute must be between :min and :max.",
		"file"    => "O campo :attribute must be between :min and :max kilobytes.",
		"string"  => "O campo :attribute must be between :min and :max characters.",
		"array"   => "O campo :attribute must have between :min and :max items.",
	),
	"confirmed"            => "O campo :attribute não é compativel com a confirmação.",
	"date"                 => "O campo :attribute não é uma data válida.",
	"date_format"          => "O campo :attribute não é compativel com o formato :format.",
	"different"            => "O campo :attribute e :other devem ser diferentes.",
	"digits"               => "O campo :attribute deve ter :digits dígitos.",
	"digits_between"       => "O campo :attribute deve estar entre :min and :max dígitos.",
	"email"                => "O campo :attribute deve ser um email válido.",
	"exists"               => "O campo selecionado :attribute é inválido.",
	"image"                => "O campo :attribute deve ser uma imagem."
,	"in"                   => "O campo selecionado :attribute é inválido.",
	"integer"              => "O campo :attribute deve ser um número inteiro.",
	"ip"                   => "O campo :attribute deve ser um endereço de IP válido.",
	"max"                  => array(
		"numeric" => "O campo :attribute não pode ser maior que :max.",
		"file"    => "O campo :attribute não pode ser maior que :max kilobytes.",
		"string"  => "O campo :attribute não pode ser maior que :max characters.",
		"array"   => "O campo :attribute não pode ter mais que :max itens.",
	),
	"mimes"                => "O campo :attribute deve ser um arquivo do tipo: :values.",
	"min"                  => array(
		"numeric" => "O campo :attribute deve ser no mínimo :min.",
		"file"    => "O campo :attribute deve ter no mínimo :min kilobytes.",
		"string"  => "O campo :attribute deve ter no mínimo :min caracteres.",
		"array"   => "O campo :attribute deve ter no mínimo :min ítens.",
	),
	"not_in"               => "O campo selecionado :attribute é inválido.",
	"numeric"              => "O campo :attribute deve ser um número.",
	"regex"                => "O campo :attribute possui formato inválido.",
	"required"             => "O campo :attribute é obrigatório.",
	"required_if"          => "O campo :attribute é obrigatório quando :other é :value.",
	"required_with"        => "O campo :attribute é obrigatório quando :values não estiver presente.",
	"required_with_all"    => "O campo :attribute é obrigatório quando :values não estiver presente.",
	"required_without"     => "O campo :attribute é obrigatório quando :values não estiver presente.",
	"required_without_all" => "O campo :attribute é obrigatório quando nenhum de :values estiver presente.",
	"same"                 => "O campo :attribute e :other devem coincidir.",
	"size"                 => array(
		"numeric" => "O campo :attribute deve ser :size.",
		"file"    => "O campo :attribute deve possuir :size kilobytes.",
		"string"  => "O campo :attribute deve conter :size caracteres.",
		"array"   => "O campo :attribute deve conter :size ítens.",
	),
	"unique"               => "O campo :attribute já está sendo utilizado.",
	"url"                  => "O campo :attribute possui um formato inválido.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => array(
		'attribute-name' => array(
			'rule-name' => 'custom-message',
		),
	),

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => array(),

);
