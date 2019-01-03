@extends('master')

@section('conteudo')
<div class="container-fluid">
  <div class="row">
    <form action='painel_asmc'>
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
              <div class="col-md-12">
                <div class="card striped-table-with-hover">
                  <div class="card-header ">
                    <h4 class="card-title">Incidentes ASM Cloud</h4>
                  </div>
                </div>
                <div class="card-body table-full-width table-responsive">
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Grupo</th>
                        <th>Status</th>
                        <th>Quantidade</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($dados as $d)
                      <tr>
                        @if ($d->status == 'Terminado' or $d->grupo == 'Total')
                        <td>{{ $d->grupo }}</td>
                        <td>{{ $d->status }}</td>
                        <td>{{ $d->tarefas }}</td>
                        @else
                        <!-- #FF6B6B - vermelho -->
                        <td style="background-color: #F9EDBA">{{ $d->grupo }}</td>
                        <td style="background-color: #F9EDBA">{{ $d->status }}</td>
                        <td style="background-color: #F9EDBA">{{ $d->tarefas }}</td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="content">
          <div class="row">
            <div class="content">
              <div id="grafico_asmc" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
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
@endsection



@section('documentReady')

//Gráfico Barras ASMC
$('#grafico_asmc').highcharts({
  chart: {
    type: 'column'
  },
  title: {
    text: 'Atividade ASMC'
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
    name: 'Backup / Restore',
    color: '#F37B21',
    data: [ {{ $backup_restore}} ]
  }, {
    name: 'Banco de Dados',
    color: '#4D4D4F',
    data: [ {{ $dbas }} ]
  },
  {
    name: 'Servidores',
    color: '#ECD078',
    data: [ {{ $servidores }} ]
  },
  {
    name: 'Aplicação',
    color: '#42ACB2',
    data: [ {{ $aplicacao }} ]
  }]
});




@endsection
