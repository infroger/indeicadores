<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class PainelASMCController extends Controller
{


    //Obtem apenas nomes de versão que interessam, descartando os outros
    public $nome_versao = ['Janeiro 1'   =>11,  'Janeiro 2'   =>12,  'Janeiro 3'   =>13,  'Janeiro 4'   =>14,
                    'Fevereiro 1' =>21,  'Fevereiro 2' =>22,  'Fevereiro 3' =>23,  'Fevereiro 4' =>24,
                    'Março 1'     =>31,  'Março 2'     =>32,  'Março 3'     =>33,  'Março 4'     =>34,
                    'Abril 1'     =>41,  'Abril 2'     =>42,  'Abril 3'     =>43,  'Abril 4'     =>44,
                    'Maio 1'      =>51,  'Maio 2'      =>52,  'Maio 3'      =>53,  'Maio 4'      =>54,
                    'Junho 1'     =>61,  'Junho 2'     =>62,  'Junho 3'     =>63,  'Junho 4'     =>64,
                    'Julho 1'     =>71,  'Julho 2'     =>72,  'Julho 3'     =>73,  'Julho 4'     =>74,
                    'Agosto 1'    =>81,  'Agosto 2'    =>82,  'Agosto 3'    =>83,  'Agosto 4'    =>84,
                    'Setembro 1'  =>91,  'Setembro 2'  =>92,  'Setembro 3'  =>93,  'Setembro 4'  =>94,
                    'Outubro 1'   =>101, 'Outubro 2'   =>102, 'Outubro 3'   =>103, 'Outubro 4'   =>104,
                    'Novembro 1'  =>111, 'Novembro 2'  =>112, 'Novembro 3'  =>113, 'Novembro 4'  =>114,
                    'Dezembro 1'  =>121, 'Dezembro 2'  =>122, 'Dezembro 3'  =>123, 'Dezembro 4'  =>124,
                   ];


    public function painel(Request $request) {


        //define_sessao($request);
	$fdie	      =  date_create(date('Y-m-d') ." first day of this month");
	$fdfe         =  date_create(date('Y-m-d') ." last day of this month");
  $fdataie      = ($request->input('fdataie') ? $request->input('fdataie') : $fdie->format('Y-m-d'));     //1o do mês anterior
  $fdatafe      = ($request->input('fdatafe') ? $request->input('fdatafe') : $fdfe->format('Y-m-d'));     //último do mês anterior

	$categories = '';


  //Tarefas em aberto no período
	$q = "some_sql";
    //echo $q; //die;
    $dados = DB::connection('sqlsrv')->select($q);
    //print_r($dados); die;
    //echo "Tempo 1: " .(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) ." s<br>\n";



    //Gráfico de tarefas nos últimos 4 meses
    $q = "some_sql";
      //echo $q; //die;
      $dados2 = DB::connection('sqlsrv')->select($q);
      //print_r($dados2); die;
      //echo "Tempo 1: " .(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) ." s<br>\n";

      $cat = array(); $categories=''; $ponto_focal=''; $backup_restore = ''; $dbas=''; $servidores=''; $aplicacao='';
      foreach ($dados2 as $k=>$d) {
        $cat[$d->mes1] = $d->mes1;
          //$categories .= "'" .$d->mes1 ."', ";
          switch ($d->grupo) {
            case 'T/GTI - Ponto Focal':               $ponto_focal    .= $d->tarefas .", "; break;
            case 'T/GTI Backup / Restore':            $backup_restore .= $d->tarefas .", "; break;
            case 'T/GTI Banco de Dados':              $dbas           .= $d->tarefas .", "; break;
            case 'T/GTI Gestão de Servidores':        $servidores     .= $d->tarefas .", "; break;
            case 'T/GTI Infraestrutura de Aplicação': $aplicacao      .= $d->tarefas .", "; break;
          }
      }
      $categories = '"' .implode('", "', $cat) .'"';

        /*
         *  View
         */
         return view('painel_asmc', ['nivelAcesso'    => $request->session()->get('nivelAcesso'),
                                //'fdias_quantitativo' => $fdias_quantitativo,
				//'fmatriculaquant'    => $fmatriculaquant,
                                'fdataie'            => $fdataie,
                                'fdatafe'            => $fdatafe,
                                'dados'              => $dados,
                                'dados2'             => $dados2,
                                'categories'         => $categories,
//                                'ponto_focal'        => $ponto_focal,
                                'backup_restore'     => $backup_restore,
                                'dbas'               => $dbas,
                                'servidores'         => $servidores,
                                'aplicacao'          => $aplicacao,

                               ]);

    }



}
