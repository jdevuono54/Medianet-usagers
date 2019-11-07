<?php


namespace medianetapp\control;
use medianetapp\model\Kind;
use medianetapp\model\Type;
use medianetapp\model\User as user;

use medianetapp\model\Borrow;
use medianetapp\model\Document;
use medianetapp\view\MedianetView;
use mf\auth\Authentification;
use mf\router\Router;
use mf\auth\exception\AuthentificationException;
use medianetapp\auth\MedianetAuthentification;

class MedianetController extends \mf\control\AbstractController
{
    public function __construct(){
        parent::__construct();
    }

    public function viewCatalogue(){
        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER){
            $lastDocuments = Document::select()->orderBy('id', 'DESC')->limit(6)->get();
            $vue = new MedianetView($lastDocuments);
            $vue->render("catalogue");

        }
        else{
            Router::executeRoute("login");
        }
    }
    public function viewLogin(){
        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER){
            $this->viewCatalogue();
        }
        else{
            $vue = new MedianetView(null);
            $vue->render("login");
        }
    }

    public function checkLogin(){
        if(isset($_POST["mail"]) && isset($_POST["password"])){
            $auth = new MedianetAuthentification();

            try{
                $auth->loginUser($_POST["mail"],$_POST["password"]);

                $this->viewCatalogue();

            }catch (AuthentificationException $e){
                $vue = new MedianetView(["error_message" => $e->getMessage()]);
                $vue->render("login");
            }
        }
    }



    /*Action : View search form*/
    public function viewSearch(){
        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER){
            $view = new MedianetView(null);
            $view->render("search");
        }
        else{
            Router::executeRoute("login");
        }
    }
    /*Action : Search*/
    public function search(){
        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER) {

            if (isset($_REQUEST["txtKey"]) && isset($_REQUEST["txtType"]) && isset($_REQUEST["txtKind"])) {

                /*Filtred input*/
                $filtredKey = filter_var($_REQUEST["txtKey"], FILTER_SANITIZE_STRING);

                /*Get documents*/
                $documents = Document::where('title', 'like', $filtredKey . '%')
                    ->orWhere('description', 'like', '%' . $filtredKey . '%')
                    ->where('id_Type', '=', $_REQUEST["txtType"])
                    ->where('id_Kind', '=', $_REQUEST["txtKind"])
                    ->get();
                $view = new MedianetView($documents);
                $view->render("catalogue");
            }
        }
        else{
            Router::executeRoute("login");
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

    public function viewDocument(){


    if(isset($_GET["reference"])){

        $ficheDoc = Document::select()->where("reference", "=", $_GET["reference"])->first();

        if($ficheDoc == null ){

            Router::executeRoute("catalogue");
        }else{
            $vue = new MedianetView($ficheDoc);
            $vue->render("viewDocument");
        }


    }else{

        Router::executeRoute("catalogue");
    }


    }

}