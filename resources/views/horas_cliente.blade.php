@extends('master')

@section('conteudo')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Horas por Cliente</h4>
                                <p class="category">Horas trabalhadas por cliente</p>
                                
                            <div class="row">
                                <form action="horas_cliente">
                                    
                                @if( $nivelAcesso > 0 )
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>Matrícula</label>
                                    <input type="text" class="form-control" id="fmatricula" name="fmatricula" value="{{ $fmatricula }}">
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>Cliente</label>
                                    <input type="text" class="form-control" id="fcliente" name="fcliente" value="{{ $fcliente }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>Data inicial</label>
                                    <input type="text" class="form-control" id="fdatai" name="fdatai" value="{{ $fdatai }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                    <label>Data final</label>
                                    <input type="text" class="form-control" id="fdataf" name="fdataf" value="{{ $fdataf }}" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                    <label>Detalhe</label>
                                    <input type="checkbox" class="form-control" id="fdetalhe" name="fdetalhe" value="S"  {{ $fdetalhe_checked }}>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Submeter</label><br>
                                        <button type="submit" >
                                        <i class="fa fa-search" style="vertical-align: bottom"></i>
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    	<th>Cliente</th>
                                        @if( isset($dados[0]->data_registro)  )
                                    	<th>Data</th>
                                        @endif
                                        @if( $nivelAcesso > 0 and isset($dados[0]->nome) )
                                    	<th>Nome</th>
                                        @endif
                                    	<th>Horas OP</th>
                                    	<th>Percentual</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($dados as $d)
                                        <tr>
                                        	<td>{{ $d->cliente }}</td>
                                            @if( isset($d->data_registro)  )
                                        	<td>{{ $d->data_registro }}</td>
                                            @endif
                                            @if( $nivelAcesso > 0  and isset($d->nome) )
                                        	<td>{{ $d->nome }}</td>
                                            @endif
                                        	<td>{{ number_format($d->horas, 2) }}</td>
                                        	<td>{{ $d->percentual }} %</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                        @if( $nivelAcesso > 0  and isset($dados[0]->nome)  )
                                        <td></td>
                                        @endif
                                        @if( isset($dados[0]->data_registro)  )
                                    	<td></td>
                                        @endif
                                        <td style="font-weight:bold;">Total</td>
                                        <td style="font-weight:bold;">{{ number_format($thop, 2) }}</td>
                                        <td style="font-weight:bold;">100 %</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a id='asalvar' href="horas_cliente_csv">Salvar CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scriptsRodape')
    <script src="assets/js/horas_cliente.js"></script>
@endsection

