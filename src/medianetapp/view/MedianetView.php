<?php

namespace medianetapp\view;

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
        return "";
    }
    private function renderFooter(){
        return "";
    }
    private function renderCatalogue(){
        $documents = $this->data;
        $httpRequet = new HttpRequest();
        $src = $httpRequet->root;
        $blocsDocuments="";

        foreach ($documents as $document){

            $blocsDocuments .= <<<EQT
<div class='document'>
    <a href="document?id=$document->id">
        <div class='vignette'>
            <img src="${src}/html/img/small/$document->picture">
        </div>
        $document->title
    </a>
   </div>
EQT;
        }
        $html = <<<EQT
            <div class="catalogue">
                <a href="${src}/main.php/search" class="searchLink">Recherche</a>
                ${blocsDocuments}
</div>
EQT;
        return $html;
    }

    private function renderFicheDocument(){      $chemin = new HttpRequest();
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
                         <h4 id="descriprion"> Description :</h4> 
                             <div id="descriprion">
                             $fiche->description
                            </div>
                            
                                
                         <div id="type">Format : ${type}</div>
                         <div id="genre">Genre : ${kind} </div>
                         <div id="dispo">Disponibilité : ${dispo}</div>
                            
                         
                    
                    </div>

               
            
            </article>
        </section>

EQT;
        echo $ficheDocument;



    }
    private function renderFicheDocument(){
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
                         <h4 id="descriprion"> Description :</h4> 
                             <div id="descriprion">
                             $fiche->description
                            </div>
                            
                                
                         <div id="type">Format : ${type}</div>
                         <div id="genre">Genre : ${kind} </div>
                         <div id="dispo">Disponibilité : ${dispo}</div>
                            
                         
                    
                    </div>

               
            
            </article>
        </section>

EQT;
        echo $ficheDocument;



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
                    <form method='post' action ='to_search'>
                    <div id='search_form_key'>
                        <label for='txtKey'>Mot clé :</label>
                        <input type = 'text' name = 'txtKey' required/>
                    </div>
                    <div id='search_form_type'>
                        <label for='txtType' class='doc'>Type :</label>
                        <select name='txtType' required>{$typeOptions}</select>
                    </div>
                    <div id='search_form_kind'>
                        <label for='txtKind' class='doc'>Genre :</label>
                        <select name='txtKind' required>{$kindOptions}</select>
                    </div>
                    <div id='search_form_button'>
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