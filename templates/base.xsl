<?xml version="1.0" encoding="utf-8"?>

<!DOCTYPE xsl:stylesheet [<!ENTITY nbsp "&#160;">]>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" xmlns:fb="http://www.facebook.com/2008/fbml">

	<xsl:output method="html"
		 omit-xml-declaration="yes"
		 indent="yes"
		 doctype-public="-//W3C//DTD HTML 4.01//EN"
		 doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"

	/>
	
	<xsl:template match="index">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>
					<xsl:value-of select="/index/headers/title" />
				</title>
				<link rel="stylesheet" href="{/index/headers/base}combine.css/{/index/stylesheets}" type="text/css"/>
				<link rel="icon" type="image/x-icon" href="favicon.ico" />
				<base href="{headers/base}"/>
			</head>
			<body id="page_{/index/headers/class_name}">
				<div id="master_container">
					<h1>This is your main template, from which (usually) all other templates are wrapped in.</h1>
					<xsl:apply-templates />
				</div>
				<script language="javascript" type="text/javascript" src="{/index/headers/base}combine.js/{/index/scripts}"></script>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="errors">
		<div class="errors">
			<xsl:for-each select="error">
				<div>
					<xsl:value-of select="." />
				</div>
			</xsl:for-each>
		</div>
	</xsl:template>

	<xsl:template match="error">
		<div class="error">
			<xsl:value-of select="." disable-output-escaping="yes" />
		</div>
	</xsl:template>

	<xsl:template match="success">
		<div class="success">
			<div>
				<xsl:value-of select="." />
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="*"></xsl:template>
  
</xsl:stylesheet>
