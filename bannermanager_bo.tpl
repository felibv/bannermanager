<img src='{$module_dir}bannermanager.gif' style='float:left; margin-right:15px;' />
<b>{l s='This module allows you to include as many banners as you like.' mod='bannermanager'}</b><br />
<br />{l s='You can upload, order, activate or deactivate as many banners and select if you want them in the right or left columns.' mod='bannermanager'}<br />
<br /><br />
<a href="" onclick="addBanner();return false;"><img border="0" src="../img/admin/add.gif"> {l s='Add a new banner' mod='bannermanager'} </a>
<div>
	<div class="tab-pane" id="tab-pane-bnr" style="width:100%;margin:10px 0 0">
		<script type="text/javascript">			
			var tabPaneBnr = new WebFXTabPane( document.getElementById( "tab-pane-bnr" ) );
		</script>
		<input type="hidden" name="tabs" id="tabs" value="{$block}" />
		<div class="tab-page" id="tab-page-1">
			<h4 class="tab">{l s='General Configuration' mod='bannermanager'} </h4>
			<script type="text/javascript">
				tabPaneBnr.addTabPage( document.getElementById( "tab-page-1" ) );
			</script>
			{include file="./bannermanager_cfg.tpl" }
		</div>
		<div class="tab-page" id="tab-page-2">
			<h4 class="tab">{l s='Left Banners' mod='bannermanager'} </h4>
			<script type="text/javascript">
				tabPaneBnr.addTabPage( document.getElementById( "tab-page-2" ) );
			</script>
			{include file="./bannermanager_form.tpl" block="1" title="Left" banners=$banners_1 }
		</div>
		<div class="tab-page" id="tab-page-3">
			<h4 class="tab">{l s='Right Banners' mod='bannermanager'} </h4>
			{include file="./bannermanager_form.tpl" block="2" title="Right" banners=$banners_2 }
		</div>
		<div class="tab-page" id="tab-page-4">
			<h4 class="tab">{l s='Home Banners' mod='bannermanager'} </h4>
			{include file="./bannermanager_form.tpl" block="3" title="Home" banners=$banners_3 }
		</div>
		<script type="text/javascript">
		setupAllTabs();
		</script>
	</div>
	<div class="clear"></div>
</div>
{include file="./bannermanager_add.tpl"}
