<?php
/*
 * UWS - Universal Wealth System
 * addToInventory.php
 * GPL license
 * author: Fabio Barone
 * date: 30. Nov. 2009
 * 
 * This file handles adding goods to the inventory.
 * It first displays a form where the user can enter
 * the good, its amount and the factor. Clicking the
 * update button calculates the amount of inventory units.
 * Saving the entry will call saveToInventory.php.
 */
	session_start();
	include "config.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

	terrafirma1.0 by nodethirtythree design
	http://www.nodethirtythree.com

-->
<html>
<head>


<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Universal Wealth System UWS - <?php echo translate("uws:add_inventory") ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" href="default.css" />
	
<script type="text/javascript">
		//the action behind the save button, as the form's action
		//is linked to updating the form itself'
        function saveToInventory() {
                document.inventorize.action = "saveToInventory.php";
                document.inventorize.submit();
        }
        
</script>
</head>

<body>
<div id="outer">

	<div id="upbg"></div>

	<div id="inner">
	
	<?php include "header.php" ?>

			<h3><?php echo translate("uws:add_inventory") ?></h3>
			<div class="date">
			<?php echo date('d F Y') ?></div> <!-- div date -->
				</div><!-- div header -->
				<div class="content">
<?php 
	
	//first get variables from the environment and the 
	//POST variable
	$username 		= $_SESSION['uname'];
		
	$user			= "";
	$factor 		= $_POST['factor'];	
	$unit 			= $_POST['asset'];
	$value 			= $_POST['value'];
	$desc 			= $_POST['desc'];
	
	$weighted_val  	= $factor * $value;
	
	$selected_asset = null;
	$checked 		= "checked";
	$submit    		= "disabled";
	
	//first the submit button is disabled;
	//if the form has been submitted (update has been clicked),
	//the save button is enabled
	if (isset($_POST['update']))
	{			
		$submit = "";
	}
	//first the donate checkbox is checked;
	//when the form has been submitted (update has been clicked),
	//check the donate checkbox setting accordingly
	if ( (isset($_POST['update'])) && (! isset($_POST['donate'])) )	
	{	
		$checked="";	
	}
	//set the value of the asset in the list box
	//to the selected value by the user, after
	//the form has been submitted (update has been clicked)
	if (isset($_POST['asset']))
	{
		$selected_asset = $_POST['asset'];
	}
	//get the value of the username if it is not the loggedin user
	if (isset($_POST['user']))
	{
		//echo "POST VARIABLE user set!";
		$user = $_POST['user'];
	}
	else
	{
		$user	= $username;
	}
	
	
	//first get all asset names from the database and fill them into an array
	$sql = "SELECT asset FROM assetlist";
	$query = mysql_query($sql);
	$asset_list = array();
	
	while ($result = mysql_fetch_array($query)) 
	{
		array_push($asset_list, current($result));
	}
	
	//echo "donate isset: " . (isset($_POST['donate'])) . " - submit isset: " . ($_POST['submit']);
	//Now show the form
?>
	<form name="inventorize" id="story" action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
<table class="formtable" width="470" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50" class="text"><?php echo translate("uws:user") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text"><input name="user" type="text" id="user" value="<?php echo $user?>" size="30" />
          </span></td>
        </tr>
        <tr>
          <td width="50">&nbsp;</td>
          <td width="5">&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td width="50" class="text"><?php echo translate("uws:asset") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text">          
        	<!--  <input name="unit" type="text" id="unit" value="<?php echo $unit?>" size="30" /> -->
        	 <select name="asset" size="1">
             <?php
             //fill all assets from the db into the list and 
             //pre-select the one selected by the user if the
             //form has been submitted (update button clicked)
             foreach ($asset_list as $asset) {
             	echo "<option";
             	if (! strcmp($asset,$selected_asset)) {
             		echo " selected>";
             	}
             	else {
             		echo ">";
             	} 
             	echo $asset ."</option";
             }
             ?>
             </select>
          </span></td>
        </tr>
        <tr>
          <td width="50">&nbsp;</td>
          <td width="5">&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td width="50" class="text"><?php echo translate("uws:desc") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text"><input name="desc" type="text" id="desc" value="<?php echo $desc ?>" size="30"   />
          </span></td>
        </tr>
        <tr>
          <td width="50">&nbsp;</td>
          <td width="5">&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td width="50" class="text"><?php echo translate("uws:donation") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text"><input type="checkbox" name="donate" value="donate" <?php echo $checked?>  />
          </span></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <table class="formtable" width="470" cellspacing="0" cellpadding="0">
      
        <tr>
          <td width="65" class="text"><?php echo translate("uws:factor") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text">
            <input name="factor" type="text" id="factor" size="8" maxlength="8" value="<?php echo number_format($factor, 6, '.', '\'') ?>" />
          </span></td>
        </tr>
        
        <tr>
          <td width="50" class="text"><?php echo translate("uws:value") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text">
            <input name="value" type="text" id="value" size="8" maxlength="8" value="<?php echo $value ?>" />
          </span></td>
        </tr>
        <tr>
          <td width="50" class="text"><?php echo translate("uws:inventory_val") ?></td>
          <td width="5">&nbsp;</td>
          <td><span class="text"><?php echo ($weighted_val) ?></span></td>
        </tr>
        <tr>
          <td width="50" height="5"></td>
          <td height="5"></td>
          <td></td>
          <td height="5"></td>
        </tr>
        <tr bgcolor=" #666666">
          <td width="50" height="1"></td>
          <td height="5"></td>
          <td></td>
          <td height="1"></td>
        </tr>
        <tr>
          <td width="50" height="5"></td>
          <td height="5"></td>
          <td></td>
          <td height="5"></td>
        </tr>
        <tr>
          <td colspan="4"><div align="left" class="text">
            <input type="submit" name="update" id="update" value="<?php echo translate("uws:calc_weighted_val") ?>"/>
            <br />
            <br />
            <input type="button" name="save" id="save" value="<?php echo translate("uws:save_record") ?>" onclick="javascript:saveToInventory()" <?php echo $submit ?> />
            <br />
            <br />
          </div></td>
          <td width="80">&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="weighted_val" id="weighted_val" value="<?php echo $weighted_val ?>" />
      <input type="hidden" name="logged_user" id="logged_user" value="<?php echo $username ?>" />
  </form>
-->
				</div>			
				<div class="footer">
					<ul>
						<li class="printerfriendly"><a href="#">Printer Friendly</a></li>
						<li class="readmore"><a href="#">Read more</a></li>
					</ul>
				</div>
			</div>
	
		</div>
		
		<div id="secondarycontent">

		<!-- Displaying lists links -->
<?php include "lists.php" ?>
		<!-- Displaying action links -->
<?php include "actions.php" ?>

		<!-- secondary content end -->

		</div>
	
		<div id="footer">
		
			&copy; UWS. </a>.
		
		</div>

	</div>

</div>

</body>
</html>
