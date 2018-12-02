<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use  \Hcode\Model;

class User extends Model
{
	const SESSION = "User";

	public static function login($login, $password) // REALIZA LOGIN
	{

		$sql = new Sql(); // abre conexão com o banco

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login 
		)); //Seleciona da tabela tb_users onde deslogin é igual ao login digitado

		if(count($results) === 0)
		{
			 throw new \Exception("Usuário inexistente ou senha inválida.");
			 
		}

		$data = $results[0]; // 
		
		if (password_verify($password, $data["despassword"]) === true)
		{
			$user = new User();

			$user->setData($data);

			$_SESSION[User::SESSION] = $user->getValues();


		}
		else
		{
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}
	}

	public static function verifyLogin($inadmin = true)// VERIFICA SE ESTA LOGADO
	{

		if (
			!isset($_SESSION[User::SESSION])
			||
			$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
		
		) {
			header("Location: /admin/login");
			
		  }
	}

	public static function logout() //LOGOUT
	{
		$_SESSION[User::SESSION] = NULL;
	}

	public static function listALL() //LISTA USUARIOS
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

	public function save()
	{
		$sql = new Sql();

	$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)",array(
		":desperson"=>$this->getdesperson(),
		":deslogin"=>$this->getdeslogin(),
		":despassword"=>$this->getdespassword(),
		"desemail"=>$this->getdesemail(),
		"nrphone"=>$this->getnrphone(),
		"inadmin"=>$this->getinadmin()
	));
	var_dump($results);
	$this->setData($results[0]);

	}

}
?>