@extends('master')

@section('conteudo')
<div class="container-fluid">
    <!--
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action='painel'>
                {{ Form::select('fdias_quantitativo', [60=>60, 90=>90, 120=>120, 150=>150, 180=>180, 365=>365], $fdias_quantitativo,["onchange"=>"this.form.submit()"]) }} dias
		&nbsp;&nbsp;<label>Matrícula</label>  <input type="text" class="form-control" id="fmatriculaquant" name="fmatriculaquant" value="{{ $fmatriculaquant }}">
                </form>
                <div class="content">
                    <div id="grafico_quantitativo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>                             
                </div>
                <p class="text-right"><font size="1">Atualizado em: {{ $data_cubo }}</font></p>
                <a id='asalvar_quantitativo' href='painel_quantitativo_csv'>Salvar CSV</a>
            </div>
        </div>
    </div>
    -->
    <div class="row">
	<form action='painel'>
		<div class="col-md-12">
			 <div class="card">
		         	<div class="content">
					<div class="row">
						<div class="col-md-2">
							<label>Dias</label>
							{{ Form::select('fdias_quantitativo', [60=>60, 90=>90, 120=>120, 150=>150, 180=>180, 365=>365], $fdias_quantitativo,["onchange"=>"this.form.submit()", 'class'=>'form-control']) }}
						</div>
						@if ($nivelAcesso > 0 )
						<div class="col-md-3">
                					<label>Matrícula</label>
							<input type="text" class="form-control" id="fmatriculaquant" name="fmatriculaquant" value="{{ $fmatriculaquant }}">
						</div>
						@endif
						<div class="col-md-1">
			                            <div class="form-group">
				                            <label>Submeter</label><br>
				                            <button type="submit" >
					                            <i class="fa fa-search" style="vertical-align: bottom"></i>
				                            </button>
			                            </div>
	                		        </div>
					</div>
					<div class="row">
						<div class="content">
							<div id="grafico_quantitativo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
						</div>
						<p class="text-right"><font size="1">Atualizado em: {{ $data_cubo }}</font></p>
				                <a id='asalvar_quantitativo' href='painel_quantitativo_csv'>Salvar CSV</a>
					</div>
				</div>
			</div>
		</div>
	</form>
    </div>
    <div class="row">
        <form action='painel'>
        <div class="col-md-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data inicial</label>
                                <input type="text" class="form-control" id="fdataie" name="fdataie" value="{{ $fdataie }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data final</label>
                                <input type="text" class="form-control" id="fdatafe" name="fdatafe" value="{{ $fdatafe }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                            <label>Submeter</label><br>
                            <button type="submit" >
                                <i class="fa fa-search" style="vertical-align: bottom"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="content">
                            <div id="grafico_cli_esq" style="min-width: 310px; height: 400px; margin: 0 auto"></div>                             
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data inicial</label>
                                <input type="text" class="form-control" id="fdataid" name="fdataid" value="{{ $fdataid }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data final</label>
                                <input type="text" class="form-control" id="fdatafd" name="fdatafd" value="{{ $fdatafd }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                            <label>Submeter</label><br>
                            <button type="submit" >
                                <i class="fa fa-search" style="vertical-align: bottom"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="content">
                            <div id="grafico_cli_dir" style="min-width: 310px; height: 400px; margin: 0 auto"></div>                             
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data inicial</label>
                                <input type="text" class="form-control" id="fdatamiie" name="fdatamiie" value="{{ $fdatamiie }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data final</label>
                                <input type="text" class="form-control" id="fdatamife" name="fdatamife" value="{{ $fdatamife }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Matrícula</label>
                                <input type="text" class="form-control" id="fmatriculamie" name="fmatriculamie" value="{{ $fmatriculamie }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                            <label>Submeter</label><br>
                            <button type="submit" >
                                <i class="fa fa-search" style="vertical-align: bottom"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="content">
                            <div id="grafico_manutinc_esq" style="min-width: 310px; height: 400px; margin: 0 auto"></div>                             
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data inicial</label>
                                <input type="text" class="form-control" id="fdataid" name="fdatamiid" value="{{ $fdatamiid }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data final</label>
                                <input type="text" class="form-control" id="fdatafd" name="fdatamifd" value="{{ $fdatamifd }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Matrícula</label>
                                <input type="text" class="form-control" id="fmatriculamid" name="fmatriculamid" value="{{ $fmatriculamid }}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                            <label>Submeter</label><br>
                            <button type="submit" >
                                <i class="fa fa-search" style="vertical-align: bottom"></i>
                            </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="content">
                            <div id="grafico_manutinc_dir" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form> 
    </div>    
</div>
@endsection



@section('scriptsRodape')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="assets/js/painel.js"></script>
@endsection

@section('documentReady')

            //Gráfico Quantitativo
            $('#grafico_quantitativo').highcharts({
                chart: {
                    type: 'line'
                },	
                title: {
                    text: 'Atividade Open Project'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [ <?php echo $categories ?> ]
                },
                yAxis: {
                    title: {
                        text: 'Quantidade'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series: [{
                    name: 'Solicitações',
                    color: '#F53E20',
                    data: [ {{ $solicitacoes }} ] 
                }, {
                    name: 'Tarefas',
                    color: '#F37B21',
                    data: [ {{ $tarefas}} ]
                }, {
                    name: 'Amb. Novos',
                    color: '#4D4D4F',
                    data: [ {{ $amb_novos }} ]
                },
		{
                    name: 'Extra Prior.',
                    color: '#ECD078',
                    data: [ {{ $extra_priorizacao }} ]
                },
                {
                    name: 'Pontos Hist.',
                    color: '#42ACB2',
                    data: [ {{ $pontos_historia }} ]
                },
                {
                    name: 'Horas OP',
                    color: '#53777A',
                    data: [ {{ $horas_op }} ]
                }]
            });
            
            
            
        //Gráfico Clientes Esquerda
        $('#grafico_cli_esq').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Horas por Cliente'
            },
            subtitle: {
                text: '{{ $fdataie }} a {{ $fdatafe }}'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Horas',
                colorByPoint: true,
                data: [ <?php echo $clientes_dadose ?> ]
            }]
        });
        
        
        
        
        //Gráfico Clientes Direita
        $('#grafico_cli_dir').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Horas por Cliente'
            },
            subtitle: {
                text: '{{ $fdataid }} a {{ $fdatafd }}'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Horas',
                colorByPoint: true,
                data: [ <?php echo $clientes_dadosd ?> ]
            }]
        });
        
        
        
        
        //Gráfico Manutenções / Incidentes Esquerda
        $('#grafico_manutinc_esq').highcharts({
            colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
                    "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],            
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Requisições x Manut./Inc. x TPD'
            },
            subtitle: {
                text: '{{ $nomemie }}  {{ $fdatamiie }} a {{ $fdatamife }}'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Horas',
                colorByPoint: true,
                data: [ <?php echo $manut_dadose ?> ]
            }]
        });
                    


        //Gráfico Manutenções / Incidentes Direita
        $('#grafico_manutinc_dir').highcharts({
            colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
                    "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],            
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Requisições x Manut./Inc. x TPD'
            },
            subtitle: {
                text: '{{ $nomemid }} {{ $fdatamiid }} a {{ $fdatamifd }}'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Horas',
                colorByPoint: true,
                data: [ <?php echo $manut_dadosd ?> ]
            }]
        });


                    
@endsection
