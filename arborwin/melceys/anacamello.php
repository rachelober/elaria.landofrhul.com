<?php 
$animal = "Melceys";
$name = "Anacamello";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="p601m.gif" width="150" height="186" alt="<?php echo($name); ?>"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>p601m</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Male</td>
    </tr>
    <tr> 
      <td>Theme:</td>
      <td>Candy</td>
    </tr>
    <tr> 
      <td>Lineage:</td>
      <td>Odessa's Keis x Relia</td>
    </tr>
    <tr> 
      <td>Litter:</td>
      <td>71</td>
    </tr>
    <tr> 
      <td>Pack:</td>
      <td><a href="http://www.geocities.com/pinkwaterkc/posd.html">The Pack of 
        Silver Dust</a></td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://arborwin.com/">Arborwin</a></td>
    </tr>
  </table>
</div>
<?php include('../../files/footer.php'); ?>
