<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Response;
use App\Usuario;

class HorasPontosHistoria extends Controller
{



    public function busca_ph(Request $request) {

        $nivelAcesso = $request->session()->get('nivelAcesso', '0');
        //$fmatricula    = $request->input('fmatricula');
        $fcliente    = $request->input('fcliente');
        $fversao   = $request->input('fversao');
        $username = getenv('AUTHENTICATE_SAMACCOUNTNAME');
        //echo "username: $username";   //print_r($_SERVER);
        $matricula = $request->session()->get('matricula', '0');
        //echo "matricula: $matricula<br>\n";
        //echo "matricula: $matricula<br>\n";

        $versoes = busca_versoes($request);
        //print_r($versoes);

        if ($fversao == '') $fversao = $versoes[0]->nome_versao;
        //echo "fversao: $fversao<br>\n";

        //Compõe SQL query
        $q = "some_sql";

        //echo "$q<br>\n"; //die;
        $dados = DB::connection('pgsql')->select($q);
        //print_r($dados);

        //$matriculas = busca_matriculas_mongo();
        //print_r($matriculas);

        //print_r($dados);
        //usort($dados, "cmp_buscaHoras_tarefa");
        return $dados;
        }



    public function pontos_historia(Request $request) {

        $nivelAcesso = $request->session()->get('nivelAcesso');
        $fversao     = $request->input('fversao');
        //echo $fversao;

        $versoes = busca_versoes($request);
        //print_r($versoes);
        if ($fversao == '') $fversao = $versoes[0]->nome_versao;
        //echo $fversao;

        $dados = $this->busca_ph($request);


        return view('horas_pontoshistoria', ['nivelAcesso'  => $nivelAcesso,
                                             'fversao'      => $fversao,
                                             'versoes'      => $versoes,
                                             'dados'        => $dados,
                                            ]);
    }




    public function pontos_historia_csv(Request $request) {
        $dados = $this->busca_ph($request);
        //print_r($dados); die;

        $callback = function() use ($dados) {
            $fp = fopen('php://output', 'w');

            fputcsv($fp, array('Solicitação', 'Descrição', 'Pontos História', 'Horas OP'));

            foreach ($dados as $d) {
                $linha['nivel1']          = $d->nivel1;
                $linha['nome_tarefa']     = $d->nome_tarefa;
                $linha['pontos_historia'] = $d->pontos_historia;
                $linha['horas_registro']  = $d->horas_registro;
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
        ,   'Content-Disposition' => 'attachment; filename=pontos_historia.csv'
        ,   'Expires'             => '0'
        ,   'Pragma'              => 'public'
        ];

        return Response::stream($callback, 200, $headers);
      }


}
