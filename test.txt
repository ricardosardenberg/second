<?php

// Complete refactoring of search web from scratch, from 4900 loc to ?


// rs add address full after crawbusiness 25 april 2014




// functions

// prepare_location


function prepare_location ($locfull)
{

$loc = str_replace(" ", "+", $locfull);

 
//echo "<br>Loc: $loc <br>";

return $loc;


}



// prepare_input


function prepare_input ($input)
{

// Find R$

$strrealpos = STRPOS($input, "R$");

if ( $globals['debugall'] ) echo "<br>Position of R$ is $strrealpos <br>";

// echo "<br>This is the input:<br>";

// print_r($input);




// Find Number

// Find first digit

if ( $strrealpos > 2 )
 {



$numbeg = $strrealpos + 2;

$strnumber = substr($input, $numbeg, 1);

$numbervalid = FALSE;

if ( !is_numeric($strnumber) )
 {
  
//  echo "<br>First Digit: $strnumber <br>";

  if ( $strnumber == " " )
    {
      $numbeg = $numbeg + 1;

      $strnumber = substr($input, $numbeg, 1);

      if ( !is_numeric($strnumber))
        {
         
        echo "<br>Second Digit: $strnumber <br>";

         if ( $strnumber == " " )
           {

            $numbeg = $numbeg + 1;

            $strnumber = substr($input, $numbeg, 1);

            if ( is_numeric($strnumber) )
              {
               $numbervalid = TRUE;
              }
           } // if equals " "
        } // end of !is_numeric
        else $numbervalid = TRUE;
         
    }
  }
  else $numbervalid = TRUE;

// echo "<br>Beg. of Number is $numbeg <br>";

if ( $numbervalid )
{

$numpos = $numbeg + 1;

// echo "<br>Number: $strnumber <br>";

$digstr = substr($input, $numpos, 1);

While ( is_numeric( $digstr ) )
{

$strnumber = $strnumber.$digstr;

// echo "<br>Number: $strnumber <br>";

$numpos = $numpos + 1;

$digstr = substr($input, $numpos, 1);

// echo "<br> Digstr: $digstr <br>";


}

// echo "<br> Number is $strnumber <br>";

}
else
{
 // echo "<br> Number is not valid <br>";

 $arrayinput[3] = "notvalid";

}
// Find "/"

$slashvalid = FALSE;

$slashpos = $numpos;

$slashstr = $digstr;

if ( $slashstr == "/" )
  {

   $slashvalid = TRUE;

  }
  else
  {
 
   $slashpos = $slashpos + 1;

   $slashstr = substr($input, $slashpos, 1);

   if ( $slashstr = "/" )
     {
      $slashvalid = TRUE;
     }
   }


// Find UOM

$uomvalid = FALSE;

if ( $slashvalid && $numbervalid )
 {


  $uompos = $slashpos + 1;



$inputlen = strlen($input);

$uomlen = $inputlen - $uompos;

// echo "<br>Len of UOM = $uomlen <br>";

$uomstr = substr($input, $uompos, $uomlen);

// echo "<br>UOMSTR $uomstr <br>";

switch ($uomstr):
    case "dia":
        $uomvalid = TRUE;
        break;
    case "corrida":
        $uomvalid = TRUE;
        break;
    case "hora":
        $uomvalid = TRUE;
        break;

   case "empreita":
        $uomvalid = TRUE;
        break;

    case "empreitada":
        $uomvalid = TRUE;
        break;

    case "projeto":
        $uomvalid = TRUE;
        break;


    case "trabalho":
        $uomvalid = TRUE;
        break;

    case "servico":
        $uomvalid = TRUE;
        break;


    case "tarefa":
        $uomvalid = TRUE;
        break;

    case "resultado":
        $uomvalid = TRUE;
        break;

     case "aula":
        $uomvalid = TRUE;
        break;

    case "mes":
        $uomvalid = TRUE;
        break;

      



    default:
        $uomvalid = FALSE;
endswitch;






 }

// echo "<br>UOM : $uomstr <br>";

} // end of R$ exists
else
{

$strcurrencypos = STRPOS($input, "$");

if ( $strcurrencypos > 2 ) $arrayinput[3] = "notvalid";
  else
  {

   $strslashpos = STRPOS($input, "/");

   if ( $strslashpos > 2 ) $arrayinput[3] = "notvalid";
     else $arrayinput[3] = "valnoprice";
  }

}

if ( $arrayinput[3] <> "valnoprice" && $arrayinput[3] <> "notvalid" && $numbervalid && slashvalid && $uomvalid )
  { 

    $arrayinput[0] = 1;

    $arrayinput[1] = $strnumber;

    $arrayinput[2] = $uomstr;

    $arrayinput[3] = "valprice";

   }
  else
   {

    if ( $arrayinput[3] <> "valnoprice" )
     {
    $arrayinput[0] = 0;

    $arrayinput[3] = "notvalid";
     }

/*
    if ( $numbervalid ) echo "<br> Number $strnumber is valid ! <br>";
       else echo "<br>Number is not valid ! <br>";

    if ( $slashvalid ) echo "<br> Slash is valid <br>";
       else echo "<br> Slash is not valid <br>";

    if ( $uomvalid ) echo "<br> UOM $uomstr is valid ! <br>";
       else echo "<br> UOM is not valid ! <br>";

*/

    }



// Find UOM


// Return Array

// echo "<br>Arrayinput: <br>";

// print_r($arrayinput);






return $arrayinput;

}

