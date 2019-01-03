<?php

namespace App\Http\Controllers;

require_once 'lib.php';

use App\User;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class ClientesController extends Controller
{

    public function clientes(Request $request) {

        //$supervisor = getSupervisor();
        $nivelAcesso = $request->session()->get('nivelAcesso', '0');

        
        //echo "diretorio: " .getcwd(); die;
        //$dados=file('Clientes_Produtos.csv');
        //$dados = str_getcsv('Clientes_Produtos.csv');
        $dados = array_map('str_getcsv', file('Clientes_Produtos.csv'));
        array_shift($dados); //remove cabeçalho do arquivo CSV
        //print_r($dados);
            
        
        return view('clientes', ['nivelAcesso'  => $nivelAcesso, 
                                 'dados'       => $dados,
                                ]);        
    }
    


}
