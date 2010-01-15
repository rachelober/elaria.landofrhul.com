<?php 
$animal = "Kirin";
$name = "Ashardwor";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="004_M.png" alt="<?php echo($name); ?>" width="198" height="368"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>004</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Buck</td>
    </tr>
    <tr> 
      <td>Elements:</td>
      <td>Water, Moonlight</td>
    </tr>
    <tr> 
      <td>Price:</td>
      <td>900 voodles</td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://www.cosmicjive.org/">Syrcaid</a></td>
    </tr>
  </table>
  
</div>
<?php include('../../files/footer.php'); ?>
