<?php 
$animal = "Chevei";
$name = "Andille";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="c67f.gif" alt="<?php echo($name); ?>" width="323" height="431"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>c67f</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Mare</td>
    </tr>
    <tr> 
      <td>Lineage:</td>
      <td>Junice x Tamot</td>
    </tr>
    <tr> 
      <td>Genotype:</td>
      <td>Ears: R*, r<br />
        Forefeet: G*, g*<br />
        Hindfeet: H, h*<br />
        Mane: M*, M*<br />
        Tail: T*, t<br />
        Fetlocks: d*, d*<br />
        Mutation: S, S<br />
        Coloring: W*, W</td>
    </tr>
    <tr> 
      <td>Phenotype:</td>
      <td>deer ears, meso fore, paws hind, long mane, fox tail, long fets, no 
        mut, random col. </td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://arborwin.com/">Arborwin</a></td>
    </tr>
  </table>
  <h2>Foal</h2>
  <p align="center"><img src="c67f_foal.gif" alt="Andille" width="277" height="274"></p>
</div>
<?php include('../../files/footer.php'); ?>
