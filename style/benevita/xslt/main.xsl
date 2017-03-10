<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o util"
 >
  <xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

	<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
  <xsl:call-template name="util:doctype"/>
	<html>
		<xsl:call-template name="util:html-attributes"/>
		<head>
			<title><xsl:value-of select="@title"/> » <xsl:value-of select="f:frame/@title"/></title>
			<xsl:call-template name="util:metatags"/>
			<xsl:call-template name="util:css"/>
			<xsl:call-template name="util:js"/>
		</head>
		<body>
			<div class="layout">
				<div class="layout_top">
					<xsl:comment/>
				</div>
				<div class="layout_navigation">
					<xsl:call-template name="util:languages"/>
					<ul class="layout_navigation">
						<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
					</ul>
				</div>
				<div class="layout_left">
					<xsl:call-template name="secondlevel"/>
					<div class="layout_box layout_contact">
						<div class="layout_box_top"><xsl:comment/></div>
						<div class="layout_box_middle">
							<p><strong>Kontakt</strong></p>
							<p>
								Dalby Allé 33<br/>
								Dall Villaby<br/>
								9230 Svenstrup<br/>
								(+45) 22 41 44 47<br/>
								<a href="mailto:kontakt@benevita.dk">kontakt@benevita.dk</a>
							</p>
						</div>
						<div class="layout_box_bottom"><xsl:comment/></div>
					</div>
				</div>
				<div class="layout_right">
					<!--div class="layout_box">
						<xsl:apply-templates select="//f:newsblock"/>
					</div-->
					<div class="layout_box">
						<div class="layout_box_top"><xsl:comment/></div>
						<div class="layout_box_middle">
              <xsl:comment/>
							<xsl:for-each select="//f:newsblock//o:object">
								<blockquote style="height: 60px;">
									<p class="title"><xsl:value-of select="o:title"/></p>
									<p class="note"><xsl:value-of select="o:note"/></p>
								</blockquote>
							</xsl:for-each>
						</div>
						<div class="layout_box_bottom"><xsl:comment/></div>
					</div>
					<div class="layout_box">
						<div class="layout_box_top"><xsl:comment/></div>
						<div class="layout_box_middle">
							<img src="{$path}services/images/?id=32&amp;width=175" style="width: 175px; border: 0px;"/>
						</div>
						<div class="layout_box_bottom"><xsl:comment/></div>
					</div>
				</div>
				<div class="layout_content">
					<div class="layout_content_top"><xsl:comment/></div>
					<div class="layout_content_middle">
						<xsl:apply-templates select="p:content"/>
						<xsl:comment/>
					</div>
					<div class="layout_content_bottom"><xsl:comment/></div>
				</div>
				<div class="layout_bottom">
					Benevita, Dalby Allé 33, Dall Villaby, 9230 Svenstrup, (+45) 22 41 44 47, <a href="mailto:kontakt@benevita.dk">kontakt@benevita.dk</a>
				</div>
			</div>
			<script src="{$path}style/{$design}/js/layout.js" type="text/javascript"><xsl:text> </xsl:text></script>
		</body>
	</html>
</xsl:template>


<xsl:template match="p:content">
	<xsl:apply-templates/>
</xsl:template>




<xsl:template match="h:hierarchy/h:item">
<xsl:if test="not(@hidden='true')">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>normal</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<li class="{$style}">
<a>
<xsl:call-template name="util:link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</li>
</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<div class="layout_menu">
			<div class="layout_menu_top"><xsl:comment/></div>
			<div class="layout_menu_middle">
				<p><xsl:value-of select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/@title"/></p>
				<ul>
					<xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
				</ul>
			</div>
			<div class="layout_menu_bottom"><xsl:comment/></div>
		</div>
	</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul class="case_side_navigation">
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li>
<a class="{$style}">
<xsl:call-template name="util:link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</li>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li>
<a class="{$style}">
<xsl:call-template name="util:link"/>
<span><xsl:value-of select="@title"/></span>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<ul><xsl:apply-templates/></ul>
</xsl:if>
</li>
</xsl:if>
</xsl:template>




</xsl:stylesheet>