// prepare_location

function prepare_latlng($loc)
{




// Get lat and long by address  


       
        $address = $loc; // Google HQ




        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;


if ( $GLOBALS['debugall'] ) 
  {

   echo "<br>STEP010 Inside prepare_latlng <br>";

   echo "<br> Results for loc : $loc <br>";

   echo "<br>new latitude :".$latitude; echo "<br>";

   echo "<br>new longitude :".$longitude; echo "<br><br>";
  }






$newline = "<br />";

// echo "<b> Lat/Lng: <b>".$newline;

$vetor = explode(",", $page);

// next to add location to database and xml


$lat2=" ";
$lng2=" ";


$result=$vetor[0];

$lat2 = $vetor[2];

$lat = $lat2;

$lng2 = $vetor[3];

$lng = $lng2;




$lat = $latitude;

$lng = $longitude;

$latlng[0] = $latitude;

$latlng[1] = $longitude;

// end

if ( !is_numeric($lat) && !is_numeric($lng) )  
  { 


if ( $globals['debugall'] )

  {  

    echo "<br> latcon and lngcon are not numeric <br>";

  } 


$citypos = strpos($loc, "sao paulo");

if ( $citypos === false )
  { 

   $citypos = strpos($loc, "osasco");

   if ( $citypos === false )
     { 

      $citypos = strpos($loc, "campinas");

      if ( $citypos === false  )
        { 

        $citypos = strpos($loc, "porto seguro");

        if ( $citypos === true )
          {  

           // -16.45135,-39.064569


           $latlng[0] = -16.45135;

           $latlng[1] -39.064569;
 
          }
          else
          {

           // default eh sao paulo

           // -23.548881 , -46.63868
 
           $latlng[0] = -23.548881;

           $latlng[1] = -46.63868;

          } // end of else for if not porto seguro

         } // end of if campinas false
      
     } // end of if osasco false
     else
     {
       // -23.531693,-46.789923

      $latlng[0] = -23.531693;

      $latlng[1] = -46.789923;

     }

    } // end of if sao paulo false
    else
    {

     // -23.548881 , -46.63868

     $latlng[0] = -23.548881;

     $latlng[1] = -46.63868;

     
    } // end of else for sao paulo is false

   } // if !is_numeric
 



if ( $GLOBALS['debugall'] )
 {

 echo "<br>Inside prepare_latlng <br>";

 echo "<br>Lat : ".$latlng[0];

 echo "<br>Lng : ".$latlng[1];

 }


return $latlng;

}


function calc_distance($lat, $lng, $lat2, $lng2)
{


// lat, lng, lat2, lng2

// echo "<br> lat $lat lng $lng lat2 $lat2 lng $lng2 <br>";


// calculate distance


$d = 0;


$R = 6371; 

$dLat = ($lat - $lat2) * 0.0174532925;

$dLng = ($lng - $lng2) * 0.0174532925;

$radlat = $lat * 0.0174532925;

$radlat2 = $lat2 * 0.0174532925;

$radlng = $lng * 0.0174532925;





// echo " ,radians ok ";

$a = sin($dLat/2) * sin($dLat/2) +

         cos($radlat) * cos($radlat2) * 

         sin($dLng/2) * sin($dLng/2); 

$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 

$d = $R * $c;


// to transform to kilometers

if ( $GLOBALS['country'] == 'US' ) $d = $d;
   else $d = $d * 1.5;


// $d = 5;  purme anytime to put distance in consideration, purme bellow for far

$short = false;



$debugfar = "FALSE";



$short = true;




// if ($short) { echo " Short is true "; }
//   else echo " Short is false ";







// echo "the distance for $idworker2 is:".$d;

if ( $d == "" || $d < 0.0001 )
  {

    $d = 10; 

    if ( $GLOBALS['debugall'] )
       {

         echo "<br><b> Inside calc_distance distance is $d </b><br>";

         echo "<br> ";

         echo $GLOBALS['idworker']; echo " "; echo $GLOBALS['city'];

         echo "<br>";

         echo "<br> lat $lat lng $lng lat2 $lat2 lng $lng2 <br>";




       }


  }



$d = ROUND($d,0);








$distance = $d;











return $distance;

}




