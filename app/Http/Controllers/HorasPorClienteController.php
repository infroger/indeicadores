<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;

class HorasPorClienteController extends Controller
{


    public function buscaDados(Request $request) {
        
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        $fcliente    = $request->input('fcliente');
        $fdatai      = $request->input('fdatai');
        $fdataf      = $request->input('fdataf');
        $fdetalhe    = $request->input('fdetalhe');
        $fdetalhe_checked = ($fdetalhe == 'S') ? 'checked' : '';
        
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês
        
        $username = getenv('AUTHENTICATE_SAMACCOUNTNAME');
        //$matriculas = busca_matriculas();
        //$fmatricula = array_search($username, array_column($matriculas, 'usuario', 'matricula'));
        $nome = $request->session()->get('nome', '0'); 


        if ($nivelAcesso > 0)
            $fmatricula  = $request->input('fmatricula');
        else
            $fmatricula = $request->session()->get('matricula', '0'); 

        
        $select = ['field1', 'field2', 'field3', 'sum(field4) as field5'];
        $from   = 'some_table';
        $where  = ["where1", "dwhere2", "where3'",
                   "where4" 
                  ];
        $groupby = ['groupby1', 'groupby2', 'groupby3'];
        $orderby = ['orderby1', 'orderby2', 'orderby3'];
        
        if ($fdetalhe != 'S') {
            $select  = array_diff($select , ['fieldn']);
            $groupby = array_diff($groupby, ['fieldn']);
            $orderby = array_diff($orderby, ['fieldn']);

            $select  = array_diff($select , ['fieldn']);
            $groupby = array_diff($groupby, ['fieldn']);
            $orderby = array_diff($orderby, ['fieldn']);
        }
        
        if ($nivelAcesso > 0 and $fmatricula == '')
            $where  = array_diff($where , ["matricula = '$fmatricula'"]);
            
        if  ($fcliente == '') 
            $where  = array_diff($where , ["lower(cliente) like lower('%$fcliente%')"]);
         
        
        $q = monta_query($select, $from, null, $where, $groupby, $orderby);        
        //echo $q; //die;
        $dados = DB::connection('pgsql')->select($q);
        //print_r($dados); die;

        $usuarios = busca_matriculas_mongo();
        //$nomes = $usuarios->pluck('nome', 'matricula');
        //print_r($nomes);        
        
        //Coloca nome
        $thop = 0;
        foreach ($dados as $k=>$d) { 
            $thop    += $d->horas;
            if ($fdetalhe == 'S') {
                //$dados[$k]->nome = $nomes[$dados[$k]->matricula];
                $n = $d->matricula;
                $obj = array_filter( $usuarios,function ($e) use ($n) { return $e->matricula == $n; } );
                $obj2 = array_shift($obj);
                $d->nome = $obj2->nome;
            }
        }
        
        //Calcula percentual
        foreach ($dados as $k=>$d) {
            $dados[$k]->percentual = number_format($dados[$k]->horas / $thop * 100, 1);
        }
        
        usort($dados, "cmp_buscaHoras_cliente");

        return $dados;        
    }



    public function HorasPorCliente(Request $request) {

        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        $fmatricula  = $request->input('fmatricula');
        $fcliente    = $request->input('fcliente');
        $fdatai      = $request->input('fdatai');
        $fdataf      = $request->input('fdataf');
        $fdetalhe    = $request->input('fdetalhe');
        $fdetalhe_checked = ($fdetalhe == 'S') ? 'checked' : '';
        
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês

        $dados = $this->buscaDados($request);

        //Totaliza horas
        $thop = 0;
        foreach ($dados as $k=>$d) 
            $thop    += $d->horas;
        
        return view('horas_cliente', ['nivelAcesso' => $nivelAcesso, 
                                      'dados'       => $dados,
                                      'fmatricula'  => $fmatricula,
                                      'fcliente'    => $fcliente,
                                      'fdatai'      => $fdatai,
                                      'fdataf'      => $fdataf,
                                      'fdetalhe'    => $fdetalhe,
                                      'fdetalhe_checked'    => $fdetalhe_checked,
                                      'thop'        => $thop,
                                ]);        
    }
    





    public function horas_cliente_csv(Request $request) {
        $fdetalhe = $request->input('fdetalhe');
        $dados = $this->buscaDados($request);
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        //print_r($dados); die;
       

        $callback = function() use ($dados, $nivelAcesso, $fdetalhe) {
            $fp = fopen('php://output', 'w');

            if ($fdetalhe == 'S')
                fputcsv($fp, array('Cliente', 'Data', 'Nome', 'Horas OP', 'Percentual'));
            else
                fputcsv($fp, array('Cliente', 'Horas OP', 'Percentual'));
            
            foreach ($dados as $d) {
                $linha['cliente']       = $d->cliente;
                if ($fdetalhe == 'S') {
                    $linha['data']       = $d->data_registro;
                    $linha['nome']       = $d->nome;
                }
                $linha['horas']            = number_format($d->horas, 2);
                $linha['percentual']     = number_format($d->percentual, 2);
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
        ,   'Content-Disposition' => 'attachment; filename=horas_cliente.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
        ];      
        
        return Response::stream($callback, 200, $headers);
      }


}
