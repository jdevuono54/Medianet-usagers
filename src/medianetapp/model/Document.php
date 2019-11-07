<?php


namespace medianetapp\model;


class Document extends \Illuminate\Database\Eloquent\Model
{

    protected $table      = 'Document';  /* le nom de la table */
    protected $primaryKey = 'id';     /* le nom de la clé primaire */
    public    $timestamps = false;    /* si vrai la table doit contenir
                                      les deux colonnes updated_at,
                                      created_a*/

    public function state(){

        return $this->belongsTo('medianetapp\model\State', 'id_State');
    }
    public function type(){

        return $this->belongsTo('medianetapp\model\Type', 'id_Type');
    }
    public function kind(){

        return $this->belongsTo('medianetapp\model\Kind', 'id_Kind');
    }
}