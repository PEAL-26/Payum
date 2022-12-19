<?php

namespace App\Generics;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Psr\Log\LoggerInterface;

class ApiController extends ResourceController
{
    protected $request;
    protected $response;
    protected $format    = 'json';

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }


    protected function responseJSON($data = null, $errors = [], $code = 200)
    {
        switch ($code) {
            case '200':
                $codeText = 'OK';
                $codeDescription = 'O recurso solicitado foi processado e retornado com sucesso.';
                $sucesso = true;
                break;
            case '201':
                $codeText = 'Created';
                $codeDescription = 'O recurso informado foi criado com sucesso.';
                $sucesso = true;
                break;
            case '400':
                $codeText = 'Bad Request';
                $codeDescription = 'Não foi possível interpretar a requisição. Verifique a sintaxe das informações enviadas.';
                $sucesso = false;
                break;
            case '401':
                $codeText = 'Unauthorized';
                $codeDescription = 'A chave da API está desativada, incorreta ou não foi informada corretamente. Consulte a sessão sobre autenticação da documentação.';
                $sucesso = false;
                break;
            case '402':
                $codeText = 'Payment Required';
                $codeDescription = 'A chave da API está correta, porém a conta foi bloqueada por inadimplência. Neste caso, acesse o painel para verificar as pendências.';
                $sucesso = false;
                break;
            case '403':
                $codeText = 'Forbidden';
                $codeDescription = 'O acesso ao recurso não foi autorizado. Este erro pode ocorrer por dois motivos: (1) Uma conexão sem criptografia foi iniciada. Neste caso utilize sempre HTTPS. (2) As configurações de perfil de acesso não permitem a ação desejada. Consulte as configurações de acesso no painel de administração.';
                $sucesso = false;
                break;
            case '404':
                $codeText = 'Not Found';
                $codeDescription = 'O recurso solicitado ou o endpoint não foi encontrado.';
                $sucesso = false;
                break;
            case '406':
                $codeText = 'Not Acceptable';
                $codeDescription = 'O formato enviado não é aceito. O cabeçalho Content-Type da requisição deve contar obrigatoriamente o valor application/json para requisições do tipo POST e PUT.';
                $sucesso = false;
                break;
            case '422':
                $codeText = 'Unprocessable Entity';
                $codeDescription = 'A requisição foi recebida com sucesso, porém contém parâmetros inválidos. Para mais detalhes, verifique o atributo errors no corpo da resposta.';
                $sucesso = false;
                break;
            case '429':
                $codeText = 'Too Many Requests';
                $codeDescription = 'O limite de requisições foi atingido. Verifique o cabeçalho Retry-After para obter o tempo de espera (em segundos) necessário para tentar novamente.';
                $sucesso = false;
                break;
            case '500':
                $codeText = 'Internal Server Error';
                $codeDescription = 'Ocorreu uma falha no sistema. Por favor, entre em contacto com o suporte.';
                $sucesso = false;
                break;
        }

        $array['success'] = $sucesso;

        if ($sucesso)
            $array['message'] = $codeDescription;
        else {
            $array['errors'] = [$codeDescription];
            
            if (is_array($errors) && count($errors))
                $array['errors'] = $errors;
        }

        if ($data) $array['data'] = $data;

        return $this->respond($array, $code, $codeText);
    }
}
