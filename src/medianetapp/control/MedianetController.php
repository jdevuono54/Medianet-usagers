<?php


namespace medianetapp\control;
use medianetapp\model\User as user;

use medianetapp\model\Borrow;
use medianetapp\model\Document;
use medianetapp\model\SignupRequest;
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

    public function viewSignupRequest(){
        $view = new MedianetView(null);
        $view->render("signup_request");
    }

    public function addSignupRequest(){
      if(isset($_REQUEST['txtName']) && isset($_REQUEST['txtMail']) && isset($_REQUEST['txtPassword1'])
      && isset($_REQUEST['txtPassword2']) && isset($_REQUEST['txtPhone'])) {

          /*Valeurs filtrés*/
          $name = strip_tags(trim($_REQUEST['txtName']));
          $mail = strip_tags(trim($_REQUEST['txtMail']));
          $password1 = $_REQUEST['txtPassword1'];
          $password2 = $_REQUEST['txtPassword2'];
          $phone = strip_tags(trim($_REQUEST['txtPhone']));

          if(User::where('mail','=',$mail)->first() or SignupRequest::where('mail','=',$mail)->first()) {
              $view = new MedianetView(["error_message"=>"L'adresse email existe déja!"]);
              $view->render("signup_request");
          }
          else {
              if($password1 != $password2) {
                  $view = new MedianetView(["error_message"=>"Les champs de mot de passe ne sont pas identiques!"]);
                  $view->render("signup_request");
              }
              else {
                  $signupRequest = new SignupRequest();
                  $signupRequest->name = $name;
                  $signupRequest->mail = $mail;
                  $signupRequest->password = $this->hashPassword($password1);
                  $signupRequest->phone = $phone;
                  $signupRequest->save();
                  Router::executeRoute("home");
              }
          }
      }
    }

    protected function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }


}
