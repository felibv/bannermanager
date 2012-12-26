<div>
<form action="{$smarty.server.REQUEST_URI}" method="post">
	<fieldset>
	<legend><img src="{$path}logo.gif" />{$title} {l s='Banners' mod='bannermanager'}</legend>

	<span>{l s='Table with the' mod='bannermanager'} {$title} {l s='banners' mod='bannermanager'}</span><br /><br />
    <table cellspacing="0" cellpadding="0" class="table tableDnD" style="width:80%;" id="bannerTable-{$block}">
            <thead>
                <tr class="nodrag nodrop">
                    <th colspan="2">{l s='Image' mod='bannermanager'}</th>
                    <th>{l s='Description' mod='bannermanager'}</th>
                    <th>{l s='Link' mod='bannermanager'}</th>
                    <th align="center">{l s='Order' mod='bannermanager'}</th>
                    <th align="center">{l s='Blank' mod='bannermanager'}</th>
                    <th align="center">{l s='Active' mod='bannermanager'}</th>
                    <th align="center">{l s='Actions' mod='bannermanager'}</th>
                </tr>
            </thead>    
            <tbody>
				{if !$banners}
                	<tr>
                        <td colspan="8" align="left" class="">
							<label for="bannerBlock_empty" class="t">{l s='There are no banners for' mod='bannermanager'} <b>{$title}</b></label>
                        </td>
                    </tr>
				{/if}
            	{assign var='oldBlock' value=0}
                {assign var='irow' value=0}
            	{foreach from=$banners key='index' item='banner' name='banner'}
                {if $banner.block_id != $oldBlock}
                	<tr>
                        <th colspan="8" align="left" class="alt_row">
                        	<label for="bannerBlock_{$banner.id_banner_manager}" class="t">{l s='Banners assigned to the' mod='bannermanager'} <b>{if $banner.block_id == 1}{l s='left' mod='bannermanager'}{else}{l s='right' mod='bannermanager'}{/if}</b> {l s='column' mod='bannermanager'}</label>
                        </th>
                    </tr>
                {/if}
                {assign var='oldBlock' value=$banner.block_id}
                <tr name="banner_{$banner.id_banner_manager}" id="{$banner.id_banner_manager}" {if $irow++ % 2}class="alt_row"{/if}>
                	<td class="positions" width="15">{$banner.order}<input type="checkbox" style="display:none" value="{$banner.id_banner_manager}" name="bannerManagerId[]" checked="checked"></td>
                    <td><img src="{$path}banners/{$banner.image_name}" name="image_{$banner.id_banner_manager}"/><input type="hidden" name="image_name_{$banner.id_banner_manager}" value="{$banner.image_name}"/></td>
                    <td><input type="text" value="{$banner.description}" name="desc_{$banner.id_banner_manager}" size="30"/></td>
                    <td><textarea rows="3" name="link_{$banner.id_banner_manager}" cols="35">{$banner.image_link}</textarea></td>
                    <td {if $leftBanners >= 2}class="dragHandle"{/if} id="td_{$banner.id_banner_manager}">
                    <!--Drag & drop images -->
<!--						<a {if $banner.order == 1}style="display: none;"{/if} href="{$currentIndex}&id_banner={$banner.id_banner_manager}&direction=0&changePosition={$rand}#{$banner.block_id}"><img src="../img/admin/up.gif" alt="{l s='Up' mod='bannermanager'}" title="{l s='Up' mod='bannermanager'}" /></a><br />
                        <a {if $banner.order == 2}style="display: none;"{/if} href="{$currentIndex}&id_banner={$banner.id_banner_manager}&direction=1&changePosition={$rand}#{$banner.block_id}"><img src="../img/admin/down.gif" alt="'.$this->l('Down').'" title="'.$this->l('Down').'" /></a>
 -->                    <input type="text" value="{$banner.order}" name="order_{$banner.id_banner_manager}" align="right" size="2"/></td>
                    <td align="center"><input type="checkbox" class="noborder" value="{$banner.id_banner_manager}" name="blank_{$banner.id_banner_manager}" {if (intval($banner.open_blank))} checked="checked"{/if}></td>
                    <td align="center"><input type="checkbox" class="noborder" value="{$banner.id_banner_manager}" name="active_{$banner.id_banner_manager}" {if (intval($banner.active))} checked="checked"{/if}><input type="hidden" name="block_{$banner.id_banner_manager}" value="{$banner.block_id}"/></td>
                    <td align="center"><img src="../img/admin/delete.gif" alt="{l s='Delete Banner' mod='bannermanager'}" title="{l s='Delete Banner' mod='bannermanager'}" onclick="deleteBanner({$banner.id_banner_manager}, '{l s='Do you want to delete the following banner?' mod='bannermanager'}');"/></td>
                </tr>
                {/foreach}
            </tbody>
    </table>
    <br />
	<p class="center"><input class="button" name="bannersSubmit" value="{l s='Update banners' mod='bannermanager'}" type="submit" /></p>
	</fieldset>
</form>
</div>