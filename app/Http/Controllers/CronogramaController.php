<?php 

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Session;
use DateTime;
use App\Fileentry;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Mail;
use Swift_Transport;
use Swift_Message;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Http\Response;

class CronogramaController extends Controller {

	private $api;
	//private $uri = 'http://marcosdordetti.com/pcc/api/public/api';
	private $uri = 'http://localhost:8080/api-cronograma/public/api';

	public function __construct(Client $client)
	{
		$this->api = $client;
	}

	//LOGIN
	public function login()
	{

		return view('cronograma.login');
	}

	//DASHBOARD
	public function index()
	{
		$usu_nome = Session::get('usu_nome');
		$token = Session::get('token');
		$now = new \DateTime();
		$data = $now->format('Y-m-d');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		if ($this->primeiro_acesso()) {

			return redirect()->action('CronogramaController@perfil');
		}

		try {

			$resultado = $this->api->get($this->uri.'/projetos?token='.$token);
			$resultadoDia = $this->api->get($this->uri.'/projetosDia/'.$data.'?token='.$token);

        } catch (\GuzzleHttp\Exception\ClientException $e) {

			return redirect()->action('CronogramaController@login');

		}		

		$projetos = json_decode($resultado->getBody());
		$projetosDia = json_decode($resultadoDia->getBody());

		$dados = array(
			'projetos' => $projetos,
			'usu_nome' => $usu_nome,
			'projetos_dia' => $projetosDia,
		);

		return view('cronograma.index')->with('dados', $dados);
	}


