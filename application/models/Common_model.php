<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model
{
    protected $table_name = "";
    protected $table_id = "";

    /*过滤字段*/
    function del_field($data){
        $fields = $this->db->list_fields($this->table_name);
        foreach($data as $k=>$v){
            if(!in_array($k,$fields)){
                unset($data[$k]);
            }
        }
        return $data;
    }

    /*添加*/
    function add($data){
        $data=$this->del_field($data);
        $this->db->insert($this->table_name,$data);
        return $this->db->insert_id();
    }

    /*查询所有*/
    function select_all(){
        return $this->db->get($this->table_name)->result_array();
    }

    /*查询一条*/
    function select_one($id){
        return $this->db->get_where($this->table_name,array($this->table_id=>$id))->row_array();
    }

    /*根据条件查询*/
    function select_where($where){
        return $this->db->get_where($this->table_name,$where)->result_array();
    }

    /*根据条件查询一条*/
    function select_where_one($where){
        return $this->db->get_where($this->table_name,$where)->row_array();
    }

    /*两表联查*/
    //关联id，第二张表表名，第二张表id
    function select_two($id,$table_name,$table_id){
        return $this->db->join($table_name,"$this->table_name.$id=$table_name.$table_id")->get($this->table_name)->result_array();
    }

    /*删除*/
    function delete($id){
        $this->db->delete($this->table_name,array($this->table_id=>$id));
        return $this->db->affected_rows();
    }

    /*修改*/
    function save($data){
        $data=$this->del_field($data);
        $this->db->update($this->table_name,$data,array($this->table_id=>$data[$this->table_id]));
        return $this->db->affected_rows();
    }

    /*放入回收站*/
    function put_trash($id){
        $this->db
            ->update("$this->table_name",array("{$this->table_name}_trash"=>0),array("{$this->table_name}_id"=>$id));
        return $this->db->affected_rows();
    }

    /*回收站恢复*/
    function get_trash($id){
        $this->db
            ->update("$this->table_name",array("{$this->table_name}_trash"=>1),array("{$this->table_name}_id"=>$id));
        return $this->db->affected_rows();
    }

    /*查询展示图片*/
    function select_image($id){
        return $this->db
            ->select("{$this->table_name}_image")
            ->get_where("$this->table_name",array("{$this->table_name}_id"=>$id))
            ->row_array();
    }

    /*查询字段*/
    function get_field(){
        return $this->db->list_fields($this->table_name);
    }

    /*查询记录数*/
    function get_count($where="1=1"){
        return $this->db->where($where)->count_all_results($this->table_name);
    }

    /*查询范围内的记录数*/
    function count_where_in($field,$id,$where="1=1"){
        return $this->db->where($where)->where_in($field,$id)->count_all_results($this->table_name);
    }

    /*分页-查询所有*/
    function page_all($limit=0,$where="1=1"){
        return $this->db->where($where)->get($this->table_name,config_item('admin_page'),$limit)->result_array();
    }

    /*查询地区表*/
    function sel_top($parent=0){
        return $this->db->get_where("ci_region",array("parent_id"=>$parent))->result_array();
    }

    /*无线级分类查询*/
    function get_type(){
        return $this->db->select("*,CONCAT(type_path,'-',type_id) as newpath")
            ->order_by("newpath","asc")
            ->get($this->table_name)
            ->result_array();
    }

    /*点击量加一*/
    function hits_add($id){
        $this->db
            ->set("{$this->table_name}_hits","{$this->table_name}_hits+1",FALSE)
            ->where(array("{$this->table_name}_id"=>$id))
            ->update($this->table_name);
    }
}