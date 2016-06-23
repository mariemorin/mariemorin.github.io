<?php
session_start();

$destinataire="mariemorin2003@yahoo.fr";


/*
* cette fonction sert à nettoyer et enregistrer un texte
*/
function Rec($text)
{
$text = htmlspecialchars(trim($text), ENT_QUOTES);
if (1 === get_magic_quotes_gpc())
{$text = stripslashes($text);
}

$text = nl2br($text);
return $text;
};

/*
* Cette fonction sert à vérifier la syntaxe d'un email
*/
function IsEmail($email)
{
$value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
return (($value === 0) || ($value === false)) ? false : true;
}

$nom = (isset($_POST['nom']))   ? Rec($_POST['nom'])   : '';
$email = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
$message = (isset($_POST['message'])) ? Rec($_POST['message'])  : '';

$email = (IsEmail($email)) ? $email : '';


$errors = array(); // on crée une vérif de champs
if($nom == '' ) // on verifie l'existence du champ et d'un contenu
  {$errors ['nom'] = "Vous n'avez pas renseigné votre nom";
  }

if($email == '' ) // on verifie l'existence du champ et d'un contenu
  {$errors ['email'] = "Votre email est incorrect";
  }

if($message == '')
  {$errors ['message'] = "Vous n'avez pas renseigné votre message";
  }


if(!empty($errors)){ // si erreur on renvoie vers la page précédente
  $_SESSION['errors'] = $errors;//on stocke les erreurs
  $_SESSION['inputs'] = $_POST;
  header('Location: index.php');
  }

  else{
  $_SESSION['success'] = 1;

  $headers = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
  $headers .= "Content-Transfer-Encoding: 8bit\r\n";
  $headers .= 'Répondre à: ' . $email . "\r\n";


  $sujet = 'Message envoyé par ' . $nom . ' depuis le site mariemorin. ';


// Remplacement de certains caractères spéciaux
$message = str_replace("&#039;","'",$message);
$message = str_replace("&#8217;","'",$message);
$message = str_replace("&quot;",'"',$message);
$message = str_replace('<br>','',$message);
$message = str_replace('<br />','',$message);
$message = str_replace("&lt;","<",$message);
$message = str_replace("&gt;",">",$message);
$message = str_replace("&amp;","&",$message);


	mail($destinataire,$sujet,$message,$headers);
	header('Location: index.php');


}
