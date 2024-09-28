<?php

namespace App\Services;

use Symfony\Component\Form\FormInterface;

class ImageManager
{


    const UPLOAD_DIR_IMAGE = 'images';

    
    /**
     * Undocumented function
     *
     * @param FormInterface $form le formulaire 
     * @param string $filds  le nom de la colonne ou propriété de la class 
     * @param Object $table  la table sur la quelle on veut enregistrer l'image 
     * @param string $imageDefault si new sans image , j'enregistre l'image par default si edit je recup l'ancien nom 
     * @return void
     */
    public function EnregistreImage( FormInterface $form , $filds ,Object $table ,$imageDefault){

        $image = $form->get($filds)->getData();
        $methode = 'set' . ucfirst($filds);
        if($image){
             $new_name_image = uniqid() . '.' . $image->guessExtension();
             $image->move(self::UPLOAD_DIR_IMAGE,$new_name_image);
             $table->$methode($new_name_image);

        }else{
            $table->$methode($imageDefault);
        }

    }
}