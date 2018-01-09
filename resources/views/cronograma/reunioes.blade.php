@extends('layout.principal')

@section('conteudo')
<div zclass="w3-main" id="main" style="margin-left: 15%">

	<div class="w3-esamc">
		<div class="w3-container">
			<div class="col-md-9">
	    		<h1 class="w3-text-white">Reuniões</h1>				
			</div>
    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
                Bem-vindo {{$dados['usu_nome']}}
    		</div>
		</div>			
 	</div>


 	<div class="w3-container">
        <div class="cal1"></div>

        <div class='event-listing col-md-4' id='eventos'>
            <table class='w3-table event-listing-title' style='border: 1px solid black;'>
                <thead>
                    <tr class='w3-tr header-days'>
                        <td class='w3-td w3-esamc' style='font-size: 160%; color: #fff; text-align: center;'>
                            <b>Projetos do Dia</b>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr style='border: 1px solid black;'>
                        <td class='event-item'>
                            <div id='projetos-dia'>
                                @if (! $dados['projetos_dia'])
                                    <b>Não há projetos!</b>
                                @else
                                    @for ($i = 0; $i < count($dados['projetos_dia']); $i++)
                                        <a href='#'>
                                            <div>
                                                <b>Projeto: </b><b>{{$dados['projetos_dia'][$i]->prj_titulo}}</b>
                                                <br>
                                                <b>Data: </b>{{$dados['projetos_dia'][$i]->cro_dtentrega}}
                                            </div>
                                        </a>

                                        @if (($i + 1) < count($dados['projetos_dia']))
                                            <hr style='border: 0.5px solid black;'>
                                        @endif

                                    @endfor
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

	</div>
</div>


<script type="text/javascript">

// Call this from the developer console and you can control both instances
var calendars = {};     

$(document).ready(function() {          

    var events = [];

    <?php foreach($dados['cronogramas'] as $item): ?>

        var dateAux = '<?=$item->cro_dtentrega?>';
        var titleAux = '<?=$item->prj_titulo?>';

        var insertEvents = {};
            insertEvents =
            {
                date: dateAux,
                title: titleAux,
            }

        events.push(insertEvents);

    <?php endforeach; ?>


    calendars.clndr1 = $('.cal1').clndr({
        events: events,
        clickEvents: {            
            click: function (target) {
                if(target.events.length) {
                    console.log(target.events);

                    var count;

                    document.getElementById("projetos-dia").innerHTML = '';

                    for (count = 0; count < target.events.length; count++) {
                        console.log('porjeto: ', target.events[count].title);
                        console.log('data: ', target.date._i);

                        document.getElementById("projetos-dia").innerHTML +=
                                '<a href="#">' +
                                '<div>' +
                                '<b>Projeto: </b><a href="#""><b>'+ target.events[count].title + '</b>' +
                                '<br>' +
                                '<b>Data: </b>'+ target.events[count].date +
                                '</div>' +
                                '</a>';

                        if ((count+1) < target.events.length) {
                            document.getElementById("projetos-dia").innerHTML += '<hr style="border: 0.5px solid black;">';                                
                        }                                
                    }
                }
                else {
                    document.getElementById("projetos-dia").innerHTML = 
                        '<b>Não há projetos!</b>';
                }


            },
            today: function () {
                console.log('Cal-1 today');
            },
            nextMonth: function () {
                console.log('Cal-1 next month');
            },
            previousMonth: function () {
                console.log('Cal-1 previous month');
            },
            onMonthChange: function () {
                console.log('Cal-1 month changed');
            },
            nextYear: function () {
                console.log('Cal-1 next year');
            },
            previousYear: function () {
                console.log('Cal-1 previous year');
            },
            onYearChange: function () {
                console.log('Cal-1 year changed');
            },
            nextInterval: function () {
                console.log('Cal-1 next interval');
            },
            previousInterval: function () {
                console.log('Cal-1 previous interval');
            },
            onIntervalChange: function () {
                console.log('Cal-1 interval changed');
            }
        },
        multiDayEvents: {
            singleDay: 'date',
            endDate: 'endDate',
            startDate: 'startDate'
        },
        showAdjacentMonths: true,
        adjacentDaysChangeMonth: false
    });


    $(document).keydown( function(e) {
        // Left arrow
        if (e.keyCode == 37) {
            calendars.clndr1.back();
        }

        // Right arrow
        if (e.keyCode == 39) {
            calendars.clndr1.forward();
        }
    });
});
</script>
    	
 @stop