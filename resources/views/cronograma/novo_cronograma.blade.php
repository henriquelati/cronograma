@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Nova consulta</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>


		<form class="w3-container" action="/cronograma/salvar" method="POST">

			<div class="col-md-12">

				<div class="col-md-6">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

					<p>
						<label><b>Data</b></label>
						<input class="w3-input w3-border" type="date" id="data" name="data" value="<?php echo date("Y-m-d"); ?>">
					</p>

					<p>
						<label><b>Projeto</b></label>
						<label style="display:none;" id="prj_cod" name="prj_cod"></label>
						<select name="projetos" id="projetos" class="w3-select w3-borde" onchange="mostrar_alunos(this.options[this.selectedIndex].value)">

							<option value="0"></option>
		        			@foreach ($dados['projetos'] as $p)

								<option value="{{$p->prj_cod}}">{{$p->prj_titulo}}</option>

							@endforeach

			            </select>
					</p>
					
					<p>
						<label><b>Assunto</b></label>
						<input class="w3-input w3-border" type="text" name="assunto" id="assunto">
					</p>

					<p>
						<label><b>Observação</b></label>
						<textarea class="w3-input w3-border" type="text" name="observacao" id="observacao"></textarea>
					</p>

					<p>
		    			<input type="checkbox" class="w3-check" name="entrega" id="entrega"><b> ENTREGA OK</b>
		    		</p>

		    	</div>
	    		<div class="col-md-12">
	    			<input type="hidden" name="quantidade_aluno" id="quantidade_aluno" value="0" />
		        	<h3>Integrantes</h3>

		        		<div id="mostrar_alunos">
		        			<div class="col-md-2">
				        		<img src="../img/user.jpg" class="w3-circle" style="width: 100%;">
				        	</div>
						</div>

		        </div>
			 	

		    	<div class="col-md-12">
			    	<button class="w3-btn w3-esamc w3-text-white" type="submit" style="margin: 1%;">
						Salvar
					</button>
				</div>
			</div>

	    </form>
 	</div>

 <script type="text/javascript">
 	function mostrar_alunos(prj_cod) {

 		document.getElementById("mostrar_alunos").innerHTML = "";

		$.ajax({
	        url: 'http://localhost:8080/api-cronograma/public/api/presencas/'+prj_cod+'?token=<?=$dados['token']?>',
	        method: 'GET',
	        headers: {
                    'Access-Control-Allow-Origin': '*'
                },
	        error: function(erro) {
	        	console.log("ERROR")
	        },
	        success: function(dados) {

	        	document.getElementById("prj_cod").innerHTML = prj_cod
	        	document.getElementById("quantidade_aluno").value = dados.length

	        	for (var i = 0; i < dados.length; i++) {

		        	document.getElementById("mostrar_alunos").innerHTML += 
		        		'<div class="col-md-2">' +
					        '<img src="'+dados[i]['usu_foto']+'" class="w3-circle" style="width: 100%;">' +
					        '<input type="checkbox" class="w3-check" name="user_'+i+'" checked>' +
					        '<label>'+dados[i]['usu_nome']+'</label>' +
					        '<input type="hidden" id="usu_cod_'+i+'" name="usu_cod_'+i+'" value="'+dados[i]['pre_usu_cod']+'"></input>' +
					    '</div>';
				}
	        },
   		});
		
	}
 </script>
 @stop