// connect to db


$localhost_db = "us-cdbr-azure-east2-d.cloudapp.net";

$username = "b571b1c9c8789f";

$password = "6e9a501d";

$database = "movelusAWzrTPBhw";


$link = mysql_connect($localhost_db,$username,$password);


@mysql_select_db($database) or die( "Unable to select database");




mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');









// set variables


header('Content-Type: text/html; charset=utf-8');

$debugall    = FALSE;

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



if ( $debugall || $debugstep020 )
 {

  echo "<br> MAIN STEP 020   <br>";

  echo "<br> searchrefactor 1.0.0 <br>";

  echo "<br> $today2 <br>";

  echo "<br>";
 
 }



// count for rank for short distances

 $countproshort = 0;

// $a to count elements on alreadyarray

$a = 0;

//

$searchinput = $_POST['searchinput'];


$name = $_POST['name'];


// echo "<br> Name is $name <br>";



$strcount = strlen($searchinput);


$licenseflag = FALSE;

$phoneflag = FALSE;

$newphone = preg_replace('/\D/', '', $searchinput);


if ( $strcount > 6 ) $phoneflag = is_numeric($newphone);


$strcountphone = strlen($newphone);


$flagnumeric = is_numeric($searchinput);


if ( $flagnumeric && $strcount < 7 && $strcount > 5 )  $licenseflag = TRUE;



if ( $debugall || $debugstep030 ) echo "<br> STEP030 strcount is $strcount flagnumeric $flagnumeric phoneflag is $phoneflag  newphone is $newphone <br>";



$searchfirst = $searchinput;


// $arrayinput = prepare_input($searchinput);



$loc = $_POST['loc'];


$locfirst = $loc;


$locfull = $loc;





$loc = prepare_location($locfull);


$latlngcon = prepare_latlng($loc);




$numchar = strpos($loc);



$country = substr($loc, -2, 2);

// echo "<br> this is country: $country <br>";


// Prepare Location


$locfull = $loc;


$loc = prepare_location($locfull);


if ( $debugall || $debugstep040 )
  {

     echo "<br>MAIN STEP 040 After PREPARE_LOCATION <br>";

     echo "<br>Result of prepare_location <br>";

     echo "<br>Loc: $loc <br>";

  }



// Select from Service


$querser = "SELECT DISTINCT idworker, servicename FROM service WHERE servicename like '%$searchinput%' "; 


$resultser = mysql_query($querser);

$numser = mysql_numrows($resultser);

if ( $debugall || $debugstep050 )
  {

   echo "<br> MAIN STEP050 After select from service  <br>";

   echo "<br> Number of entries found on service db: $numser for search input $searchinput <br>";

  }

$countline = $countline + 1;

$j = 0;

$jx = 0;

while ( $j < $numser )
{
 
 $idworker   = mysql_result($resultser,$j,"idworker");

 $quersing = "SELECT * FROM worker WHERE idworker like '$idworker' "; 


 $resultsing = mysql_query($quersing);

 $numsing = mysql_numrows($resultsing);

// echo "<br> MAIN STEP050   <br>";

// echo "<br> While for Service, number of entries for idworker: $idworker found on worker db: $numsing <br>";


 $js = 0;

 if ( $js < $numsing )
  {

    $nameworker = mysql_result($resultsing,$js, "nameworker");

    $idcompany  = mysql_result($resultsing,$js,"idcompany"); 

    $compname   = mysql_result($resultsing,$js,"compname"); 

    $numord     = mysql_result($resultsing,$js,"numord");

    $prorank    = mysql_result($resultsing,$js,"prorank");

    $posfeed    = mysql_result($resultsing,$js,"posfeed");

    $level      = mysql_result($resultsing,$js,"level");

    $defaultservice = mysql_result($resultsing,$js,"defaultservice");

    $phone      = mysql_result($resultsing,$js,"phone");


$top10[$jx][1] = $idworker;

$top10[$jx][2] = $nameworker; 

$top10[$jx][3] = $numord;

$top10[$jx][4] = $level;

$top10[$jx][5] = $posfeed;

$top10[$jx][6] = $prorank;

$top10[$jx][7] = $updpri;

$top10[$jx][8] = $updsta;

$top10[$jx][9] = $idcompany;

$top10[$jx][10] = $servicedesc;

$top10[$jx][11] = $distance;





 $querloc = "SELECT * FROM location WHERE idworker like '$idworker' "; 


 $resultloc = mysql_query($querloc);

 $numloc = mysql_numrows($resultloc);

 $jloc = 0;

 if ( $numloc > 0 )
  {
 
   $latpro    = mysql_result($resultloc,$jloc,"lat");

   $lngpro    = mysql_result($resultloc,$jloc,"lng");


  } 



   $distance = calc_distance($latpro, $lngpro, $latlngcon[0], $latlngcon[1]);

   $top10[$jx][11] = $distance;

   $jx = $jx + 1;


   }

$j = $j + 1;




$countline = $countline + 1;


if ( $debugall )
  {

    echo "<br><b> # $countline $nameworker $compname $phone $defaultservice </b><br>";

  }
 
}





