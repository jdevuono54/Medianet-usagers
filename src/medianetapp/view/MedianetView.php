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
    <a href="$logout">
        <img src="${src}/html/img/icons/profil.png" alt="profil">
    </a>
    <a href="$logout">
        <img src="${src}/html/img/icons/exit.png" alt="logout">
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
                     <source media="(min-width: 325px)" srcset="$chemin->root/html/img/medium/$picture">
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
}