<?php 
$animal = "Chevei";
$name = "Mablei";
include('../../files/header.php'); ?>
<div id="CONTENT"> 
  <p align="center"><img src="c108f.gif" width="511" height="431" alt="<?php echo($name); ?>"></p>
  <h2>Stats</h2>
  <table>
    <tr> 
      <th colspan="2"><?php echo($name); ?></th>
    </tr>
    <tr> 
      <td>ID:</td>
      <td>c108f</td>
    </tr>
    <tr> 
      <td>Gender:</td>
      <td>Mare</td>
    </tr>
    <tr> 
      <td>Lineage:</td>
      <td>Junice x Ambrin</td>
    </tr>
    <tr> 
      <td>Genotype:</td>
      <td>Ears: R*, r*<br>
        Forefeet: G*, G<br>
        Hindfeet: h, h*<br>
        Mane: M*, M<br>
        Tail: t, t*<br>
        Fetlocks: d*, d*<br>
        Mutation: S, S<br>
        Coloring: W*, w*</td>
    </tr>
    <tr> 
      <td>Phenotype:</td>
      <td>deer ears, meso hore, meso hind, long mane, lion tail, long fetlocks, 
        no mut, random col.</td>
    </tr>
    <tr> 
      <td colspan="2"><?php echo($animal); ?> &copy; <a href="http://arborwin.com/">Arborwin</a></td>
    </tr>
  </table>
  <h2>Foal</h2>
  <p align="center"><img src="/elaria/arborwin/chevei/c108f_foal.gif" alt="Mablei" width="365" height="320"></p>
</div>
<?php include('../../files/footer.php'); ?>