$querwor = "SELECT * FROM worker WHERE defaultservice like '%$searchinput%' "; 


$resultwor = mysql_query($querwor);

$numwor = mysql_numrows($resultwor);



if ( $debugall || $debugstep070 )
 {
 
   echo "<br> MAIN STEP070  <br>";

   echo "<br> Number of entries found on worker db: $numwor <br>";

 }




$j2 = 0;

while ( $j2 < $numwor )
{

 
$idworker   = mysql_result($resultwor,$j2,"idworker");

$nameworker = mysql_result($resultwor,$j2, "nameworker");

$idcompany  = mysql_result($resultwor,$j2,"idcompany"); 

$compname   = mysql_result($resultwor,$j2,"compname"); 

$numord     = mysql_result($resultwor,$j2,"numord");

$prorank    = mysql_result($resultwor,$j2,"prorank");

$posfeed    = mysql_result($resultwor,$j2,"posfeed");

$level      = mysql_result($resultwor,$j2,"level");

$defaultservice = mysql_result($resultwor,$j2,"defaultservice");

$phone      = mysql_result($resultwor,$j2,"phone");


$j2 = $j2 + 1;



$top10[$jx][1] = $idworker;

$top10[$jx][2] = $nameworker; 

$top10[$jx][3] = $numord;

$top10[$jx][4] = $level;

$top10[$jx][5] = $posfeed;

$top10[$jx][6] = $prorank;

$top10[$jx][7] = $updpri;

$top10[$jx][8] = $updsta;

$top10[$jx][9] = $idcompany;

$top10[$jx][10] = $servicedesc;

$top10[$jx][11] = $distance;


$jx = $jx + 1;




$countline = $countline + 1;



// echo "<br><b> # $countline $nameworker $compname $phone $defaultservice </b><br>";

}



// Select using SYNONYM



// Choose SYNONYM


if ( $countline < 10 )
 {

  $quersyn = "SELECT * FROM synonym WHERE origword like '%$searchinput%' "; 


  $resultsyn = mysql_query($quersyn);

  $numsyn = mysql_numrows($resultsyn);

  if ( $debugall )
    {

     echo "<br> MAIN STEP080 After select from service  <br>";

     echo "<br> Number of entries found on service db: $numser for search input $searchinput <br>";

    }


   $jsyn = 0;

   if ( $jsyn < $numsyn )
    {

    $searchinput = mysql_result($resultsyn,$jsyn, "synoword");

    }




// Select from Service with Synonym



$querser = "SELECT DISTINCT idworker, servicename FROM service WHERE servicename like '%$searchinput%' "; 


$resultser = mysql_query($querser);

$numser = mysql_numrows($resultser);

if ( $debugall )
  {

   echo "<br> MAIN STEP090 After select from service  <br>";

   echo "<br> Number of entries found on service db: $numser for search input $searchinput <br>";

  }

$countline = $countline + 1;

$j = 0;

$jx = 0;

while ( $j < $numser )
{
 
 $idworker   = mysql_result($resultser,$j,"idworker");

 $quersing = "SELECT * FROM worker WHERE idworker like '$idworker' "; 


 $resultsing = mysql_query($quersing);

 $numsing = mysql_numrows($resultsing);

// echo "<br> MAIN STEP100   <br>";

// echo "<br> While for Service, number of entries for idworker: $idworker found on worker db: $numsing <br>";


 $js = 0;

 if ( $js < $numsing )
  {

    $nameworker = mysql_result($resultsing,$js, "nameworker");

    $idcompany  = mysql_result($resultsing,$js,"idcompany"); 

    $compname   = mysql_result($resultsing,$js,"compname"); 

    $numord     = mysql_result($resultsing,$js,"numord");

    $prorank    = mysql_result($resultsing,$js,"prorank");

    $posfeed    = mysql_result($resultsing,$js,"posfeed");

    $level      = mysql_result($resultsing,$js,"level");

    $defaultservice = mysql_result($resultsing,$js,"defaultservice");

    $phone      = mysql_result($resultsing,$js,"phone");


$top10[$jx][1] = $idworker;

$top10[$jx][2] = $nameworker; 

$top10[$jx][3] = $numord;

$top10[$jx][4] = $level;

$top10[$jx][5] = $posfeed;

$top10[$jx][6] = $prorank;

$top10[$jx][7] = $updpri;

$top10[$jx][8] = $updsta;

$top10[$jx][9] = $idcompany;

$top10[$jx][10] = $servicedesc;

$top10[$jx][11] = $distance;


$jx = $jx + 1;









   }

$j = $j + 1;




$countline = $countline + 1;


if ( $debugall )
  {

    echo "<br><b> # $countline $nameworker $compname $phone $defaultservice </b><br>";

  }
 
}





$querwor = "SELECT * FROM worker WHERE defaultservice like '%$searchinput%' "; 


$resultwor = mysql_query($querwor);

$numwor = mysql_numrows($resultwor);



if ( $debugall )
 {
 
   echo "<br> MAIN STEP110  <br>";

   echo "<br> Number of entries found on worker db: $numwor <br>";

 }




$j2 = 0;

while ( $j2 < $numwor )
{

 
$idworker   = mysql_result($resultwor,$j2,"idworker");

$nameworker = mysql_result($resultwor,$j2, "nameworker");

$idcompany  = mysql_result($resultwor,$j2,"idcompany"); 

$compname   = mysql_result($resultwor,$j2,"compname"); 

$numord     = mysql_result($resultwor,$j2,"numord");

$prorank    = mysql_result($resultwor,$j2,"prorank");

$posfeed    = mysql_result($resultwor,$j2,"posfeed");

$level      = mysql_result($resultwor,$j2,"level");

$defaultservice = mysql_result($resultwor,$j2,"defaultservice");

$phone      = mysql_result($resultwor,$j2,"phone");


$j2 = $j2 + 1;



$top10[$jx][1] = $idworker;

$top10[$jx][2] = $nameworker; 

$top10[$jx][3] = $numord;

$top10[$jx][4] = $level;

$top10[$jx][5] = $posfeed;

$top10[$jx][6] = $prorank;

$top10[$jx][7] = $updpri;

$top10[$jx][8] = $updsta;

$top10[$jx][9] = $idcompany;

$top10[$jx][10] = $servicedesc;

$top10[$jx][11] = $distance;


$jx = $jx + 1;










$countline = $countline + 1;



// echo "<br><b> # $countline $nameworker $compname $phone $defaultservice </b><br>";

}




} // end of if few count line








