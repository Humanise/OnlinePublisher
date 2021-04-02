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
 exclude-result-prefixes="p f h n o"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<xsl:call-template name="util:doctype"/>
<html>
  <xsl:call-template name="util:html-attributes"/>
<head>
  <title>
    <xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"><xsl:value-of select="@title"/> » </xsl:if>
    <xsl:value-of select="f:frame/@title"/>
  </title>
  <link rel="preconnect" href="https://fonts.gstatic.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
  <xsl:call-template name="util:viewport"/>
  <meta name="google-site-verification" content="vagGQtrnVxxm4omlbXckjUkFqucyeVPmo-CE_LxQQ10" />
  <xsl:call-template name="util:metatags"/>
  <xsl:call-template name="util:css">
  </xsl:call-template>
  <xsl:call-template name="util:js"/>
</head>
<body>
  <div class="layout">
    <div class="layout_header">
      <div class="hero">
        <p class="hero_title">Villa Havblik</p>
        <p class="hero_place">Løkken</p>
        <div class="hero_photo"><xsl:comment/></div>
      </div>
      <xsl:apply-templates select="f:frame/h:hierarchy"/>
    </div>
    <div class="layout_body">
      <xsl:apply-templates select="p:content"/>
    </div>
  </div>
  <div class="footer">
    <div class="footer_body">
      <div class="footer_part">
        <h2 class="common_header">Menu</h2>
        <ul class="footer_menu">
        <xsl:for-each select="f:frame/h:hierarchy/h:item">
          <xsl:if test="not(@hidden='true')">
            <li>
              <a class="common_link">
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
              <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
              <ul><xsl:apply-templates select="h:item"/></ul>
              </xsl:if>
            </li>
          </xsl:if>
        </xsl:for-each>
        </ul>
      </div>
      <div class="footer_part layout_contact">
        <h2 class="common_header">Adresse</h2>
        <p>Nordlysvej 14</p>
        <p>9840 Løkken</p>
        <p>Danmark</p>
      </div>
      <div class="footer_part layout_contact">
        <h2 class="common_header">Kontakt</h2>
        <p>Buster Munk</p>
        <p>Tlf: 91 11 21 58</p>
        <p><a href="mailto:bustermunk@gmail.com" class="common_link"><span class="common_link_text">bustermunk@gmail.com</span></a></p>
      </div>
    </div>
    <p class="footer_powered">
      <a href="https://www.humanise.dk/">Designet og udviklet af Humanise</a>
    </p>
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


<xsl:template match="h:hierarchy">
  <ul class="navigation">
    <xsl:comment/>
    <xsl:apply-templates select="h:item"/>
  </ul>
</xsl:template>


<xsl:template match="h:item">
  <xsl:variable name="style">
    <xsl:text>navigation_item</xsl:text>
    <xsl:choose>
      <xsl:when test="//p:page/@id=@page"><xsl:text> navigation_item-selected</xsl:text></xsl:when>
      <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> navigation_item-active</xsl:text></xsl:when>
    </xsl:choose>
  </xsl:variable>
  <xsl:if test="not(@hidden='true')">
    <li>
      <a class="{$style}">
        <xsl:call-template name="util:link"/>
        <xsl:value-of select="@title"/>
      </a>
      <xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
      <ul><xsl:apply-templates select="h:item"/></ul>
      </xsl:if>
    </li>
  </xsl:if>
</xsl:template>


<!--            Links              -->


<xsl:template match="f:links/f:top">
<span>
<a title="Udskriv siden" href="{$page-path}print=true">Udskriv</a>
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span>
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;|&#160;</span></xsl:if>
<a title="XHTML 1.1" href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;|&#160;</span></xsl:if>
<a title="{@alternative}">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;|&#160;</span>
<a title="{@alternative}">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->


<xsl:template match="f:text/f:bottom">
<xsl:apply-templates/>
<xsl:text> </xsl:text>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}">
<xsl:call-template name="util:link"/>
<xsl:apply-templates/>
</a>
</xsl:template>

</xsl:stylesheet>