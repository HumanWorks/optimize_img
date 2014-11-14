<?php
/**
 * PHP cli script that optimizes images (jpg and png) recursive in a directory
 * 
 * <code>
 * $ php optimize_img.php
 * </code>
 * 
 * Before using it make sure to install optipng and jpegtran. On Debian/Ubuntu that would be:
 * 
 * <code>
 * $ sudo apt-get install libjpeg-progs optipng
 * </code>
 * 
 * @version $Id: optimize_img.php,v 1.00 2009/11/18 10:54:32 $
 * @copyright Copyright (c) 2014 Nick Papanotas (http://twitter.com/HumanWorks)
 * @author Nick Papanotas <nikolas@webdigity.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
 *
 */

define('DIRECTORY', dirname(__FILE__));

function GetDirContents($dir){
       if (!is_dir($dir)){die ("Function GetDirContents: Problem reading : $dir!");}
       if ($root=@opendir($dir)){
           while ($file=readdir($root)){
               if($file=="." || $file==".."){continue;}
               if(is_dir($dir."/".$file)){
                   $files=@array_merge($files,GetDirContents($dir."/".$file));
               }else{
               $files[]=$dir."/".$file;
               }
           }
       }
       return $files;
}


foreach(GetDirContents(DIRECTORY) as $f){
    $ext = strtolower(array_pop(explode('.', $f)));
    if ($ext == 'jpg' || $ext == 'png') {
        echo $f, "\n";
        $size = filesize($f);
        if ( $ext == 'jpg')
            exec( 'jpegtran -copy none -optimize -outfile '. $f . ' ' . $f );
        else
            exec( 'optipng ' . $f );
        clearstatcache();
        $size_new = filesize($f);
        $perc = 100 - ($size_new/$size ) * 100;
        echo 'Original size: ', $size, ' bytes, New size: ', $size_new, ' bytes.', "\nReduced by: ", number_format($perc,2), "%\n";
    }
}
?>