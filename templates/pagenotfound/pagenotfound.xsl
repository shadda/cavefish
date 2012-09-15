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
	
	<xsl:template match="/index/pagenotfound">
		<div class="content {/index/headers/sub_content}">
			<h1><span class="txt_404">404</span> Page not found</h1>
		</div>
	</xsl:template>
	
</xsl:stylesheet>
