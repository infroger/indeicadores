<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class HorasTPD extends Controller
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
            if ($col == null) {
                    echo "Matrícula não encontrada: $d->matricula - $d->usuario<br>\n";
                    //die;
            } 
            $d->colaborador = $col->nome;
        }            
        //print_r($dados);
        //usort($dados, "cmp_buscaHoras_tarefa");
        return $dados;
        }



    public function horas_tpd(Request $request) {

        $nivelAcesso = $request->session()->get('nivelAcesso');
        $fmatricula  = $request->input('fmatricula');
        $fcliente    = $request->input('fcliente');
        $fdatai      = $request->input('fdatai');
        $fdataf      = $request->input('fdataf');
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês

        $dados = $this->buscaHoras($request);
               
        return view('horas_tpd', ['nivelAcesso'    => $nivelAcesso, 
                                       'fmatricula'   => $fmatricula, 
                                       'fcliente'     => $fcliente, 
                                       'fdatai'       => $fdatai, 
                                       'fdataf'       => $fdataf, 
                                       'dados'        => $dados,
                                       //'solicitacoes' => $solicitacoes,
                                       //'clientes'     => $clientes,
                                       //'thoras'       => $thoras,
                                      ]);        
    }
    
    
    

    public function horas_tpd_csv(Request $request) {
        $dados = $this->buscaHoras($request);
        //print_r($dados); die;

        $callback = function() use ($dados) {
            $fp = fopen('php://output', 'w');

            fputcsv($fp, array('Nome', 'Horas TPD', 'Horas OP', 'Percentual'));
            
            foreach ($dados as $d) {
                $linha['nome']          = $d->colaborador;
                $linha['htpd']          = $d->htpd;
                $linha['hop']           = $d->hop;
                $linha['percentual']    = $d->percentual;
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
        ,   'Content-Disposition' => 'attachment; filename=horas_tpd.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
        ];      
        
        return Response::stream($callback, 200, $headers);
      }


}
