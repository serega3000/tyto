<?php

	$dir = __DIR__."/data.json";
	$config = include(__DIR__."/config.php");
	
	if( !isset($_SERVER['PHP_AUTH_USER']) 
			|| $_SERVER['PHP_AUTH_USER'] != $config['user'] 
			|| $_SERVER['PHP_AUTH_PW'] != $config['password'])
	{
		header('WWW-Authenticate: Basic realm="Tyto realm"');
		header('HTTP/1.0 401 Unauthorized');
		echo 'Access denied.';
		exit;		
	}

	$filename = __DIR__."/data.json";
	
	$dir = __DIR__."/data";
	if(!file_exists($dir) || !is_writable(($dir)))
	{
		echo "please make directory $dir and make it writeable";
		exit;
	}
	
	$filename = $dir."/data.json";
	
	if(!file_exists(($filename)))
	{
		file_put_contents($filename, file_get_contents(__DIR__."/init_data.json"));
	}
	
	$interface_info_data_file = __DIR__."/data/interface.json";
	$interface_id = uniqid();

	if(file_exists($interface_info_data_file))
	{
		$interface_data = (array)json_decode(file_get_contents($interface_info_data_file));
		$interface_time = $interface_data['time'];
		$last_interface_id = $interface_data['id'];
		
		if($interface_id != $last_interface_id && $interface_time > time() - 20)
		{
			echo "already opened in another window. Or wait ".($interface_time - time() + 20)."sec.";
			exit;
		}
	}		
	
	file_put_contents($interface_info_data_file, json_encode(array(
		"id" => $interface_id,
		"time" => time()
	)));	

