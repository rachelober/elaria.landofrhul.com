<?php 
$animal = "Minitaurs";
$name = "Lerathi";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="001_f.png" alt="<?php echo($name); ?>" width="207" height="333"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>001</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Mare</td>
    </tr>
    <tr> 
      <td>Price:</td>
      <td>500 voodles</td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://locker.uky.edu/~bseast2/minitaurs/">Amethyst</a></td>
    </tr>
  </table>
  
</div>
<?php include('../../files/footer.php'); ?>
