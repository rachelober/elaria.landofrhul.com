<?php 
$animal = "Minitaurs";
$name = "Delrado";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="012_m.png" alt="<?php echo($name); ?>" width="267" height="333"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>012</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Stallion</td>
    </tr>
    <tr> 
      <td>Lineage:</td>
      <td><a href="http://sunberry.ruthieangel.com/">002</a> x <a href="http://sunberry.ruthieangel.com/">011</a></td>
    </tr>
    <tr> 
      <td>Price:</td>
      <td>300 voodles</td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://locker.uky.edu/~bseast2/minitaurs/">Amethyst</a></td>
    </tr>
  </table>
</div>
<?php include('../../files/footer.php'); ?>