?><!DOCTYPE html>
<!--[if lt IE 7]>
<html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7">
</html>
<![endif]-->
<!--[if IE 7]>
<html lang="en" class="no-js lt-ie9 lt-ie8">
</html>
<![endif]-->
<!--[if IE 8]>
<html lang="en" class="no-js lt-ie9">
</html>
<![endif]-->
<!--[if gt IE 8]>
<html lang="en" class="no-js">
</html>
<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>tyto: manage and organise.
</title>
<meta name="description" content="manage and organise things with the tool you take over. tyto is an open source task management and organisation tool.">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1.0">
<link rel="stylesheet" href="vendor/tyto/tyto/css/vendor.css">
<link rel="stylesheet" href="vendor/tyto/tyto/css/style.css">
</head>
<body>
<div id="cookie-banner" class="cookie-banner hide">
<div class="cookie-banner-content">
<div class="image">
<img src="vendor/tyto/tyto/images/tyto.png">
</div>
<div class="blurb">
<h6>tyto uses cookies
</h6>
<p class="small">cookies enable tyto to provide a better experience to you. By closing this message and using tyto you agree to the use of cookies. 
<a href="cookies.html" target="_blank">read more
</a>. 
<a data-action="cookie-close" class="btn btn-inverse">close
</a>
</p>
</div>
</div>
</div>
<input id="tytofiles" name="files[]" single type="file" class="hide tytofiles">
<div class="tyto-header container">
<div class="row">
<div class="col-md-4 col-md-push-8">
<div class="tyto-header-content">
<h2 class="tyto-title">tyto
</h2>
<div class="tyto-logo">
<img src="vendor/tyto/tyto/images/tyto.png" class="tyto-logo-image">
</div>
</div>
</div>
<div class="col-md-8 col-md-pull-4">
<div class="actions">
<button title="add item." data-action="additem" class="btn btn-default additem">add item
<i class="fa fa-file-o">
</i>
</button>
<button title="add column." data-action="addcolumn" class="btn btn-default addcolumn">add column
<i class="fa fa-columns">
</i>
</button>
<button title="save board." data-action="savebarn" class="btn btn-default saveboard">save
<i class="fa fa-floppy-o">
</i>
</button>
<a id="savetyto" class="hide savetyto">
</a>
<a id="tytoemail" class="hide tytoemail">
</a>
<button title="undo." data-action="undolast" disabled="true" class="btn btn-disabled undolast">undo
<i class="fa fa-undo">
</i>
</button>
<div class="btn-group">
<button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle">
<i class="fa fa-cog">
</i>
<span class="caret">
</span>
</button>
<ul role="menu" class="dropdown-menu">
<li>
<a title="wipe board" data-action="wipeboard">wipe board
<i class="fa fa-eraser">
</i>
</a>
</li>
<li class="divider">
</li>
<li>
<a title="export" data-action="exportbarn">export
<i class="fa fa-download">
</i>
</a>
</li>
<li>
<a title="load" data-action="loadbarn">load
<i class="fa fa-folder-o">
</i>
</a>
</li>
<li class="divider">
</li>
<li>
<a title="email" data-action="emailbarn">email
<i class="fa fa-envelope-o">
</i>
</a>
</li>
<li class="divider">
</li>
<li>
<a title="toggle auto-save" data-action="toggleautosave">auto-save
<i class="fa fa-check-square-o">
</i>
</a>
</li>
<li>
<a title="delete save" data-action="deletesave">delete save
<i class="fa fa-trash-o">
</i>
</a>
</li>
<li class="divider">
</li>
<li>
<a title="show help" data-action="helpbarn">help
<i class="fa fa-user">
</i>
</a>
</li>
<li>
<a title="show info" data-action="infobarn">info
<i class="fa fa-info">
</i>
</a>
</li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div id="barn" class="barn">
<i title="add column" data-action="addcolumn" class="fa fa-plus addcolumn">
</i>
</div>
<div id="tytoHelpModal" tabindex="-1" role="dialog" aria-labelledby="tytoIntroModal" aria-hidden="true" class="modal fade introModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button data-dismiss="modal" aria-hidden="true" class="close">&times;
</button>
<img src="vendor/tyto/tyto/images/tyto.png" class="modal-tyto-logo">
<h4 id="myModalLabel" class="modal-title">welcome to tyto!
</h4>
</div>
<div class="modal-body">
<div class="intro">
<p>
<strong>tyto
</strong> helps you manage and organise things.
</p>
<p>this help dialog will only aid you in how to actually interact with tyto. For help with how to extend, customise and develop against tyto refer to the 
<a href ="https://github.com/jh3y/tyto">github repo
</a>.
</p>
<ul>
<li>
<a data-tyto-help-show="items" class="help-link items">items
</a>
</li>
<li>
<a data-tyto-help-show="columns" class="help-link columns">columns
</a>
</li>
<li>
<a data-tyto-help-show="menu" class="help-link menu">menu
</a>
</li>
</ul>
</div>
<div class="help">
<div data-tyto-help-section="items" class="help-section items hide">
<h4>items
</h4>
<p>Items are draggable pieces of content that can be moved around to different columns on the board, you can add more by using the action button or using the icon at the bottom of each column. You can remove an item by hitting the icon on the item.
</p>
<p>To edit an item;
</p>
<ul>
<li>edit an items title by clicking it and then modifying its content.
</li>
<li>edit an items content by clicking its content and modifying the content.
</li>
</ul>
<p>to move an item
</p>
<ul>
<li>drag it by dragging the move icon next to the item title.
</li>
</ul>
</div>
<div data-tyto-help-section="columns" class="help-section columns hide">
<h4>columns
</h4>
<p>columns simply store your items, you can edit the title of a column by simply clicking on its title. You can add new columns by using the menu and remove columns by simply clicking the icon at the top of the column you wish to remove.
</p>
<p>you can also drag columns and resorting them by dragging the move icon.
</p>
</div>
<div data-tyto-help-section="menu" class="help-section menu hide">
<h4>menu
</h4>
<p>the menu can be used to add new columns and items. But it can also be used to save your current board and also load a board if you have a valid config file available. You can also use the menu to email your current board, wipe your board etc.
</p>
</div>
</div>
</div>
<div class="modal-footer">
<button data-dismiss="modal" aria-hidden="true" class="btn btn-primary">close help.
</button>
</div>
<script>var helpModal = document.querySelector('#tytoHelpModal'),
  helpSections = helpModal.querySelectorAll('[data-tyto-help-section]'),
  sectionToShow;
