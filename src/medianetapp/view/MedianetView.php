<?php

namespace medianetapp\view;

use medianetapp\model\Kind;
use medianetapp\model\Type;
use mf\router\Router;

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

        /*
         *
         * MANQUE URL FOR
         */
        $blocsDocuments="";
        foreach ($documents as $document){
            $blocsDocuments .= "<div class='document'><a href='?id=".$document->id."'>".$document->title."</a></div>
";
        }
        $html = <<<EQT
            <div class="catalogue">
                ${blocsDocuments}
</div>
EQT;
        return $html;
    }

    public function renderLogin(){
        $error_message = $this->data["error_message"];
        $html = <<< EQT
<form method="POST" action="check_login">
    <div>
        <label>Adresse mail :</label>
        <input type="email" name="mail" required>
    </div>
    <div>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <input type="submit" value="Connexion">
        <p class="error_message">${error_message}</p>
    </div>
</form>
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