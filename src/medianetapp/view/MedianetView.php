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
            case "user":
                $content = $this->renderUser();
                break;
                case "update_profil":
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
