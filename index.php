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

$app->post('/usuario/login', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    require_once 'Basics/Usuario.php';
    require_once 'Controller/UsuarioController.php';

    $usuario = new Usuario();
    $usuario->setEmail($data["email"]);
    $usuario->setSenha($data["senha"]);
    $usuario->setTipoId($data["tipo"]);

    $usuarioController = new UsuarioController();
    $retorno = $usuarioController->login($usuario);

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

$app->post('/usuario/update', function(Request $request, Response $response, $args) {
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

$app->get('/empresa/list', function(Request $request, Response $response, $args) {
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
            'qtd'           => count($retorno),
            'empresas' 		=> $retorno,
            'token'			=> $jwt
        );

        return $response->withJson($res, $res[status]);

    }


});

$app->post('/empresa/insert', function(Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $auth = auth($request);

    if($auth[status] != 200){
        return $response->withJson($auth, $auth[status]);
        die;
    }
    require_once 'Basics/Empresa.php';
    require_once 'Controller/EmpresaController.php';

    $global_dir = $this->get('upload_directory');
    $files = $request->getUploadedFiles();
    $file_brand = $files['foto'];
    $filename = getNameFile($file_brand);

    $empresa = new Empresa();
    $empresa->setNome($data["nome"]);
    $empresa->setTelefone($data["telefone"]);
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
    $empresa->setFotoMarca($filename);

    $empresaController = new EmpresaController();
    $retorno = $empresaController->insert($empresa);

    if ($retorno['status'] == 500){
        return $response->withJson($retorno, $retorno[status]);
        die;
    }else{

        moveUploadedFile($global_dir.DIRECTORY_SEPARATOR."empresa",$file_brand, $filename);

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


function setToken($obj){
    //Gerar TOKEN
    $tokenId    = base64_encode(mcrypt_create_iv(32));
    $issuedAt   = time();
    $notBefore  = $issuedAt + 10;  //Adicionado 10 segundos
    $expire     = $notBefore + 86400; // Válido por 1 dia(Para testes) Tempo calculado em segundos
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
		} catch (Exception $e) {
			return array('status' => 401, 'message' => 'Acesso não autorizado');
		}
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