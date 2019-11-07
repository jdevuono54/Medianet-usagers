<?php


namespace medianetapp\control;
use medianetapp\model\User as user;

use medianetapp\model\Borrow;
use medianetapp\model\Document;
use medianetapp\view\MedianetView;
use mf\router\Router;

class MedianetController extends \mf\control\AbstractController
{
    public function __construct(){
        parent::__construct();
    }

    public function viewHome(){
        $vue = new MedianetView(null);
        $vue->render("home");
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