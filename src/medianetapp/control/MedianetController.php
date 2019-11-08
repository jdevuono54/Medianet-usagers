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

    public function viewUser(){
             if(isset($_SESSION["id"]) && filter_var($_SESSION["id"],FILTER_VALIDATE_INT)){
        $user = User::where("id","=",$_SESSION["id"])->first();

        if($user != null){
            $vue = new MedianetView($user);
            $vue->render("user");
        }
    }

    else{
      $vue = new MedianetView(null);
      $vue->render("user");
    }
    }


    public function updateProfil(){

      if(isset($_REQUEST['txtName']) && isset($_REQUEST['txtMail']) && isset($_REQUEST['txtPhone'])) {

          /*Valeurs filtrés*/
          $name = strip_tags(trim($_REQUEST['txtName']));
          $mail = strip_tags(trim($_REQUEST['txtMail']));
          $phone = strip_tags(trim($_REQUEST['txtPhone']));

          $user= User::where("id","=",$_SESSION["id"])->first();

          if(User::where('mail','=',$mail)->where('id','<>',$_SESSION["id"])->first()!=$mail){
             $user->name=$name;
             $user->mail=$mail;
             $user->phone=$phone;
             $user->save();
             $vue = new MedianetView($user);
             $vue->render("user");
          }
          else{
                 echo "Mail existe deja !!";
          }




        }

      }
    }
