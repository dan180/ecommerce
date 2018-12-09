<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;


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
	$user = new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;



});

//ROTA DE TELA visualizar usuario
$app->get("/admin/users/:iduser",function($iduser){

	User::verifyLogin();//verificar se esta logado e acesso ADM	
	$user = new User();
    $user->get((int)$iduser);
    $page = new PageAdmin();
    $page->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));

});

//rotas para salvar no BD
$app->post("/admin/users/create", function(){

	User::verifyLogin();	
	//var_dump($_POST);
	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0; // verificar se o inadmin foi definido o valor ´1 se nao o valor é 0

	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12
 	]);

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;
});


//rotas para salvar a edição
$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();
	header("Location: /admin/users");
	exit;
});

?>