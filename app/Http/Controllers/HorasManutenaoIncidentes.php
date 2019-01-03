<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class HorasManutencaoIncidentes extends Controller
{



    public function buscaHoras(Request $request) {
        
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        $fmatricula    = $request->input('fmatricula');
        $fcliente    = $request->input('fcliente');
        $fdatai   = $request->input('fdatai');
        $fdataf   = $request->input('fdataf');
        $username = getenv('AUTHENTICATE_SAMACCOUNTNAME');
        //echo "username: $username";   //print_r($_SERVER);
        $matricula = $request->session()->get('matricula', '0');
        //echo "matricula: $matricula<br>\n";
        
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês

        $where = ' 1=1 ';        
        if ($fcliente != '') {
            $where .= " and lower(cliente) like lower('%$fcliente%') ";
        }
        if ($fmatricula != '') {
            $where .= " and lower(matricula) = '$fmatricula' ";
        }
        

        //Compõe SQL query
        $q = "some_sql";
        
        //echo "$q<br>\n"; //die;
        $dados = DB::connection('pgsql')->select($q);
        //print_r($dados);
        
        $matriculas = busca_matriculas_mongo();
        //print_r($matriculas);
        
        //enriquece dados (nome, por exemplo)
        foreach ($dados as $d) {
            //$k = array_search($d->matricula, $matriculas);
            $col=null;
            foreach ($matriculas as $m) {
                //echo "$d->matricula ";
                if ($m->matricula == $d->matricula)
                    $col = $m;
                }
            $d->colaborador = $col->nome;
        }            
        //print_r($dados);
        //usort($dados, "cmp_buscaHoras_tarefa");
        return $dados;
        }



    public function horas_manutencao_incidentes(Request $request) {

        $nivelAcesso = $request->session()->get('nivelAcesso');
        $fmatricula  = $request->input('fmatricula');
        $fcliente    = $request->input('fcliente');
        $fdatai      = $request->input('fdatai');
        $fdataf      = $request->input('fdataf');
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês

        $dados       = $this->buscaHoras($request);

        //Agrega solicitações por cliente
        $rowspan = Array();
        foreach ($dados as $d) {
            if (!isset($rowspan[$d->cliente]))
                $rowspan[$d->cliente] = 0;
            if (!isset($rowspan[$d->solicitacao]))
                $rowspan[$d->solicitacao] = 0;
            $clientes[$d->cliente]['nome'] = $d->cliente;
            $clientes[$d->cliente]['rowspan'] = ++$rowspan[$d->cliente];
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['id'] = $d->solicitacao;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['nome'] = $d->nome_solicitacao;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['rowspan'] = ++$rowspan[$d->solicitacao];
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['id'] = $d->tarefa;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['nome'] = $d->nome_tarefa;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['colaborador'] = $d->colaborador;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['horas'] = $d->horas;
        }
        //print_r($clientes);

        /*
        //Agrega tarefas por solicitação
        foreach ($dados as $d) {
            $t['tarefa'] = $d->tarefa;
            $t['nome_tarefa'] = $d->nome_tarefa;
            $solicitacoes[$d->solicitacao][] = $t;
        }
        */

        //Totaliza horas
        $thoras = 0;
        foreach ($dados as $k=>$d) {
            $thoras += $d->horas;
        }

               
        return view('horas_manutencao_incidentes', ['nivelAcesso'    => $nivelAcesso, 
                                       'fmatricula'   => $fmatricula, 
                                       'fcliente'     => $fcliente, 
                                       'fdatai'       => $fdatai, 
                                       'fdataf'       => $fdataf, 
                                       'dados'        => $dados,
                                       //'solicitacoes' => $solicitacoes,
                                       'clientes'     => $clientes,
                                       'thoras'       => $thoras,
                                      ]);        
    }
    
    


}
