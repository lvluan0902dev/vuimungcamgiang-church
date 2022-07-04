<style type="text/css">
	nav{background:red left no-repeat; background-position: 15px; color: white; text-align:center; position: fixed; top:30%; left:0; width: 100%; } 
	.code{padding: 7px; font-size: 23px; } 
	.clickmoi{border: 0px; padding: 10px; font-size: 23px; border-radius:2px; color:white; background: blue; cursor: pointer; }
	.myLink {color:#333333; text-decoration: none;}
</style>
<nav>
	<center>
	    <?php
	    if ($_SESSION['nbre_essai'] != 0) {
	    ?>
		<h3>ANTI DDOS SYSTEM ACTIVATED</h3>
		<form method="post" name="<?=$nom_form; ?>">
			<input type="hidden" name="<?=$_SESSION['variable_du_form']; ?>" value="JnYHSNp">
			<img height="130" width="400" src="anti_ddos/securitecode.php"><br>
			<h2> You have <?=($_SESSION['nbre_essai']); ?> tries left(s)</h2>
			<input type="text" name="valCAPTCHA" class="code" placeholder="Enter the code here">
			<!--<input type="button" class="clickmoi" onclick="go()" value="Verify">-->
			<button type="button" class="clickmoi" onclick="document.<?=$nom_form; ?>.submit();">Verify</button>
		</form>
		<?php
	    }
	    else {
	        ?>
	        <h3>
	            You have been blocked on suspicion of DDOS
	            <br>
	            Contact: <a class="myLink" href="https://www.facebook.com/lvluan0902/" target="_blank">Le Van Luan</a>
	        </h3>
	        <?php
	    }
		?>
	</center>
</nav>
<!--<script type="text/javascript">function go(){ document.<?=$nom_form; ?>.submit(); }</script>-->