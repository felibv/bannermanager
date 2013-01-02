<span>{l s='Your banners are separated in different tabs, up there' mod='bannermanager'}</span>
<br /><br />
<span>{l s='You can always add a new banner from the top clicking on the "+" icon.' mod='bannermanager'}</span>
<br /><br />
<fieldset>
	<legend><img src="{$module_dir}logo.gif" />{l s='Make a contribution to the development' mod='bannermanager'}</legend>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin:auto;" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="95PNPV4LSD7UW">
		<div style="float:left;margin:0 20px;">
		<b>{l s='Did you find this module is useful?' mod='bannermanager'}</b><br />
		<b>{l s='Want to contribute to improve it?' mod='bannermanager'}</b><br />
		<br /><br />
		<input type="hidden" name="on0" value="Upcoming improvements?">{l s='To enjoy the improvements, click on the image and you pay me one or more coffees.' mod='bannermanager'}<br />
		<select name="os0">
			<option value="No">{l s='I do not want to know the improvements ' mod='bannermanager'} </option>
			<option value="Yes">{l s='Yes, I know the improvements ' mod='bannermanager'} </option>
		</select>
		</div>
		<input type="image" src="{$module_dir}coffee_btn.png" border="0" name="submit" alt="PayPal. La forma rapida y segura de pagar en Internet.">
		<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
	</form>
	<table>
	<tr><td width="50%"><h4>Improved/changed features 0.9.1:</h4><pre>
	[*] Now work with AdBlock
	[*] Improvements in banners tab
	[*] Clean up code and templates

	Added Features:
	[+] View banners with fancybox [BO]
	[+] Image folder move to /img/bnrs</pre>
	</td><td>
	<h4>TO-DO release 0.9.2:</h4>
	<ol>
	<li>[BO] AJAX save banner order </li>
	<li>[BO] Full compatibility with PS 1.5 </li>
	<li>[FO] Check presence of AdBlockers </li>
	<li>[BO/FO] Footer hook</li>
	<li>[FO] Separate banners in the same column.</li>
	</ol>
	</td>
	</tr>
	</table>
</fieldset>