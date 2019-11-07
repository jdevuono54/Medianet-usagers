<?php
namespace medianetapp\auth;
use mf\auth\Authentification;
use mf\auth\exception\AuthentificationException;
use medianetapp\model\User;

class MedianetAuthentification extends \mf\auth\Authentification {
    const ACCESS_LEVEL_USER  = 1;

    /* constructeur */
    public function __construct(){
        parent::__construct();
    }

    public function loginUser($mail, $password){
        $user = User::where("mail","=",$mail)->first();
        if($user == null){
            throw new AuthentificationException("Adresse mail ou mot de passe incorrect");
        }
        else{
            $auth = new Authentification();
            $auth->login($user->mail,$user->password,$password,self::ACCESS_LEVEL_USER);
        }
    }
}