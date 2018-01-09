<?php

//DASHBOARD
Route::get('/', 'CronogramaController@index');
Route::get('/index', 'CronogramaController@index');

//DASHBOARD -> NOVO CRONOGRAMA
Route::get('/cronograma/novo', 'CronogramaController@novo_cronograma');

//SALVAR NOVO CRONOGRAMA
Route::post('/cronograma/salvar', 'CronogramaController@novo_assunto');


//PROJETOS
Route::group(array('prefix' => 'projetos'), function()
{
	//TODOS OD PROJETOS ATIVOS
	Route::get('/', 'CronogramaController@projetos');

	//NOVO PROJETO
	Route::get('/novo', 'CronogramaController@novo');
	Route::post('/novo', 'CronogramaController@criar_novo');

	//DETALHES PROJETO/CRONOGRAMA
	Route::get('/cronograma', 'CronogramaController@cronograma');

	//UPLOAD DE ARQUIVO
	Route::post('/upload', 'CronogramaController@gravar_arquivo');

	//TODOS OS PROJETOS INATIVOS
	Route::get('/historico', 'CronogramaController@historico');
});


//CONVERSA
Route::get('/conversa', 'CronogramaController@conversa');


//REUNIÕES
Route::get('/reunioes', 'CronogramaController@reunioes');


//LOGIN
Route::get('/login', 'CronogramaController@login');
Route::post('/logar', 'CronogramaController@logar');

//RECUPERAR SENHA
Route::post('/recuperar_senha', 'CronogramaController@recuperar_senha');

//LOGOUT
Route::get('/logout', 'CronogramaController@logout');


//DETALHES
Route::get('/detalhes/{id}', 'CronogramaController@detalhes');
Route::post('/editar_detalhes', 'CronogramaController@editar_detalhes');


//DETALHES DO ASSUNTO DO CRONOGRAMA
Route::get('/detalhes_assunto/{id}', 'CronogramaController@detalhes_assunto');
Route::post('/salvar_assunto/{id}', 'CronogramaController@salvar_assunto');


//DETALHES DO PERFIL
Route::get('/perfil', 'CronogramaController@perfil');
Route::post('/perfil/editar', 'CronogramaController@editar_perfil');


//CRIAR USUARIO ORIENTADOR
Route::get('/novo_orientador/1020304050_', 'CronogramaController@novo_orientador');
Route::post('/salvar_orientador/1020304050_', 'CronogramaController@salvar_orientador');

//CASO O USUÁRIO DIGITE QUALQUER URL
Route::get('/error', function(){
	abort(404);
});

//TESTE EDUZZ
Route::get('/eduzz', 'CronogramaController@eduzz');