// end of SYNONYM




// Select from CRAWBUSINESS 


if ( $licenseflag ) $quercraw = "SELECT * FROM crawbusiness WHERE license like '%$searchinput%'  ";
   else
   $quercraw = "SELECT * FROM crawbusiness WHERE busname like '%$searchinput%' and ( city like 'san jose' or city like 'mountain view' or city like 'sunnyvale' or city like 'palo alto') limit 20 "; 


if ( $phoneflag ) $quercraw = "SELECT * FROM crawbusiness WHERE busphone like '%$searchinput%'  ";


$resultcraw = mysql_query($quercraw);

$numcraw = mysql_numrows($resultcraw);


if ( $debugall || $debugstep120 )
  {

   if ( $licenseflag ) $strflag = "License flag is TRUE";
      else $strflag = "License flag is FALSE";

   echo "<br> MAIN STEP120  <br>";

   echo "<br> Number of entries found on craw db: $numcraw for search input $searchinput  and $strflag <br>";
  
  }

$j3 = 0;

while ( $j3 < $numcraw )
{

 

$nameworker = mysql_result($resultcraw,$j3, "busname");

$idworker   = $nameworker."@busname.com"; 

$busname   = mysql_result($resultcraw,$j3,"busname"); 

$numord     = mysql_result($resultcraw,$j3,"numord");

$numord     = 3;


$prorank    = mysql_result($resultcraw,$j3,"prorank");



$posfeed    = mysql_result($resultcraw,$j3,"posfeed");

$posfeed    = 100;


$level      = mysql_result($resultcraw,$j3,"level");


$level      = 5;


$defaultservice = mysql_result($resultcraw,$j3,"defaultservice");

$phone      = mysql_result($resultcraw,$j3,"busphone");

$address    = mysql_result($resultcraw,$j3,"address1");

$city       = mysql_result($resultcraw,$j3,"city");

$state     = mysql_result($resultcraw,$j3,"state");

$license    = mysql_result($resultcraw,$j3,"license");

$statlic    = mysql_result($resultcraw,$j3,"statlic");

$idcompany  = $license;

$j3 = $j3 + 1;


if ( !empty($address) ) $addressfull = $address;

if ( !empty($city) && $address <> $city ) $addressfull = $addressfull." - ".$city;

if ( !empty($state) ) $addressfull = $addressfull." - ".$state;



$top10[$jx][1] = $idworker;

$top10[$jx][2] = $nameworker; 

$top10[$jx][3] = $numord;

$top10[$jx][4] = $level;

$top10[$jx][5] = $posfeed;

$top10[$jx][6] = $prorank;

$top10[$jx][7] = $updpri;

$top10[$jx][8] = $statlic;

$top10[$jx][9] = $idcompany;

$top10[$jx][10] = $servicedesc;

// $top10[$jx][11] = $distance;

$top10[$jx][12] = $phone;

$top10[$jx][13] = $addressfull;

$jx = $jx + 1;











if ( $debugall ||  $debugstep130 ) echo "<br><b> STEP130 This is addressfull $addressfull for professional $busname  lic $license </b><br>";


$addressfull = prepare_location($addressfull);


$latlngpro = prepare_latlng($addressfull);



$locfull = $loc;

$loc = prepare_location($locfull);


$latlngcon = prepare_latlng($loc);




$distance = calc_distance($latlngcon[0], $latlngcon[1], $latlngpro[0], $latlngpro[1]);


$top10[$jx][11] = $distance;


$countline = $countline + 1;


if ( $debugall )
  {

 echo "<br><b> # $countline  $busname  $phone  $address  $city  $state lic # $license status $statlic rank $prorank % </b><br>";

 echo "<br><br> Location $loc for Consumer lat lng : <br>";

 print_r($latlngcon);

 echo "<br> Location $addressfull for Professional is lat lng : <br>";

 print_r($latlngpro);

 echo "<br><b> Distance is $distance </b><br>";

 echo "<br>";

 }

}

 
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

