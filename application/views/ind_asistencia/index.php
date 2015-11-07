<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Bienvenido</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

    <link rel="stylesheet" href="http://localhost/escuelas/assets/js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">

    <style type="text/css">
        .multiselect.dropdown-toggle.btn.btn-default > div.restricted {
            margin-right: 5px;
            max-width: 100px;
            overflow: hidden;
        }
    </style>

</head>
<body>
<div class="row">
    <div class="col-md-12">
        <h3>Módulo Indicador de asistencia</h3>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="padding-top: 15px; padding-left: 15px; padding-bottom: 15px;">
                <div class="form-inline">
                    <div class="form-group">
                            <label for="exampleInputName2">Periodo Lectivo: </label>
                            <select class="form-control" id="select_periodo_lectivo">
                            

                                <?php
                                foreach ($periodos as $key => $value) {
                                    ?>
                                    <option value="<?php echo $key;?>"><?php echo $value; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="selectMonth">Mes: </label>
                            <select class="form-control" id="select_mes">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="selectCurse">Grado / Curso: </label>
                            <select class="form-control" id="select_curso">


                                <?php
                                foreach ($cursos as $value) {
                                    ?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail2">Actividad: </label>
                        
                            <select class="form-control" id="select_actividad">
                                <?php
                                foreach ($actividad as $value) {
                                    ?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button id="generar_grafico" class="btn btn-default">Generar Gr&aacute;fico</button>

                        </div>
                    </div>

            </div>
        </div>
    </div>

    <div class="row">
    	<div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body" style="text-align: center;">
                    <canvas id="myChart" width="400" height="350"></canvas>
                </div>
            </div>
    	</div>
    </div>
</div>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

<script type="text/javascript" src="http://localhost/escuelas/assets/js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>



<script type="text/javascript">
    base_url = 'http://localhost/escuelas/index.php';

    $(document).ready(function() {
        var periodo = [];
        var mes = [];
        var actividad = [];
        var curso = [];



        $('.dropdowns').multiselect({
            buttonWidth: '100%',

            // onChange: function(element, checked) {
            //     var element1 = $('#select_periodo_lectivo option:selected');
            //     var element2 = $('#select_mes option:selected');
            //     var element3 = $('#select_actividad option:selected');
            //     var element4 = $('#select_curso option:selected');
                
            //     $(element1).each(function(index, brand){
            //         periodo.push($(this).val());
            //     });

            //     $(element2).each(function(index, brand){
            //         mes.push($(this).val());
            //     });

            //     $(element3).each(function(index, brand){
            //         periodo.push($(this).val());
            //     });

            //     $(element4).each(function(index, brand){
            //         actividad.push([$(this).val()]);
            //     });

            //     console.log(periodo);
            // }
        });
        var i;

        var getValueSelected = function(elMultSelect) {
            var txt=[];
            console.log(elMultSelect);
            var selectedOptionValue = $('#'+elMultSelect).val();
            if (selectedOptionValue!== null) {
                for (var i=0; i<selectedOptionValue.length; i++) {
                    var val = selectedOptionValue[i]; 
                    txt.push(selectedOptionValue[i]);
                }
            } else {
                return null;
            }

            return txt;
        }

        $('#generar_grafico').on('click', function() {
            
            var url=base_url+'/ind_asist/getFilterIndAsistencia';
            var val_periodo_lectivo = [];
            var val_mes = [];
            var val_actividad = [];
            var val_curso = [];
            var dataFilter = {};

            dataFilter.periodo_lectivo = $('#select_periodo_lectivo').val(); // getValueSelected('select_periodo_lectivo');
            dataFilter.mes             = $('#select_mes').val(); // getValueSelected('select_mes');
            dataFilter.actividad       = $('#select_actividad').val(); // getValueSelected('select_actividad');
            dataFilter.curso           = $('#select_curso').val(); // getValueSelected('select_curso');

            $.ajax({
              type: "POST",
              url: url,
              data: dataFilter,
              dataType: "JSON",
              success: function(data) {
                    console.log(data);
              },
            });
        });

    });
    

    var data = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                label: "Indicador de asistencia",
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
                label: "My Second dataset",
                fillColor: "rgba(151,187,205,0.5)",
                strokeColor: "rgba(151,187,205,0.8)",
                highlightFill: "rgba(151,187,205,0.75)",
                highlightStroke: "rgba(151,187,205,1)",
                data: [28, 48, 40, 19, 86, 27, 90]
            }
        ]
    };

    var options = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero : true,

        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines : true,

        //String - Colour of the grid lines
        scaleGridLineColor : "rgba(0,0,0,.05)",

        //Number - Width of the grid lines
        scaleGridLineWidth : 1,

        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,

        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines: true,

        //Boolean - If there is a stroke on each bar
        barShowStroke : true,

        //Number - Pixel width of the bar stroke
        barStrokeWidth : 2,

        //Number - Spacing between each of the X value sets
        barValueSpacing : 5,

        //Number - Spacing between data sets within X values
        barDatasetSpacing : 1,

        //String - A legend template
        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

    };

    var myBarChart = new Chart(document.getElementById('myChart').getContext("2d")).Bar(data, options);

</script>
</body>