<?php

use App\Usuario;



    function define_sessao($request) {

        $username = getenv('AUTHENTICATE_SAMACCOUNTNAME');
        //echo "username: $username<br>\n";  //print_r($_SERVER);
        $request->session()->put('username', $username);
        $matriculas = busca_matriculas_mongo($username);
        $obj = $matriculas;
        //echo "matricula: $obj->matricula<br>\n";
        if (isset($obj->matricula))
            $request->session()->put('matricula', $obj->matricula);
        else
            die('Sua matrícula não foi encontrada. Favor pedir a inclusão ao administrador da aplicação (roger.krolow@procempa.com.br).');
        $request->session()->put('nome', $obj->nome);

        #Define nível de acesso
        # 0 - normal
        # 1 - supervisor
        # 2 - admin
        if (   $username == 'marcio.scherer'
            //or $username == 'adriana'
            //or $username == 'sergio'
            or $username == 'roger.krolow'
	    //or $username == 'roger.teste'
	    or $username == 'vargas'
           )
            $request->session()->put('nivelAcesso', '1');
         else
            $request->session()->put('nivelAcesso', '0');
    }





	class Matricula {
		public	$login;
		public	$nome;
		public  $matricula;
		function __construct($l, $n, $m) {
			$this->login = $l;
			$this->nome = $n;
			$this->matricula = $m;
		}
	}


    function busca_matriculas_mongo($login='') {
	/*
        //$usuarios = Usuario::all();
        if ($nome == '')
            $usuarios = Usuario::orderBy('nome')->get();
        else
            $usuarios = Usuario::where('login', $nome)->first();
        //print_r($usuarios); die;

	return $usuarios;
	*/

	$matriculas[] = new Matricula("username1" , "person name 1" , 000000);
	$matriculas[] = new Matricula("username2" , "person name 2" , 000000);
	$matriculas[] = new Matricula("username3" , "person name 3" , 000000);
	$matriculas[] = new Matricula("username4" , "person name 4" , 000000);
	$matriculas[] = new Matricula("username5" , "person name 5" , 000000);
	$matriculas[] = new Matricula("username6" , "person name 6" , 000000);
	$matriculas[] = new Matricula("username7" , "person name 7" , 000000);
	$matriculas[] = new Matricula("username8" , "person name 8" , 000000);
	$matriculas[] = new Matricula("username9" , "person name 9" , 000000);
	$matriculas[] = new Matricula("username10", "person name 10", 000000);

	if ($login == '')
		$usuarios = $matriculas;
	else
		//$usuarios = $matriculas[array_search($nome, array_column($matriculas,'nome'))];
		foreach($matriculas as $mat) {
			//echo "nome: $nome";
			//print_r($mat);
			//echo $mat->nome;
			if ($mat->login == $login) {
				$usuarios = $mat;
				break;
				}
			}
	//print_r($usuarios);
//	die;
	//print_r((object) $usuarios);
	return $usuarios;
    }


    function arr_matriculas_nome() {
        $dados = busca_matriculas_mongo();
        foreach ($dados as $d)
            $arr[$d->matricula] = $d->nome;
        return $arr;
    }


    function cmp_buscaHoras_detalhe($a, $b) {
        //return strcmp($a->colaborador, $b->colaborador);

        //$comp = strcmp($a->colaborador, $b->colaborador);
        $coll  = collator_create('pt_BR');
        $comp = collator_compare($coll, $a['colaborador'], $b['colaborador']);

        if ($comp == 0)
                return strcmp($a['data'], $b['data']);
        else
            return $comp;
    }

    function cmp_buscaHoras($a, $b) {
	     $coll  = collator_create('pt_BR');
	     return collator_compare($coll, $a['colaborador'], $b['colaborador']);
    }



    function cmp_buscaHoras_cliente($a, $b) {
        $coll  = collator_create('pt_BR');
        $comp = collator_compare($coll, $a->cliente, $b->cliente);

        if ($comp ==0) {
            $comp2 = collator_compare($coll, $a->data_registro, $b->data_registro);
            if ($comp2 ==0)
                return collator_compare($coll, $a->nome, $b->nome);
            else
                return $comp2;
        } else
            return $comp;

        return $comp;
    }



    function cmp_buscaHoras_tarefa($a, $b) {
        $coll  = collator_create('pt_BR');
        $comp = collator_compare($coll, $a->cliente, $b->cliente);

        if ($comp ==0)
                return collator_compare($coll, $a->colaborador, $b->colaborador);
        else
            return $comp;

        return $comp;
    }


    function monta_query($s, $from, $j, $w, $g, $o) {

        $select  = implode(', ', $s);
        $join    = (sizeof($j) > 0) ? $join = implode(' ', $j) : $join = '';
        $where   = implode(' and ', $w);
        $groupby = implode(', ', $g);
        $orderby = implode(', ', $o);

        if ($join != '')
            $q = "some_sql";
        else
            $q = "some_sql";
        //echo "$q<br>\n"; //die;
        return $q;
    }


    function busca_versoes($request) {
      //Usa sessão para não fazer mais de uma consulta no banco por sessão
      $dados = $request->session()->get('versoes', '0');

      if ($dados == 0) {
        #Expressão regular: "Maio 1 - 2018" - https://regexr.com/3q1pn
        $q = "some_sql";
        $dados = DB::connection('pgsql')->select($q);
        //print_r($dados);
        $request->session()->put('versoes', $dados);
        }
      return $dados;
    }

?>
