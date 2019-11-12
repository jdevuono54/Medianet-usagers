<?php

namespace medianetapp\view;

use medianetapp\auth\MedianetAuthentification;
use medianetapp\model\Kind;
use medianetapp\model\Type;
use mf\router\Router;
use mf\utils\HttpRequest;

class MedianetView extends \mf\view\AbstractView
{
    public function __construct( $data ){
        parent::__construct($data);
    }

    private function renderHeader(){
        $httpRequet = new HttpRequest();
        $src = $httpRequet->root;
        $router = new Router;
        $catalogueRoute = $router->urlFor("catalogue");
        $searchRoute = $router->urlFor("search");
        $logout = $router->urlFor("logout");
        $checkSignup = $router->urlFor("signup_request");
        $profilRoute = $router->urlFor("user");

        $header = <<<EQT

<a href="$catalogueRoute">
    <h1>Medianet</h1>
</a>

EQT;

        if(isset($_SESSION['access_level']) && $_SESSION['access_level'] === MedianetAuthentification::ACCESS_LEVEL_USER) {
            $header .= <<<EQT
<nav>
    <a href="$catalogueRoute">
        <img src="${src}/html/img/icons/home.png" alt="catalogue">
    </a>
    <a href="$searchRoute">
        <img src="${src}/html/img/icons/search.png" alt="recherche">
    </a>
    <a href="$profilRoute">
        <img src="${src}/html/img/icons/profil.png" alt="profil">
    </a>
    <a href="$logout">
        <img src="${src}/html/img/icons/exit.png" alt="logout">
    </a>
</nav>
EQT;
        }else{
            $header .= <<<EQT
<nav>
    <a href="$checkSignup">
        <img src="${src}/html/img/icons/signup.png" alt="signupCheck">
    </a>
</nav>
EQT;
        }


        return $header;
    }
    private function renderFooter(){
        return "Copyright@2019";
    }
    private function renderCatalogue(){
        $documents = $this->data["documents"];
        $httpRequet = new HttpRequest();
        $src = $httpRequet->root;
        $blocsDocuments="";
        $btn_suivant="";
        $btn_retour="";

        if(!isset($this->data["search"]) || $this->data["search"] == false){
            if(isset($this->data["next"]) && $this->data["next"]== true){
                $btn_suivant = "<a href='".$src."/main.php/catalogue?page=".$this->data["next"]."'>Suivant</a>";
            }
            if(isset($this->data["return"]) && $this->data["return"]== true){
                $btn_retour = "<a href='".$src."/main.php/catalogue?page=".$this->data["return"]."'>Retour</a>";
            }
        }

        foreach ($documents as $document){

            $blocsDocuments .= <<<EQT
<div class='document'>
    <a href="document?reference=$document->reference">
        <div class='vignette'>
            <img src="${src}/html/img/small/$document->picture" alt="$document->picture">
        </div>
        $document->title
    </a>
   </div>
EQT;
        }
        $html = <<<EQT
            <div class="catalogue">
                <div id="titreDoc">
                <h1>Catalogue</h1>
            </div>
                ${blocsDocuments}
            <div class="pagination">
                ${btn_retour}
                ${btn_suivant}
            </div>
</div>
EQT;
        return $html;
    }

    private function renderFicheDocument(){
        $chemin = new HttpRequest();
        $fiche = $this->data;
        $dispo = $fiche->state->name;
        $type = $fiche->type->name;
        $kind = $fiche->kind->name;
        $picture = $fiche->picture;

        /*<img alt='home' src='$chemin->root/html/img/large/$picture'>*/

        $test = $chemin->root."/html/img/large/".$picture;

        $ficheDocument = <<<EQT

        <section>
            <article id="articleDoc">
            <div id="titreDoc">
                        <h1>Fiche détaillée : </h1>
            </div>
            
            <section id="sectionImg">
            
                <picture id="imageDoc">
                     <source media="(min-width: 992px)" srcset="$chemin->root/html/img/large/$picture">
                     <source media="(min-width: 600px)" srcset="$chemin->root/html/img/medium/$picture">
                     <source media="(min-width: 0px)" srcset="$chemin->root/html/img/small/$picture">
                     <img alt='home' src='$chemin->root/html/img/medium/$picture'>
                </picture>
            
            </section>

                    
                    <div id="detailDoc">
                    
                         <h4 id="titre">$fiche->title </h4>
                         <h4 class="description"> Description :</h4> 
                             <div class="description">
                             $fiche->description
                            </div>
                            
                                
                         <div id="type">Format : ${type}</div>
                         <div id="genre">Genre : ${kind} </div>
                         <div id="dispo">Disponibilité : ${dispo}</div>
                            
                         
                    
                    </div>

               
            
            </article>
        </section>

EQT;
        return $ficheDocument;
    }
    public function renderLogin(){
        $error_message = $this->data["error_message"];
        $html = <<< EQT
<div id="login_form">
    <form method="POST" action="check_login">
        <div class="mail_login_form">
            <label>Adresse mail :</label>
            <input type="email" name="mail" required>
        </div>
        <div>
            <label>Mot de passe :</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <input type="submit" value="Connexion" class="validate_btn">
            <p class="error_message">${error_message}</p>
        </div>
    </form>
</div>
EQT;
        return $html;
    }
  

