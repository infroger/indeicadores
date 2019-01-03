@extends('master')

@section('conteudo')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Relatório de Pontos de História x Horas OP</h4>

                                <div class="row">
                                    <form id="pontos_historia" action="pontos_historia">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label>Sprint</label>
                                            <?php
                                                $vers = '';
                                                foreach ($versoes as $v)
                                                  $vers[$v->nome_versao] = $v->nome_versao;
                                                //echo "fversao: $fversao";
                                             ?>
                                             {{ Form::select('fversao', [$vers], $fversao, ["onchange"=>"this.form.submit()", 'class'=>'form-control']) }}
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    	<th>Solicitação</th>
                                    	<th>Descrição</th>
                                    	<th>Pontos História</th>
                                    	<th>Horas OP</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $tph = 0;
                                            $thr = 0;
                                        ?>
                                        @foreach ($dados as $d)
                                        <tr>
                                           	<td style="vertical-align:top"><a href="https://openproject.procempa.com.br/work_packages/{{ $d->nivel1 }}" target="_blank">{{ $d->nivel1 }}</a></td>
                                           	<td style="vertical-align:top">{{ $d->nome_tarefa }}</td>
                                           	<td style="vertical-align:top">{{ $d->pontos_historia }}</td>
                                           	<td style="vertical-align:top">{{ number_format($d->horas_registro, 2) }}</td>
                                        <?php
                                            $tph += $d->pontos_historia;
                                            $thr += $d->horas_registro;
                                        ?>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td style="font-weight:bold;" >Total</td>
                                        <td style="font-weight:bold;" >&nbsp;</td>
                                       	<td style="font-weight:bold;">{{ number_format($tph, 2) }}</td>
                                       	<td style="font-weight:bold;">{{ number_format($thr, 2) }}</td>
                                        <!-- td style="font-weight:bold;" >{{ $tph == 0 ? 0 : number_format($tph / $thr * 100, 2) }}%</td -->
                                    </tfoot>
                                </table>
                                <a id='asalvar' href="pontos_historia_csv">Salvar CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection


@section('scriptsRodape')
    <script src="assets/js/horas_tpd.js"></script>
@endsection
