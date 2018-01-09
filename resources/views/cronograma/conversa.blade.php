@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Conversas</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>

	 	<div class="row" style="margin: 1%; ">

	 		<!-- ÁREA QUE MOSTRA OS GRUPOS -->
	 		<div class="col-md-3 side w3-card-2 conversa-esamc" style="overflow:auto;">
				<h3 class="w3-text-esamc" style="margin-bottom: 10%;"><b>GRUPOS</b></h3>

				@foreach ($dados['projetos'] as $p)

					<hr style="border-top: 1px solid #9f9f9f; margin: -4% 0px">
					<a href="javascript:void(0);" onclick="mostrar_grupo({{$p->prj_cod}}, {{$p->prj_ori_cod}});">
						<h4 style="margin: 5%; color: #191919;"><b>{{$p->prj_titulo}}</b></h4>
					</a>

				@endforeach

				<hr style="border-top: 1px solid #9f9f9f; margin: -4% 0px">
	 		</div>


	 		<!-- ÁREA QUE MOSTRA A CONVERSA -->
	 		<div class="col-md-9 w3-card-2 conversa-esamc">

	 			<div class="col-md-12" style="overflow:auto; height: 500px;" id="conversa">
					<br>

					<label class='alert alert-info' id='alert_email'>
					 	<p style="font-size: 140%;">Selecione uma conversa!</p>
					</label>
		 			
					
				</div>

    			

				<!-- BOTÃO DE ENVIAR -->
	 			<form name="form">

					<label style="display:none;" id="prj_cod" name="prj_cod"></label>
					<label style="display:none;" id="prj_ori_cod" name="prj_ori_cod">0</label>

	 				<div class="input-group w3-container col-md-12" style="position: relative; bottom: 8px; width: 94%;">
	 					<div class="col-md-10">
	 						<input id="mensagem" name="mensagem" autocomplete="off" type="text" class="form-control" style="border: 1px solid #000" placeholder="Mensagem">
	 					</div>
						<div class="col-md-2">
							<a href="#" class="w3-btn w3-esamc w3-text-white" style="display: block;" onclick="return gravar_mensagem()">Enviar</a>	
						</div>
	 				</div>	

	 			</form>
	 		</div>
	 	</div>

 	</div>

 <script type="text/javascript">

 	setInterval(function(){

 		var prj_cod = document.getElementById("prj_cod").innerHTML;
 		var prj_ori_cod = document.getElementById("prj_ori_cod").innerHTML;

 		if (prj_ori_cod == '0') {

 		}
 		else {
 			mostrar_grupo(prj_cod, prj_ori_cod)
 		}


 	}, 5000);

 	function gravar_mensagem() {

 		console.log('aqui');

 		var mensagem = form.mensagem.value;
 		var prj_cod = document.getElementById("prj_cod").innerHTML;
 		var pro_ori_cod = document.getElementById("prj_ori_cod").innerHTML;

 		$.ajax({
	        url: 'http://localhost:8080/api-cronograma/public/api/conversas',
	        method: 'POST',
	        headers: {
                    'Access-Control-Allow-Origin': '*'
                },
	        data: {
                    'con_mensagem': mensagem,
                    'con_prj_cod' : prj_cod,
                    'con_usu_cod' : <?=$dados['usu_cod']?>,
                },
	        error: function(erro) {
	        	alert('Houve um erro ao enivar a mensagem!');
	        },
	        success: function(dados) {

	        	document.getElementById("mensagem").value = ""
	        },
   		});
 	}
 	
 	function mostrar_grupo(id, ori_id){

 		$.ajax({
	        url: 'http://localhost:8080/api-cronograma/public/api/conversas/'+id,
	        method: 'GET',
	        headers: {
                    'Access-Control-Allow-Origin': '*'
                },
	        error: function(erro) {
	        	console.log("ERROR")
	        },
	        success: function(dados) {

	            document.getElementById("prj_cod").innerHTML = id;
	            document.getElementById("prj_ori_cod").innerHTML = ori_id;

	            document.getElementById("conversa").innerHTML = "";

	            if(dados.length <= 0) {

	            	document.getElementById("conversa").innerHTML += 
		            			"<br><label class='alert alert-danger' id='alert_email'>" +
					 				"Ainda não há mensagem com esse grupo!" +
									"</label>";
	        	}
	        	else if(dados.length > 0) {

		            for (var i = 0; i < dados.length; i++) {

		            	//ORIENTADOR ENVIA A MENSAGEM
		            	if (dados[i]['con_usu_cod'] == '<?=$dados['usu_cod']?>' && dados[i]['prj_ori_cod'] == '<?=$dados['usu_cod']?>') 
		            	{
		            		document.getElementById("conversa").innerHTML += 
		            			"<div class='w3-panel msg-orientador-envia w3-round-large'>" +
					 				"<p style='margin-bottom: -3%;''><b>"+dados[i]['usu_nome']+"</b></p>" +
									"<p>"+dados[i]['con_mensagem']+"</p>" +
								"</div>";
		            	}
		            	//ALUNO ENVIA A MENSAGEM
		            	else if (dados[i]['con_usu_cod'] == '<?=$dados['usu_cod']?>')
		            	{
		            		document.getElementById("conversa").innerHTML += 
		            			"<div class='w3-panel msg-aluno-envia w3-round-large'>" +
					 				"<p style='margin-bottom: -3%;''><b>"+dados[i]['usu_nome']+"</b></p>" +
									"<p>"+dados[i]['con_mensagem']+"</p>" +
								"</div>";
		            	}
		            	//RECEBE MENSAGEM DO ORIENTADOR
		            	else if (dados[i]['con_usu_cod'] == ori_id)
		            	{
		            		document.getElementById("conversa").innerHTML += 
		            			"<div class='w3-panel msg-orientador-recebe w3-round-large'>" +
					 				"<p style='margin-bottom: -3%;''><b>"+dados[i]['usu_nome']+"</b></p>" +
									"<p>"+dados[i]['con_mensagem']+"</p>" +
								"</div>";

		            	}
		            	//RECEBE MENSAGEM DO ALUNO
		            	else if (dados[i]['con_usu_cod'] != '<?=$dados['usu_cod']?>')
		            	{
		            		document.getElementById("conversa").innerHTML += 
		            			"<div class='w3-panel msg-aluno-recebe w3-round-large'>" +
					 				"<p style='margin-bottom: -3%;''><b>"+dados[i]['usu_nome']+"</b></p>" +
									"<p>"+dados[i]['con_mensagem']+"</p>" +
								"</div>";
		            	}

		            }
		        }

		        var div = document.getElementById('conversa');
      			div.scrollTop = div.scrollHeight;

	        },
   		});



 	}

 	
 </script>

 @stop