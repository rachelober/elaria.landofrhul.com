<?php 
$animal = "Melceys";
$name = "Spook";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="p682f.gif" width="393" height="228" alt="<?php echo($name); ?>"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>p682f</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Female</td>
    </tr>
    <tr> 
      <td>Theme:</td>
      <td>Riptide</td>
    </tr>
    <tr> 
      <td>Lineage:</td>
      <td>Wild x Wild</td>
    </tr>
    <tr> 
      <td>Litter:</td>
      <td>78 (Halloween)</td>
    </tr>
    <tr> 
      <td>Pack:</td>
      <td><a href="http://www.geocities.com/akatsukunosora/Cipher.html">The Pack 
        of Shadow Cipher</a></td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://arborwin.com/">Arborwin</a></td>
    </tr>
  </table>
</div>
<?php include('../../files/footer.php'); ?>
