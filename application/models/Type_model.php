<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Type_model extends Common_model
{
    protected $table_name = "type";
    protected $table_id = "type_id";

    /*分类导航*/
    function nav_type($parent_id,$type_all){
        $type=array();
        foreach($type_all as $k=>$v){
            if($v['type_parent']==$parent_id){
                $type[$k]=$v;
                $type[$k]['son']=$this->nav_type($v['type_id'],$type_all);
            }
        }
        return $type;
    }
}