<form action="http://www.movelus.com/id/movelus/searchweb.php" method="post">
<input type="text" class="searchbox1" name="searchinput" size = 120 value="<?php echo $searchfirst ?>" />
<input type="text" class="searchbox2" name="loc" size = 50 value="<?php echo $locfirst ?>" />

<input type="submit" class="searchbutton" value="Search" />
</form>
</div>



<link href="/favicon.ico" rel="shortcut icon">



</head>

<body class=corporate text=#000000 vlink=#800080 alink=#ff0000 link=#0000cc bgcolor=#ffffff topmargin=3>

<br>



<?php

// new by ricardo sardenberg 11 march 2013


$presentad = FALSE;

$strserpos = strpos($searchinput, "intor");

//$searchinput = "%".$searchinput."%";

$queryad = "SELECT * FROM ad WHERE keywords REGEXP '$searchinput?'  ";

        $resultad = mysql_query($queryad);

        $numad = mysql_numrows($resultad);

if ( $debugall || $debugstep200 )
  {
     echo "<br><b> number of ads on ad with $searchinput:".$numad."</b><br>";

     echo mysql_errno($link) . ": " . mysql_error($link) . "\n";

  }


if ( $numad < 1)
  {

        $queryad = "SELECT * FROM ad WHERE keywords REGEXP 'movelus?' AND country LIKE '$country' ";

        $resultad = mysql_query($queryad);

        $numad = mysql_numrows($resultad);


        if ( $debugall || $debugstep200 ) echo "<br><b> Number of Ads found for movelus is $numad </b><br>";


  }
 








        $ja = 0;

        


     while ( $ja < $numad && $ja < 3  )
       {

        $presentad = TRUE;

        $idsponsor = mysql_result($resultad,$ja,"idsponsor");

        $idcampaign = mysql_result($resultad,$ja,"idcampaign");

// 

$source = "web";

$idcustomer = "anonymous";

$latcon = $latlngcon[0];

$lngcon = $latlngcon[1];

$queryadship = "INSERT INTO adship (idsponsor, idcampaign, idcustomer, source, searchinput, today, lat, lng) VALUES ('$idsponsor', '$idcampaign', '$idcustomer','$source','$searchinput','$today2','$latcon','$lngcon')";

        $resultadadship = mysql_query($queryadship);

        $numadship = mysql_numrows($resultadadship);

//     echo "<br> numero de workers available on ad with $searchinput:".$numad;

//    echo "error on insert to adship".mysql_errno($link) . ": " . mysql_error($link) . "\n";





   
        $lat2 = mysql_result($resultad,$ja, "lat");

        $lng2 = mysql_result($resultad,$ja, "lng");

        $statusad2 = mysql_result($resultad,$ja, "statusad");

        $line12 = mysql_result($resultad,$ja, "line1");

        $line22 = mysql_result($resultad,$ja,"line2");

        $line32 = mysql_result($resultad,$ja,"line3");

        $line42 = mysql_result($resultad,$ja, "line4");

        $line52 = mysql_result($resultad,$ja, "line5");

        $ja = $ja + 1;
      
 $linead[0]  = "<table border="; 

 $linead[0] .= chr(34);

 $linead[0] .= "1";

 $linead[0] .= chr(34);

 $linead[0] .= " align=";

 $linead[0] .= chr(34);

 $linead[0] .= "right";

 $linead[0] .= chr(34);

 $linead[0] .= ">";

 echo $linead[0];

 echo "<tr><th>";

 echo $line12;

 echo "</th></tr><tr><td>";

 echo $line22;

 echo "</td></tr><tr><td><a href=";

 echo chr(34);

 echo $line32;

 echo chr(34);

 echo ">$line32</a></td></tr><tr><td>";

 echo $line42;

 echo "</td></tr>";

 echo "</table>";

  }
        






