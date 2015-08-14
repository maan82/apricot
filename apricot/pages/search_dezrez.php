<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php 
    if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
        {
          if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
          }
        
          $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
        
          switch ($theType) {
            case "text":
              $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
              break;    
            case "long":
            case "int":
              $theValue = ($theValue != "") ? intval($theValue) : "NULL";
              break;
            case "double":
              $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
              break;
            case "date":
              $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
              break;
            case "defined":
              $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
              break;
          }
          return $theValue;
        }
    }
    if(!function_exists("GetFromDezrez")) {
        function GetFromDezrez($url) {
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $output = curl_exec($ch);
            curl_close($ch); 
            return $output; 
        }
    }
    
    function PictureExists($propertyId, $dezrezPicId) {
        $query_Prop_Type_RS = "SELECT * FROM pictures where PROPERTY_ID=".$propertyId." and DEZREZ_ID =".$dezrezPicId;
        $Prop_Type_RS = mysql_query($query_Prop_Type_RS) or die(mysql_error());
        $row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
        $count = mysql_num_rows($row_Prop_Type_RS);
        mysql_freeresult($Prop_Type_RS);  
        return $count > 0;
    }
    
    function DownloadProperties($dezrezRentalPeriod, $propertyFor) {
        $URL_AUTH_STRING = "&apiKey=B4EDB1F9-0210-45AC-8E67-B176C6FCFA76&eaid=1587&baid=2610";
        $PROP_SEARCH_URL = "http://www.dezrez.com/DRApp/DotNetSites/WebEngine/property/Default.aspx?&perpage=32000&xslt=-1".$URL_AUTH_STRING;
        $PROP_PICTURE_URL = "http://www.dezrez.com/DRApp/DotNetSites/WebEngine/property/pictureResizer.aspx?".$URL_AUTH_STRING;
        $PROP_DETAILS_URL = "http://www.dezrez.com/DRApp/DotNetSites/WebEngine/property/Property.aspx?xslt=-1".$URL_AUTH_STRING;

        // $output contains the output string 
        $propertiesXML = new SimpleXMLElement(GetFromDezrez($PROP_SEARCH_URL."&rentalPeriod=".$dezrezRentalPeriod));
        $i = 0;
        $propRS = array();
        $propertiesEnteriesXML = $propertiesXML->propertySearchSales->properties;
        if ($dezrezRentalPeriod != "0") {
            $propertiesEnteriesXML = $propertiesXML->propertySearchLettings->properties;
        }
        $totalRows_propRS = $propertiesEnteriesXML->pages['count'];
        $totalPages_propRS = $propertiesEnteriesXML->pages['pageCount'];
        foreach ($propertiesEnteriesXML->property as $element) {
            echo $element["priceVal"];
            $deletePictures = sprintf("DELETE from pictures where PROPERTY_ID=%s", GetSQLValueString($element['id'], "int"));
            $Result1 = mysql_query($deletePictures) or die(mysql_error());
            
            $deleteProp = sprintf("DELETE from property_details where PROPERTY_ID=%s", GetSQLValueString($element['id'], "int"));
            $Result1 = mysql_query($deleteProp) or die(mysql_error());
            
            $propertyDetailsXML = new SimpleXMLElement(GetFromDezrez($PROP_DETAILS_URL."&pid=".$element['id']));
            $fullDescription = "";
            foreach ($propertyDetailsXML->propertyFullDetails->property->text->areas->area as $area) {
                $fullDescription = $fullDescription. "<h3>".$area['title']."</h3>";
                foreach ($area->feature as $feature) {
                    $fullDescription = $fullDescription. "<h4>".$feature->heading."</h4>";
                    $fullDescription = $fullDescription. "<p>".$feature->description."</p>";
                }
            }
        $address = $element->num." ,".$element->sa1." ,".$element->city
        ." ,".$element->town." ,".$element->county." ,".$element->postcode
        ." ,".$element->country." ,".$element->useAddress;    
        $insertSQL = 
            sprintf("INSERT INTO property_details (
                  PROPERTY_ID,
                  STATUS_ID,
                  PROPERTY_FOR_ID,
                  PROPERTY_TYPE_ID,
                  PRICE, 
                  CURRENCY_ID, 
                  BEDROOMS, 
                  BATHROOMS, 
                  KITCHENS, 
                  DRAWING_ROOMS, 
                  DINING_ROOMS, 
                  PARKING,
                  LAWN, 
                  BRIEF_DESCRIPTION,
                  DETAIL_DESCRIPTION, 
                  UPDATION_DATE,
                  IS_HOT,
                  PROPERTY_OF_WEEK,
                  LATITUDE, 
                  LONGITUDE, 
                  PROP_HOUSE_NO, 
                  POSTCODE,  
                  CITY,
                  DEZREZ_TOWN, 
                  PROP_COUNTY, 
                  PROP_STREET, 
                  COUNTRY,
                  DEZREZ_USE_ADDRESS,
                  PROP_ADDRESS,
                  DEZREZ_LEASE_TYPE,
                  DEZREZ_DATE_STC,
                  DEZREZ_UPDATED,
                  DEZREZ_RENTAL_PERIOD,
                  DEZREZ_DELETED
                  ) 
            VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s)",
               GetSQLValueString($element['id'], "int"),
               GetSQLValueString(1, "int"),
               GetSQLValueString($propertyFor, "int"),
               GetSQLValueString($element['propertyType'], "int"),
               GetSQLValueString($element['priceVal'], "int"),
               GetSQLValueString(1, "int"),
               GetSQLValueString($element['bedrooms'], "int"),
               GetSQLValueString($element['bathrooms'], "int"),
               GetSQLValueString(0, "int"),
               GetSQLValueString($element['receptions'], "int"),
               GetSQLValueString($element['otherrooms'], "int"),
               GetSQLValueString($element['parkingSpaces'], "int"),
               GetSQLValueString($element['gardens'], "int"),
               GetSQLValueString($element->summaryDescription, "text"),
               GetSQLValueString($fullDescription, "text"),
               "NOW()",
               GetSQLValueString($element['featured'], "text"),
               GetSQLValueString($element['featured'], "text"),
               GetSQLValueString($element['latitude'], "double"),
               GetSQLValueString($element['longitude'], "double"),
               GetSQLValueString($element->num, "text"),
               GetSQLValueString($element->postcode, "text"),
               GetSQLValueString($element->city, "text"),
               GetSQLValueString($element->town, "text"),
               GetSQLValueString($element->county, "text"),
               GetSQLValueString($element->sa1, "text"),
               GetSQLValueString($element->country, "text"),
               GetSQLValueString($element->useAddress, "text"),
               GetSQLValueString(strtoupper($address), "text"),
               GetSQLValueString($element['leaseType'], "text"),
               GetSQLValueString($element['dateSTC'], "date"),
               GetSQLValueString($element['updated'], "date"),
               GetSQLValueString($element['rentalperiod'], "int"),
               GetSQLValueString($propertyDetailsXML->propertyFullDetails->property['deleted'], "text"));
               
               echo $insertSQL;
               echo $element['priceVal'];
               $Result1 = mysql_query($insertSQL) or die(mysql_error());
               
               foreach ($propertyDetailsXML->propertyFullDetails->property->media->picture as $picture) {
                   
                   $picture_ID = getSeqNextVal( $sehyogDB, $database_sehyogDB, "sequence_picture_id" );
                   $mainimg = "N";
                   if($picture['category'] == "primary") {
                       $mainimg = "Y";
                   }
                   $title = "";
                   $origWidthURL = "&width=4900";
                   $thumbWidthURL = "&width=96";
                   $slideWidthURL = "&width=200";
                   $fullWidthURL = "&width=490";
                   
                   list($width, $height, $type, $attr) = getimagesize($picture.$origWidthURL);
                   list($thumbWidth, $thumbHeight, $type, $attr) = getimagesize($picture.$thumbWidthURL);
                   list($slideWidth, $slideHeight, $type, $attr) = getimagesize($picture.$slideWidthURL);
                   list($fullWidth, $fullHeight, $type, $attr) = getimagesize($picture.$fullWidthURL);

                   if (strtoupper($picture['category']) == "FLOORPLAN") {
                       $title = strtoupper($picture['category']);
                   }
                   $insertPictureSQL = sprintf("INSERT INTO pictures ( 
                   PICTURE_ID, 
                   PROPERTY_ID, 
                   IS_MAIN, 
                   TITLE, 
                   COMMENTS, 
                   DEZREZ_ID,
                   DEZREZ_CATEGORY,
                   DEZREZ_CATEGORY_ID,
                   DEZREZ_CAPTION,
                   DEZREZ_UPDATED,
                   THUMB_PIC_PATH, 
                   THUMB_WIDTH,
                   THUMB_HEIGHT,   
                   SLIDE_PIC_PATH, 
                   SLIDE_WIDTH,
                   SLIDE_HEIGHT,   
                   FULL_PIC_PATH, 
                   FULL_WIDTH,
                   FULL_HEIGHT,   
                   ORIGINAL_PIC_PATH,
                   ORIGINAL_WIDTH,
                   ORIGINAL_HEIGHT) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                   GetSQLValueString($picture_ID, "double"),                                                                                                                               
                   GetSQLValueString($element['id'], "int"),
                   GetSQLValueString($mainimg, "text"),
                   GetSQLValueString($title, "text"),
                   GetSQLValueString($picture['caption'], "text"),
                   GetSQLValueString($picture['id'], "text"),
                   GetSQLValueString($picture['category'], "text"),
                   GetSQLValueString($picture['categoryID'], "text"),
                   GetSQLValueString($picture['caption'], "text"),
                   GetSQLValueString($picture['updated'], "text"),
                   GetSQLValueString($picture.$thumbWidthURL, "text"),
                   GetSQLValueString($thumbWidth, "int"),
                   GetSQLValueString($thumbHeight, "int"),
                   GetSQLValueString($picture.$slideWidthURL, "text"),
                   GetSQLValueString($slideWidth, "int"),
                   GetSQLValueString($slideHeight, "int"),
                   GetSQLValueString($picture.$fullWidthURL, "text"),
                   GetSQLValueString($fullWidth, "int"),
                   GetSQLValueString($fullHeight, "int"),
                   GetSQLValueString($picture.$origWidthURL, "text"),
                   GetSQLValueString($width, "int"),
                   GetSQLValueString($height, "int"));
        
    
                  $Result1 = mysql_query($insertPictureSQL) or die(mysql_error());
                  echo "\n".$insertPictureSQL;
                                       
            }                   
    
    
        }
    }
    $propFor = array("0" => "1", "4" => "2");
    foreach ($propFor as $key => $value) {
        echo DownloadProperties($key, $value);
    }
    
    
    // close curl resource to free up system resources 
         
?>