helpModal.addEventListener('click', function(event) {
  if (event.target && event.target.nodeName === "A" && event.target.className.indexOf('help-link') !== -1) {
    [].forEach.call(helpSections, function(key, value) {
      if (key.className.indexOf('hide') === -1) {
        key.className += 'hide';
      }
    });
    sectionToShow = helpModal.querySelector('[data-tyto-help-section=' + event.target.getAttribute('data-tyto-help-show') + ']');
    sectionToShow.className = sectionToShow.className.replace('hide', '');
  }
});
</script>
</div>
</div>
</div>
	
<div id="tytoInfoModal" tabindex="-1" role="dialog" aria-labelledby="tytoIntroModal" aria-hidden="true" class="modal fade introModal">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button data-dismiss="modal" aria-hidden="true" class="close">&times;
</button>
<img src="vendor/tyto/tyto/images/tyto.png" class="modal-tyto-logo">
<h1 id="myModalLabel" class="modal-title">tyto!
</h1>
</div>
<div class="modal-body">
<p>
<strong>tyto
</strong> helps you manage and organise things.
</p>
<p>
<strong>version:
</strong> 1.4.0.
</p>
<p>
<a href="https://github.com/jh3y/tyto">github.com/jh3y
</a> 2014.
</p>
<iframe src="http://ghbtns.com/github-btn.html?user=jh3y&amp;repo=tyto&amp;type=watch&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="90px" height="20">
</iframe>
<iframe src="http://ghbtns.com/github-btn.html?user=jh3y&amp;repo=tyto&amp;type=fork&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="90px" height="20">
</iframe>
<iframe src="http://ghbtns.com/github-btn.html?user=jh3y&amp;repo=tyto&amp;type=follow&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="120px" height="20">
</iframe>
</div>
</div>
</div>
</div>
<footer class="center-text">
<iframe src="http://ghbtns.com/github-btn.html?user=jh3y&amp;repo=tyto&amp;type=watch&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="90px" height="20">
</iframe>
<iframe src="http://ghbtns.com/github-btn.html?user=jh3y&amp;repo=tyto&amp;type=fork&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="90px" height="20">
</iframe>
<iframe src="http://ghbtns.com/github-btn.html?user=jh3y&amp;repo=tyto&amp;type=follow&amp;count=true" allowtransparency="true" frameborder="0" scrolling="0" width="120px" height="20">
</iframe>
</footer>
	
<script src="vendor/tyto/tyto/js/vendor.js">
</script>
	
<script src="vendor/tyto/tyto/js/tyto.js">
</script>
	
<script>
	
	(function(){
		var data = null;
		
		var interface_id = '<?= $interface_id?>';


		tyto.prototype._init = function() {
		  var tyto = this;
		  tyto.config = data;
		  tyto._createBarn(tyto.config);
		  return tyto;
		};			
		
		tyto.prototype.saveBarn = function() {
			var tyto = this;
			var data = JSON.stringify(tyto._createBarnJSON());
			$.post("save.php", {data: data,interface_id: interface_id}, function(result){				
				if(result == 'ok')
				{
					tyto.notify('board saved', 2000);
					return;
				}	
				if(result != 'same')
				{
					alert(result);
				}
			});
			return true;
		};	
		
		tyto.prototype._loadTemplates = function() {
		  tyto = this;
		  return $.when($.get("vendor/tyto/tyto/templates/column.html"), $.get("vendor/tyto/tyto/templates/item.html"), $.get("vendor/tyto/tyto/templates/email.html")).done(function(t1, t2, t3, t4) {
			tyto.columnHtml = t1[0];
			tyto.itemHtml = t2[0];
			tyto.emailHtml = t3[0];
			return tyto._init();
		  });
		};		
	
	
		$.getJSON('data/data.json',function(result){
			data = result;
			var myTyto = new tyto();
			
			$('.cookie-banner').remove();
			
			setInterval(function(){
				myTyto.saveBarn();			
			}, 10000);
		});
	})();
	

</script>

</body>