<?php
if (!defined('_VALID_TCPRO')) exit ('No direct access allowed!');
/**
 * upload_model.php
 * 
 * Contains the class dealing with uploads table
 * Based on "Easy PHP Upload 2.31" by Olaf Lederer
 * 
 * @package TeamCalPro
 * @version 3.6.020 
 * @author George Lewe
 * @copyright Copyright (c) 2004-2015 by George Lewe
 * @link http://www.lewe.com
 * @license http://tcpro.lewe.com/doc/license.txt Based on GNU Public License v3
 */
/**
 * Make sure the class hasn't been loaded yet
 */
if (!class_exists("Upload_model")) 
{
   /**
    * Provides objects and methods to upload files
    * @package TeamCalPro
    */
   class Upload_model 
   {
      var $the_file;
      var $the_temp_file;
      var $the_new_file;
      var $upload_dir;
      var $replace;
      var $do_filename_check;
      var $max_length_filename = 100;
      var $extensions;
      var $ext_string;
      var $language;
      var $http_error;
      var $rename_file; // if this var is true the file copy get a new name
      var $file_copy; // the new name
      var $message = array ();
      var $create_directory = true;
      var $error;
   
      // ---------------------------------------------------------------------
      /**
       * Constructor
       */
      function Upload_model() 
      {
         $this->language = "en";
         $this->rename_file = false;
         $this->ext_string = "";
         
         $error[0] = "File: <b>" . $this->the_file . "</b> successfully uploaded!";
         $error[1] = "The uploaded file exceeds the max. upload filesize directive in the server configuration.";
         $error[2] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.";
         $error[3] = "The uploaded file was only partially uploaded";
         $error[4] = "No file was uploaded";
         $error[10] = "Please select a file for upload.";
         $error[11] = "Only files with the following extensions are allowed: <b>" . $this->ext_string . "</b>";
         $error[12] = "Sorry, the filename contains invalid characters. Use only alphanumerical chars and separate parts of the name (if needed) with an underscore. <br>A valid filename ends with one dot followed by the extension.";
         $error[13] = "The filename exceeds the maximum length of " . $this->max_length_filename . " characters.";
         $error[14] = "Sorry, the upload directory doesn't exist!";
         $error[15] = "Uploading <b>" . $this->the_file . "...Error!</b> Sorry, a file with this name already exitst.";
         $error[16] = "The uploaded file is renamed to <b>" . $this->file_copy . "</b>.";
         $error[17] = "The file %s does not exist.";
      }
   
      // ---------------------------------------------------------------------
      /**
       * Creates and returns an HTML error message from local message array
       * 
       * @return string HTML error message
       */
      function show_error_string() 
      {
         $msg_string = "";
         foreach ($this->message as $value) $msg_string .= $value . "<br>\n";
         return $msg_string;
      }
   
      // ---------------------------------------------------------------------
      /**
       * Creates and returns a unique new filename
       * 
       * @param string $new_name New desired file name (optional)
       * @return string New filename
       */
      function set_file_name($new_name = "") 
      {
         if ($this->rename_file) 
         {
            if ($this->the_file == "") return;
            $name = ($new_name == "") ? strtotime("now") : $new_name;
            sleep(3);
            $name = "f" . $name . $this->get_extension($this->the_file);
         }
         else 
         {
            /**
             * Spaces will result in problems on linux systems. So let's replace them.
             */
            $name = str_replace(" ", "_", $this->the_file);
         }
         return $name;
      }
   
      // ---------------------------------------------------------------------
      /**
       * Uploads the file using a new filename
       * 
       * @param string $to_name New desired file name (optional)
       * @return string True if upload successful, false if not
       */
      function upload($to_name = "") 
      {
         $new_name = $this->set_file_name($to_name);
   
         if ($this->check_file_name($new_name)) 
         {
            if ($this->validateExtension()) 
            {
               if (is_uploaded_file($this->the_temp_file)) 
               {
                  $this->file_copy = $new_name;
                  if ($this->move_upload($this->the_temp_file, $this->file_copy)) 
                  {
                     $this->message[] = $this->error[$this->http_error];
                     if ($this->rename_file)
                        $this->message[] = $this->error[16];
                     return true;
                  }
               }
               else 
               {
                  $this->message[] = $this->error[$this->http_error];
                  return false;
               }
            }
            else 
            {
               $this->show_extensions();
               $this->message[] = $this->error[11];
               return false;
            }
         }
         else 
         {
            return false;
         }
      }
   
      // ---------------------------------------------------------------------
      /**
       * Checks filename format. 
       * 
       * @param string $the_name Filename to check
       * @return string True if correct, false if not. If false an error message 
       * is copied to local message variable.
       */
      function check_file_name($the_name) 
      {
         if ($the_name != "") 
         {
            if (strlen($the_name) > $this->max_length_filename) 
            {
               $this->message[] = $this->error[13];
               return false;
            }
            else 
            {
               if ($this->do_filename_check == "y") 
               {
                  if (preg_match("/^[a-z0-9_]*\.(.){1,5}$/i", $the_name)) 
                  {
                     return true;
                  }
                  else 
                  {
                     $this->message[] = $this->error[12];
                     return false;
                  }
               }
               else 
               {
                  return true;
               }
            }
         }
         else 
         {
            $this->message[] = $this->error[10];
            return false;
         }
      }
   
      // ---------------------------------------------------------------------
      /**
       * Gets the extension of a given filenam 
       * 
       * @param string $from_file Filename to check
       * @return string Filename extension
       */
      function get_extension($from_file) 
      {
         $ext = strtolower(strrchr($from_file, "."));
         return $ext;
      }
   
      // ---------------------------------------------------------------------
      /**
       * Validates the file extension of the file to upload against an array of 
       * allowed extensions. Allowed extension reside in local variable 
       * $extensions seperated by blanks. This variable is initially empty. 
       * You need to set it from outside this class.
       * 
       * @return string True if valid, false if not
       */
      function validateExtension() 
      {
         $extension = $this->get_extension($this->the_file);
         $ext_array = $this->extensions;
         if (in_array($extension, $ext_array)) 
         {
            return true;
         }
         else 
         {
            return false;
         }
      }
   
      // ---------------------------------------------------------------------
      /**
       * Used to display the allowed extensions in error message 
       */
      function show_extensions() 
      {
         $this->ext_string = implode(" ", $this->extensions);
      }
   
      // ---------------------------------------------------------------------
      /**
       * Moves the uploaded temporary file to its final location/name
       * 
       * @param string $tmp_file Temp filename
       * @param string $new_file New filename
       * @return boolean True if successful, false if not. If false an error message 
       * is copied to local message variable. 
       */
      function move_upload($tmp_file, $new_file) 
      {
         if ($this->existing_file($new_file)) 
         {
            $newfile = $this->upload_dir . $new_file;
            if ($this->check_dir($this->upload_dir)) 
            {
               if (move_uploaded_file($tmp_file, $newfile)) 
               {
                  umask(0);
                  chmod($newfile, 0644);
                  return true;
               }
               else 
               {
                  return false;
               }
            }
            else 
            {
               $this->message[] = $this->error[14];
               return false;
            }
         }
         else 
         {
            $this->message[] = $this->error_text[15];
            return false;
         }
      }
   
      // ---------------------------------------------------------------------
      /**
       * Checks whether a given directory exists. If not, creates it.
       * 
       * @param string $directory Directory to check
       * @return boolean True if exists or created, false if not or creation failed.
       */
      function check_dir($directory) 
      {
         if (!is_dir($directory)) {
            if ($this->create_directory) 
            {
               umask(0);
               mkdir($directory, 0777);
               return true;
            }
            else 
            {
               return false;
            }
         }
         else 
         {
            return true;
         }
      }
   
      // ---------------------------------------------------------------------
      /**
       * Checks whether a given file exists.
       * 
       * @param string $file_name Filename to check
       * @return boolean True if exists, false if not
       */
      function existing_file($file_name) 
      {
         if ($this->replace == "y") 
         {
            return true;
         }
         else 
         {
            if (file_exists($this->upload_dir . $file_name)) 
            {
               return false;
            }
            else 
            {
               return true;
            }
         }
      }
   
      // ---------------------------------------------------------------------
      /**
       * Retrieves the uploaded file info
       * 
       * @param string $name Filename to check
       * @return string String containing file info
       */
      function get_uploaded_file_info($name) 
      {
         $this->the_new_file = basename($name);
         $str = "File name: " . basename($name) . "\n";
         $str .= "File size: " . filesize($name) . " bytes\n";
         
         if (function_exists("mime_content_type")) 
         {
            $str .= "Mime type: " . mime_content_type($name) . "\n";
         }
         
         if ($img_dim = getimagesize($name)) 
         {
            $str .= "Image dimensions: x = " . $img_dim[0] . "px, y = " . $img_dim[1] . "px\n";
         }
         
         return $str;
      }
   
      // ---------------------------------------------------------------------
      /**
       * Deletes the temporary file
       * 
       * @param string $file Temp file to delete
       */
      function del_temp_file($file) 
      {
         $delete = @ unlink($file);
         clearstatcache();
         if (@ file_exists($file)) 
         {
            $filesys = eregi_replace("/", "\\", $file);
            $delete = @ system("del $filesys");
            clearstatcache();
            if (@ file_exists($file)) 
            {
               $delete = @ chmod($file, 0644);
               $delete = @ unlink($file);
               $delete = @ system("del $filesys");
            }
         }
      }
   }
}
?>
