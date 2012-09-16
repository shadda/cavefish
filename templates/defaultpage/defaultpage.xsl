<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="xml" 
		 omit-xml-declaration="yes"
		 indent="yes"
		 doctype-public="-//W3C//DTD HTML 4.01//EN"
		 doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
	/>
	
	<!-- [ ************************************************************** ] -->
	<!-- [   INCLUDES							   						  ] -->
	<!-- [ ************************************************************** ] -->
	
	<xsl:include href="../base.xsl" />
		
	<!-- ================================================================== -->
	
	<!-- [ ************************************************************** ] -->
	<!-- [   DASHBOARD PAGE	 					                     	  ] -->
	<!-- [ ************************************************************** ] -->
	
	<xsl:template match="/index/defaultpage">
		<div class="content {/index/headers/sub_content}">
			This is your page's subcontent. It gets loaded dynamically based on the <strong>Action</strong> parameter.
			<xsl:apply-templates />
		</div>
	</xsl:template>

	<xsl:template match="twitter-feed">
		<div id="twitter-feed-container">
			<strong>This is how easy adding a twitter feed is with the system</strong>
			<form id="twitter_form" name="twitter_form" action="#">
				<label for="twitter_username">Username:</label>
				<input type="text" class="txt" name="twitter_username" id="twitter_username" value="wesscope" />
				<input type="submit" class="submit" name="twitter_submit" value="Get Feed" />
			</form>
			<div id="twitter-feed">
				<xsl:apply-templates />
			</div>
		</div>
	</xsl:template>

	<xsl:template match="twitter-feed/tweet">
		<div class="tweet {@offset}">
			<a class="thumb" style="background-image: url({@profile_image_url});" />
			<span class="username">
				<xsl:value-of select="@username" />
			</span>
			<p class="message">
				<xsl:value-of select="@content" />
			</p>
		</div>
	</xsl:template>
	
</xsl:stylesheet>
