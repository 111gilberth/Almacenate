@extends('layouts.app')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h3>Listado de Pedidos
                            @role('corredor|admin')
                                <a href="{{route('reserva.create')}}">
                                    <button class="btn btn-success"><i class="fa fa-plus-circle"></i> Nuevo Pedido</button>
                                </a>
                            @endrole
                        </h3>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class='input-group date' id='datetimepicker5'>
                                <input type='date' name="start_date" id="start_date" class="form-control" placeholder="Inicio de Fecha" />
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                            <div class='input-group date' id='datetimepicker7'>
                                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Final Fecha"/>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <button id="btnFiterSubmitSearch" class="btn btn-info"><i class="fa fa-search"></i> Aplicar Filtro</button>
                        </div>
                    </div>
                </div>
                @foreach($reserva as $ven)
                    @include('reserva.modal')
                @endforeach
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <div class="table-responsive">
                            <table id="pedido" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>
                                <th>Cliente</th>
                                <th>Comprobante</th>
                                <th>Total</th>
                                <th>Fecha Pedido</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#pedido').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            order: [[0, "desc"]],
            ajax: {
                url: "{{route('reserva.tabla')}}",
                type: 'GET',
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                },
            },
            columns: [
                {data: 'cliente', name: 'cliente'},
                {data: 'comprobante', name: 'comprobante'},
                {data: 'total_venta', name: 'total_venta'},
                {data: 'fecha_pedido', name: 'fecha_pedido'},
                {data: 'estado', name: 'estado'},
                {data: 'opcion', name: 'opcion', orderable: false, searchable: false}
            ],
            "language": {
                "url": "{{URL::to('/')}}/admin/Spanish.json"
            }

        });
        $('#btnFiterSubmitSearch').click(function () {
            $('#ven').DataTable().ajax.reload();
        });
    </script>
@stop
