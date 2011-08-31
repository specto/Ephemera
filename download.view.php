<h3>Caution: download = delete</h3>
<?php
    foreach ($list as $id => $file) {
        if (empty($file)) {
            continue;
        }
?>
	<p id="item<?php echo $id; ?>">
<?php if (!in_array($file, $realList)) { ?>
	<del> 
<?php echo $file; ?>
	</del> 
<?php } else { ?>
<?php echo $file; ?>
<form action="?<?php echo basename($folder); ?>" method="POST" class="download">
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <input type="submit" value="Download" name="download" onclick="this.style.display='none';document.getElementById('item<?php echo $id; ?>').style.textDecoration='line-through'" />
</form>
<?php } ?>
	</p>
<?php
    }
?>
