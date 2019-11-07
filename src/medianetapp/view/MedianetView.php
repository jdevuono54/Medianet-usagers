<?php

namespace medianetapp\view;

use mf\router\Router;
use mf\utils\HttpRequest as HttpRequest;

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
    private function renderHome(){
        return "home";
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


    protected function renderBody($selector=null){
        $header = $this->renderHeader();
        $footer = $this->renderFooter();

        switch ($selector){
            case "home":
                $content = $this->renderHome();
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