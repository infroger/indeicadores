<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Indicadores - T/GTI</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>


    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

    <link href="assets/css/bootstrap-datepicker3.css" rel="stylesheet" />

</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="azure" data-image="assets/img/sidebar-4.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: dashborad
data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="/" class="simple-text">
                    Indicadores - T/GTI
                </a>
            </div>

            <ul class="nav">
                <li>
                    <a href="painel">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard OP</p>
                    </a>
                </li>
                <li>
                    <a href="painel_asmc">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard ASMC</p>
                    </a>
                </li>
                <li>
                    <a href="horas_funcionario">
                        <i class="pe-7s-news-paper"></i>
                        <p>Rel. Horas por Pessoa</p>
                    </a>
                </li>
                <li>
                    <a href="horas_cliente">
                        <i class="pe-7s-news-paper"></i>
                        <p>Rel. Horas por Cliente</p>
                    </a>
                </li>
                <li>
                    <a href="horas_tarefa">
                        <i class="pe-7s-news-paper"></i>
                        <p>Rel. Horas por Tarefa</p>
                    </a>
                </li>
                <li>
                    <a href="horas_tpd">
                        <i class="pe-7s-news-paper"></i>
                        <p>Rel. Horas TPD</p>
                    </a>
                </li>
								<li>
                    <a href="pontos_historia">
                        <i class="pe-7s-news-paper"></i>
                        <p>Rel. Pontos História</p>
                    </a>
                </li>
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <div class="content">
            @yield('conteudo')
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; 2018 <a href="http://www.procempa.com.br" target="_blank">Procempa</a>, tecnologia a serviço da cidade
                </p>
            </div>
        </footer>

    </div>
</div>




</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>

	<!--  Charts Plugin -->
	<!--script src="assets/js/chartist.min.js"></script-->

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	<script type="text/javascript">
    	$(document).ready(function(){

        	//demo.initChartist();

            /*
        	$.notify({
            	icon: 'pe-7s-gift',
            	message: "Bem vindo ao Aquiles!"

            },{
                type: 'info',
                timer: 4000
            });
            */



            @yield('documentReady')

    	});
	</script>


    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    @yield('scriptsRodape')


</html>
