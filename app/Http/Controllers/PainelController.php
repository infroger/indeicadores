<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class PainelController extends Controller
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

  /*
  *  Gráfico Quantitativo
  */
  $fdias_quantitativo  = ($request->input('fdias_quantitativo') ? $request->input('fdias_quantitativo') : 90);
  $fmatriculaquant  = $request->input('fmatriculaquant') ? $request->input('fmatriculaquant') : '';
  $dados = $this->busca_dados_quantitativo($request, $fdias_quantitativo, $fmatriculaquant);
  //print_r($dados);
  $categories = ''; $solicitacoes = ''; $tarefas = ''; $amb_novos = ''; $extra_priorizacao=0; $pontos_historia = 0; 		$horas_op = 0;
  foreach ($dados as $d) {
    $nv = explode('-', $d->nome_versao);
    $nome_versao = trim($nv[0]);
    if (!array_key_exists($nome_versao, $this->nome_versao))
       continue;
    $categories             .= "'" .substr($nome_versao, 0, 3) .substr($nome_versao, strlen($nome_versao)-1, 1) ."', ";
    $solicitacoes           .= $d->solicitacoes .", ";
    $tarefas                .= $d->tarefas .", ";
    $amb_novos              .= $d->novo_ambiente .", ";
    $extra_priorizacao      .= $d->extra_priorizacao .", ";
    $pontos_historia        .= $d->pontos_historia .", ";
    $horas_op               .= $d->horas_op .", ";
    $arr_solicitacoes[]      = $d->solicitacoes;
    $arr_tarefas[]           = $d->tarefas;
    $arr_amb_novos[]         = $d->novo_ambiente;
    $arr_extra_priorizacao[] = $d->extra_priorizacao;
    $arr_pontos_historia[]   = $d->pontos_historia;
    $arr_horas_op[]          = $d->horas_op;
  }
  $solicitacoes      = rtrim($solicitacoes, ", ");
  $tarefas           = rtrim($tarefas, ", ");
  $amb_novos         = rtrim($amb_novos, ", ");
  $extra_priorizacao = rtrim($extra_priorizacao, ", ");
  $pontos_historia   = rtrim($pontos_historia, ", ");
  $horas_op          = rtrim($horas_op, ", ");

  /*
  *  Gráfico Clientes Esquerda
  */
  $fdie	      =  date_create(date('Y-m-d') ." first day of last month");
  $fdfe         =  date_create(date('Y-m-d') ." last day of last month");
  $fdataie      = $request->input('fdataie') ? $request->input('fdataie') : $fdie->format('Y-m-d');     //1o do mês anterior
  $fdatafe      = $request->input('fdatafe') ? $request->input('fdatafe') : $fdfe->format('Y-m-d');     //último do mês anterior

  $fdataid      = $request->input('fdataid') ? $request->input('fdataid') : date('Y-m-01');             //1o do mês
  $fdatafd      = $request->input('fdatafd') ? $request->input('fdatafd') : date('Y-m-' .date('t'));    //último do mês
  //echo "fdataie: $fdataie<br>\n";
  //echo "fdatafe: $fdatafe<br>\n";
  //echo "fdataid: $fdataid<br>\n";
  //echo "fdatafd: $fdatafd<br>\n";
  //die;

  $select = ['cliente', 'sum(horas_registro) as horas'];
  $from   = 'openproject.arvore';
  $where  = ["divisao='T/GTI'", "data_registro between '$fdataie' and '$fdatafe'"];
  $groupby = ['cliente'];
  $orderby = ['cliente'];
  $q = monta_query($select, $from, null, $where, $groupby, $orderby);
  //echo $q; //die;
  $dadose = DB::connection('pgsql')->select($q);
  //print_r($dadose); //die;
  //Totaliza horas
  $thope = 0;
  foreach ($dadose as $k=>$d)
  $thope    += $d->horas;
  //Calcula percentual
  $clientes_dadose = '';
  foreach ($dadose as $k=>$d) {
    $dadose[$k]->percentual = number_format($dadose[$k]->horas / $thope * 100, 1);
    $clientes_dadose .= "{name: '$d->cliente', y: $d->percentual }, ";
  }
  //print_r($dadose); //die;


  /*
  *  Gráfico Clientes Direita
  *      Basicamente copiado do código acima que faz o gráfico da esquerda, apenas adequando a cláusua $where e renomeando as variáveis
  */

  $where  = ["divisao='T/GTI'", "data_registro between '$fdataid' and '$fdatafd'"];
  $q = monta_query($select, $from, null, $where, $groupby, $orderby);
  //echo $q; //die;
  $dadosd = DB::connection('pgsql')->select($q);
  //print_r($dadosd); //die;
  //Totaliza horas
  $thopd = 0;
  foreach ($dadosd as $k=>$d)
  $thopd    += $d->horas;
  //Calcula percentual
  $clientes_dadosd = '';
  foreach ($dadosd as $k=>$d) {
    $dadosd[$k]->percentual = number_format($dadosd[$k]->horas / $thopd * 100, 1);
    $clientes_dadosd .= "{name: '$d->cliente', y: $d->percentual }, ";
  }
  //print_r($dados); //die;



  /*
  *  Gráfico Manutenções e Incidentes Esquerda
  *
  */
  $fdie           =  date_create(date('Y-m-d') ." first day of last month");
  $fdfe           =  date_create(date('Y-m-d') ." last day of last month");
  $fdatamiie      = $request->input('fdatamiie') ? $request->input('fdatamiie') : $fdie->format('Y-m-d');     //1o do mês anterior
  $fdatamife      = $request->input('fdatamife') ? $request->input('fdatamife') : $fdfe->format('Y-m-d');     //último do mês anterior
  $fmatriculamie  = $request->input('fmatriculamie') ? $request->input('fmatriculamie') : '';

  $fdatamiid      = $request->input('fdatamiid') ? $request->input('fdatamiid') : date('Y-m-01');    //1o do mês
  $fdatamifd      = $request->input('fdatamifd') ? $request->input('fdatamifd') : date('Y-m-' .date('t'));    //último do mês
  $fmatriculamid  = $request->input('fmatriculamid') ? $request->input('fmatriculamid') : '';
  //echo "$fmatriculamie<br>\n";

  $matriculas = arr_matriculas_nome();

  $wheree = $fmatriculamie != '' ? "and matricula = '$fmatriculamie' " : '';
  $whered = $fmatriculamid != '' ? "and matricula = '$fmatriculamid' " : '';

  $q = "some_sql";
  //echo $q; //die;
  $dadose = DB::connection('pgsql')->select($q);
  //Totaliza horas
  $t = 0;
  foreach ($dadose as $k=>$d)
  $t    += $d->horas;
  //Calcula percentual
  $manut_dadose = '';
  foreach ($dadose as $k=>$d) {
    $dadose[$k]->percentual = ($t != 0) ? number_format($dadose[$k]->horas / $t * 100, 1) : 0 ;
    $manut_dadose .= "{name: '$d->tipo', y: $d->percentual }, ";
  }
  //print_r($dadose); //die;



  /*
  *  Gráfico Manutenções e Incidentes Direita
  *
  */

  $q = "some_sql";
  //echo $q; //die;
  $dadosd = DB::connection('pgsql')->select($q);
  //Totaliza horas
  $t = 0;
  foreach ($dadosd as $k=>$d)
  $t    += $d->horas;
  //Calcula percentual
  $manut_dadosd = '';
  foreach ($dadosd as $k=>$d) {
    $dadosd[$k]->percentual = ($t != 0) ? number_format($dadosd[$k]->horas / $t * 100, 1) : 0 ;
    $manut_dadosd .= "{name: '$d->tipo', y: $d->percentual }, ";
  }
  //print_r($dadosd); //die;




  $data_cubo = $this->busca_ultima_data_cubo();
  //echo "Tempo 2: " .(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) ." s<br>\n";


  /*
  *  View
  */
  return view('painel', ['nivelAcesso'    => $request->session()->get('nivelAcesso'),
  'fdias_quantitativo' => $fdias_quantitativo,
  'fmatriculaquant'    => $fmatriculaquant,
  'fdataie'            => $fdataie,
  'fdatafe'            => $fdatafe,
  'fdataid'            => $fdataid,
  'fdatafd'            => $fdatafd,
  'fdatamiie'          => $fdatamiie,
  'fdatamife'          => $fdatamife,
  'fdatamiid'          => $fdatamiid,
  'fdatamifd'          => $fdatamifd,
  'fmatriculamie'      => $fmatriculamie,
  'fmatriculamid'      => $fmatriculamid,
  'nomemie'            => $fmatriculamie != '' ? $matriculas[$fmatriculamie] : '',
  'nomemid'            => $fmatriculamid != '' ? $matriculas[$fmatriculamid] : '',
  'categories'         => $categories,
  'solicitacoes'       => $solicitacoes,
  'tarefas'            => $tarefas,
  'amb_novos'          => $amb_novos,
  'extra_priorizacao'  => $extra_priorizacao,
  'pontos_historia'    => $pontos_historia,
  'horas_op'           => $horas_op,
  'clientes_dadose'    => $clientes_dadose,
  'clientes_dadosd'    => $clientes_dadosd,
  'manut_dadose'       => $manut_dadose,
  'manut_dadosd'       => $manut_dadosd,
  'data_cubo'          => $data_cubo,
]);

}



