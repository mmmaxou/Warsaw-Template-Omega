<?php

/**
 * Created by PhpStorm.
 * User: alexr
 * Date: 15/05/2017
 * Time: 16:32
 */



class File
{
    public function addFile($gallery_id, $label){
        $bdd=new Connexion();
        $req = $bdd->myPDO()->prepare('INSERT INTO t_file(gallery_id, label) VALUES(:gallery_id, :label)');
        $req->execute(array(
            'gallery_id' => $gallery_id,
            'label' => $label
        ));
    }

    public function getAllImages($id){
        $bdd = new Connexion();
        $pdo = $bdd->myPDO();
        $req = $pdo->prepare('SELECT label,t_file.id FROM t_file join t_menu join t_gallery where t_gallery.id = t_menu.gallery_id and t_menu.page_id = :id  and t_file.gallery_id = t_gallery.id');
        $req->execute(array(
            'id'=> $id
        ));
        return $req->fetchAll();
    }

    public function deleteFileGallery($id){
        $bdd=new Connexion();
        $pdo=$bdd->myPDO();
        $req1 = $pdo->prepare('select id FROM t_file WHERE gallery_id= :id');
        $req1->execute(array(
            'id' => $id
        ));
        while ($idFile = $req1->fetch())
        {
            $this->deleteId($idFile);
        }
    }


    public function getOneImage(){
        $bdd = new Connexion();
        $pdo = $bdd->myPDO();
        $req = $pdo->query('SELECT label, gallery_id FROM t_file GROUP BY gallery_id');
        return $req->fetchAll();
    }

    public function deleteId($id){
        $bdd = new Connexion();
        $pdo = $bdd->myPDO();
        $req = $pdo->prepare('SELECT label FROM t_file WHERE id = :id');
        $req->execute(array(
            'id' => $id
        ));
        $label = $req->fetch();
        $label = $label['label'];
        $this->delete('uploads', $label);
        $req1 = $pdo->prepare('DELETE FROM t_file WHERE id= :id');
        $req1->execute(array(
            'id' => $id
        ));

    }


    function delete($folder , $file){
        $path = "../../".$folder."/".$file;
        $dir = getcwd();
        if($file!="." AND $file!=".." AND !is_dir($file))
        {
            unlink($path);
        }
    }
}