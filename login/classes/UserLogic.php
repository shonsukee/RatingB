<?php
require_once '../database/dbconnect.php';
require '../../env.php';

class UserLogic{

	/**
	 * ログイン処理
	 * @param string $email
	 * @param string $password
	 * @param bool $result
	 * 
	 */
	public static function login($email, $password){
		//結果
		$result = false;
		//ユーザをemailから検索して取得
		$user = self::getUserByEmail($email);
		
		if(!$user) {
			$_SESSION['msg'] = 'emailが存在しません．新規登録してください．';
			return $result;
		}

		//パスワードの照会
		if(password_verify($password, $user['password'])){
			//ログイン成功
			session_regenerate_id(true);
			$_SESSION['login_user'] = $user;
			$result = true;
			return $result;
		}

		$_SESSION['msg'] = 'パスワードが一致しません';
		return $result;
	
	}

	/**
	 * emailからユーザを取得
	 * @param string $email
	 * @param array|bool $user|false
	 * 
	 */
	public static function getUserByEmail($email){
		
		try{
			$user = "root";
			$pass = "";
			//DBと接続
			try{
				$pdo = new PDO('mysql:host=localhost; dbname=login', $user, $pass,
				array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
				PDO::ATTR_EMULATE_PREPARES => true,)); //インスタンス生成
			} catch(PDOException $error) {
				echo $error->getMessage();
			}
			
			$sql = 'SELECT * FROM registeruser WHERE mail = :EMAIL';
			$stmt = $pdo->prepare($sql); //sqlの準備
			$stmt->bindValue('EMAIL', $email);
			$stmt->execute();
			$user = $stmt->fetch();

			return $user;
		} catch(\Exception $e) {
			echo $e;
			error_log($e, 3, '../error.log');
			return $result;
		}
	}
	
	/**
	 * ログインチェック
	 * @param void
	 * @param bool $result
	 * 
	 */
	public static function checkLogin(){
		$result = false;
		if(isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0){
			return $result = true;
		}
		return $result;
	}
}