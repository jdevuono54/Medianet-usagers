<?php


namespace medianetapp\control;
use medianetapp\model\Kind;
use medianetapp\model\Type;
use medianetapp\model\User as user;

use medianetapp\model\Borrow;
use medianetapp\model\Document;
use medianetapp\model\SignupRequest;
use medianetapp\view\MedianetView;
use mf\auth\Authentification;
use mf\router\Router;
use mf\auth\exception\AuthentificationException;
use medianetapp\auth\MedianetAuthentification;
use Illuminate\Support\Facades\DB;

class MedianetController extends \mf\control\AbstractController
{
    public function __construct(){
        parent::__construct();
    }

    public function viewCatalogue(){
        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER){
            $next = false;
            $return = false;

            if(!isset($_GET["page"])){
                $lastDocuments = Document::select()->orderBy('id', 'DESC')->limit(6)->get();
                $next=2;
            }
            else{
                $offset = 6*($_GET["page"]-1);
                $lastDocuments = Document::select()->orderBy('id', 'DESC')->limit(6)->offset($offset)->get();

                $nbDoc = Document::select()->count();
                if($nbDoc > $offset+6){
                    $next=$_GET["page"]+1;
                }
                if($_GET["page"] > 1){
                    $return=$_GET["page"]-1;
                }
            }
            $vue = new MedianetView(["documents" => $lastDocuments,"next"=>$next,"return"=>$return]);
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

    public function logout(){
        $auth = new MedianetAuthentification();
        $auth->logout();
        Router::executeRoute("login");
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
                $idType=$_REQUEST["txtType"];
                $idKind = $_REQUEST["txtKind"];
                /*Filtred input*/
                $filtredKey = filter_var($_REQUEST["txtKey"], FILTER_SANITIZE_STRING);

                /*Get documents*/
                $documents=Document::where(function ($query) use ($filtredKey) {
                    $query->where('title', 'like', '%'.$filtredKey.'%')
                        ->orWhere('description', 'like','%'.$filtredKey.'%');
                })
                    ->where('id_Type', '=',$idType)->where('id_Kind','=',$idKind)->get();
                $view = new MedianetView(["documents" => $documents,"search" => true]);
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
        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER) {

            if (isset($_GET["reference"])) {

                $ficheDoc = Document::select()->where("reference", "=", $_GET["reference"])->first();

                if ($ficheDoc == null) {

                    Router::executeRoute("catalogue");
                } else {
                    $vue = new MedianetView($ficheDoc);
                    $vue->render("viewDocument");
                }


            } else {

                Router::executeRoute("catalogue");
            }

        }
        else{
            Router::executeRoute("login");
        }
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
