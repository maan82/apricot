

<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class SimpleImageIM {
   
   var $image;
   var $image_type;
   var $thumbWidth = 96;
   var $thumbHeight = 80;
   var $slideWidth = 200;
   var $slideHeight = 150;
   var $fullWidth = 490;
   var $fullHeight = 368;
   var $floorWidth = 640;
   var $floorHeight = 480;
   
 
   function getWidthIM($source) {
   	  $p = new phMagick($source); 
	  return $p->getWidth();
   }
   function getHeightIM($source) {
   	  $p = new phMagick($source); 
	  return $p->getHeight();
   }

   function resizeToThumbIM($source, $destination) {
      $this->resizeIM($source, $destination, $this->thumbHeight, $this->thumbWidth);		
   }
   
   function resizeToSlideIM($source, $destination) {
      $this->resizeIM($source, $destination, $this->slideHeight, $this->slideWidth);		
   }

   function resizeToFullIM($source, $destination) {
      $this->resizeIM($source, $destination, $this->fullHeight, $this->fullWidth);		
   }

   function resizeToFloorPlanIM($source, $destination) {
      $this->resizeIM($source, $destination, $this->floorHeight, $this->floorWidth);		
   }

   function resizeIM($source, $destination, $heightReq, $widthReq) {
  	  $p = new phMagick($source, $destination); 
	  $height = $p->getHeight($source);
	  $width = $p->getWidth($source);
	  
	  if(($height > $width) && ($height > $heightReq)){
	         $p->resize(0, $heightReq);
			 if($p->getWidth() > $widthReq)
			 	$p->resize($widthReq, 0);
	  }
	  else if($width > $widthReq)
	  {
	     $p->resize($widthReq, 0);
			 if($p->getHeight() > $heightReq)
			 	$p->resize(0, $heightReq);
	  }
   }

}
?>