	//NOVO ASSUNTO CRONOGRAMA
	public function novo_cronograma()
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		try {
			$resultado = $this->api->get($this->uri.'/projetos?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$projetos = json_decode($resultado->getBody());

		$dados = array(
			'usu_nome' => $usu_nome,
			'usu_cod' => $usu_cod,
			'projetos' => $projetos,
			'token' => $token,
		);

		//dd($dados);

		return view('cronograma.novo_cronograma')->with('dados', $dados);
	}

	//SALVAR NOVO ASSUNTO CRONOGRAMA
	public function novo_assunto(Request $request)
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		$dados = $request->all();

		//VERIFICA DE O ASSUNTO FOI ENTREGUE
		if (isset($dados['entrega']) ) {
			$cro_status = 1;
		} 
		else {
			$cro_status = 0;
		}

		try {
			//CRIA UM NOVO REGISTRO NA TABELA CRONOGRAMA
			$resultado_cronograma = $this->api->post($this->uri.'/cronogramas?token='.$token, [
				'form_params' => [
					'cro_assunto' => $dados['assunto'],
					'cro_dtentrega' => $dados['data'],
					'cro_prj_cod' => $dados['projetos'],
					'cro_entrega' => $cro_status,
					'cro_observacao' => $dados['observacao'],
				]
			]);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$novo_cronograma = json_decode($resultado_cronograma->getBody()->getContents());

		//UM FOR PARA CRIAR OS REGISTROS DE PRESENÇA
		for ($i=0; $i < $dados['quantidade_aluno']; $i++) { 

			//VERIFICA DE O ALUNO ESTÁ PRESENTE OU NÃO
			if (isset($dados['user_'.$i]) ) {
				$status = 1;
			} 
			else {
				$status = 0;
			}
			
			try {
				//FAZ O REGISTRO NA TABELA PRESENÇAS, ATRIBUINDO OS IDS DA ARRAY CRIADA ANTERIORMENTE
				$resultado_presenca = $this->api->post($this->uri.'/presencas?token='.$token,
					[
						'form_params' => [
							'pre_status' => $status,
							'pre_cro_cod' => $novo_cronograma->cro_cod,
							'pre_usu_cod' => $dados['usu_cod_'.$i],
						]
				]);

			} catch (\GuzzleHttp\Exception\ClientException $e) {
				return redirect()->action('CronogramaController@login');
			}
		}

		return redirect()->action('CronogramaController@index');
	}

	//PROJETOS ATIVOS
	public function projetos()
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		if ($this->primeiro_acesso()) {

			return redirect()->action('CronogramaController@perfil');
		}

		try {
			$resultado = $this->api->get($this->uri.'/projetos?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$projetos = json_decode($resultado->getBody());

		$dados = array(
			'projetos' => $projetos,
			'usu_nome' => $usu_nome,
			'usu_cod' => $usu_cod
		);

		return view('cronograma.projetos')->with('dados', $dados);
	}

	//NOVO PROJETO
	public function novo()
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');

		if (! $token || Session::get('usu_orientador') == 0) {

			return redirect()->action('CronogramaController@index');
		}
		
		if ($this->primeiro_acesso()) {

			return redirect()->action('CronogramaController@perfil');
		}

		$dados = array('usu_nome' => $usu_nome);

		return view('cronograma.novo_projeto')->with('dados', $dados);
	}

	//CRIAR NOVO PROJETO
	public function criar_novo(Request $request)
	{
		//BUSCAR O TOKEN DA SESSÃO
		$token = Session::get('token');
		//BUSCAR O ID DO USUARIO LOGADO
		$usu_cod = Session::get('usu_cod');

		//ATRIBUI TODOS OS DADOS DO FRONT DOS NOVOS USUÁRIOS
		$usuarios = $request->all();


		//VERIFICAR SE A SESSÃO ESTÁ COM O TOKEN
		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		//ATRIBUI OS DADOS DO NOVO PROJETO
		$projeto = $request->only('titulo', 'descricao');

		try {
			//CRIA NO BANDO DE DADOS O NOVO PROJETO
			$resultado_projeto = $this->api->post($this->uri.'/projetos?token='.$token, [
			    'form_params' => [
			        'prj_titulo' => $projeto['titulo'],
			        'prj_descricao' => $projeto['descricao'],
			        'prj_ori_cod' => $usu_cod,
			    ]
			]);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		//ATRIBUI A VARIAVEL OS DADOS DE RETORNO DA GRAVAÇÃO DO PROJETO
		$novo_projeto = json_decode($resultado_projeto->getBody()->getContents());

		//CRIA UMA ARRAY PARA OS IDS DOS FUTUROS USUÁRIOS
		$id_usuarios = array();

		//CRIA UM FOR PARA GRAVAR OS USUARIOS E A TABELA USUARIOPROEJTO
		for ($i=0; $i < $usuarios['qntUsuario']; $i++)
		{
			//GERA UM SENHA ALEATÓRIA PARA O USUÁRIO
			$senha = str_random(8);


			//GUARDAR INFORMAÇÃO PARA FILA DE EMAILS
			$gravar_email = $this->api->post($this->uri.'/emails', [
				'form_params' => [
					'eml_email' => $usuarios['email_'.$i],
					'eml_status' => '0',
					'eml_msg' => $senha
				]
			]);

			//CRIA UM NOVO USUÁRIO NO BANCO DE DADOS
			$resultado_usuario = $this->api->post($this->uri.'/usuarios?token='.$token, [
				'form_params' => [
					'usu_nome' => $usuarios['nome_'.$i],
					'usu_email' => $usuarios['email_'.$i],
					'usu_password' => $senha,
					'usu_primeiro_acesso' => '1',
				]
			]);

			//ENVIAR E-MAIL COM ACESSO
      		// $this->enviar_email($usuarios['email_'.$i], $senha);			

			//ATRIBUI A VARIAVEL OS DADOS DO USUARIO CRIADO
			$novo_usuario = json_decode($resultado_usuario->getBody()->getContents());

			//ADICIONA A ARRAY O ID DO NOVO USUARIO
			array_push($id_usuarios, $novo_usuario->usu_cod);

			//CRIA UM NOVO REGISTRO NO BANCO DE DADOS NA TABELA USUARIOPROJETO
			$usuarioprojeto = $this->api->post($this->uri.'/usuarioprojetos?token='.$token, [
				'form_params' => [
					'upj_prj_cod' => $novo_projeto->prj_cod,
					'upj_usu_cod' => $novo_usuario->usu_cod
				]
			]);


		}

		try{
			//CRIA UM NOVO REGISTRO NO BANCO DE DADOS NA TABELA USUARIOPROJETO PARA O ORIENTADOR
			$usuarioprojeto = $this->api->post($this->uri.'/usuarioprojetos?token='.$token, [
				'form_params' => [
					'upj_prj_cod' => $novo_projeto->prj_cod,
					'upj_usu_cod' => $usu_cod
				]
			]);
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		//CRIA UM FOR PARA GRAVAR OS CRONOGRAMAS DO PROJETO
		for ($i=1; $i <= $usuarios['qntSemana']; $i++) 
		{ 	

			//FORMATA A DATA PARA GRAVAÇÃO NO BANDO DE DADOS
			// $data_formatada = DateTime::createFromFormat('d/m/Y', $usuarios['data_'.$i]);
			$data = array("‎11‎/‎12‎/‎2017", "‎18‎/‎12‎/‎2017",  "‎25‎/‎12‎/‎2017", "‎01‎/‎01‎/‎2018");
			//dd();

			try{
				//CRIA UM NOVO REGISTRO NA TABELA CRONOGRAMA
				$resultado_cronograma = $this->api->post($this->uri.'/cronogramas?token='.$token, [
					'form_params' => [
						'cro_assunto' => $usuarios['assunto_'.$i],
						'cro_dtentrega' => '2017-12-11',
						'cro_prj_cod' => $novo_projeto->prj_cod,
						// 'cro_entrega' => '0',
					]
				]);
			} catch (\GuzzleHttp\Exception\ClientException $e) {
				return redirect()->action('CronogramaController@login');
			}


			//ATRIBUI A VARIAVEL OS DADOS DO REGISTRO EFETUADO
			$novo_cronograma = json_decode($resultado_cronograma->getBody()->getContents());

			//UM FOR PARA CRIAR OS REGISTROS DE PRESENÇA
			for ($j=0; $j < $usuarios['qntUsuario']; $j++) { 
				
				//FAZ O REGISTRO NA TABELA PRESENÇAS, ATRIBUINDO OS IDS DA ARRAY CRIADA ANTERIORMENTE
				$resultado_presenca = $this->api->post($this->uri.'/presencas?token='.$token,
					[
						'form_params' => [
							'pre_status' => 2,
							'pre_cro_cod' => $novo_cronograma->cro_cod,
							'pre_usu_cod' => $id_usuarios[$j]
						]
				]);
			}
		}

		//REDIRECIONA PARA A FUNÇÃO @PROJETOS
		return redirect()->action('CronogramaController@projetos');
	}


	//DETALHES PROJETO/CRONOGRAMA
	public function cronograma()
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		$dados = array('usu_nome' => $usu_nome);

		return view('cronograma.cronograma')->with('dados', $dados);
	}

	//PROJETOS INATIVOS
	public function historico() 
	{

		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}
		
		if ($this->primeiro_acesso()) {

			return redirect()->action('CronogramaController@perfil');
		}

		try {
			$resultado = $this->api->get($this->uri.'/projetos_historico?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$projetos = json_decode($resultado->getBody());


		$dados = array(
			'projetos' => $projetos,
			'usu_nome' => $usu_nome,
			'usu_cod' => $usu_cod
		);

		return view('cronograma.historico')->with('dados', $dados);

	}

	//CONVERSA
	public function conversa() 
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}
		
		if ($this->primeiro_acesso()) {

			return redirect()->action('CronogramaController@perfil');
		}

		try {
			$resultado = $this->api->get($this->uri.'/projetos?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$projetos = json_decode($resultado->getBody());

		$dados = array(
			'usu_nome' => $usu_nome,
			'usu_cod' => $usu_cod,
			'projetos' => $projetos,
		);

		//dd($dados);

		return view('cronograma.conversa')->with('dados', $dados);
	}


	//REUNIÕES
	public function reunioes() 
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$now = new \DateTime();
		$data = $now->format('Y-m-d');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}
		
		if ($this->primeiro_acesso()) {

			return redirect()->action('CronogramaController@perfil');
		}

		try {
			$resultado = $this->api->get($this->uri.'/cronogramas?token='.$token);
			$resultadoDia = $this->api->get($this->uri.'/projetosDia/'.$data.'?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$cronogramas = json_decode($resultado->getBody());	
		$projetos_dia = json_decode($resultadoDia->getBody());


		$dados = array(
			'usu_nome' => $usu_nome,
			'cronogramas' => $cronogramas,
			'projetos_dia' => $projetos_dia,
		);

		//dd(count($dados['projetos_dia']));

		return view('cronograma.reunioes')->with('dados', $dados);
	}


	//DETALHES
	public function detalhes($id)
	{

		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}	

		try {
			$resultado = $this->api->get($this->uri.'/usuarioprojetos/'.$id.'?token='.$token);
			$projeto = json_decode($resultado->getBody());

			$resultado = $this->api->get($this->uri.'/cronograma/'.$id.'?token='.$token);
			$cronograma = json_decode($resultado->getBody());

			$resultado = $this->api->get($this->uri.'/arquivos/'.$id.'?token='.$token);
			$arquivos = json_decode($resultado->getBody());

			$resultado = $this->api->get($this->uri.'/presenca/'.$id.'?token='.$token);
			$presencas = json_decode($resultado->getBody());

		} catch (\GuzzleHttp\Exception\ClientException $e) {

			return redirect()->action('CronogramaController@login');
		}

		$dados = array(
			'projeto' => $projeto, 
			'cronograma' => $cronograma, 
			'usu_nome' => $usu_nome,
			'usu_cod' => $usu_cod,
			'arquivos' => $arquivos,
			'token' => $token,
			'presencas' => $presencas
		);

		// dd($dados);

		return view('cronograma.detalhes')->with('dados', $dados);
	}

	//ENVIAR ARQUIVO
	public function gravar_arquivo(Request $request)
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		$dados = $request->all();

		try {
			$response = $this->api->post($this->uri.'/arquivos/add?token='.$token, [
			    'multipart' => [
			        [
			        	'name'     => 'arquivo',
	                    'filename' => \Input::file('arquivo')->getClientOriginalName(),
	                    'contents' => fopen(\Input::file('arquivo')->getRealPath(), 'r'),
			        ],
			        [
			            'name'     => 'arq_prj_cod',
			            'contents' => $dados['prj_cod']
			        ]
			    ]
			]);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		return redirect()->action('CronogramaController@detalhes', ['id' => $dados['prj_cod']]);

	}


	//DETALHES ASSUNTO
	public function detalhes_assunto($id)
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');
		$usu_cod = Session::get('usu_cod');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		try {
			$resultado = $this->api->get($this->uri.'/cronogramas/'.$id.'?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$cronograma = json_decode($resultado->getBody());

		$dados = array(
			'usu_nome' => $usu_nome,
			'cronograma' => $cronograma,
			'usu_cod' => $usu_cod,
		);

		//dd($dados);

		return view('cronograma.detalhes-assunto')->with('dados', $dados);
	}


	//SALVAR ALTERAÇÕES NO DETALHES DO ASSUNTO
	public function salvar_assunto($id, Request $request)
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');

		$dados = $request->all();

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		try {
			$this->api->put($this->uri.'/cronogramas/'.$id.'?token='.$token, [
				'form_params' => [
					'cro_assunto' => $dados['assunto'],
				]
			]);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		for ($i=0; $i < $dados['quantidade_aluno']; $i++) {

			//VERIFICA DE O ALUNO ESTÁ PRESENTE OU NÃO
			if (isset($dados['aluno_'.$i]) ) {
				$status = 1;
			} 
			else {
				$status = 0;
			}

			try {
				//FAZ A EDIÇÃO DO CAMPO NA TABELA PRESENÇAS
				$this->api->put($this->uri.'/presencas/'.$id.'?token='.$token, [
						'form_params' => [
							'pre_status' => $status,
							'pre_usu_cod' => $dados['id_aluno_'.$i],
					]
				]);

			} catch (\GuzzleHttp\Exception\ClientException $e) {
				return redirect()->action('CronogramaController@login');
			}
		}

		//VERIFICA DE O ASSUNTO FOI ENTREGUE
		if (isset($dados['entrega']) ) {
			$cro_status = 1;
		} 
		else {
			$cro_status = 0;
		}

		//dd($cro_status);

		try {

			if(isset($dados['observacao'])) {
				//ATUALIZA A TABELA CRONOGRAMA
				$this->api->put($this->uri.'/cronogramas/'.$id.'?token='.$token, [
					    'form_params' => [
					        'cro_entrega' => $cro_status,
					        'cro_observacao' => $dados['observacao']
					]
				]);
			}
			else {
				$this->api->put($this->uri.'/cronogramas/'.$id.'?token='.$token, [
					    'form_params' => [
					        'cro_entrega' => $cro_status,
					        //'cro_observacao' => $dados['observacao']
					]
				]);
			}

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}


		//REDIRECIONA PARA A DASHBOARD
		return redirect()->action('CronogramaController@index');
	}


	//EFETUAR LOGIN
	public function logar(Request $request)
	{
		$dados = $request->all();


		try {
			$result = $this->api->post($this->uri.'/auth/login', [
			    'form_params' => [
			        'usu_email' => $dados['email'],
			        'usu_password' => $dados['password']
			    ]
			]);

		} catch (\GuzzleHttp\Exception\ClientException $e) {

			$error = true;

			return view('cronograma.login')->with('error', $error);
		}

		$dados = json_decode($result->getBody()->getContents());

		Session::put('token', $dados->access_token);
		Session::put('usu_cod', $dados->usuario->usu_cod);
		Session::put('usu_nome', $dados->usuario->usu_nome);
		Session::put('primeiro_acesso', $dados->usuario->usu_primeiro_acesso);
		Session::put('usu_orientador', $dados->usuario->usu_orientador);

		return redirect()->action('CronogramaController@index');
	}


	//TELA DE EDITAR PERFIL
	public function perfil()
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		try {
			$resultado = $this->api->get($this->uri.'/usuarios/0?token='.$token);

		} catch (\GuzzleHttp\Exception\ClientException $e) {

			return redirect()->action('CronogramaController@login');
		}

		$usuario = json_decode($resultado->getBody());

		$dados = array(
			'usu_nome' => $usu_nome,
			'usuario' => $usuario,
		);

		//dd($dados);

		return view('cronograma.perfil')->with('dados', $dados);
	}


	//EDITAR PERFIL
	public function editar_perfil(Request $request)
	{
		$token = Session::get('token');
		$usu_nome = Session::get('usu_nome');

		if (! $token) {

			return redirect()->action('CronogramaController@login');
		}

		$dados = $request->all();
		$file = \Input::file('foto');
		try {

			if($file) {

				
				$fileName = \Input::file('foto')->getClientOriginalName();

				if(! $dados['senha'])
				{

					$response = $this->api->post($this->uri.'/usuarios/editar?token='.$token, [
					    'multipart' => [
					        [	'name'     => 'foto',
			                    'filename' => $fileName,
			                    'contents' => fopen(\Input::file('foto')->getRealPath(), 'r'),],
					        [	'name'     => 'usu_nome',
					            'contents' => $dados['nome']],
					        [	'name'     => 'usu_email',
					            'contents' => $dados['email']],
					        [	'name'     => 'usu_cpf',
					            'contents' => $dados['cpf']],
					        [	'name'     => 'usu_ra',
					            'contents' => $dados['ra']],
					        [	'name'     => 'usu_primeiro_acesso',
					            'contents' => '0']
					    ]
					]);


				}
				else {

					$response = $this->api->post($this->uri.'/usuarios/editar?token='.$token, [
					    'multipart' => [
					        [	'name'     => 'foto',
			                    'filename' => $fileName,
			                    'contents' => fopen(\Input::file('foto')->getRealPath(), 'r'),],
					        [	'name'     => 'usu_nome',
					            'contents' => $dados['nome']],
					        [	'name'     => 'usu_email',
					            'contents' => $dados['email']],
					        [	'name'     => 'usu_cpf',
					            'contents' => $dados['cpf']],
					        [	'name'     => 'usu_ra',
					            'contents' => $dados['ra']],
					        [	'name'     => 'usu_password',
					            'contents' => $dados['senha']],
					        [	'name'     => 'usu_primeiro_acesso',
					            'contents' => '0']
					    ]
					]);

				}
			}
			else {

				if(! $dados['senha'])
				{	

					$response = $this->api->post($this->uri.'/usuarios/editar?token='.$token, [
					    'multipart' => [
					        [   'name'     => 'usu_nome',
					            'contents' => $dados['nome']],
					        [	'name'     => 'usu_email',
					            'contents' => $dados['email']],
					        [	'name'     => 'usu_cpf',
					            'contents' => $dados['cpf']],
					        [	'name'     => 'usu_ra',
					            'contents' => $dados['ra']],
					        [	'name'     => 'usu_primeiro_acesso',
					            'contents' => '0']
					    ]
					]);
				}
				else {

					$response = $this->api->post($this->uri.'/usuarios/editar?token='.$token, [
					    'multipart' => [
					        [	'name'     => 'usu_nome',
					            'contents' => $dados['nome']],
					        [	'name'     => 'usu_email',
					            'contents' => $dados['email']],
					        [	'name'     => 'usu_cpf',
					            'contents' => $dados['cpf']],
					        [	'name'     => 'usu_ra',
					            'contents' => $dados['ra']],
					        [	'name'     => 'usu_password',
					            'contents' => $dados['senha']],
					        [	'name'     => 'usu_primeiro_acesso',
					            'contents' => '0']
					    ]
					]);
				}

			}

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		Session::put('usu_nome', $dados['nome']);
		Session::put('primeiro_acesso', '0');

		return redirect()->action('CronogramaController@index');

	}

	//RECUPERAR SENHA
	public function recuperar_senha(Request $request)
	{

		try {
			$resultado = $this->api->get($this->uri.'/usuario/'.$request['email']);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}

		$dados = json_decode($resultado->getBody());

		if($dados) {

			$senha = str_random(8);

			$this->api->post($this->uri.'/usuario/alterar_senha/'.$dados->usu_cod, [
			    'form_params' => [
			        'usu_password' => $senha,
				]
			]);

      		
      		$this->enviar_email($request['email'], $senha);

		}

		return redirect()->action('CronogramaController@login');
	}


	//ENVIAR E-MAIL
	public function enviar_email($email, $senha)
	{
  	
      	$data_toview = array();
      	$data_toview['email'] = $email;
      	$data_toview['senha'] = $senha;

      	$email_sender = 'esamc.cronograma@gmail.com';
      	$email_pass = 'P@ssw0rdESAMC';
		$email_to = $email;


      	$backup = \Mail::getSwiftMailer();


  		$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls');

  		$transport->setUsername($email_sender);
  		$transport->setPassword($email_pass);  		

  		$gmail = new Swift_Mailer($transport);

  		\Mail::setSwiftMailer($gmail);

  		$data['emailto'] = $email_to;
  		$data['sender'] = $email_to;

  		Mail::send('emails.recuperar_senha', $data_toview, function($message) use ($data){

  			$message->from($data['sender'], 'Cronograma ESAMC');
  			$message->to($data['emailto'])->replyTo($data['sender'], 'Cronograma ESAMC')->subject('Dados de acesso');

  		});

  		return true;
	}

	//VERIFICAR SE JÁ HOUVE O PRIMEIRO ACESSO
	public function primeiro_acesso()
	{
		$primeiro_acesso = Session::get('primeiro_acesso');


		if ($primeiro_acesso == 1) {
			return true;
		}

		return false;
	}   

	//EFETUAR LOGOUT
	public function logout()
	{
		Session::flush();

		return redirect()->action('CronogramaController@login');
	}


	//NOVO ORIENTADOR
	public function novo_orientador()
	{

		return view('cronograma.novo_orientador');
	}

	//SALVAR NOVO ORIENTADOR
	public function salvar_orientador(Request $request)
	{
		$usuario = $request->all();

		try {

			$resultado = $this->api->post($this->uri.'/usuario/criar_orientador', [
			'form_params' => [
				'usu_nome' => $usuario['nome'],
				'usu_email' => $usuario['email'],
				'usu_cpf' => $usuario['cpf'],
				'usu_password' => $usuario['senha'],
				'usu_orientador' => '1',
				'usu_primeiro_acesso' => '0',
			]
		]);

		} catch (\GuzzleHttp\Exception\ClientException $e) {
			return redirect()->action('CronogramaController@login');
		}


		

		// dd(json_decode($resultado->getBody()->getContents()));

		return redirect()->action('CronogramaController@login');
	}

	//TESTE EDUZZ
	public function eduzz()
	{

		// $res = $this->api->request('GET', 'https://my.eduzz.com/api2/get_me', [
		//     'headers' => [
		//         'PublicKey' => '176668',
		//         'APIKey'     => '31db130eb5'
		//     ]
		// ]);


		$res = $this->api->request('POST', 'https://my.eduzz.com/api2/get_saleslist', [ 
		    'headers' => [ 
		        'PublicKey' => '176668', 
		        'APIKey' => '31db130eb5' 
		    ], 
		    'form_params' => [ 
		       	'DATE_START' => '2017-01-01 00:00:00',
		       	'DATE_START' => '2018-01-01 00:00:00',
		       	'CLIENT_EMAIL' => 'gabrielkamila.gk@gmail.com'

		    ] 
		]);


		$contents = $res->getBody()->getContents(); 
		$contents = json_decode($contents); 

		dd($contents);

	}
	
}
