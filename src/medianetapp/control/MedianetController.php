<?php


namespace medianetapp\control;
use medianetapp\model\User as user;

use medianetapp\model\Borrow;
use medianetapp\model\Document;
use medianetapp\view\MedianetView;
use mf\router\Router;
use mf\auth\exception\AuthentificationException;
use medianetapp\auth\MedianetAuthentification;

class MedianetController extends \mf\control\AbstractController
{
    public function __construct(){
        parent::__construct();
    }

    public function viewCatalogue(){
        if(isset($_SESSION['access_level']) === MedianetAuthentification::ACCESS_LEVEL_USER){
            $vue = new MedianetView(null);
            $vue->render("catalogue");
        }
        else{
            Router::executeRoute("login");
        }
    }

    public function viewLogin(){
        $vue = new MedianetView(null);
        $vue->render("login");
    }

    public function checkLogin(){
        $auth = new MedianetAuthentification();

        try{
            $auth->loginUser($_POST["mail"],$_POST["password"]);

            $vue = new MedianetView(null);
            $vue->render("catalogue");

        }catch (AuthentificationException $e){
            $vue = new MedianetView(["error_message" => $e->getMessage()]);
            $vue->render("login");
        }
    }



    /* PERMET DE CREE UN MESSAGE D'ERREUR ET D'AFFICHER LES SOURCES DE L'ERREUR */
    private function errorMessage($startMessage, $error_target = []){
        $message_erreur = " ".$startMessage." ";

        if($error_target != []){
            foreach ($error_target as $target){
                $message_erreur .= $target.",";
            }
            $message_erreur = substr($message_erreur, 0, -1);
        }

        return $message_erreur;
    }
}