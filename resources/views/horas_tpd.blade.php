@extends('master')

@section('conteudo')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Relatório de Horas em Treinamento, Pesquisa e Desenvolvimento</h4>
                                <p class="category">Detalhamento de horas TPD</p>
                                
                                <div class="row">
                                    <form id="horas_tpd" action="horas_tpd">
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
                                <table class="table table-hover table-striped">
                                    <thead>
                                    	<th>Nome</th>
                                    	<th>Horas TPD</th>
                                    	<th>Horas OP</th>
                                    	<th>Percentual</th>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $thtpd = 0;
                                            $thop = 0; 
                                        ?>
                                        @foreach ($dados as $d)
                                        <tr>
                                           	<td style="vertical-align:top">{{ $d->colaborador }}</td>
                                           	<td style="vertical-align:top">{{ number_format($d->htpd, 2) }}</td>
                                           	<td style="vertical-align:top">{{ number_format($d->hop, 2) }}</td>
                                           	<td style="vertical-align:top">{{ number_format($d->percentual, 2) }}%</td>
                                        <?php 
                                            $thtpd += $d->htpd;
                                            $thop  += $d->hop;
                                        ?>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td style="font-weight:bold;" >Total</td>
                                       	<td style="font-weight:bold;">{{ number_format($thtpd, 2) }}</td>
                                       	<td style="font-weight:bold;">{{ number_format($thop, 2) }}</td>
                                        <td style="font-weight:bold;" >{{ $thop == 0 ? 0 : number_format($thtpd / $thop * 100, 2) }}%</td>
                                    </tfoot>
                                </table>
                                <a id='asalvar' href="horas_tpd_csv">Salvar CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


@section('scriptsRodape')
    <script src="assets/js/horas_tpd.js"></script>
@endsection




