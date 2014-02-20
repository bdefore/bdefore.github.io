<?php 
/************************************************************************
File upload v2.22 - 
A simple class for your (multiple) file uploads

Copyright (C) 2004 - 2005 - Olaf Lederer

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

_________________________________________________________________________
available at http://www.finalwebsites.com 
Comments & suggestions: http://www.finalwebsites.com/contact.php

Updates:
version 1.01 - added the $DOCUMENT_ROOT server variable to the $upload_dir
in the example.php.

version 1.02 - added the 'check_dir' method to the class. In this version 
the user get an error if the defined upload-directory doesn't exsit. 

version 1.10 - now the class will check if a file alreay exists. Use the
replace-property to overwrite an old file. Notice the checkbox inside the 
example file: Check this box to toggle the replace-property.

version 2.00 - made this class more compact (removed some useless variables)
and added a new method(show_extensions()) to the basic class. All messages 
are saved from now in the $message property (not replaced anymore) New in 
this release is the multi upload example, with an extended class is it 
possible to upload more then one file at the same time.

version 2.01 - added a new method: With "check_file_name()" the class will
controle if a filename uses only charcters recognized by a regular filesytem.

version 2.02 - The method upload() has boolean function now. There is now
a property to switch the method check_file_name() on or off. CHMOD = 0777
if the property $replace is set to "y".

version 2.10 - The check_file_name() method tests now the string length and 
checks that the filename is not empty (I removed the file check from the upload 
method). The $message property is changed into an array. This array will be 
filled by the error_text() method (this method is a extended version of the 
old report_error() method). Inside this method you can switch the message by the new 
property $language to use translations for the output. The message output is 
done by the new show_error_string() method. I did also some cleaning work and 
(small) bug fixing in several methods. I changed the multi_upload_example
to work with the new error reporting methodes, too.

version 2.20 - I changed the upload() method to handle also unique filenames.
This will happen if you set the var $rename_file on true. I added also some user
friendly (dutch) messages to the main class. I isolated the function to get the file
extension into a new method: function get_extension(). Notice the new extension:
"foto_upload", these are some easy to use functions to resize an rotate 
images. After the thumbnail and photo creation is done there is delete method to 
remove the (orig.) uploaded file.

Version 2.21 - Added spanish messages to the class, thanks Sergio for the 
translations.

version 2.22 - I changed the class to work with configurations where 
register_globals = off an added the german translations, thank you Dirk Klingenberg!
*************************************************************************/

 
class file_upload {

    var $the_file;
	var $the_temp_file;
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
	var $message = array();
	
