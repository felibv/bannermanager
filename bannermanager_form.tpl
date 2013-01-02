<div>
<script type="text/javascript">			
var come_from = 'bannerTable';
var alternate = true;
$(document).ready(function() {
	$(".vergrande").fancybox();
});
</script>
<script type="text/javascript" src="../js/jquery/jquery.tablednd_0_5.js" ></script>
<script type="text/javascript" src="../js/admin-dnd.js" ></script>
<form action="{$smarty.server.REQUEST_URI}" method="post">
	<fieldset>
	<legend><img src="{$module_dir}logo.gif" />{$title} {l s='Banners' mod='bannermanager'}</legend>
	<span>{l s='Table with the' mod='bannermanager'} {$title} {l s='banners' mod='bannermanager'}</span><br /><br />
    <table cellspacing="0" cellpadding="0" class="table tableDnD" id="bannerTable{$block}">
            <thead>
                <tr class="nodrag nodrop">
                    <th align="center"> </th>
					<th align="center"> </th>
					<th>{l s='Image' mod='bannermanager'}</th>
                    <th>{l s='Description' mod='bannermanager'}</th>
                    <th>{l s='Link' mod='bannermanager'}</th>
                    <th align="center">{l s='Order' mod='bannermanager'}</th>
                    <th align="center">{l s='Blank' mod='bannermanager'}</th>
                    <th align="center">{l s='Active' mod='bannermanager'}</th>
                    <th align="center"> </th>
                </tr>
            </thead>    
            <tbody>
				{if !$banners}
                	<tr>
                        <td colspan="9" align="left" class="">
							<label for="bannerBlock_empty" class="t">{l s='There are no banners for' mod='bannermanager'} <b>{$title}</b></label>
                        </td>
                    </tr>
				{else}
					{foreach from=$banners key='index' item='banner' name='banner'}
						<tr name="banner_{$banner.id_banner_manager}" id="bannerTable{$block}_{$banner.id_banner_manager}" {if $smarty.foreach.banner.index % 2}class="alt_row"{/if}>
							<td>{$banner.order}<input type="checkbox" style="display:none" value="{$banner.id_banner_manager}" name="bannerManagerId[]" checked="checked"></td>
							<td {if $leftBanners >= 2}class="dragHandle"{/if} id="td_bannerTable{$block}_{$banner.id_banner_manager}" width="40">
								<a {if $smarty.foreach.banner.first}style="display: none;"{/if} href="{$currentIndex}&id_banner={$banner.id_banner_manager}&direction=0&changePosition={$rand}#{$banner.block_id}"><img src="../img/admin/up.gif" alt="{l s='Up' mod='bannermanager'}" title="{l s='Up' mod='bannermanager'}" /></a><br />
								<a {if $smarty.foreach.banner.last}style="display: none;"{/if} href="{$currentIndex}&id_banner={$banner.id_banner_manager}&direction=1&changePosition={$rand}#{$banner.block_id}"><img src="../img/admin/down.gif" alt="{l s='Down' mod='bannermanager'}" title="{l s='Down' mod='bannermanager'}" /></a>
							</td>
							<td><a class="vergrande" rel="bannerTable{$block}" href="{$banners_path}{$banner.image_name}" >
								<img src="{$banners_path}{$banner.image_name}" name="image_{$banner.id_banner_manager}" style="width:80px;height:80" /><input type="hidden" name="image_name_{$banner.id_banner_manager}" value="{$banner.image_name}"/></a></td>
							<td><input type="text" value="{$banner.description}" name="desc_{$banner.id_banner_manager}" size="30"/></td>
							<td><textarea rows="3" name="link_{$banner.id_banner_manager}" cols="35">{$banner.image_link}</textarea></td>
							<td><input type="text" value="{$banner.order}" name="order_{$banner.id_banner_manager}" align="right" size="2" maxlength="3"/></td>
							<td align="center"><input type="checkbox" class="noborder" value="{$banner.id_banner_manager}" name="blank_{$banner.id_banner_manager}" {if (intval($banner.open_blank))} checked="checked"{/if}></td>
							<td align="center"><input type="checkbox" class="noborder" value="{$banner.id_banner_manager}" name="active_{$banner.id_banner_manager}" {if (intval($banner.active))} checked="checked"{/if}><input type="hidden" name="block_{$banner.id_banner_manager}" value="{$banner.block_id}"/></td>
							<td align="center"><img src="../img/admin/delete.gif" alt="{l s='Delete Banner' mod='bannermanager'}" title="{l s='Delete Banner' mod='bannermanager'}" onclick="deleteBanner({$banner.id_banner_manager}, '{l s='Do you want to delete the following banner?' mod='bannermanager'}');"/></td>
						</tr>
					{/foreach}
				{/if}
            </tbody>
    </table>
    <br />
	<p class="center"><input class="button" name="bannersSubmit" value="{l s='Update banners' mod='bannermanager'}" type="submit" /></p>
	</fieldset>
</form>
</div>