<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::group(['middleware' => 'web'], function () {
    //Route::get('/', 'PainelController@painel');
    Route::get('/', 'HorasController@horas_funcionario');
    Route::get('painel', 'PainelController@painel');
    Route::get('painel_quantitativo_csv', 'PainelController@painel_quantitativo_csv');
    Route::get('painel_clientes_csv', 'PainelController@painel_clientes_csv');
    Route::get('horas_funcionario', 'HorasController@horas_funcionario');
    Route::get('horas_funcionario_csv', 'HorasController@horas_funcionario_csv');
    Route::get('clientes', 'ClientesController@clientes');
    Route::get('horas_cliente', 'HorasPorClienteController@HorasPorCliente');
    Route::get('horas_cliente_csv', 'HorasPorClienteController@horas_cliente_csv');
    Route::get('horas_tarefa', 'HorasPorTarefaController@horas_tarefa');
    Route::get('horas_tarefa_csv', 'HorasPorTarefaController@horas_tarefa_csv');
    Route::get('horas_manutencao_incidentes', 'HorasManutencaoIncidentes@horas_manutencao_incidentes');
    Route::get('horas_tpd', 'HorasTPD@horas_tpd');
    Route::get('horas_tpd_csv', 'HorasTPD@horas_tpd_csv');
    Route::get('pontos_historia', 'HorasPontosHistoria@pontos_historia');
    Route::get('pontos_historia_csv', 'HorasPontosHistoria@pontos_historia_csv');
    Route::get('painel_asmc', 'PainelASMCController@painel');

});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
