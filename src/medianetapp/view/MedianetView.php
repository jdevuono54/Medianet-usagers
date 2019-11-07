<?php

namespace medianetapp\view;

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

        foreach ($documents as $document){
            echo $document->title;
        }
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