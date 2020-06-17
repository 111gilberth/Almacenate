@extends('layouts.app')
@section('css')
    <style>
        .alert-dismissible {
            padding-right: 0px;
        }

        .alert {
            padding: 0px;
            height: 98px !important;
        }
    </style>
@stop
@section('content')
    @role('admin')
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Productos Faltantes</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            @foreach ($aviso as $avisos)
                @if ( $avisos->stock <= 5)
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                        <div class="alert alert-danger alert-dismissible">
                            @if ( $avisos->stock == 0)
                                <h5>No quedan productos de <abbr title="{{$avisos->nombre}}"><strong>{{$avisos->nombre}}
                                    </abbr></strong></h5>
                            @else
                                <h5>Quedan {{$avisos->stock}} del producto {{$avisos->nombre}}</h5>@endif
                        </div>
                    </div>
                @elseif ($avisos->stock <= 20)
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="alert alert-info alert-dismissible">
                            <h5>Quedan {{$avisos->stock}} productos de <abbr
                                        title="{{$avisos->nombre}}"><strong>{{$avisos->nombre}}</abbr></strong></h5>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <!-- /.box-body -->
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Productos más vendidos</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <div id="donutchart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Productos con mayor recaudación</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <div id="donutchart2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-header with-border">
            @php
                $cont=0
            @endphp
            @foreach ($promedioventa as $promedioventas)
                @php
                    $cont=$cont + $promedioventas->total_venta
                @endphp
            @endforeach
            <h3 class="box-title">Recaudación de los ultimos 7 días</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body chart-responsive">
            <div class="chart" id="line-chart"></div>
        </div>
        <!-- /.box-body -->
    </div>
    {{-- <td align="right" width="98"><a href="#" onclick="window.open('{{pagina.html}}','TITULO','width =550,height=400');"> <IMG SRC="http://127.0.0.1/sistema/public/imagenes/articulos/image.jpg" WIDTH=47 HEIGHT=96 ALT=""></a></td> --}}
    @if ($config == '')
        @include('layouts.modalconfig')
    @endif
    @endrole
@endsection

@section('js')
    <script type="text/javascript">
        $("#myModalNorm").modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        function control(f) {
            var ext = ['gif', 'jpg', 'jpeg', 'png'];
            var v = f.value.split('.').pop().toLowerCase();
            for (var i = 0, n; n = ext[i]; i++) {
                if (n.toLowerCase() == v)
                    return
            }
            var t = f.cloneNode(true);
            t.value = '';
            f.parentNode.replaceChild(t, f);
            alert('Debe ser de tipo imagen');
        }

        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                    @foreach ($estadistica as $estadisticas)
                ['{{$estadisticas->nombre}}', {{$estadisticas->cantidad}}],
                @endforeach
            ]);

            var options = {
                title: '',
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                    @foreach ($estadistica as $estadisticas)
                ['{{$estadisticas->nombre}}', {{$estadisticas->precio_venta}}],
                @endforeach
            ]);

            var options = {
                pieHole: 0.4,
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
            chart.draw(data, options);
        }
    </script>

    <script type="text/javascript">
        var line = new Morris.Line({
            element: 'line-chart',
            resize: true,
            data: [
                    @foreach ($promedioventa as $promedioventas)
                {
                    x: '{{$promedioventas->fecha_hora}}', item1: {{$promedioventas->total_venta}}},
                @endforeach
            ],
            xkey: 'x',
            ykeys: ['item1'],
            labels: ['Entradas'],
            lineColors: ['#3c8dbc'],
            hideHover: 'auto',
        });
    </script>


@endsection
