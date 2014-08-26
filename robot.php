<?php


// connect to db


$username = "bcee071567347c";

$password = "de9f0a45";

$database = "cdb_591ad22aea";


echo "<br> So far so good <br>";

$link = mysql_connect(localhost,$username,$password);


@mysql_select_db($database) or die( "Sorry, Unable to select database");




mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');









// set variables


header('Content-Type: text/html; charset=utf-8');




$question     = TRUE;

$debugall     = FALSE;

$debugstep010 = FALSE;

$debugstep020 = FALSE;

$debugstep030 = FALSE;

$debugstep040 = FALSE;

$debugstep050 = FALSE;

$debugstep060 = FALSE;

$debugstep070 = FALSE;

$debugstep130 = FALSE;



$debugstep200 = FALSE;


$maxdist = 150;


// time


//Build Today    

$thour=date('H');
$tmin=date('i');
$tsec=date('s');
$tmonth=date('n');
$tday=date('d');
$tyear=date('Y');




$today2 = date("D F j, Y, g:i a", mktime($thour - 3 ,$tmin,$tsec,$tmonth,$tday,$tyear));

// $today2 = date('l jS \of F Y h:i:s A');

$today2         = date('ymdhis');

$diainput       = $_POST['diainput'];

$debugall       = $_POST['debugall'];

$userid         = $_POST['userid'];

$diainputorig   = $diainput;



if ( $debugall ) 
  {

   echo "<br> STEP010 $today2 <br>";

   echo "<br> INPUT <br>";

   echo "<br> diainput is $diainput <br>";

   echo "<br> debugall is $debugall <br>";

   echo "<br> userid is $userid <br>";

  }
  



if ( !empty($userid) )
  {


// select from conversation ordered by id, desc

// store first 20 rows 






$j = 0;


$querconv = "SELECT * FROM conversation WHERE userid like '$userid' ORDER BY id DESC "; 

$resultconv = mysql_query($querconv);

$numconv = mysql_numrows($resultconv);






while ( $j < $numconv && $j < 19 )
{
 
 $convin    = mysql_result($resultconv,$j, "convin");

 $convout   = mysql_result($resultconv,$j, "convout");

 $convtoday = mysql_result($resultconv,$j, "today");

 $conv[$j][0][0] = $convin;

 $conv[$j][0][1] = $convout;

 
 $j = $j + 1;

 }


// check profile for name, profession, marital statues, kids, age,


$jp = 0;

$querpeople = "SELECT * FROM people WHERE userid like '$userid'"; 


$resultpeople = mysql_query($querpeople);


$numpeople    = mysql_numrows($resultpeople);






while ( $jp < $numpeople )
{
 
 $partner    = mysql_result($resultpeople,$jp, "partner");

 $name       = mysql_result($resultpeople,$jp, "name");

 $profession = mysql_result($resultpeople,$jp, "profession");


 $nofirstname = FALSE;
 
 
 $jp = $jp + 1;

 }


if ( $debugall ) echo "<br> numconv $numconv numpeople $numpeople userid: $userid  name: $userid <br>";

 

  }








$pos = strpos($diainput, '?');


if ( $pos ) 
  {
    $question = TRUE;

    $diainput = str_replace("?", "", $diainput, $count);
 
  }

    $diainput = str_replace("'", "", $diainput, $count);

    $diainput = str_replace(".", "", $diainput, $count);

    $diainput = str_replace(",", "", $diainput, $count);

if ( $debugall)   echo "<br> New DIAINPUT for question: $diainput <br>";

  


$whatdoyouthink = strpos($diainput, "what do you think");


if ( $debugall ) echo "<br> this is whatdoyouthink : $whatdoyouthink <br>";
            
if ( $whatdoyouthink > -1 )
  {
    $diainput = "what do you think";

    $diainputorig = $diainput;

  }

$sentence = explode(" ", $diainput);


$countvalidwords = 0;


$totalwords = count($sentence);


if ( $debugall || $debugstep020 )
 {

  echo "<br> MAIN STEP 020   <br>";

  echo "<br> searchrefactor 1.0.0 <br>";

  echo "<br> $today2 <br>";

  echo "<br> DIAINPUT $diainput <br>";

  echo "<br>";
 
 }








