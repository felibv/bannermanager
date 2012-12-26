<link href="{$rel_path}bnrmanager.css" rel="stylesheet" type="text/css" />
<!-- MODULE Banner Mannager {$banner_class} -->
<div id='bnrmanager-{$banner_class}' class="{$banner_class}">
{foreach from=$banners item=banner}
	{if $banner.active}
        <p>
			<a href="{$banner.image_link}" target="{if $banner.open_blank==0}_self{else}_blank{/if}" title="{$banner.description}">
				<img src="{$this_path}{$banner.image_name}" alt="{$banner.description}" />
            </a>
        </p>
	{/if}
{/foreach}
</div>
<!-- /MODULE Banner Mannager {$banner_class} -->