public function busca_ultima_data_cubo() {

  $q = "select max(data_hora) as data from openproject.arvore";
  //echo $q; //die;
  $dados = DB::connection('pgsql')->select($q);
  //print_r($dados); //die;
  return $dados[0]->data;
}


public function busca_dados_quantitativo(Request $request, $fdias, $fmatriculaquant) {

  if ($fmatriculaquant <> '')
  $where = " matricula = '$fmatriculaquant' ";
  else
  $where = ' 1=1 ';

  $q="some_big_sql";
  //echo $q; //die;
  $dados = DB::connection('pgsql')->select($q);
  //print_r($dados); //die;
  //echo "Tempo 1: " .(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) ." s<br>\n";

  return $dados;
}



public function painel_quantitativo_csv(Request $request) {

  $dados = $this->busca_dados_quantitativo($request, 180);
  //print_r($dados); die;

  $callback = function() use ($dados) {
    $fp = fopen('php://output', 'w');
    fputcsv($fp, array('Nome Versão', 'Início Versão', 'Solicitações', 'Tarefas', 'Novos Ambientes', 'Extra Priorização', 'Pontos História'));
    foreach ($dados as $d) {
      $nv = explode('-', $d->nome_versao);
      $nome_versao = trim($nv[0]);
      if (!array_key_exists($nome_versao, $this->nome_versao))
      continue;
      fputcsv($fp, (array) $d);
    }
    fclose($fp);
  };

  $headers = [
    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
    ,   'Content-type'        => 'text/csv'
    ,   'Content-Disposition' => 'attachment; filename=indicadores_open_project.csv'
    ,   'Expires'             => '0'
    ,   'Pragma'              => 'public'
  ];
  return Response::stream($callback, 200, $headers);

}



}
