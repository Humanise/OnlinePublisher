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
	<title>
		<xsl:if test="not(//p:page/@id=//p:context/p:home/@page)">
			<xsl:value-of select="@title"/>
			<xsl:text> - </xsl:text>
		</xsl:if>
		<xsl:value-of select="f:frame/@title"/>
	</title>
	<xsl:call-template name="util:metatags"/>
	<link href='http://fonts.googleapis.com/css?family=Nobile:regular,bold%7CReenie+Beanie&amp;subset=latin' rel='stylesheet' type='text/css'/>
	<xsl:call-template name="util:css"/>
	<xsl:call-template name="util:js"/>
</head>
<body>
	<div class="layout">
		<div class="layout_top">
			<div class="layout_centered">
				<strong>Grønttorvet - Aalborg</strong>
				<ul class="layout_navigation">
          <xsl:comment/>
					<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
				</ul>
			</div>
		</div>
		<div class="layout_header">
			<div class="layout_centered">
				<strong>Hver onsdag og lørdag i Ågade, Aalborg</strong>
			</div>
		</div>
		<div class="layout_middle layout_centered">
			<div class="layout_navigation">
				<xsl:call-template name="util:languages"/>

			</div>
			<!--xsl:if test="//p:page/p:context/p:home[@page=//p:page/@id]">
				<div class="layout_front"><xsl:comment/></div>
			</xsl:if-->
			<xsl:apply-templates select="p:content"/>
		</div>
		<div class="layout_bottom">
			<a href="http://www.in2isoft.dk/" class="layout_designed">Designet og udviklet af In2iSoft</a>
		</div>
	</div>
	<xsl:call-template name="util:googleanalytics"/>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
	<div class="layout_content">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="f:userstatus">
	<xsl:choose>
		<xsl:when test="$userid>0">
		<span class="userstatus">Bruger: <strong><xsl:value-of select="$usertitle"/></strong></span>
		<xsl:text> · </xsl:text>
		<a href="./?id={@page}&amp;logout=true" class="common">Log ud</a>
		</xsl:when>
		<xsl:otherwise>
		<a href="./?id={@page}" class="common">Log ind</a>
		</xsl:otherwise>
	</xsl:choose>
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
    <ul class="case_sub_navigation">
      <xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
    </ul>
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





<!--            Links              -->


<xsl:template match="f:links/f:top">
  <div class="links_top">
  <div>
  <xsl:apply-templates select="//f:frame/f:userstatus"/> ·
  <a title="Udskriv siden" class="common" href="?id={//p:page/@id}&amp;print=true">Udskriv</a>
  <xsl:apply-templates/>
  </div>
  </div>
</xsl:template>

<xsl:template match="f:links/f:bottom">
  <div class="case_links">
  <xsl:apply-templates/>
  <xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
  <a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer"><span>XHTML 1.1</span></a>
  </div>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
  <xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
  <a title="{@alternative}" class="common">
  <xsl:call-template name="util:link"/>
  <span><xsl:value-of select="@title"/></span>
  </a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
  <span>&#160;&#183;&#160;</span>
  <a title="{@alternative}" class="common">
  <xsl:call-template name="util:link"/>
  <span><xsl:value-of select="@title"/></span>
  </a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
  <span class="text">
    <xsl:comment/>
    <xsl:apply-templates/>
  </span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
	<br/>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
  <a title="{@alternative}" class="common">
    <xsl:call-template name="util:link"/>
    <span><xsl:apply-templates/></span>
  </a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
<div class="case_news">
<h2><xsl:value-of select="@title"/></h2>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
<div class="case_news_item">
<h3>
<xsl:value-of select="o:title"/>
</h3>
<p class="case_news_text">
<xsl:apply-templates select="o:note"/>
</p>
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:links"/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:links">
<p class="case_news_links">
<xsl:apply-templates/>
</p>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
<p class="case_news_date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></p>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="common">
<xsl:call-template name="util:link"/>
<span>
<xsl:value-of select="@title"/>
</span>
</a>
</xsl:template>

</xsl:stylesheet>