<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use App\Entity\Fichiersequipes;

class DirectoryNamer implements DirectoryNamerInterface 
{
    /**
     * Returns the name of a directory where files will be uploaded
     *      
     * @param Media $media
     * @param PropertyMapping $mapping 
     *
     * @return string 
     */
     public function directoryName($media, PropertyMapping $mapping): string
     {   
         if (($media->get('memoire')) or ($media->get('annexe'))){
             $path= '/memoires/';
         }
         
          if ($media->get('resume')){
             $path= '/resumes/';
         }
          if ($media->get('fichesecur')){
             $path= '/fichessecur/';
         }
          if ($media->get('presentationr')){
             $path= '/presentation/';
         }
          return $path;
         
     }
   
}