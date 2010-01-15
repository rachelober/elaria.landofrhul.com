<?php
// --------------------------------------------
// | The EP-Dev Counter script        
// |                                           
// | Copyright (c) 2002-2003 EP-Dev.com :           
// | This program is distributed as free       
// | software under the GNU General Public     
// | License as published by the Free Software 
// | Foundation. You may freely redistribute     
// | and/or modify this program.               
// |                                           
// --------------------------------------------

echo "The absolute path to this directory is:<br><b>".str_replace("abs.php", "", $_ENV['PATH_TRANSLATED'])."</b><br><br><br> Please set \$OPTION['Absolute_Path'] to equal the above bolded path. For example...<br><br>\$OPTION['Absolute_Path'] = \"".str_replace("abs.php", "", $_ENV['PATH_TRANSLATED'])."\";";