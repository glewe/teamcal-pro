<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * avatar_model.php
 * 
 * Contains the Avatar class
 *
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe <george@lewe.com>
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
/**
 * Make sure the class hasn't been loaded yet
 */
if (!class_exists("Avatar_model")) 
{
   /**
    * Provides objects and methods to deal with avatars
    * @package TeamCalPro
    */
   class Avatar_model 
   {
      var $filename = '';
      var $tmpfilename = '';
      var $fileextension = '';
      var $height = '';
      var $message = '';
      var $path = '';
      var $result ='';
      var $width = '';

      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Avatar_model() 
      {
         global $CONF;
         unset($CONF);
         require ("config.tcpro.php");
         require_once( $CONF['app_root']."models/config_model.php" );
         $C = new Config_model;
         $this->maxHeight = $C->readConfig("avatarHeight");
         $this->maxWidth = $C->readConfig("avatarWidth");
         $this->maxSize = $C->readConfig("avatarMaxSize");
         $this->path = $CONF['app_avatar_dir'];
         $this->allowedtypes = array ( "gif", "jpg", "png" );
      }

      // ---------------------------------------------------------------------
      /**
       * Find avatar for a given user (username=avatar file name)
       * 
       * @param string $uname Username (file name) to find
       * @return boolean True if found, false if not
       */
      function find($uname) 
      {
         foreach ($this->allowedtypes as $extension) 
         {
            if (file_exists($this->path.$uname.".".$extension)) 
            {
               $this->filename = $uname;
               $this->fileextension = $extension;
               return true;
            }
         }
         return false;
      }

      // ---------------------------------------------------------------------
      /**
       * Deletes avatar for a given user (username=avatar file name)
       * 
       * @param string $uname Username (file name) to delete
       */
      function delete($uname) 
      {
         foreach ($this->allowedtypes as $extension) 
         {
            if (file_exists($this->path.$uname.".".$extension)) 
            {
               unlink($this->path.$uname.".".$extension);
            }
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Saves avatar for a given user (username=avatar file name)
       * 
       * @param string $uname Username (file name) to save
       */
      function save($uname) 
      {
         global $_FILES;
         global $LANG;
         $this->result = 0;
         $this->message = '';

         if (is_uploaded_file($_FILES['imgfile']['tmp_name'])) 
         {
            $this->filename = $_FILES['imgfile']['name'];
            $this->tmpfilename = $_FILES['imgfile']['tmp_name'];
            $this->fileextension = $this->getFileExtension($this->filename);
            $this->fileextension = strtolower($this->fileextension);

            if ( is_numeric(array_search(strtolower($this->fileextension), $this->allowedtypes)) ) 
            {
               $newfile = $this->path . $uname . "." . $this->fileextension;
               /**
                * Check size and resize if necessary
                */
               $imgsize = GetImageSize($this->tmpfilename);
               $width = $imgsize[0];
               $height = $imgsize[1];
               if (($imgsize[0] > $this->maxWidth) || ($imgsize[1] > $this->maxHeight)) 
               {
                  if ($width > $this->maxWidth && $height <= $this->maxHeight) 
                  {
                     $ratio = $this->maxWidth / $width;
                  }
                  elseif ($height > $this->maxHeight && $width <= $this->maxWidth) 
                  {
                     $ratio = $this->maxHeight / $height;
                  }
                  elseif ($width > $this->maxWidth && $height > $this->maxHeight) 
                  {
                     $ratio1 = $this->maxWidth / $width;
                     $ratio2 = $this->maxHeight / $height;
                     $ratio = ($ratio1 < $ratio2) ? $ratio1 : $ratio2;
                  } 
                  else 
                  {
                     $ratio = 1;
                  }
                  $nWidth = floor($width * $ratio);
                  $nHeight = floor($height * $ratio);
                  
                  //echo "<script type=\"text/javascript\">alert(\"Debug: ".$imgsize[0]." ".$imgsize[1]." ".$nWidth." ".$nHeight."\");</script>";
                  
                  switch (strtolower($this->fileextension)) 
                  {
                     case "gif":
                     $origPic = imagecreatefromgif($this->tmpfilename);
                     $newPic = imagecreate($nWidth, $nHeight);
                     imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                     imagegif($newPic, $newfile);
                     imagedestroy($origPic);
                     break;

                     case "jpg":
                     case "jpeg":
                     $origPic = imagecreatefromjpeg($this->tmpfilename);
                     $newPic = imagecreatetruecolor($nWidth, $nHeight);
                     imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                     imagejpeg($newPic, $newfile, 90);
                     imagedestroy($origPic);
                     break;

                     case "png":
                     $origPic = imagecreatefrompng($this->tmpfilename);
                     $newPic = imagecreate($nWidth, $nHeight);
                     imagecopyresized($newPic, $origPic, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
                     imagepng($newPic, $newfile);
                     imagedestroy($origPic);
                     break;
                  }
               }
               else  
               {
                  /**
                   * The file is within the size restrictions. Just copy it to its destination.
                   */
                  if (!copy($this->tmpfilename, $newfile)) 
                  {
                     $this->message = $LANG['ava_write_error'];
                  }
               }
               /**
                * Delete the temporary uploaded file
                */
               unlink($this->tmpfilename);
               /**
                * Delete previous avatars if exist
                */
               foreach ($this->allowedtypes as $type) 
               {
                  if ($type!=$this->fileextension && file_exists($this->path . $uname . "." . $type)) unlink($this->path . $uname . "." . $type);
               }
            } 
            else 
            {
               $this->message = $LANG['ava_wrongtype_1'];
               $this->message .= $this->fileextension." . ";
               $this->message .= $LANG['ava_wrongtype_2'];
               foreach ($this->allowedtypes as $allowedtype) 
               {
                  $this->message .= strtoupper($allowedtype) . ", ";
               }
               $this->message = substr($this->message, 0, strlen($this->message)-2);
               $this->message .= ".";
            }
         }
         else 
         {
            switch ($_FILES['imgfile']['error']) 
            {
               case 1 : // UPLOAD_ERR_INI_SIZE
               $this->message = $LANG['ava_upload_error_1'];
               break;
   
               case 2 : // UPLOAD_ERR_FORM_SIZE
               $this->message = $LANG['ava_upload_error_2a'] . $this->maxSize . $LANG['ava_upload_error_2b'];
               break;
   
               case 3 : // UPLOAD_ERR_PARTIAL
               $this->message = $LANG['ava_upload_error_3'];
               break;
   
               case 4 : // UPLOAD_ERR_NO_FILE
               $this->message = $LANG['ava_upload_error_4'];
               break;
   
               default :
               $this->message = $LANG['ava_upload_error'];
               break;
            }
         }
      }

      // ---------------------------------------------------------------------
      /**
       * Extract file extension of a given file name
       * 
       * @param string $str File name to scan
       * @return string File extension if exists
       */
      function getFileExtension($str) 
      {
         $i = strrpos($str, ".");
         if (!$i) return "";
         $l = strlen($str) - $i;
         $ext = substr($str, $i +1, $l);
         return $ext;
      }
   }
}
?>