?>










<?php    

// print



$n = $jx;

if ( $debugall )

  {

    echo "<br> MAIN STEP 080 Number of Elements on TOP10 $n <br>";

  } 


$ERRORMSGNUM = 0;

if ( $ERRORMSGNUM < 1)
 {
 
 if ( $n > 0 )
    {

     if ( $n > 500) { $n = 500 ; }

 //    echo "<p> Your request for quote was successfully sent to the $n closest top professional(s) in <b> $subject </b> services !!! </p>";
  
 //    echo "<p> You should be contacted before $todaylimit, you may also enter another request to get 3 more quotes from the next 3 vendors in the ranking </p>";


  $countpro = 0;

  $countproshort = 0;

  $rankprint = 1;

  $al = 0;


  while ( $countpro < $n )
  {

   $countpro = $countpro + 1;
    
      
 //   print_r($potential); // ricardo nov 11 2011



$idworker2     = $top10[$countproshort][1];

$nameworker2   = $top10[$countproshort][2]; 

$numord2       = $top10[$countproshort][3];

$level2        = $top10[$countproshort][4];

$posfeed2      = $top10[$countproshort][5];

$prorank2      = $top10[$countproshort][6];


$updpri2       = $top10[$countproshort][7];

$updsta2       = $top10[$countproshort][8];

$idcompany2    = $top10[$countproshort][9];

$servicedesc2  = $top10[$countproshort][10];

$distance2     = $top10[$countproshort][11];

$phone2        = $top10[$countproshort][12];

$address2      = $top10[$countproshort][13];

if ( $debugall )
  {

   echo "<br> STEP 100 Main Inside while ( countpro $countpro < n $n ) just before print <br>";

   echo "<br> idworker2 $idworker2 nameworker2 $nameworker2 idcompany $idcompany2 prorank $prorank2 <br>";

  }




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







//   echo $linelim; 

     $count3 = 0; // ricardo changed from 1 to 0 on nov 14 2011





        $key = $top10[$countproshort][1];

        $countproshort = $countproshort + 1;
  
//        echo "<br>Key for $countpro is $key <br>";
       


$intop10final = in_array($idworker2, $alreadyarray);

if ( $debugall ) 
  {
 
   echo "<br> Just before triple if <br> ";

   echo "<br> intop10final is $intop10final  for idworker $idworker2 nameworker is $nameworker2   idcompany is $idcompany2 <br>";

   print_r($alreadyarray);

  }

if (( !$intop10final && !empty($nameworker2) && !empty($idcompany2)) || ( !intop10final && crawlon ) )
  {

    $alreadyarray[$al] = $idworker2;

    $al = $al + 1;

  

         $queryabout = "SELECT * FROM about WHERE idworker='$idworker2' ORDER BY aboutnum ASC";

        $resultabout = mysql_query($queryabout);

        $numabout = mysql_numrows($resultabout);

     if ($debugall)   echo "<br> numero entries about for $idworker2 :".$numabout;

       $k = 0; $sitef = "";

       while ( $k <  $numabout)
        {

          
           $abouttemp = mysql_result($resultabout,$k, "aboutdesc");

           $aboutline[$k] = $abouttemp;

          //  echo "<br> Aboutline $aboutline <br>";


           $possite = strpos($abouttemp, "Site");

          // echo "<br> Position of Site in $abouttemp : $possite <br>";

           if ($possite < 1 && $possite > -1)
             {
               
           
               $sitef = substr($abouttemp,6);

             }
           $k = $k + 1;

         } // while k < numabout

      
     


      //  $service2 = $top10[$countpro][7];


   
       if ( $debugall ) echo "<br> Just completed about. nameworker2 is $nameworker2 and idcompany2 is $idcompany2 <br>";

       
        if ( $rankprint < 2 and $presentad ) echo "<br><br><br><br><br><br>";
      

       if ( !empty($nameworker2) and !empty($idcompany2))
        {


        echo "<p><b> # $rankprint : $nameworker2 ( $stalic2 ) - $idcompany2 - $servicedesc2 </b><p>";

        
        $rankprint = $rankprint + 1; 

 
   if ( $country == "US" ) echo "<p> Quality: <img src='http://www.movelus.com/id/movelus/images/stars$level2.jpg' /> ( $numord2 orders ) - Positive Feedback: $posfeed2 % - Ranking: $prorank2 % ";
      else echo "<p> Qualidade: <img src='http://www.movelus.com/id/movelus/images/stars$level2.jpg' /> ( $numord2 orders ) - Avaliacoes Positivas: $posfeed2 % - Ranking: $prorank2 % ";


    if ( $country == "US" ) echo "<p> Standard Price: $updpri2  -  Status: $updsta2  - $today2 - <a target='_blank' href='$sitef'> $sitef </a> </p>";

       else  echo "<p> Preco Padrao: $updpri2  -  Status: $updsta2  - $today2 - <a target='_blank' href='$sitef'> $sitef </a> </p>";

        $k = 0;

    if ( $country == "US" )  echo "<p><b> About: </b></p>";

       else  echo "<p><b> Sobre:  </b></p>";

if ( !empty($address2) )
  {

   $aboutnow1 = "Address: ".$address2;

   echo "<p>  $aboutnow1 </p>";

  }


if ( !empty($phone2) )
  {

    $aboutnow2 = "Phone: ".$phone2;

    echo "<p>  $aboutnow2 </p>";

  }





         while ($k < $numabout)
         {

         $aboutnow = $aboutline[$k];

         $strposhttp = strpos($aboutnow, "http");

   //    echo "<br> strposhttp $strposhttp for $k <br>";

         


         if ( $strposhttp > 0 && $strposhttp <> " ")
           {

            $aboutnow = substr($aboutnow, $strposhttp,100);

     //     echo "<br> aboutnow $aboutnow <br>";

            echo "<p> <a target='_blank' href='$aboutnow'> $aboutnow </a> </p>";
           }
            else echo "<p> $aboutnow </p>";

         $k = $k + 1;

         }

         $site2 = " ";
         $sitef = " ";

 
     //    echo "<p> Distance to this service is : ".$top10[$countpro][8]." mile(s) </p>";

     




   
        if ( $country == "US" ) 
          {

            echo "<p> Distance to this service: ".$distance." mile(s) </p>";



          } 
          else echo "<p> Distancia ate' este servico : ".$distance2." km(s) </p>";

        echo "<br>";

        echo $linelim[$count3];

        
   //   else echo "<br>Already listed<br>";

      
        }
        
        else
        {

         if ( $debugall ) echo "<br>Empty fields nameworker $nameworker2 idcompany $idcompany2 for $idworker2 <br>";

        }

         } // end of if for notintop10final

        

        } // while $countpro < $n

  
 } // end of $n > 0

} // end of ERRORMSG < 1
        
 
  if ( $countpro < 1 )
    {

    if ( $vazio ) $searchinput = "services";

// oiapoque 3.831498,-51.835427

// oeste -7.55 ,  -73.983333

// chui -33.689969 , -53.455321

// seixas 7.155453,-34.79479



    if ( $latcon < 3.84 && $lngcon > -73.983333 && $latcon > -33.689969 && $lngcon <  -34.79479 )
     {

 echo "<p> Confirmacao:  Nao foi possivel encontrar profissionais ou <b> $searchinput </b> disponiveis !!! </p>";
  
     }
    else
    {

     if ( !empty($name) ) 
      {
        echo "<p> Confirmation:  $name, we could not find professionals or $searchinput available !!! </p>";

        echo "<br> I'm a robot and I don't store the information you give me. If you give me your Facebook Password I will search to check if your Facebook friends have referrals for this type of service you are looking for. Do you want to try that? <br>";     



     }
     else
    echo "<p> Confirmation:  We could not find professionals or $searchinput available !!! </p>";
  
  
    }
   
    }








