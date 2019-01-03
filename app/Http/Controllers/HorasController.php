<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class HorasController extends Controller
{



    public function buscaHoras(Request $request) {
        
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        //$fnome    = $request->input('fnome');
        $fdatai   = $request->input('fdatai');
        $fdataf   = $request->input('fdataf');
        if ($nivelAcesso == 0 and !isset($fdatai)) 
            $fdetalhe='S';
        else
            $fdetalhe = $request->input('fdetalhe');
                
        $username = getenv('AUTHENTICATE_SAMACCOUNTNAME');
        //echo "username: $username";   //print_r($_SERVER);
        $matricula = $request->session()->get('matricula', '0');
        //echo "matricula: $matricula<br>\n";
        
        
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês
        
        //$supervisor = getSupervisor();

        //Compõe SQL query
        //Query Ronda
        $where='';
        if ($nivelAcesso == 0) 
              $where = " and matricula=$matricula ";
        
        if ($fdetalhe == 'S')
            $q = "some big sql";
        
        //echo "$q<br>\n"; //die;
        $dadosOP = DB::connection('pgsql')->select($q);
        //print_r($dados);
        
        $matriculas = busca_matriculas_mongo();
        //print_r($matriculas);        
        

    	$dadosRonda = $this->horas_ronda($request, $fdatai, $fdataf, $fdetalhe);
    	//print_r($dadosRonda);
    	
        //Merge dos dois vetores (vetor de objetos do OP e vetor associativo do Ronda) e enriquecimento com nome do colaborador
    	//http://stackoverflow.com/a/1953077
        $arrDados = (object) array_merge((array) $dadosOP, (array) $dadosRonda);   	
        //print_r($arrDados);
        //$n=0;
        foreach($arrDados as $el) {
            if (is_object($el)) {
                $m = $fdetalhe == 'S' ? $el->matricula .'-' .$el->data : $el->matricula;
                $dados[$m]['matricula'] = $el->matricula;
                if ($fdetalhe == 'S')
                    $dados[$m]['data'] = $el->data;
                $dados[$m]['hop'] = $el->hop;
                if (!isset($dados[$m]['hronda'])) $dados[$m]['hronda'] = 0;
            } else {
                $m = $fdetalhe == 'S' ? $el['matricula'] .'-' .$el['data'] : $el['matricula'];
                $dados[$m]['matricula'] = $el['matricula'];
                if ($fdetalhe == 'S')
                    $dados[$m]['data'] = $el['data'];
                $dados[$m]['hronda'] = $el['hronda'];
                if (!isset($dados[$m]['hop'])) $dados[$m]['hop'] = 0;
            }
            $dados[$m]['diferenca'] = $dados[$m]['hronda'] - $dados[$m]['hop'];
            
            if ($dados[$m]['hronda'] == 0 or $dados[$m]['hop'] == 0)
                $dados[$m]['diferenca_destaque'] = 'destaque';
            else
                $dados[$m]['diferenca_destaque'] = (abs($dados[$m]['hronda'] - $dados[$m]['hop']) / max($dados[$m]['hronda'], $dados[$m]['hop'])) > 0.25 ? 'destaque' : '';
             
            //Nome colaborador
            if (!isset($dados[$m]['colaborador'])) {
               $col=null;
               foreach ($matriculas as $mat) {
                  if ($mat->matricula == $dados[$m]['matricula']) {
        	         $col = $mat;
                     break;
                  }
                }
            $dados[$m]['colaborador'] = $col->nome;
            }      
         
         //$n++;                
         }       
    	//print_r($dados); //die;
    	
    	if (!isset($dados))
    	   $dados = array();
                
        //print_r($dados);
        if ($fdetalhe == 'S')
            usort($dados, "cmp_buscaHoras_detalhe");
	else
	    usort($dados, "cmp_buscaHoras");
        return $dados;
        }



    public function horas_ronda(Request $request, $fdatai, $fdataf, $fdetalhe) {
        
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        $matricula = $request->session()->get('matricula', '0');

        $where='';
        if ($nivelAcesso == 0) 
              $where = " and matrícula=$matricula ";
        
	//197 = T/GTI, 225 = T/ST11
    	if ($fdetalhe == 'S')
    		$q = "some_sql";
    	else
    		$q = "some_other_sql";
    	//echo "$q<br>\n"; //die;
    	$dados = DB::connection('mysql')->select($q);
    	$dados = json_decode(json_encode($dados), true);
        //print_r($dados); die;
    	return $dados;
    	
    }




    public function horas_funcionario(Request $request) {

        define_sessao($request);

        $nivelAcesso = $request->session()->get('nivelAcesso');
        //$fnome  = $request->input('fnome');
        $fdatai = $request->input('fdatai');
        $fdataf = $request->input('fdataf');
        if ($nivelAcesso == 0 and !isset($fdatai)) 
            $fdetalhe='S';
        else
            $fdetalhe = $request->input('fdetalhe');

        //echo "fdetalhe: $fdetalhe<br>\n";

        $fdetalhe_checked = ($fdetalhe == 'S') ? 'checked' : '';
        $dados = $this->buscaHoras($request);
        //$supervisor = getSupervisor();
        //$nivelAcesso = $request->session()->get('nivelAcesso', '0');

        
        if ($fdatai == '') $fdatai=date('Y-m-01');             //1o do mês
        if ($fdataf == '') $fdataf=date('Y-m-' .date('t'));    //último do mês
        
        //Totalizando horas
        $hronda = 0; $hop = 0; $hdif = 0;
        foreach ($dados as $d) {
            $hronda += $d['hronda'];
            $hop    += $d['hop'];
            $hdif   += $d['hronda'] - $d['hop'];
        }
        
        return view('horas_funcionario', ['nivelAcesso'  => $nivelAcesso, 
                                       //'fnome'  => $fnome, 
                                       'fdatai' => $fdatai, 
                                       'fdataf' => $fdataf, 
                                       'fdetalhe' => $fdetalhe, 
                                       'fdetalhe_checked' => $fdetalhe_checked, 
                                       'dados'  => $dados,
                                       'hronda' => $hronda,
                                       'hop'    => $hop,
                                       'hdif'   => $hdif,
                                      ]);        
    }
    

    public function horas_funcionario_csv(Request $request) {
        //echo "olá!";
        $fdetalhe = $request->input('fdetalhe');
        //echo "fdetalhe: $fdetalhe<br>\n"; 
        $dados = $this->buscaHoras($request);
        //$supervisor = getSupervisor();
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        //print_r($dados); die;
       

        $callback = function() use ($dados, $nivelAcesso, $fdetalhe) {
            $fp = fopen('php://output', 'w');

            if ($nivelAcesso > 0)
                if ($fdetalhe == 'S')
                    fputcsv($fp, array('Colaborador', 'Data', 'Horas Ronda', 'Horas OP', 'Diferenca'));
                else
                    fputcsv($fp, array('Colaborador', 'Horas Ronda', 'Horas OP', 'Diferenca'));
            else
                fputcsv($fp, array('Data', 'Horas Ronda', 'Horas OP', 'Diferenca'));
            
            foreach ($dados as $d) {
                if ($nivelAcesso > 0)
                    $linha['colaborador'] = $d->colaborador;
                if ($fdetalhe == 'S')
                    $linha['data']       = $d->data;
                $linha['hr']             = number_format($d->hr, 2);
                $linha['hop']            = number_format($d->hop, 2);
                $linha['diferenca']      = number_format($d->diferenca, 2);
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
        ,   'Content-Disposition' => 'attachment; filename=horas_funcionario.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
        ];      
        
        return Response::stream($callback, 200, $headers);
      }


}
