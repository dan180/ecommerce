<?php 
session_start();
require_once("vendor/autoload.php");


use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index");

});

//Chama tela admin
$app->get('/admin', function() {

	User::verifyLogin();//verificar se esta logado e acesso ADM
    
	$page = new PageAdmin();
	$page->setTpl("index");

});

//Chama tela de login
$app->get('/admin/login', function() {
    
	$page = new PageAdmin([
		"header"=>false,   //esconde cabeçalho
		"footer"=>false   //esconde rodapé
	]);
	$page->setTpl("login");

});

//Valida usuario
$app->post('/admin/login', function() {
    
	User::login($_POST["login"], $_POST["password"]);

	header("location: /admin");

	exit;
});

// delosga usuario
$app->get('/admin/logout', function(){

	User::logout();
	header("Location: /admin/login");
	exit;

});

//ROTA DE TELA Listar todos os usuarios
$app->get("/admin/users",function(){

	User::verifyLogin();//verificar se esta logado e acesso ADM

	$users = User::listALL(); //Chama função para receber lista de usuarios

	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users" => $users

	));

});

//ROTA DE TELA Criar usuario
$app->get("/admin/users/create",function(){

	User::verifyLogin();//verificar se esta logado e acesso ADM

	$page = new PageAdmin();
	
	$page->setTpl("users-create");

});

//rota para apagar o usuario
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

});

//ROTA DE TELA visualizar usuario
$app->get("/admin/users/:iduser",function($iduser){

	User::verifyLogin();//verificar se esta logado e acesso ADM

	$page = new PageAdmin();
	
	$page->setTpl("users-update");

});


//rotas para salvar no BD
$app->post("/admin/users/create", function(){

	User::verifyLogin();	
	//var_dump($_POST);
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0; // verificar se o inadmin foi definido o valor ´1 se nao o valor é 0

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;




});

//rotas para salvar a edição
$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

});



$app->run();

 ?>