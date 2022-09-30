<?php
require_once('template/header.php');
function curdate() {
    return date('Y-m-d');
}

// echo the date to screen
?>
<div>
<h1 class="text-center">Список трансляций</h1>
</div>
<div class="container">

</div>
<div class="container">
<div class="row row-cols-1 row-cols-md-auto align-items-end justify-content-center">
	<div class="col">
		<label for="dateValue" class="form-label">Дата игры</label>
		<input class="form-control" id="dateValue" type="date" value="<?php echo curdate(); ?>" />
	</div>
	<div class="col">
		<label for="nameValue" class="form-label">Имя игрока</label>
		<input class="form-control" id="nameValue" type="text" value="" />
	</div>
	<div class="col">
		<label for="tagValue" class="form-label">Тэг игры</label>
		<input class="form-control" id="tagValue" type="text" value="" />
	</div>
    <div class="col align-bottom justify-items-end">
        <input type="button" class="btn btn-primary btn_click_attr" value="Найти" onclick=setVar()>
    </div>
  </div>
</div>
</br>

<div id="game_list"></div>
<script type="text/javascript">
	let tag = ''
	let name = ''
	let dategame = '<?php echo curdate() ?>'
	getgame()
</script>

<?php
require_once('template/footer.php');
?>