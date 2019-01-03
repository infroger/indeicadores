@extends('master')

@section('conteudo')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Relatório de Horas</h4>
                                <p class="category">Relação entre horas OpenProject e horas Ronda</p>
                                
                                <div class="row">
                                    <form id="horas_funcionario" action="horas_funcionario">
                                        
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
                                        <label>Detalhe</label>
                                        <input type="checkbox" class="form-control" id="fdetalhe" name="fdetalhe" value="S" {{ $fdetalhe_checked }}>
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
                                <table class="table table-hover table-striped">
                                    <thead>
                                        @if( $nivelAcesso > 0 )
                                    	<th>Nome</th>
                                        @endif
                                        @if( $fdetalhe == 'S' )
                                    	<th>Data</th>
                                        @endif
                                    	<th>H. Ronda</th>
                                    	<th>Horas OP</th>
                                    	<th>Diferença</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($dados as $d)
                                        <tr>
                                            @if( $nivelAcesso > 0)
                                        	<td>{{ $d['colaborador'] }}</td>
                                            @endif
                                            @if( $fdetalhe == 'S' )
                                            	<td>{{ $d['data'] }}</td>
                                            @endif
                                            @if ($d['hronda'] > 0)
                                            	<td>{{ number_format($d['hronda'], 2) }}</td>
                                        	@else
                                            	<td style="background-color: #F9EDBA" title="Falta lançamento">0.00</td>
                                            @endif
                                            @if ($d['hop'] > 0)
                                            	<td>{{ number_format($d['hop'], 2) }}</td>
                                            @else
                                            	<td style="background-color: #F9EDBA" title="Falta lançamento">0.00</td>
                                            @endif
                                            @if ($d['diferenca_destaque'] == 'destaque')
                                            	<td style="background-color: #F9EDBA" title="Diferença > 75%">{{ number_format($d['diferenca'], 2) }}</td>
                                            @else
                                            	<td>{{ number_format($d['diferenca'], 2) }}</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @if( $nivelAcesso > 0 or $fdetalhe == 'S')
                                        <tr>
                                        @if( $nivelAcesso > 0 and $fdetalhe == 'S' )
                                        <td></td>
                                        @endif
                                        <td style="font-weight:bold;">Totais</td>
                                        <td style="font-weight:bold;">{{ number_format($hronda, 2) }}</td>
                                        <td style="font-weight:bold;">{{ number_format($hop, 2) }}</td>
                                        <td style="font-weight:bold;">{{ number_format($hdif, 2) }}</td>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                                <a id='asalvar' href="horas_funcionario_csv">Salvar CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                                <div class="alert alert-info">
                                    <span>ATENÇÃO: é esperado que as horas Open Project sejam pelo menos 75% das horas trabalhadas no Ronda. Portanto, para jornada de 8h, em dias de jornada normal, espera-se no mínimo 6h lançadas no OP por dia. Para jornada de 6h, espera-se 4,5h lançadas no OP.</span>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


@section('scriptsRodape')
    <script src="assets/js/horas_funcionario.js"></script>
@endsection




