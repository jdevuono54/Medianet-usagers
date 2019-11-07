<?php


namespace medianetapp\control;
use medianetapp\model\Kind;
use medianetapp\model\Type;
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

    /*Action : View search form*/
    public function viewSearch(){
        $view = new MedianetView(null);
        $view->render("search");
    }

    /*Action : Search*/
    public function search(){
        if(isset($_REQUEST["txtKey"])&&isset($_REQUEST["txtType"])&&isset($_REQUEST["txtKind"])){

            /*Filtred input*/
            $filtredKey=filter_var($_REQUEST["txtKey"],FILTER_SANITIZE_STRING);

            /*Get documents*/
            $documents = Document::where('title','like',$filtredKey.'%')
                ->orWhere('description','like','%'.$filtredKey.'%')
                ->where('id_Type','=',$_REQUEST["txtType"])
                ->where('id_Kind','=',$_REQUEST["txtKind"])
                ->get();
            $view = new MedianetView($documents);
            $view->render("search_result");
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