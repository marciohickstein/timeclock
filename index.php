<?php

require_once("utils.php");
require_once("autoload.php");
require_once("vendor/autoload.php");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App();

// RESTful API to employees
// Implemented all methods: GET, POST, PUT and DELETE

// Method GET: Get list of all employees
$app->get('/employee/', function (Request $request, Response $response, array $args) use ($app) {
    // Get array of all employees
    $employee = new Employee();
    $data = $employee->getList();

    // Write response http
    writeResponse($response, $request->getParam("format"), $data);
    return $response;
});

// Method GET: Get just one employee
$app->get('/employee/{id}', function (Request $request, Response $response, array $args) use ($app) {
    $data = array();
    if (isset($args['id'])) {
        // Get employee by ID
        $employee = new Employee();
        if ($employee->loadById($args['id'])) {
            $data = array(array(
                "id" => $employee->getId(),
                "nome" => $employee->getName(),
                "occupation" => $employee->getOccupation()
            ));
        }
        else
            $data = getHttpError(6, "Registro " . $args['id'] . " nao encontrado no sistema");
    }

    // Write response http
    writeResponse($response, $request->getParam("format"), $data);
    return $response;
});

// Method DELETE: Remove just one employee
$app->delete('/employee/{id}', function (Request $request, Response $response, array $args) use ($app) {
    $data = array();
    if (isset($args['id'])) {
        // Remove employee by ID
        $id = $args['id'];
        $employee = new Employee();
        if ($employee->loadById($id)) {
            if ($employee->deleteById($id)){
                $data = array(array(
                    "id" => $employee->getId(),
                    "nome" => $employee->getName(),
                    "occupation" => $employee->getOccupation(),
                    "status" => "Registro excluido com sucesso"
                ));
            }
            else 
                $data = getHttpError(3, "Nao foi possivel excluir o registro");
        }
        else 
            $data = getHttpError(2, "Nao foi possivel localizar o registro para remover");
    }
    else 
        $data = getHttpError(1, "Faltou o parametro de identificacao do registro");
    
    // Write response HTTP
    writeResponse($response, $request->getParam("format"), $data);
    return $response;
});

// Method POST: Create one employee
$app->post('/employee/', function (Request $request, Response $response, array $args) use ($app) {

    // Tenta converter os dados vindos para um array
    try{
        // Os dados devem vir no formato JSON!!!
        $strjson = $request->getBody();
        $data = json_decode($strjson, true);
        if ($data == null || count($data) == 0)
            $data = (isset($_POST) && count($_POST) > 0) ? $_POST : array();
    }catch(Exception $e){
        $data = getHttpError(8, "Formato JSON invalido");
    } 

    if  (count($data) > 0){
        // Tenta inserir o registro no sistema
        try {
            $employee = new Employee();
            
            if ($employee->setData($data)){
                if (!$employee->insert())
                    $data = getHttpError(9, $employee->getErrorMessage());
            }
            else
                $data = getHttpError(6, $this->getErrorLoadData());
        } catch (Exception $e) {
            $data = getHttpError(7, "Nao foi possivel inserir os dados");
        }
    }

    writeResponse($response, $request->getParam("format"), $data);
    return $response;
});

// Method PUT: Update one employee
$app->put('/employee/{id}', function (Request $request, Response $response, array $args) use ($app) {
    if (isset($args['id'])) {
        // Tenta converter os dados vindos para um array
        try{
            // Os dados devem vir no formato JSON!!!
            $strjson = $request->getBody();
            $data = json_decode($strjson, true);
            if ($data == null || count($data) == 0)
                $data = (isset($_POST) && count($_POST) > 0) ? $_POST : array();
        }catch(Exception $e){
            $data = getHttpError(8, "Formato JSON invalido");
        } 

        if  (count($data) > 0){
            // Tenta inserir o registro no sistema
            try {
                $employee = new Employee();
                
                if ($employee->loadById($args['id'])){
                    if ($employee->setData($data)){
                        if (!$employee->updateAll())
                            $data = getHttpError(9, $employee->getErrorMessage());
                    }
                    else
                        $data = getHttpError(6, $this->getErrorLoadData());
                }
                else
                    $data = getHttpError(6, "Registro " . $args['id'] . " nao encontrado no sistema");
            } catch (Exception $e) {
                $data = getHttpError(7, "Nao foi possivel inserir os dados");
            }
        }
    }
    else 
        $data = getHttpError(1, "Faltou o parametro de identificacao do registro");

    writeResponse($response, $request->getParam("format"), $data);
    return $response;
});

$app->run();

?>