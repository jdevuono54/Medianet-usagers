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
    private function renderHome(){
        return "home";
    }


    protected function renderBody($selector=null){
        $header = $this->renderHeader();
        $footer = $this->renderFooter();

        switch ($selector){
            case "home":
                $content = $this->renderHome();
                break;
            case "signup_request":
                $content = $this->renderFormSignupRequest();
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
}
