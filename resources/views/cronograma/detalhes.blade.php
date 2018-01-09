@extends('layout.principal')

@section('conteudo')
	<div zclass="w3-main" id="main" style="margin-left: 15%">

		<p hidden id='token'>{{$dados['token']}}</p>
		<p hidden id='prj_cod'>{{$dados['projeto'][0]->prj_cod}}</p>


		<div class="w3-esamc">
			<div class="w3-container">
				<div class="col-md-9">
		    		<h1 class="w3-text-white">Detalhes</h1>				
				</div>
	    		<div class="col-md-3" style="text-align: center; padding-top: 2%; color: white; font-size: 120%;">
	    			Bem-vindo {{$dados['usu_nome']}}
	    		</div>
			</div>			
	 	</div>

	 	<div class="w3-conainer" style="padding: 1%;">
	 		<div class="col-md-4">

	 			<!-- DETALHES PROJETO E ALUNOS -->
			 	<table class="w3-table event-listing-title" style="border: 1px solid black; margin-bottom: 6%;">
					<thead>
						<tr class="w3-tr header-days">
							<td class="w3-td w3-esamc" style="font-size: 160%; color: #fff;">
								<b>Projeto:</b> {{$dados['projeto'][0]->prj_titulo}}
							</td>
						</tr>
						<tr class="w3-tr header-days">
							<td class="w3-td w3-esamc-light" style="font-size: 115%; color: #fff; border: 1px solid black">
								<b>Detalhes:</b> {{$dados['projeto'][0]->prj_descricao}}
							</td>
						</tr>
					</thead>
					<tbody style="font-size: 110%;">

						@foreach ($dados['projeto'] as $p)
							@if ($p->usu_cod != $dados['usu_cod'])
							<tr>
								<td class="w3-td">
									<b>Aluno: </b> {{$p->usu_nome}}
									<br>
									<b>E-mail: </b> {{$p->usu_email}}
								</td>
							</tr>
							@endif
						@endforeach
					</tbody>
				</table>

				<!-- VERSÕES DO PROJETO -->
				<table class="w3-table event-listing-title" style="border: 1px solid black;">
					<thead>
						<tr class="w3-tr header-days">
							<td class="w3-td w3-esamc" style="font-size: 160%; color: #fff;">
								<b>Versões do Projeto</b>
							</td>
						</tr>
					</thead>
					<tbody style="font-size: 110%;">
						@foreach ($dados['arquivos'] as $a)
						<tr>
							<td class="w3-td" style="border: 1px solid black;">
								<div class="col-md-10">
									<b>Titulo: </b> {{$a->arq_nome}}
									<br>
									<b>Data: </b> {{$a->created_at}}
								</div>
								<div class="col-md-2" style="text-align: right;">
									<a href="{{$a->arq_local}}" target="_blank"><i class="fa fa-download fa-2x" aria-hidden="true"></i></a>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>

				@if ($dados['projeto'][0]->prj_ativo == 1)
					<form action="/projetos/upload" method="POST" enctype="multipart/form-data">

			 			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			 			<input type="hidden" name="prj_cod" value="{{$dados['projeto'][0]->prj_cod}}" />
						<label class="w3-btn w3-esamc w3-text-white" style="margin: 2%;">
		    					<input class="w3-input" type="file" id="upload" name="arquivo">
						</label>
						<button class="w3-btn w3-esamc w3-text-white" type="submit" type="submit" style="width: 100%; margin-bottom: 2%;">
							Salvar
						</button>
							
					</form>
				@endif
			</div>

			<!-- CRONOGRAMA -->
			<div class="col-md-8" style="padding-bottom: 2%;">
				<table class="w3-table event-listing-title" style="border: 1px solid black;">
					<thead>
						<tr class="w3-tr header-days">
							<td class="w3-td w3-esamc" style="font-size: 200%; color: #fff; text-align: center;">
								<b>CRONOGRAMA</b>
							</td>
						</tr>
						<tr class="w3-tr header-days">
							<td class="w3-td w3-esamc-light" style="font-size: 115%; color: #fff; border: 1px solid black">
								Detalhes dos assuntos que devem ser entregues pelo projeto e suas datas
							</td>
						</tr>
					</thead>
					<tbody style="font-size: 110%;">

						<tr>
							<td>
						@foreach ($dados['cronograma'] as $c)

							<?php if($c->cro_dtentrega < date("Y-m-d") || $dados['projeto'][0]->prj_ativo == 0): ?>

								<div class="w3-panel cronograma-passado w3-round-small" style="margin-bottom: -1%;">
									<div class="col-md-8">
						 				<p style="margin-bottom: -7%; margin-top: -1%;"><b>Assunto:</b> {{$c->cro_assunto}}</p>
										<p  style="margin-bottom: -0.5%;"><b>Data:</b> 
											<?php 	
												$originalDate = "$c->cro_dtentrega";
												$newDate = date("d/m/Y", strtotime($originalDate));  
											?>
											{{$newDate}}
										</p>
									</div>

									@if ($c->cro_entrega == '1')
										<div class="col-md-4">
											<i style="color: #059c00; margin-left: 70%;" class="fa fa-check fa-2x" aria-hidden="true"></i>
										</div>
									@else
										<div class="col-md-4">
											<i style="color: #ac0000; margin-left: 70%;" class="fa fa-close fa-2x" aria-hidden="true"></i>
										</div>
									@endif
								</div>

							<?php  else: ?>

								<div class="w3-panel cronograma-atual w3-round-small" style="margin-bottom: -1%;">
									@if (Session::get('usu_orientador') == 1)
										<div class="col-md-1">
											<a href="#" alt="Excluir Cronograma" onclick="excluir_assunto({{$c->cro_cod}})">
												<i style="color: #ac0000;" class="fa fa-close fa-1x" aria-hidden="true"></i>
											</a>
										</div>
										<div class="col-md-7">
							 				<p style="margin-bottom: -7%; margin-top: -1%;"><b>Assunto:</b> {{$c->cro_assunto}}</p>
											<p  style="margin-bottom: -0.5%;"><b>Data:</b>
												<?php 	
													$originalDate = "$c->cro_dtentrega";
													$newDate = date("d/m/Y", strtotime($originalDate));  
												?>
												{{$newDate}}
											</p>
										</div>
									@else
										<div class="col-md-8">
							 				<p style="margin-bottom: -7%; margin-top: -1%;"><b>Assunto:</b> {{$c->cro_assunto}}</p>
											<p  style="margin-bottom: -0.5%;"><b>Data:</b> 
												<?php 	
													$originalDate = "$c->cro_dtentrega";
													$newDate = date("d/m/Y", strtotime($originalDate));  
												?>
												{{$newDate}}
											</p>
										</div>
									@endif
									<div class="col-md-4">
										<a href="../detalhes_assunto/{{$c->cro_cod}}" style="margin-left: 70%;"><i class="fa fa-pencil fa-2x" aria-hidden="true"></i></a>
									</div>
								</div>

							<?php  endif; ?>

							@if ($c->cro_observacao)
								<div class="w3-panel cronograma-obs w3-round-small" style="margin-bottom: -1%;">
						 			<p style="margin-bottom: -0.25%; margin-top: -0.25%;"><b>Observação:</b> {{$c->cro_observacao}}</p>
								</div>
							@endif

						@endforeach
							<br>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

		<!-- PRESENÇAS ALUNOS -->
		@if (Session::get('usu_orientador') == 1)
		<div class="col-md-12">
			<center>
			<table class="w3-table event-listing-title" style="border: 1px solid black; width: 90%; margin-top: 3%;">
				<thead>
					<tr class="w3-tr header-days">
						<td class="w3-td w3-esamc" colspan="10" style="font-size: 160%; color: #fff;">
							<b><center>PRESENÇAS ALUNOS</center></b>
						</td>
					</tr>
				</thead>
				<tbody style="font-size: 110%;">
					<tr>

						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								<b>ASSUNTO</b> 
							</div>
						</td>


						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								<b>ALUNO</b> 
							</div>
						</td>

						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								<b>DATA</b> 
							</div>
						</td>

						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								<b>PRESENÇA</b> 
							</div>
						</td>			

					</tr>
					
					@foreach ($dados['presencas'] as $p)
					<tr>

						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								{{{$p->cro_assunto}}}
							</div>
						</td>
						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								{{$p->usu_nome}}
							</div>
						</td>
						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								<?php 	
									$originalDate = "$p->cro_dtentrega";
									$newDate = date("d/m/Y", strtotime($originalDate));  
								?>
								{{$newDate}}
							</div>
						</td>

						<td class="w3-td" style="border: 1px solid black;">
							<div class="col-md-12">
								<?php if($p->pre_status == 1) : ?>
									<i style="color: #059c00; margin-left: 70%;" class="fa fa-check" aria-hidden="true"></i>
								<?php elseif($p->pre_status == 0) : ?>
									<i style="color: #ac0000; margin-left: 70%;" class="fa fa-close" aria-hidden="true"></i>
								<?php elseif($p->cro_dtentrega < date("Y-m-d")) : ?>
									<i style="color: #ac0000; margin-left: 70%;" class="fa fa-close" aria-hidden="true"></i>
								<?php else: ?>
									<i><center>Sem registro</center></i>
								<?php endif; ?>
							</div>
						</td>

					</tr>
					@endforeach
						
				</tbody>
			</table>
			</center>
			<br>
		</div>
		@endif


		@if (Session::get('usu_orientador') == 1 && $dados['projeto'][0]->prj_ativo == 1)
			<div class="col-md-12">
				<br>
				<center>
				<button class="w3-btn w3-esamc w3-text-white" type="submit" type="submit" onclick="finarlizar_projeto()" style="width: 80%; margin-bottom: 2%; margin-top: 4%;">
					FINALIZAR PROJETO
				</button>
				</center>
			</div>
		@endif


 	</div>
 	<script type="text/javascript">


 		function confirmacao()
 		{
 			return confirm('Deseja realmente excluir assunto do cronograma ?')
 		}

 		function excluir_assunto(id)
 		{
 			token = document.getElementById("token").innerHTML
 			prj_cod = document.getElementById('prj_cod').innerHTML

 			if(confirmacao()){
 				
 				$.ajax({
			        url: 'http://localhost:8080/api-cronograma/public/api/cronogramas/'+id+'?token='+token,
			        method: 'DELETE',
			        headers: {
		                    'Access-Control-Allow-Origin': '*'
		                },
			        success: function(dados) {

          				document.location.href = "/detalhes/"+prj_cod;
			        },
		   		});
 			}
 			else {

 			}
 		}

 		function finarlizar_projeto()
 		{
 			token = document.getElementById("token").innerHTML
 			prj_cod = document.getElementById('prj_cod').innerHTML

 			$.ajax({
		        url: 'http://localhost:8080/api-cronograma/public/api/projetos/'+prj_cod+'?token='+token,
		        method: 'PUT',
		        headers: {
	                    'Access-Control-Allow-Origin': '*'
	                },
            	data: {
                      'prj_ativo': '0'
                  },
		        success: function(dados) {

      				document.location.href = "/projetos/historico";
		        },
	   		});
 		}
 	</script>
 @stop