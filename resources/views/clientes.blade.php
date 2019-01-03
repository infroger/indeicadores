@extends('master')

@section('conteudo')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Produtos x Clientes</h4>
                                <p class="category">Relação entre produtos e clientes</p>
                                
                            <div class="content table-responsive table-full-width">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    	<th>Cliente</th>
                                    	<th>Produto</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($dados as $d)
                                        <tr>
                                        	<td>{{ $d[0] }}</td>
                                        	<td>{{ $d[1] }}</td>
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
@endsection
