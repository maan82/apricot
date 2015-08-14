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
    
    function CanRun() {
        $RS = mysql_query('SELECT MAX(CREATION_DATE) < NOW() - INTERVAL 1 HOUR  RUN FROM property_details') or die(mysql_error());
        $rowRS = mysql_fetch_assoc($RS);
        $run = $rowRS['RUN'];
        mysql_freeresult($Prop_Type_RS);
        return $run == "1";
    }

    function getLastMaxCreationDate() {
        $RS = mysql_query('SELECT MAX(CREATION_DATE) MAX_DATE FROM property_details') or die(mysql_error());
        $rowRS = mysql_fetch_assoc($RS);
        $run = $rowRS['MAX_DATE'];
        mysql_freeresult($Prop_Type_RS);
        return $run ;
    }
    
    function deletePropertiesDeletedFromDezrez($lastMaxCreationDate) {
        $deletePictures = sprintf("DELETE from pictures where PROPERTY_ID IN (SELECT PROPERTY_ID from property_details where CREATION_DATE <= %s)", GetSQLValueString($lastMaxCreationDate, "date"));
        $Result1 = mysql_query($deletePictures) or die(mysql_error());
        echo "Deleted old pictures : ".$Result1;
        $deletePictures = sprintf("DELETE from property_details where CREATION_DATE <= %s", GetSQLValueString($lastMaxCreationDate, "date"));
        $Result1 = mysql_query($deletePictures) or die(mysql_error());
        echo "Deleted old properties : ".$Result1;
    }

    function getImage($dezrezURL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $dezrezURL); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // good edit, thanks!
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); // also, this seems wise considering output is image.
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    } 
       
    function addSTC($propID, $dezrezThumbImageURL, $dezrezSlideImageURL){
        $uploaddir = '../property_images/';
        $propdir = $uploaddir.$propID;
        if(!file_exists($propdir))
            mkdir($propdir);
        
        $logo_file = "images/soldstc.png"; 
        $thumb_img = '../images/thumb_tmp';
        $content = file_get_contents($dezrezThumbImageURL);
        file_put_contents($thumb_img, $content);
        $image_file = $thumb_img;
        $image_info = getimagesize($thumb_img);
        //echo "mannestateDB = ".$mannestateDB;
        $picture_ID = getSeqNextVal( $sehyogDB, $database_sehyogDB, "sequence_picture_id" );
        $uploadfilename = $picture_ID.'_STC_THUMB';
        $photo = "";
        $image_type = $image_info[2];
        //echo "type = ".$image_info;
          if( $image_type == IMAGETYPE_JPEG ) {
             $photo = imagecreatefromjpeg($image_file);
             $uploadfile = '../property_images/'.$propID.'/'.$uploadfilename.".jpg";
          } elseif( $image_type == IMAGETYPE_GIF ) {
             $photo = imagecreatefromgif($image_file);
             $uploadfile = '../property_images/'.$propID.'/'.$uploadfilename.".gif";
          } elseif( $image_type == IMAGETYPE_PNG ) {
             $photo = imagecreatefrompng($image_file);
             $uploadfile = '../property_images/'.$propID.'/'.$uploadfilename.".png";
          }
        //echo "photo".$photo;
        $fotoW = imagesx($photo); 
        $fotoH = imagesy($photo); 
        $targetfile = $uploadfile; 
    
        $logoImage = imagecreatefrompng($logo_file); 
        $logoW = imagesx($logoImage); 
        $logoH = imagesy($logoImage); 
        $photoFrame = imagecreatetruecolor($fotoW,$fotoH); 
        $dest_x = $fotoW - $logoW; 
        $dest_y = $fotoH - $logoH; 
        imagecopyresampled($photoFrame, $photo, 0, 0, 0, 0, $fotoW, $fotoH, $fotoW, $fotoH); 
        imagecopy($photoFrame, $logoImage, 0, 0, 0, 0, $logoW, $logoH); 
        imagejpeg($photoFrame, $targetfile);  
    //  echo "dest_x".$dest_x;
    
        $slide_img = '../images/slide_tmp';
        file_put_contents($slide_img, file_get_contents($dezrezSlideImageURL));
            
        $image_file = $slide_img; 
        $uploadfilename = $picture_ID.'_STC_SLIDE';
          if( $image_type == IMAGETYPE_JPEG ) {
             $photo = imagecreatefromjpeg($image_file);
             $uploadfile = '../property_images/'.$propID.'/'.$uploadfilename.".jpg";
          } elseif( $image_type == IMAGETYPE_GIF ) {
             $photo = imagecreatefromgif($image_file);
             $uploadfile = '../property_images/'.$propID.'/'.$uploadfilename.".gif";
          } elseif( $image_type == IMAGETYPE_PNG ) {
             $photo = imagecreatefrompng($image_file);
             $uploadfile = '../property_images/'.$propID.'/'.$uploadfilename.".png";
          }
        $fotoW = imagesx($photo); 
        $fotoH = imagesy($photo); 
        $slidetargetfile = $uploadfile; 
    
        $logoImage = imagecreatefrompng($logo_file); 
        $logoW = imagesx($logoImage); 
        $logoH = imagesy($logoImage); 
        $photoFrame = imagecreatetruecolor($fotoW,$fotoH); 
        $dest_x = $fotoW - $logoW; 
        $dest_y = $fotoH - $logoH; 
        imagecopyresampled($photoFrame, $photo, 0, 0, 0, 0, $fotoW, $fotoH, $fotoW, $fotoH); 
        imagecopy($photoFrame, $logoImage, 0, 0, 0, 0, $logoW, $logoH); 
        imagejpeg($photoFrame, $slidetargetfile);  
        
        
        $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
                                       GetSQLValueString("N", "text"),
                                       GetSQLValueString($propID, "int"));
        $mainRS = mysql_query($updateSQL) or die(mysql_error());
        //echo $updateSQL;
        $insertSQL = sprintf("INSERT INTO pictures ( PICTURE_ID, PROPERTY_ID, IS_MAIN, TITLE, COMMENTS, THUMB_PIC_PATH, SLIDE_PIC_PATH, FULL_PIC_PATH,    ORIGINAL_PIC_PATH) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                   GetSQLValueString($picture_ID, "double"),                                                                                                                               
                   GetSQLValueString($propID, "int"),
                   GetSQLValueString('Y', "text"),
                   GetSQLValueString("STC", "text"),
                   GetSQLValueString("", "text"),
                   GetSQLValueString($targetfile, "text"),
                   GetSQLValueString($slidetargetfile, "text"),
                   GetSQLValueString($slidetargetfile, "text"),
                   GetSQLValueString($slidetargetfile, "text"));
        echo $insertSQL;
        $picRS = mysql_query($insertSQL) or die(mysql_error());
    
    }

    function IsSTC($property) {
        return "2" == $property['sold'];
    }
    

    function DownloadProperties($dezrezRentalPeriod, $propertyFor) {
        $URL_AUTH_STRING = "&apiKey=B4EDB1F9-0210-45AC-8E67-B176C6FCFA76&eaid=1587&baid=2610";
        $PROP_SEARCH_URL = "http://www.dezrez.com/DRApp/DotNetSites/WebEngine/property/Default.aspx?&perpage=1&xslt=-1&showSTC=true".$URL_AUTH_STRING;
        $PROP_PICTURE_URL = "http://www.dezrez.com/DRApp/DotNetSites/WebEngine/property/pictureResizer.aspx?".$URL_AUTH_STRING;
        $PROP_DETAILS_URL = "http://www.dezrez.com/DRApp/DotNetSites/WebEngine/property/Property.aspx?xslt=-1".$URL_AUTH_STRING;
         $origWidthURL = "&width=4900";
         $thumbWidthURL = "&width=96";
         $slideWidthURL = "&width=200";
         $fullWidthURL = "&width=490";

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
            echo "Loading property ....... ".$element["id"];
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
               GetSQLValueString($propertyDetailsXML->propertyFullDetails->property->text->description[1], "text"),
               GetSQLValueString("<div>".$element->summaryDescription."</div>".$fullDescription, "text"),
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

          //     echo $insertSQL;
           //    echo $element['priceVal'];
               $Result1 = mysql_query($insertSQL) or die(mysql_error());
               $main_picture = $propertyDetailsXML->propertyFullDetails->property->media->picture[0];
               if (IsSTC($element) && $main_picture) {
                    addSTC($element['id'], $main_picture.$thumbWidthURL, $main_picture.$slideWidthURL);    
               } 
               foreach ($propertyDetailsXML->propertyFullDetails->property->media->picture as $picture) {

                   $picture_ID = getSeqNextVal( $sehyogDB, $database_sehyogDB, "sequence_picture_id" );
                   $mainimg = "N";
                   if($picture['category'] == "primary" && !IsSTC($element)) {
                       $mainimg = "Y";
                   }
                   $title = "";

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
                 //echo "\n".$insertPictureSQL;

            }


        }
    }
    if (!CanRun()) {
        $lastMaxCreationDate = getLastMaxCreationDate();
        echo "Last lastMaxCreationDate :".$lastMaxCreationDate;
        $propFor = array("0" => "1", "4" => "2");
        foreach ($propFor as $key => $value) {
            echo date('Y-m-d H:i:s');
            echo "\n";
            DownloadProperties($key, $value);
            
            echo "\n".date('Y-m-d H:i:s');
            echo "\n";
        }
        deletePropertiesDeletedFromDezrez($lastMaxCreationDate);
    } else {
        echo "\n Cannot update more than once in 1 hour. Exiting.";
    }


    // close curl resource to free up system resources

?>