@extends('master')

@section('conteudo')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Relatório de Horas por Tarefa</h4>
                                <p class="category">Detalhamento de horas trabalhadas por tarefa</p>
                                
                                <div class="row">
                                    <form id="horas_tarefa" action="horas_tarefa">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label>Cliente</label>
                                            <input type="text" class="form-control" id="fcliente" name="fcliente" value="{{ $fcliente }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <label>Matrícula</label>
                                            <input type="text" class="form-control" id="fmatricula" name="fmatricula" value="{{ $fmatricula }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>Data inicial</label>
                                            <input type="text" class="form-control" id="fdatai" name="fdatai" value="{{ $fdatai }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>Data final</label>
                                            <input type="text" class="form-control" id="fdataf" name="fdataf" value="{{ $fdataf }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
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
                                    </form>
                                </div> 
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table" border="1" bordercolor="#E3E3E3">
                                    <thead>
                                    	<th>Cliente</th>
                                    	<th>Solicit.</th>
                                    	<th>Nome Solicitação</th>
                                    	<th>Tarefa</th>
                                    	<th>Nome Tarefa</th>
                                    	<th>Pessoa</th>
                                    	<th>Horas</th>
					<th>PH</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($clientes as $cli)
                                        <tr>
                                           	<td rowspan="{{ $cli['rowspan'] }}" style="vertical-align:top">{{ substr($cli['nome'], 0, strpos($cli['nome'], ' ')) }}</td>
                                           	@foreach ($cli['solicitacoes'] as $sol)
						@if (isset($sol['extrap']) and $sol['extrap'] == 1 ) 
	                                               	<td rowspan="{{ $sol['rowspan'] }}" style="vertical-align:top; background-color: #F9B19F" title="Extra Priorização"><a href="https://openproject.procempa.com.br/work_packages/{{ $sol['id'] }}" target="_blank">{{ $sol['id'] }}</a></td>
        	                                       	<td rowspan="{{ $sol['rowspan'] }}" style="vertical-align:top; background-color: #F9B19F" title="Extra Priorização">{{ $sol['nome'] }}</td>
						@else 
	                                               	<td rowspan="{{ $sol['rowspan'] }}" style="vertical-align:top"><a href="https://openproject.procempa.com.br/work_packages/{{ $sol['id'] }}" target="_blank">{{ $sol['id'] }}</a></td>
        	                                       	<td rowspan="{{ $sol['rowspan'] }}" style="vertical-align:top">{{ $sol['nome'] }}</td>
						@endif 
                                               	@foreach ($sol['tarefas'] as $tar)
                                                        @if ($tar['id'] > 0)
                                                           	<td rowspan="{{ $tar['rowspan'] }}"><a href="https://openproject.procempa.com.br/work_packages/{{ $tar['id'] }}" target="_blank">{{ $tar['id'] }}</a></td>
                                                        	<td rowspan="{{ $tar['rowspan'] }}">{{ $tar['nome'] }}</td>
                                                        @else
                                                           	<td rowspan="{{ $tar['rowspan'] }}" style="background-color: #F9EDBA" title="Horas lançadas na solicitação"><a href="https://openproject.procempa.com.br/work_packages/{{ $tar['id'] }}" target="_blank">{{ $tar['id'] }}</a></td>
                                                        	<td rowspan="{{ $tar['rowspan'] }}" style="background-color: #F9EDBA" title="Horas lançadas na solicitação">-</td>
                                                        @endif                                               	
                                                       	@foreach ($tar['matriculas'] as $mat)
                                                           	<td>{{ substr($mat['colaborador'], 0, strpos($mat['colaborador'], ' ')) }}</td>
                                                           	<td>{{ number_format($mat['horas'], 2) }}</td>
								<td>{{ $mat['ph'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td style="font-weight:bold;" colspan="5">&nbsp;</td>
                                        <td style="font-weight:bold;" >Total</td>
                                        <td style="font-weight:bold;" >{{ number_format($thoras, 2) }}</td>
                                    </tfoot>
                                </table>
                                <a id='asalvar' href="horas_tarefa_csv">Salvar CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


@section('scriptsRodape')
    <script src="assets/js/horas_tarefa.js"></script>
@endsection




