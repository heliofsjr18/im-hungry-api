<?php
date_default_timezone_set('America/Sao_Paulo');
header("Content-Type: application/json");
require 'lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;
use Firebase\JWT\JWT;

define('SECRET_KEY','im-hungry-api');
define('ALGORITHM','HS256');

$app = new \Slim\App(array('templates.path' => 'templates', 'settings' => ['displayErrorDetails' => true]));

$container = $app->getContainer();
$container['upload_directory'] = __DIR__ . '/uploads';

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->get('/', function(Request $request, Response $response, $args) {
	return $response->withJson(['status' => 200, 'message' => "Api Manager I'm Hungry"]);
});

// Consumo Geral

$app->post('/cep', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Enderecos.php';
    require_once 'Controller/EnderecosController.php';


    $cep = str_replace(".", "", $data['cep']);
    $cep = str_replace("-", "", $cep);
    $cep = mask($cep,'#####-###');

    $endereco = new Enderecos();
    $endereco->setCep($cep);

    $enderecoController = new EnderecosController();
    $retorno = $enderecoController->listCep($endereco);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "CEP Encontrado!",
            'dados' 		=> $retorno[0],
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

// Consumo WEB

$app->post('/web/usuario/login', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setEmail($data["email"]);
    $usuario->setSenha($data["senha"]);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->loginWeb($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{
        $jwt = setToken($retorno[0]);
        $res = array(
            'status'        => 200,
            'message'       => "SUCCESS",
            'usuario'       => $retorno[0],
            'token'         => $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/web/usuario/listAll', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';


    $usuario = new Usuario();
    $usuario->setTipoId($data["tipo_usuario"]);
    $usuario->setStatus($data["enabled"]);
    $usuario->setFilialId($data["filial_id"]);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->listAll($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Funcionarios Encontradas!",
            'enabled'       => $data["enabled"],
            'qtd'           => count($retorno),
            'funcionarios' 		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/usuario/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setNome($data["nome"]);
    $usuario->setCpf($data["cpf"]);
    $usuario->setTelefone($data["telefone"]);
    $usuario->setData($data["data"]);
    $usuario->setEmail($data["email"]);
    $usuario->setSenha("123");
    $usuario->setCep($data["cep"]);
    $usuario->setEnderecoNumero($data["numero_end"]);
    $usuario->setEnderecoComplemento($data["complemento"]);
    $usuario->setStatus($data["enabled"]);
    $usuario->setTipoId($data["tipo_usuario"]);
    $usuario->setFilialId($data["filial_id"]);
    $usuario->setFotoPerfil($data["foto_perfil"]);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->insert($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Usuário Cadastrado!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/adm/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setNome($data["nome"]);
    $usuario->setEmail($data["email"]);
    $usuario->setSenha($data["senha"]);
    $usuario->setFotoPerfil($data["fot64"]);
    $usuario->setStatus(1);
    $usuario->setTipoId(1);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->insert($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{
        $jwt = setToken($retorno[0]);
        $res = array(
            'status'        => 200,
            'message'       => "SUCCESS",
            'result'        => "Usuário cadastrado e o login foi realizado!",
            'usuario'       => $retorno[0],
            'token'         => $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/web/usuario/update', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setId($data["idAt"]);
    $usuario->setNome($data["nome"]);
    $usuario->setCpf($data["cpf"]);
    $usuario->setTelefone($data["telefone"]);
    $usuario->setData($data["data"]);
    $usuario->setEmail($data["email"]);
    $usuario->setCep($data["cep"]);
    $usuario->setEnderecoNumero($data["numero_end"]);
    $usuario->setEnderecoComplemento($data["complemento"]);
    $usuario->setFotoPerfil($data["foto_perfil"]);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->update($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status'      => 200,
            'message'     => "SUCCESS",
            'result'      => "Usuário Atualizado!",
            'token'          => $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/web/usuario/enabled', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setId($data["idChange"]);
    $usuario->setStatus($data["status"]);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->enabled($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Dados Atualizados!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);
    }
});

$app->post('/web/empresa/listAll', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Empresa.php';
    require_once 'Controller/EmpresaController.php';


    $empresa = new Empresa();
    $empresa->setUserId($auth['token']->data->user_id);
    $empresa->setEnabled($data["enabled"]);

    $empresaController = new EmpresaController();
    $retorno = $empresaController->listAll($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Empresas Encontradas!",
            'enabled'       => $data["enabled"],
            'qtd'           => count($retorno),
            'empresas' 		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/empresa/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Empresa.php';
    require_once 'Controller/EmpresaController.php';

    //$global_dir = $this->get('upload_directory');
    //$files = $request->getUploadedFiles();
    //$file_brand = $files['foto'];
    //$filename = getNameFile($file_brand);

    $telefone = str_replace("(", "", $data["telefone"]);
    $telefone = str_replace(")", "", $telefone);
    $telefone = str_replace("-", "", $telefone);

    $empresa = new Empresa();
    $empresa->setNome($data["nome"]);
    $empresa->setTelefone($telefone);
    $empresa->setCnpj($data["cnpj"]);
    $empresa->setCep($data["cep"]);
    $empresa->setLatitude($data["lat"]);
    $empresa->setLongitude($data["long"]);
    $empresa->setNumeroEndereco($data["numero_end"]);
    $empresa->setComplementoEndereco($data["complemento_end"]);
    $empresa->setDataFundacao($data["dataFund"]);
    $empresa->setFacebook($data["facebook"]);
    $empresa->setInstagram($data["instagram"]);
    $empresa->setTwitter($data["twitter"]);
    $empresa->setUserId($auth['token']->data->user_id);
    //$empresa->setFotoMarca($filename);
    $empresa->setFotoMarca($data["foto"]);

    $empresaController = new EmpresaController();
    $retorno = $empresaController->insert($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        //moveUploadedFile($global_dir.DIRECTORY_SEPARATOR."empresa",$file_brand, $filename);

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Empresa Cadastrada!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/empresa/update', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Empresa.php';
    require_once 'Controller/EmpresaController.php';

    $telefone = str_replace("(", "", $data["telefone"]);
    $telefone = str_replace(")", "", $telefone);
    $telefone = str_replace("-", "", $telefone);

    $empresa = new Empresa();
    $empresa->setId($data["idAt"]);
    $empresa->setNome($data["nome"]);
    $empresa->setTelefone($telefone);
    $empresa->setCnpj($data["cnpj"]);
    $empresa->setCep($data["cep"]);
    $empresa->setLatitude($data["lat"]);
    $empresa->setLongitude($data["long"]);
    $empresa->setNumeroEndereco($data["numero_end"]);
    $empresa->setComplementoEndereco($data["complemento_end"]);
    $empresa->setDataFundacao($data["dataFund"]);
    $empresa->setFacebook($data["facebook"]);
    $empresa->setInstagram($data["instagram"]);
    $empresa->setTwitter($data["twitter"]);
    $empresa->setUserId($auth['token']->data->user_id);
    $empresa->setFotoMarca($data["foto"]);

    $empresaController = new EmpresaController();
    $retorno = $empresaController->update($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Dados Atualizados!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/empresa/enabled', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Empresa.php';
    require_once 'Controller/EmpresaController.php';

    $empresa = new Empresa();
    $empresa->setId($data["idChange"]);
    $empresa->setEnabled($data["status"]);

    $empresaController = new EmpresaController();
    $retorno = $empresaController->enabled($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Dados Atualizados!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/filial/listAll', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }

    require_once 'Controller/EmpresaFilialController.php';

    $user_id = $auth['token']->data->user_id;
    $status = $data["enabled"];

    $empresaController = new EmpresaFilialController();
    $retorno = $empresaController->listAll($user_id, $status);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Filiais Encontradas!",
            'qtd'           => count($retorno),
            'filiais' 		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/filial/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/EmpresaFilial.php';
    require_once 'Controller/EmpresaFilialController.php';

    $empresa = new EmpresaFilial();
    $empresa->setNome($data["nome"]);
    $empresa->setTelefone($data["telefone"]);
    $empresa->setCnpj($data["cnpj"]);
    $empresa->setCep($data["cep"]);
    $empresa->setLatitude($data["lat"]);
    $empresa->setLongitude($data["long"]);
    $empresa->setNumeroEndereco($data["numero_end"]);
    $empresa->setComplementoEndereco($data["complemento_end"]);
    $empresa->setEmpresaId($data['empresa_id']);

    $empresaController = new EmpresaFilialController();
    $retorno = $empresaController->insert($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Filial Cadastrada!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/filial/update', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/EmpresaFilial.php';
    require_once 'Controller/EmpresaFilialController.php';

    $empresa = new EmpresaFilial();
    $empresa->setId($data['idAt']);
    $empresa->setNome($data["nome"]);
    $empresa->setTelefone($data["telefone"]);
    $empresa->setCnpj($data["cnpj"]);
    $empresa->setCep($data["cep"]);
    $empresa->setLatitude($data["lat"]);
    $empresa->setLongitude($data["long"]);
    $empresa->setNumeroEndereco($data["numero_end"]);
    $empresa->setComplementoEndereco($data["complemento_end"]);
    $empresa->setEmpresaId($data['empresa_id']);

    $empresaController = new EmpresaFilialController();
    $retorno = $empresaController->update($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "A filial foi atualizada!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/filial/enabled', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/EmpresaFilial.php';
    require_once 'Controller/EmpresaFilialController.php';

    $empresa = new EmpresaFilial();
    $empresa->setId($data["idChange"]);
    $empresa->setEnabled($data["status"]);

    $empresaController = new EmpresaFilialController();
    $retorno = $empresaController->enabled($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Dados Atualizados!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/web/filial/status', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/EmpresaFilial.php';
    require_once 'Controller/EmpresaFilialController.php';

    $empresa = new EmpresaFilial();
    $empresa->setId($data["idChange"]);
    $empresa->setStatus($data["status"]);

    $empresaController = new EmpresaFilialController();
    $retorno = $empresaController->status($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Dados Atualizados!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/web/menu/listAll', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/MenuFilialItens.php';
    require_once 'Controller/MenuFilialItensController.php';

    $menu = new MenuFilialItens();
    $menu->setFilialId($data["filial_id"]);
    $menu->setStatus($data["enabled"]);

    $menuController = new MenuFilialItensController();
    $retorno = $menuController->listAll($menu);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Menu Encontrado!",
            'menu'  		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/menu/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/MenuFilialItens.php';
    require_once 'Controller/MenuFilialItensController.php';

    $data['valor'] = str_replace('R$ ', '' , $data['valor']);
    $data['valor'] = str_replace('.', '' , $data['valor']);
    $data['valor'] = str_replace(',', '.' , $data['valor']);

    $itens = new MenuFilialItens();
    $itens->setNome($data["nome"]);
    $itens->setValor($data["valor"]);
    $itens->setTempoMedio($data["tempo"]);
    $itens->setPromocao($data["promo"]);
    $itens->setFilialId($data["filial_id"]);

    $menuController = new MenuFilialItensController();
    $retorno = $menuController->insert($itens, $data["fotos"]);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Item cadastrado!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/menu/update', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/MenuFilialItens.php';
    require_once 'Controller/MenuFilialItensController.php';

    $data['valor'] = str_replace('R$ ', '' , $data['valor']);
    $data['valor'] = str_replace('.', '' , $data['valor']);
    $data['valor'] = str_replace(',', '.' , $data['valor']);

    $itens = new MenuFilialItens();
    $itens->setNome($data["nome"]);
    $itens->setValor($data["valor"]);
    $itens->setTempoMedio($data["tempo"]);
    $itens->setPromocao($data["promo"]);
    $itens->setStatus($data["statusAt"]);
    $itens->setId($data["item_id"]);

    $menuController = new MenuFilialItensController();
    $retorno = $menuController->update($itens);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Item atualizado!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/item/foto', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/ItensFotos.php';
    require_once 'Controller/MenuFilialItensController.php';

    $item = new ItensFotos();
    $item->setFotFile($data["foto"]);
    $item->setItemId($data["item_id"]);

    $menuController = new MenuFilialItensController();
    $retorno = $menuController->addImage($item);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Foto cadastrada!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/item/foto/del', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/ItensFotos.php';
    require_once 'Controller/MenuFilialItensController.php';

    $item = new ItensFotos();
    $item->setId($data["fot_id"]);
    $item->setItemId($data["item_id"]);

    $menuController = new MenuFilialItensController();
    $retorno = $menuController->delImage($item);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Foto excluída!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/checkout/changeFlag', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }

    $status = $data["status"];
    $idChange = $data["idChange"];

    require_once 'Controller/CheckoutItensController.php';

    $checkout = new CheckoutItensController();
    $retorno = $checkout->changeFlag($status, $idChange);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Dados Atualizados!",
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/web/pedidos', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }

    $user_id = $auth['token']->data->user_id;
    $filial_id = $auth['token']->data->filial_id;
    $status = $data["status"];

    require_once 'Controller/CheckoutItensController.php';

    $checkout = new CheckoutItensController();
    $retorno = $checkout->listAll($user_id, $filial_id, $status);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'pedidos' 		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/fidelidade/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/FidelidadeFilial.php';
    require_once 'Controller/FidelidadeFilialController.php';

    $fidelidade = new FidelidadeFilial();
    $fidelidade->setQtd($data["qtd"]);
    $fidelidade->setValor($data["valor"]);
    $fidelidade->setBeneficio($data["beneficio"]);
    $fidelidade->setFilialId($data["filial_id"]);
    
    $fidelidadeFilialController = new FidelidadeFilialController();
    $retorno = $fidelidadeFilialController->insert($fidelidade);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status'        => 200,
            'message'       => "SUCCESS",
            'result'        => "Fidelidade Cadastrada!",
            'token'         => $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/web/fidelidade/remove', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/FidelidadeFilial.php';
    require_once 'Controller/FidelidadeFilialController.php';

    $fidelidade = new FidelidadeFilial();
    $fidelidade->setId($data["cartao_fid_id"]);
    $fidelidade->setQtd($data["qtd"]);
    $fidelidade->setValor($data["valor"]);
    $fidelidade->setBeneficio($data["beneficio"]);
    $fidelidade->setFilialId($data["filial_id"]);

    $fidelidadeFilialController = new FidelidadeFilialController();
    $retorno = $fidelidadeFilialController->remove($fidelidade);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status'        => 200,
            'message'       => "SUCCESS",
            'result'        => "Fidelidade Removida!",
            'token'         => $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

// Consumo do APP

$app->post('/app/cliente/login', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setEmail($data["email"]);
    $usuario->setSenha($data["senha"]);
    $usuario->setTipoId(3);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->loginApp($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{
        $jwt = setToken($retorno[0]);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'usuario' 		=> $retorno[0],
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/app/cliente/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setNome($data["nome"]);
    $usuario->setEmail($data["email"]);
    $usuario->setSenha($data["senha"]);
    $usuario->setFotoPerfil($data["fot64"]);
    $usuario->setStatus(1);
    $usuario->setTipoId(3);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->insert($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{
        $jwt = setToken($retorno[0]);
        $res = array(
            'status'        => 200,
            'message'       => "SUCCESS",
            'result'        => "Usuário cadastrado e o login foi realizado!",
            'usuario'       => $retorno[0],
            'token'         => $jwt
        );

        return $response->withJson($res, $res[status]);

    }

});

$app->post('/app/cliente/update', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setId($auth['token']->data->user_id);
    $usuario->setNome($data["nome"]);
    $usuario->setCpf($data["cpf"]);
    $usuario->setEmail($data["email"]);
    $usuario->setSenha($data["senha"]);
    $usuario->setTelefone($data["telefone"]);
    $usuario->setData($data["dataNasc"]);
    //$usuario->setFotoPerfil($data["foto"]);


    $usuarioControle = new UsuarioController();
    $retorno = $usuarioControle->update($usuario);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($retorno[0]);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Usuário atualizado.",
            'usuario' 		=> $retorno[0],
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/app/filial/list', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Controller/EmpresaFilialController.php';

    $lat = $data["latitude"];
    $long = $data["longitude"];
    $search = $data["search"];
    $fidelidade = $data["onlyFidelidade"];

    $empresaController = new EmpresaFilialController();
    $retorno = $empresaController->listApp($lat, $long, $search, $fidelidade);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Filiais Encontradas!",
            'qtd'           => count($retorno),
            'filiais' 		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/app/menu/list', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/MenuFilialItens.php';
    require_once 'Controller/MenuFilialItensController.php';

    $menu = new MenuFilialItens();
    $menu->setFilialId($data["filial_id"]);
    $menu->setNome($data["search"]);
    $menu->setStatus('true');

    $menuController = new MenuFilialItensController();
    $retorno = $menuController->listAll($menu);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Menu Encontrado!",
            'menu'  		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->get('/app/session', function(Request $request, Response $response, $args) {

    $auth = auth($request);
    if($auth["status"] != 200){
        return $response->withJson($auth, $auth["status"]);
        die;
    }
    //Carregando libs do pagSeguro
    \PagSeguro\Library::initialize();
    \PagSeguro\Library::cmsVersion()->setName("ImHungry")->setRelease("1.0.0");
    \PagSeguro\Library::moduleVersion()->setName("ImHungry")->setRelease("1.0.0");

    // Inicializando Session
    try {
        $sessionCode = \PagSeguro\Services\Session::create(
            \PagSeguro\Configuration\Configure::getAccountCredentials()
        );
        return $response->withJson(['status' => 200, 'sessionId' => $sessionCode->getResult()]);
    } catch (Exception $e) {
        die($e->getMessage());
        $res = array('status' => 404, 'message' => "ERROR", 'result' => 'Erro ao consultar PagSeguro!');
        return $response->withJson($res, $res["status"]);
    }

});

$app->post('/app/checkout', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }

    require_once 'Basics/CheckoutItens.php';
    require_once 'Controller/CheckoutItensController.php';

    $array_itens = $data['item_id'];
    $array_qtd = $data['item_qtd'];
    $token = $data['token'];
    $hash = $data['hash'];
    $user_id = $auth['token']->data->user_id;
    $cartao_id = $data['cartao_id'];

    $checkoutController = new CheckoutItensController();
    $retorno = $checkoutController->generate($array_itens, $array_qtd, $token, $hash, $user_id, $cartao_id);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Compra realizada, verifique o status do pagamento!",
            'code'        	=> $retorno['code'],
            'reference'  	=> $retorno['reference'],
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->get('/app/checkout', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }

    require_once 'Basics/CheckoutItens.php';
    require_once 'Controller/CheckoutItensController.php';

    $user_id = $auth['token']->data->user_id;

    $checkoutController = new CheckoutItensController();
    $retorno = $checkoutController->appListAll($user_id);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{
        $jwt = setToken($retorno[0]);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'checkouts' 	=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }



});

$app->post('/app/notification', function(Request $request, Response $response, $args) {

    $data = $request->getParsedBody();

    \PagSeguro\Library::initialize();
    \PagSeguro\Library::cmsVersion()->setName("ImHungry")->setRelease("1.0.0");
    \PagSeguro\Library::moduleVersion()->setName("ImHungry")->setRelease("1.0.0");

    try {
        if (\PagSeguro\Helpers\Xhr::hasPost()) {
            $notification = \PagSeguro\Services\Transactions\Notification::check(
                \PagSeguro\Configuration\Configure::getAccountCredentials()
            );

        } else {
            throw new \InvalidArgumentException($_POST);
        }

        $code = $notification->getCode();
        $status = $notification->getStatus();
        $referencia = $notification->getReference();
        $disponivel = $notification->getEscrowEndDate();
        $lastEventDate = $notification->getLastEventDate();

        $delimiter = explode("T", $lastEventDate);
        $date = $delimiter[0];
        $delimiter = explode(".", $delimiter[1]);
        $time = $delimiter[0];
        $lastEventDate = $date." ".$time;

        if (!is_null($disponivel)){
            $delimiter = explode("T", $disponivel);
            $date = $delimiter[0];
            $delimiter = explode(".", $delimiter[1]);
            $time = $delimiter[0];
            $disponivel = $date." ".$time;
        }

        require_once 'Basics/CheckoutItens.php';
        require_once 'Controller/CheckoutItensController.php';

        $checkoutController = new CheckoutItensController();
        $retorno = $checkoutController->notification($code, $status, $referencia, $disponivel, $lastEventDate);

        if ($retorno['status'] == 500){
            return $response->withJson($retorno, $retorno[status]);
            die;
        }else{

            $res = array(
                'status' 		=> 200,
                'message' 		=> "SUCCESS",
                'result' 		=> "Notificação recebida com sucesso! Dados da transação alterados.",
                'linhas'        => $retorno,
                'trans_status'  => $status,
                'code'        	=> $code
            );

            return $response->withJson($res, $res[status]);

        }

    } catch (Exception $e) {
        die($e->getMessage());
    }

});

$app->post('/app/checkout/status', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }

    require_once 'Basics/StatusPagSeguro.php';
    require_once 'Controller/CheckoutItensController.php';

    $ref = $data['referencia'];
    $user_id = $auth['token']->data->user_id;

    $checkoutController = new CheckoutItensController();
    $retorno = $checkoutController->consult($ref, $user_id);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        $statusPagSeguro = new StatusPagSeguro();
        $statusPagSeguro->setCode($retorno[0]->checkout_status);
        $status = $statusPagSeguro->getStaus();

        $jwt = setToken($auth['token']->data);
        $res = array(
            'status' 		=> 200,
            'message' 		=> "SUCCESS",
            'result' 		=> "Checkout localizado",
            'code'        	=> $status['code'],
            'significado'  	=> $status['significado'],
            'explicacao'  	=> $status['explicacao'],
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});


function setToken($obj){
    //Gerar TOKEN
    $tokenId    = base64_encode(mcrypt_create_iv(32));
    $issuedAt   = time();
    $notBefore  = $issuedAt + 10;  //Adicionado 10 segundos
    $expire     = $notBefore + 8640000; // Válido por 100 dias(Para testes) Tempo calculado em segundos
    $serverName = 'http://rafafreitas.com/'; /// Nome do seu domínio
    ///
    $data = [
        'iat'  => $issuedAt,         // Emitido em: hora em que o token foi gerado
        'jti'  => $tokenId,          // Json Token Id: Um identificador exclusivo para o token
        'iss'  => $serverName,       // Emissor
        'nbf'  => $notBefore,        // Nãp antes
        'exp'  => $expire,           // Expirar
        'data' => $obj  // Dados relacionados ao usuário registrado

    ];

    $secretKey = SECRET_KEY;
    /// Here we will transform this array into JWT:
    $jwt = JWT::encode(
        $data, //Data to be encoded in the JWT
        $secretKey,
        ALGORITHM
    );
    //$unencodedArray = ['token'=> $jwt];
    return $jwt;
}

function auth($request) {
	$authorization = $request->getHeaderLine("Authorization");
	
	if (trim($authorization) == "") {
		return array('status' => 500, 'message' => 'ERROR', 'result' => 'Token não informado');
	} else {
		try {
            JWT::$leeway = 60; 
			$token = JWT::decode($authorization, SECRET_KEY, array('HS256'));
			return array('status' => 200, 'token' => $token);
		} catch (Firebase\JWT\ExpiredException $ex) {
            return array(
                'status' => 401,
                'result' => 'Acesso não autorizado',
                'message' => $ex->getMessage()
            );
        }

//        catch (Firebase\JWT\ExpiredException $e) {
//            $e->getMessage();
//            die;
//            return array('status' => 401, 'message' => 'Acesso não autorizado');
//        }
	}
}

function luhn_check($number) {
	
	// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
	$number=preg_replace('/\D/', '', $number);

	// Set the string length and parity
	$number_length=strlen($number);
	$parity=$number_length % 2;

	// Loop through each digit and do the maths
	$total=0;
	for ($i=0; $i<$number_length; $i++) {
	$digit=$number[$i];
	// Multiply alternate digits by two
	if ($i % 2 == $parity) {
		$digit*=2;
		// If the sum is two digits, add them together (in effect)
		if ($digit > 9) {
		$digit-=9;
		}
	}
	// Total up the digits
	$total+=$digit;
	}

	// If the total mod 10 equals 0, the number is valid
	if ($total % 10 == 0) {
		return  true;
	} else {
		return false;
	} 
}

function sendMail($html, $email, $nome) {
    
        $mail = new PHPMailer(true);
        $mail->IsSMTP(); // Define que a mensagem será SMTP
        $mail->Host = "mail.test.com.br"; // Endereço do servidor SMTP
        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
        $mail->Username = 'contato@test.com.br'; // Usuário do servidor SMTP
        $mail->Password = 'eG^E~e}9IO;{'; // Senha do servidor SMTP
        $mail->From = "app@test.com.br"; // Seu e-mail
        $mail->FromName = "API - I'm Hungry"; // Seu nome
        $mail->IsHTML(true); // Define que o e-mail será enviado como HTML	
        $mail->AddAddress($email, $nome);
        $mail->Subject  = "I'm Hungry API - Mensagem Enviada via API"; // Assunto da mensagem
        $mail->Body = $html;
        $mail->Send();
}

function mask($val, $mask){
	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++) {
	  if($mask[$i] == '#') {
	    if(isset($val[$k])) {
	    $maskared .= $val[$k++];
	    }
	  } else{
	    if(isset($mask[$i])) {
	      $maskared .= $mask[$i];
	    }
	  }
	}
	return $maskared;
}

function validaCPF($cpf = null) {
    
       // Verifica se um número foi informado
       if(empty($cpf)) {
           return false;
       }
    
       // Elimina possivel mascara
       $cpf = ereg_replace('[^0-9]', '', $cpf);
       $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
       // Verifica se o numero de digitos informados é igual a 11 
       if (strlen($cpf) != 11) {
           return false;
       }
       // Verifica se nenhuma das sequências invalidas abaixo 
       // foi digitada. Caso afirmativo, retorna falso
       else if ($cpf == '00000000000' || 
           $cpf == '11111111111' || 
           $cpf == '22222222222' || 
           $cpf == '33333333333' || 
           $cpf == '44444444444' || 
           $cpf == '55555555555' || 
           $cpf == '66666666666' || 
           $cpf == '77777777777' || 
           $cpf == '88888888888' || 
           $cpf == '99999999999') {
           return false;
        // Calcula os digitos verificadores para verificar se o
        // CPF é válido
        } else {   
            
           for ($t = 9; $t < 11; $t++) {
                
               for ($d = 0, $c = 0; $c < $t; $c++) {
                   $d += $cpf{$c} * (($t + 1) - $c);
               }
               $d = ((10 * $d) % 11) % 10;
               if ($cpf{$c} != $d) {
                   return false;
               }
           }
    
           return true;
       }
}

function getNameFile(UploadedFile $uploadedFile){

    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    return $filename;
}

function moveUploadedFile($directory, UploadedFile $uploadedFile, $filename){

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

}

$app->run();