// end of print











if ( $debugall )
   {  

    echo "<br> MAIN STEP070  <br>";

    echo "<br> END <br>";

    echo "<br> $today2 <br>";

   }








?>



<?php

 if ( $latcon < 3.84 && $lngcon > -73.983333 && $latcon > -33.689969 && $lngcon <  -34.79479 )
   {

    echo "<p> Ajude nos a melhorar a disponibilidade recomendando os melhores profissionais que voce conhece e convidando seus amigos para usar a Movelus: </p>";

    echo "<a href='http://www.movelus.com/share'>Compartilhe com Amigos</a>";

   }
   else

   {

echo "<p> Help us improve availability  by recommending the best professionals you know and by inviting your friends to use the service: </p>";

echo "<a href='http://www.movelus.com/share'>Share with Friends and Professionals</a>";

   }


if ( $debugall ) 
  {

   $today2 = date("D F j, Y, g:i a", mktime($thour + 1 ,$tmin,$tsec,$tmonth,$tday,$tyear));

   $today2 = $today2 = date('l jS \of F Y h:i:s:u A');

   echo "<br> Main Program SEARCHWEB V.1.0.0 (Last update by RS 27 Feb 2014) <br>";

   echo "<br> Execution completes at $today2 <br>";

  }

?>

 <br><br>






<p><font size="-1"><a href="http://www.movelus.com/">To Home page click here</a></font></p>




</body></html>