    /*Function that return the search view body*/
    private function renderSearchForm(){
        $errorMessage="";
        $typeOptions="";
        $kindOptions="";
        /*Get Types and kinds*/
        $types = Type::all();
        $kinds = Kind::all();

        /*Build the Type select*/
        foreach ($types as $type){
            $typeOptions.="<option value='{$type->id}'>{$type->name}</option>";
        }

        /*Build the Kind select*/
        foreach ($kinds as $kind){
            $kindOptions.="<option value='{$kind->id}'>{$kind->name}</option>";
        }

        return "<h1 class='nomPage'>Rechercher dans le catalogue</h1>
                 <div id='search_form'>
                    <form method='post' action ='to_search' class='container'>
                    
                        <div id='key'>
                            <label for='txtKey'>Mot clé :</label>
                            <input type = 'text' name = 'txtKey'/>
                        </div>
                        
                        <div id='type'>
                            <label for='txtType' class='doc'>Type :</label>
                            <select name='txtType' required>{$typeOptions}</select>
                        </div>
                        
                        <div id='kind'>
                            <label for='txtKind' class='doc'>Genre :</label>
                            <select name='txtKind' required>{$kindOptions}</select>
                        </div>
                        
                        <div id='btn'>
                            <input type='submit' value='Rechercher' class='validate_btn'/>
                        </div>.$errorMessage
                        
                    </form>
                </div>";
    }


    protected function renderBody($selector=null){
        $header = $this->renderHeader();
        $footer = $this->renderFooter();

        switch ($selector){
            case "catalogue":
                $content = $this->renderCatalogue();
                break;
            case "login":
                $content = $this->renderLogin();
                break;
            case "search":
                $content=$this->renderSearchForm();
                break;
            case "viewDocument":
                $content = $this->renderFicheDocument();
                break;
            case "signup_request":
                $content = $this->renderFormSignupRequest();
                break;
            case "update_profil":
                $content = $this->renderUser();
                break;
            case "user":
                $content = $this->renderUser();
                break;
        }


        $body = <<< EQT
            <header>
                ${header}
            </header>

                    ${content}

            <footer>
                ${footer}
            </footer>
EQT;
        return $body;
    }



    private function renderFormSignupRequest(){
        $errorMessage="";
        if(isset($this->data["error_message"])){
            $errorMessage="<p class='error_message'>{$this->data["error_message"]}</p>";
        }
        return "<h1 class='nomPage'>Demande d'inscription</h1>
                 <div id='signup_form'>
                    <form method='post' action ='add_signup_request'>
                    <div>
                        <label for='txtName'>Nom :</label>
                        <input type = 'text' name = 'txtName' required/>
                    </div>
                    <div>
                        <label for='txtMail'>Mail :</label>
                        <input type = 'email' name = 'txtMail' required/>
                    </div>
                    <div>
                        <label for='txtPassword1'>Mot de passe :</label>
                        <input type = 'password' name = 'txtPassword1' required/>
                    </div>
                    <div>
                        <label for='txtPassword2'>Répeter le mot de passe :</label>
                        <input type = 'password' name = 'txtPassword2' required/>
                    </div>
                    <div>
                        <label for='txtPhone'>Tel (Format: 0123456789) :</label>
                        <input type = 'tel' name = 'txtPhone' pattern='[0-9]{10}' required/>
                    </div>
                    <input type='submit' value='Enregistrer' value = 'Enregistrer'/>$errorMessage
                    </form>
                </div>";
    }

    private function renderUser(){

        $user = $this->data;

        $html = "<div class='divProfil' ><h1 class='h1'>Profil :</h1>
    
              <form class='formgl' action='update_profil' method='post'>
    <div class='formProfil'>
    <label for='name'>Name  : </label><br>
    <input type='text' name='txtName' id='name' value=".$user->name." >
    </div><br>
    <div class='formProfil'>
    <label for='email'>E-Mail : </label><br>
    <input type='email' name='txtMail' id='email' value =".$user->mail.">
    </div><br>
    <div class='formProfil'>
    <label for='phone'>Phone: </label><br>
    <input type='phone' name='txtPhone' id='email' value =".$user->phone.">
    </div><br>
    <div class='formProfil'>
    <label for='date'>Date d'adhésion : </label><br>
    <input type='date' name='txtDate' id='email' value =".$user->membership_date.">
    </div><br>
    <div class='formProfil'>
    <input type='submit' id='btnvalider' value='Save!'>
    </div><br>
    </form>
    
                <div class='divProDoc' id='divProfil'>
                      <h1 class='h2'>Les documents empruntés :</h1>
                      <table class='tableprofil' id='tableProfil'>
                  <thead>
                    <tr>
                      <th>Réference </th>
                      <th>Titre</th>
                      <th>Description </th>
                      <th>Date d'emprunt  </th>
                      <th>Date prévu du retour </th>
                      </tr>
                  </thead>
                  <tbody>
    
              ";

        $borrows = $user->Emprunts()->where("real_return_date","=",null)->get();

        foreach ($borrows as $borrow){

            $html .= $this->constructBlocBorrow($borrow,true);

        }

        $html .= "</tbody></table></div></div>";




        return $html;

    }
    private function constructBlocBorrow($borrow,$closeborrowdiv){



        $html ="
                           <tr>
                            <td data-label='Réference'> ".$borrow->document()->first()->reference."</td>
                            <td data-label='Titre'> ".$borrow->document()->first()->title."</td>
                            <td data-label='Description'>".$borrow->document()->first()->description."</td>
                            <td data-label='Date d'emprunt'> ".$borrow->borrow_date."</td>
                            <td data-label='Date prévu du retour'> ".$borrow->return_date."</td>
                          </tr>
    ";



        return $html;
    }
}
