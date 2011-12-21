<?php
//width and height image
$config['width']=150;
$config['height']=50;

//image background color (rgb)
$config['background']=null;//array(1,1,1) //if gradient ==false
$config['gradient']=true;
	
//create random lines 	
$config['randomLinesBottom']=true;		
$config['randomLinesTop']=true;		
	
//if text is mathematical operation	
$config['math']=false;
$config['math_int1']=array(1,10);
$config['math_int2']=array(1,10);
$config['math_math']=array('+','-','*');
	
//number of characters if not math	
$config['lenght']=4;
$config['randomText']=true;
$config['fontPath']= 'Media/Font/';
$config['fonts']= array('Garuda-Bold.ttf');

//special effects
//Options: blur, embossing, edgedetect, meanRemoval
$config['effects']=array();
return $config;
