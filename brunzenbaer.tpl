{include file='documentHeader'}
<head>
	<title>Brunzenbär Admin - {PAGE_TITLE|language}</title>

	{include file='headInclude' sandbox=false}
	
	<link rel="canonical" href="{link controller='BrunzenbaerPage'}{/link}" />
</head>

<body id="tpl{$templateName|ucfirst}">
	{include file='header'}

	<header class="boxHeadline">    
		<h1>Brunzenbär Admin</h1>
	</header>

	{include file='userNotice'}
	
	<form method="post" action="">
		<div class="container containerPadding marginTop">
			<fieldset>
				<legend>Admin-Seite für die Brunzenbär-Nominierungen</legend>
				
				<dl>
					<dt><label for="period">Zeitraum</label></dt>
					<dd>
						{@$periodform}
					</dd>
				</dl>
			</fieldset>
		</div>
	
		<div class="formSubmit">
			<input type="submit" value="Absenden" accesskey="s" />
		</div>
	</form>
	
	{@$results}

	{include file='footer'}
</body>
</html>