	function file_upload() {
		$this->language = "en"; // choice of en, nl, es
		$this->rename_file = false;
		$this->ext_string = "";
	}
	// some error (HTTP)reporting, change the messages or remove options if you like.
	function error_text($err_num) {
		switch ($this->language) {
			case "nl":
			$error[0] = "Foto succesvol kopieert.";
			$error[1] = "Het bestand is te groot, controlleer de max. toegelaten bestandsgrootte.";
			$error[2] = "Het bestand is te groot, controlleer de max. toegelaten bestandsgrootte.";
			$error[3] = "Fout bij het uploaden, probeer het nog een keer.";
			$error[4] = "Fout bij het uploaden, probeer het nog een keer.";
			$error[10] = "Selecteer een bestand.";
			$error[11] = "Het zijn alleen bestanden van dit type toegestaan: <b>".$this->ext_string."</b>";
			$error[12] = "Sorry, de bestandsnaam bevat tekens die niet zijn toegestaan. Gebruik alleen nummer, letters en het underscore teken. Een geldige naam eindigt met een punt en de extensie.";
			$error[13] = "De bestandsnaam is te lang, het maximum is: ".$this->max_length_filename." teken.";
			$error[14] = "Sorry, het opgegeven directory bestaat niet!";
			$error[15] = "Uploading <b>".$this->the_file."...Fout!</b> Sorry, er is al een bestand met deze naam aanwezig.";
			break;
			case "de":
			$error[0] = "Die Datei: <b>".$this->the_file."</b> wurde hochgeladen!"; 
			$error[1] = "Die hochzuladende Datei ist gr&ouml;&szlig;er als der Wert in der Server-Konfiguration!"; 
			$error[2] = "Die hochzuladende Datei ist gr&ouml;&szlig;er als der Wert in der Klassen-Konfiguration!"; 
			$error[3] = "Die hochzuladende Datei wurde nur teilweise &uuml;bertragen"; 
			$error[4] = "Es wurde keine Datei hochgeladen"; 
			$error[10] = "W&auml;hlen Sie eine Datei aus!."; 
			$error[11] = "Es sind nur Dateien mit folgenden Endungen erlaubt: <b>".$this->ext_string."</b>";
			$error[12] = "Der Dateiname enth&auml;lt ung&uuml;ltige Zeichen. Benutzen Sie nur alphanumerische Zeichen f&uuml;r den Dateinamen mit Unterstrich.Ein g&uuml;ltiger Dateiname endet mit einem Punkt, gefolgt von der Endung."; 
			$error[13] = "Der Dateiname &uuml;berschreitet die maximale Anzahl von ".$this->max_length_filename." Zeichen."; 
			$error[14] = "Das Upload-Verzeichnis existiert nicht!"; 
			$error[15] = "Upload <b>".$this->the_file."...Fehler!</b> Eine Datei mit gleichem Dateinamen existiert bereits.";
			break;
			case "es":
			$error[0] = "El fichero: <b>".$this->the_file."</b> se ha cargado correctamente!";
			$error[1] = "El fichero a cargar excede del tama&ntilde;o m&aacute;ximo de la directiva en la configuraci&oacute;n del servidor.";
			$error[2] = "El fichero a cargar excede del tama&ntilde;o m&aacute;ximo de la directiva especificada en el formulario html.";
			$error[3] = "El fichero a cargar solo lo ha sido parcialmente.";
			$error[4] = "El fichero no ha sido cargado.";
			$error[10] = "Por favor seleccione un fichero a cargar.";
			$error[11] = "Solo ficheros con las siguientes extensiones est&aacute;n permitidos: <b>".$this->ext_string."</b>";
			$error[12] = "Lo siento, el nombre del fichero contiene car&aacute;cteres invalidos. Use solo car&aacute;cteres alfanum&eacute;ricos y separe (si es necesario) con un subrayado. Un nombre de fichero correcto acaba con un punto seguido de la extensi&oacute;n.";
			$error[13] = "El nombre de fichero excede de la longitud m&aacute;xima de ".$this->max_length_filename." car&aacute;cteres.";
			$error[14] = "¡Lo siento, el directorio de destino no existe!";
			$error[15] = "Cargando <b>".$this->the_file."...Error!</b> lo siento, un fichero con el mismo nombre ya existe.";
			break;
			default:
			// start http errors
			$error[0] = "File: <b>".$this->the_file."</b> successfully uploaded!";
			$error[1] = "The uploaded file exceeds the max. upload filesize directive in the server configuration.";
			$error[2] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.";
			$error[3] = "The uploaded file was only partially uploaded";
			$error[4] = "No file was uploaded";
			// end  http errors
			$error[10] = "Please select a file for upload.";
			$error[11] = "Only files with the following extensions are allowed: <b>".$this->ext_string."</b>";
			$error[12] = "Sorry, the filename contains invalid characters. Use only alphanumerical chars and separate parts of the name (if needed) with an underscore. A valid filename ends with one dot followed by the extension.";
			$error[13] = "The filename exceeds the maximum length of ".$this->max_length_filename." characters.";
			$error[14] = "Sorry, the upload directory doesn't exist!";
			$error[15] = "Uploading <b>".$this->the_file."...Error!</b> Sorry, a file with this name already exitst.";
			
		}
		return $error[$err_num];
	}
	function show_error_string() {
		$msg_string = "";
		foreach ($this->message as $value) {
			$msg_string .= $value."<br>\n";
		}
		return $msg_string;
	}
	function upload() {
		if ($this->check_file_name()) {
			if ($this->validateExtension()) {
				if (is_uploaded_file($this->the_temp_file)) {
					// this check/conversion is used for unique filenames 
					$this->file_copy = ($this->rename_file) ? strtotime("now").$this->get_extension($this->the_file) : $this->the_file;
					if ($this->move_upload($this->the_temp_file, $this->file_copy)) {
						$this->message[] = $this->error_text($this->http_error);
						return true;
					}
				} else {
					$this->message[] = $this->error_text($this->http_error);
					return false;
				}
			} else {
				$this->show_extensions();
				$this->message[] = $this->error_text(11);
				return false;
			}
		} else {
			return false;
		}
	}
	function check_file_name() {
		if ($this->the_file != "") {
			if (strlen($this->the_file) > $this->max_length_filename) {
				$this->message[] = $this->error_text(13);
				return false;
			} else {
				if ($this->do_filename_check == "y") {
					if (ereg("^[a-zA-z0-9_]*\.[a-zA-az]{3,4}$", $this->the_file)) {
						return true;
					} else {
						$this->message[] = $this->error_text(12);
						return false;
					}
				} else {
					return true;
				}
			}
		} else {
			$this->message[] = $this->error_text(10);
			return false;
		}
	}
	function get_extension($from_file) {
		$ext = strtolower(strrchr($from_file,"."));
		return $ext;
	}
	function validateExtension() {
		$extension = $this->get_extension($this->the_file);
		$ext_array = $this->extensions;
		if (in_array($extension, $ext_array)) { 
			return true;
		} else {
			return false;
		}
	}
	// this method is only used for detailed error reporting
	function show_extensions() {
		$this->ext_string = implode(" ", $this->extensions);
	}
	function move_upload($tmp_file, $new_file) {
		umask(0);
		if ($this->existing_file()) {
			$newfile = $this->upload_dir.$new_file;
			if ($this->check_dir()) {
				if (move_uploaded_file($tmp_file, $newfile)) {
					if ($this->replace == "y") {
						system("chmod 0777 $newfile");
					} else {
						system("chmod 0755 $newfile");
					}
					return true;
				} else {
					return false;
				}
			} else {
				$this->message[] = $this->error_text(14);
				return false;
			}
		} else {
			$this->message[] = $this->error_text(15);
			return false;
		}
	}
	function check_dir() {
		if (!is_dir($this->upload_dir)) {
			return false;
		} else {
			return true;
		}
	}
	function existing_file() {
		if ($this->replace == "y") {
			return true;
		} else {
			if (file_exists($this->upload_dir.$this->the_file)) {
				return false;
			} else {
				return true;
			}
		}
	}
}
?>