$countline = $countline + 1;

$j = 0;

$jx = 0;



while ( $j < $totalwords )
{
 
 $thisword   = $sentence[$j];

 $quersing = "SELECT * FROM word WHERE word like '$thisword' "; 


 $resultsing = mysql_query($quersing);

 $numsing = mysql_numrows($resultsing);

 if ( $numsing > 0 ) $countvalidwords = $countvalidwords + 1;


 $j = $j + 1;

 }


if ( $debugall ) echo "<br> Number of Valid words : $countvalidwords - Total number of words : $totalwords <br>";



if  ( $countvalidwords < $totalwords ) $answer = "<br>Robot:  I don't understand what you say. My English vocabulary has only 100 words :)<br>";
 else
 
      if ( $nofirstname ) $answer = "<br>Robot:  What is your first name? <br>";
        else
        {

          $mynameis = strpos( $diainput, "my name is");

          $mynameisname = explode( " ", $diainput);

          $k = $mynameis + 3;
         

    if ( $debugall )  echo "<br> on $diainput mynameis is $mynameis <br>";

          if ( $mynameis > -1 )
            {

              $name = $mynameisname[$k];

              $answer = "<br>Robot: Hey, nice to meet you, $name <br>";


              $mynameisname = explode( " ", $diainput);

              $k = $mynameis + 3;
         
              $name = $mynameisname[$k];




            }
           else
            {

               $doyouknowmyname = strpos( $diainput, "do you know my name");

             
              if ( $doyouknowmyname > -1 ) $answer = "Yes, your name is ".$name;
                else
                { 


                  $quersen = "SELECT * FROM sentence WHERE sent like '$diainput' "; 


                  $resultsen = mysql_query($quersen);


                  $numsen = mysql_numrows($resultsen);

 
                  if ( $debugall )  echo "<br> Number of sentences found: $numsen <br>";


                  $j = 0;

                  if ( $numsen > 0 )
                    {
                     // get answer

  
                     $cat1 = mysql_result($resultsen,$j, "cat1"); 

                     $answer =  "<br> Robot: $cat1 <br>";


                     }
                     else $answer = "<br> Robot: I'm a little bit lost, please help me understand <br>";

              } // end of else doyouknowmyname
            } // end of else mynameis  


        } // end of else nofirstname

    

$answer = str_replace("<br>", "", $answer, $count);

$answer = str_replace("Robot:", "", $answer, $count);


$querin = "INSERT INTO conversation (convin, convout, userid, today ) VALUES ( '$diainput', '$answer', '$userid', '$today2' ) "; 


$resultin = mysql_query($querin);


if ( $debugall ) echo "error on insert to conversation ".mysql_errno($link) . ": " . mysql_error($link) . "\n";


$numin = mysql_numrows($resultin);





?>










<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html><head><title>Movelus Corporate Information: Company Overview</title>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">

<style>
<!--

body, font, p, a, span { font-family: arial,sans-serif; }

