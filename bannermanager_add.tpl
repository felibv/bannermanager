<script language="javascript">
	{literal}
	function deleteBanner(bnr, question) {
		if (confirm(question)){
			document.deleteBannerForm.bannerDelete.value = bnr;
			document.deleteBannerForm.deleteBannerSubmit.click();
		} else {
			return false;
		}
	}
	function addBanner() {
		document.getElementById('addBanner').style.display = '';
		document.getElementById('addBanner').scrollIntoView();
	}
	{/literal}
</script>
<br/><br/>
<!--Add new banner -->
<div style="display:none;" id="addBanner" name="addBanner">
<form action="{$smarty.server.REQUEST_URI}" method="post" enctype="multipart/form-data">
<fieldset>
    <legend><img src="../img/admin/add.gif" />{l s='Add new banner' mod='bannermanager'}</legend>
    <span>{l s='Provide the following information to create a new banner' mod='bannermanager'}</span><br /><br />
    <label for="banner_description">{l s='Banner description' mod='bannermanager'}:</label>
    <div class="margin-form">
        <input type="text" name="banner_description" size="30" />
    </div>
    <br />
	<label for="banner_link">{l s='Banner link' mod='bannermanager'}:</label>
    <div class="margin-form">
        <input type="text" name="banner_link" size="80" />
        <p>{l s='Provide full URL for link the banner (e.g. http://www.google.com/search?q=search%20this' mod='bannermanager'}</p>
    </div>
    <br />
	<label for="banner_order">{l s='Banner order' mod='bannermanager'}:</label>
    <div class="margin-form">
        <input type="text" name="banner_order" size="4" />
        <p>{l s='The orden within the block' mod='bannermanager'}</p>
    </div>
    <br />
	<label for="banner_block_id">{l s='Block space' mod='bannermanager'}:</label>
    <div class="margin-form">
    	<input type="Radio" id="banner_block_id_left" checked="checked" value="1" name="banner_block_id" />
        <label for="banner_block_id_left" class="t">{l s='Left' mod='bannermanager'}</label>
        <br />
    	<input type="Radio" id="banner_block_id_right" value="2" name="banner_block_id" />
        <label for="banner_block_id_right" class="t">{l s='Right' mod='bannermanager'}</label>
        <br />
    	<input type="Radio" id="banner_block_id_home" value="3" name="banner_block_id" />
        <label for="banner_block_id_home" class="t">{l s='Home' mod='bannermanager'}</label>
    </div>
    <br />
	<label for="banner_image">{l s='Banner image' mod='bannermanager'}:</label>
    <div class="margin-form">
        <input type="file" name="banner_image" />
        <p>{l s='Select an image from your computer' mod='bannermanager'}</p>
    </div>
    <br />
	<label for="banner_blank">{l s='Open in new window?' mod='bannermanager'}:</label>
    <div class="margin-form">
        <input type="checkbox" name="banner_blank" />
        <p>{l s='Check it if you want the link opens in a new window' mod='bannermanager'}</p>
    </div>
    <br />
	<label for="banner_active">{l s='Active?' mod='bannermanager'}:</label>
    <div class="margin-form">
        <input type="checkbox" name="banner_active" checked="checked" />
        <p>{l s='Check it if you want to enable the new banner' mod='bannermanager'}</p>
    </div>
    <br />
	<p class="center"><input class="button" name="addBannerSubmit" value="{l s='Add banner' mod='bannermanager'}" type="submit" /></p>
</fieldset>
</form>
<form action="{$smarty.server.REQUEST_URI}" method="post" class="hidden" name="deleteBannerForm">
	<fieldset>
	<input type="hidden" value="" name="bannerDelete"/>
	<input class="hidden" name="deleteBannerSubmit" value="{l s='Delete banner' mod='nps'}" type="submit" />
	</fieldset>
</form>
<br/><br/>
</div>