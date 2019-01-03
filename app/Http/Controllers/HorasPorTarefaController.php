<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class HorasPorTarefaController extends Controller
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
        usort($dados, "cmp_buscaHoras_tarefa");
        return $dados;
        }



    public function horas_tarefa(Request $request) {

        $nivelAcesso = $request->session()->get('nivelAcesso');
        $fmatricula  = $request->input('fmatricula');
        $fcliente    = $request->input('fcliente');
        $fdatai      = $request->input('fdatai');
        $fdataf      = $request->input('fdataf');
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês

        $dados = $this->buscaHoras($request);

        //Agrega solicitações por cliente
        $rowspan_cli = Array();
        $rowspan_sol = Array();
        $rowspan_tar = Array();
        $clientes = array(); //Necessário para 1o dia da versão (sem dados)
        foreach ($dados as $d) {
            if (!isset($rowspan_cli[$d->cliente]))
                $rowspan_cli[$d->cliente] = 0;
            if (!isset($rowspan_sol[$d->cliente][$d->solicitacao]))
                $rowspan_sol[$d->cliente][$d->solicitacao] = 0;
            if (!isset($rowspan_tar[$d->cliente][$d->solicitacao][$d->tarefa]))
                $rowspan_tar[$d->cliente][$d->solicitacao][$d->tarefa] = 0;
            $clientes[$d->cliente]['nome'] = $d->cliente;
            $clientes[$d->cliente]['rowspan'] = ++$rowspan_cli[$d->cliente];
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['id'] = $d->solicitacao;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['nome'] = $d->nome_solicitacao;
	    $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['extrap'] = $d->extrap;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['rowspan'] = ++$rowspan_sol[$d->cliente][$d->solicitacao];
            if ($d->tarefa == 0)
                $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['rowspan'] = 1;
            else
                $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['rowspan'] = ++$rowspan_tar[$d->cliente][$d->solicitacao][$d->tarefa];
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['id'] = $d->tarefa;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['nome'] = $d->nome_tarefa;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['matriculas'][$d->matricula]['colaborador'] = $d->colaborador;
            $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['matriculas'][$d->matricula]['horas'] = $d->horas;
	    $clientes[$d->cliente]['solicitacoes'][$d->solicitacao]['tarefas'][$d->tarefa]['matriculas'][$d->matricula]['ph'] = $d->ph;
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

               
        return view('horas_tarefa', ['nivelAcesso'    => $nivelAcesso, 
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
    
    
    

    public function horas_tarefa_csv(Request $request) {
        $dados = $this->buscaHoras($request);
        //print_r($dados); die;

        $callback = function() use ($dados) {
            $fp = fopen('php://output', 'w');

            fputcsv($fp, array('Cliente', 'Solicitação', 'Nome Solicitação', 'Tarefa', 'Nome Tarefa', 'Pessoa', 'Horas'));
            
            foreach ($dados as $d) {
                $linha['cliente']           = $d->cliente;
                $linha['solicitacao']       = $d->solicitacao;
                $linha['nome_solicitacao']  = $d->nome_solicitacao;
                $linha['tarefa']            = $d->tarefa;
                $linha['nome_tarefa']       = $d->nome_tarefa;
                $linha['colaborador']       = $d->colaborador;
                $linha['horas']             = $d->horas;
                $linhas[] = $linha;
            }
            
            foreach ($linhas as $l) {
                fputcsv($fp, $l);
            }
            fclose($fp);   
        };
        
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
        ,   'Content-type'        => 'text/csv'
        ,   'Content-Disposition' => 'attachment; filename=horas_tarefa.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
        ];      
        
        return Response::stream($callback, 200, $headers);
      }


}