.sectionbreak {	BORDER-TOP: #80c65a 1px solid; BACKGROUND-COLOR: #90dc98}
.accent1 {background-color: #DEEFE0}
.accent2 {background-color: #006633}
.accent3 {background-color: #339966}
.accent4 {background-color: #efefef}
.actdots {background-image: url(/images/dot2.gif)}
.navactive {color: #006633}
.txtgry {color: #666666}
.ulclose {margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; border: none; list-style-type: square;} 
.liclose {margin-top: 0px; margin-right: 0px; margin-bottom: 5px; margin-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px; border: none; list-style-type: square;} 
li {list-style-type: square;} 
.faqin {margin-left: 15px; padding-left: 15px} 
.i {MARGIN-LEFT: 1em; MARGIN-RIGHT: 1em}
//-->

</style>






<style type="text/css">

.searchbox1 { width: 450px; height: 35px; border: 1px solid #999999; padding: 5px; }

.searchbox2 { width: 100px; height: 35px; border: 1px solid #999999; padding: 5px; }

.searchbutton { width: 75px; height: 35px; border: 1px solid #999999; padding: 5px; }

body { margin-left:100px; }

</style>















<img src=/movelus_small.jpg alt="Movelus" width=173 height=43 vspace=5 border=0>



<div id="searchwrapper">

<form action="http://www.movelus.com/id/movelus/robot.php" method="post">
<input type="text" class="searchbox1" name="diainput" size = 120 value="<?php echo $diainputorig ?>" />
<input type="text" class="searchbox2" name="loc" size = 50 value="<?php echo $locfirst ?>" />
<input type="text" class="searchbox2" name="debugall"  value="0" size= 1 />
<input type="text" class="searchbox2" name="userid"  value="<?php echo $userid ?>" size= 30 />

<input type="submit" class="searchbutton" value="Search" />
</form>
</div>



<link href="/favicon.ico" rel="shortcut icon">



</head>

<body class=corporate text=#000000 vlink=#800080 alink=#ff0000 link=#0000cc bgcolor=#ffffff topmargin=3>

<br>












<?php    



     echo $answer;



     $linelim[1]  = "<a href=";
     $linelim[1] .= chr(34);
     $linelim[1] .= "http://www.movelus.com/id/movelus/customerreviewphplocal.php";
     $linelim[1] .= "?idworker=$idworkerone";

     $linelim[1] .= chr(34);
     $linelim[1] .= " > Click here to see reviews and more info about this professional. </a></></p>";


     $linelim[2]  = "<a href=";
     $linelim[2] .= chr(34);
     $linelim[2] .= "http://www.movelus.com/id/movelus/customerreviewphplocal.php";
     $linelim[2] .= "?idworker=$idworkertwo";

     $linelim[2] .= chr(34);
     $linelim[2] .= " > Click here to see reviews and more info about this professional. </a></></p>";


     $linelim[3]  = "<a href=";
     $linelim[3] .= chr(34);
     $linelim[3] .= "http://www.movelus.com/id/movelus/customerreviewphplocal.php";
     $linelim[3] .= "?idworker=$idworkerthree";

     $linelim[3] .= chr(34);
     $linelim[3] .= " > Click here to see reviews and more info about this professional. </a></></p>";









     $linelim[1]  = "<a href=";
     $linelim[1] .= chr(34);
     $linelim[1] .= "http://www.movelus.com/id/movelus/customerreviewphplocal.php";
     $linelim[1] .= "?idworker=$idworkerone";

     $linelim[1] .= chr(34);
     $linelim[1] .= " > Click here to see reviews and more info about this professional. </a></></p>";


     $linelim[2]  = "<a href=";
     $linelim[2] .= chr(34);
     $linelim[2] .= "http://www.movelus.com/id/movelus/customerreviewphplocal.php";
     $linelim[2] .= "?idworker=$idworkertwo";

     $linelim[2] .= chr(34);
     $linelim[2] .= " > Click here to see reviews and more info about this professional. </a></></p>";


     $linelim[3]  = "<a href=";
     $linelim[3] .= chr(34);
     $linelim[3] .= "http://www.movelus.com/id/movelus/customerreviewphplocal.php";
     $linelim[3] .= "?idworker=$idworkerthree";

     $linelim[3] .= chr(34);
     $linelim[3] .= " > Click here to see reviews and more info about this professional. </a></></p>";









if ( $debugall )
   {  

    echo "<br> MAIN STEP070  <br>";

    echo "<br> END <br>";

    echo "<br> $today2 <br>";

    echo "Current PHP version: ".phpversion();

   }








?>



<?php

 if ( $latcon < 3.84 && $lngcon > -73.983333 && $latcon > -33.689969 && $lngcon <  -34.79479 )
   {

    echo "<p> Ajude nos a melhorar a disponibilidade recomendando os melhores profissionais que voce conhece e convidando seus amigos para usar a Movelus: </p>";

    echo "<a href='http://www.movelus.com/share'>Compartilhe com Amigos</a>";

   }
 

$today2 = date('ymdhis');

if ( $debugall ) 
  {

//   $today2 = date("D F j, Y, g:i a", mktime($thour + 1 ,$tmin,$tsec,$tmonth,$tday,$tyear));

   

   echo "<br> Main Program ROBOT V.1.0.0 (Last update by RS 27 Feb 2014) <br>";

   echo "<br> Execution completes at $today2 <br>";

  }

?>

 <br><br>






<p><font size="-1"><a href="http://www.movelus.com/">To Home page click here</a></font></p>




</body></html>




