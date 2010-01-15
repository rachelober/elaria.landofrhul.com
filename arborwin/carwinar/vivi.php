<?php 
$animal = "Carwinar";
$name = "Vivi`am'chri";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="r15m.gif" width="146" height="223" alt="<?php echo($name); ?>"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>r15m</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Male</td>
    </tr>
    <tr> 
      <td>Parentage:</td>
      <td>Wild x Wild</td>
    </tr>
    <tr> 
      <td>Price:</td>
      <td>20c</td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://arborwin.com/">Arborwin</a></td>
    </tr>
  </table>
</div>
<?php include('../../files/footer.php'); ?>
