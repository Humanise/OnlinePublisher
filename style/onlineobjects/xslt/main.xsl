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
 xmlns:html="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o html util"
 >
  <xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

  <xsl:include href="../../basic/xslt/util.xsl"/>

  <xsl:variable name="onlineobjects_domain" select="'onlineobjects.com'" />

  <xsl:template match="p:page">
    <xsl:variable name="theme" select="//p:design/p:parameter[@key='theme']" />
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
        <xsl:call-template name="util:viewport"/>
        <xsl:call-template name="util:metatags"/>
        <xsl:call-template name="util:js"/>
        <link rel="preconnect" href="https://fonts.gstatic.com"/>
        <link href="https://account.{$onlineobjects_domain}/status.css" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&amp;family=Inter:wght@100;200;300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
        <xsl:call-template name="util:css"/>
        <script src="https://account.{$onlineobjects_domain}/status.js" async="async" defer="defer"><xsl:comment/></script>
      </head>
      <body>
        <xsl:call-template name="top"/>
        <div class="layout">
          <div class="layout_top">
            <xsl:comment/>
            <xsl:call-template name="navigation-first-level"/>
          </div>
          <div class="layout_middle">
            <xsl:call-template name="navigation-second-level"/>
            <div class="layout_content">
              <xsl:apply-templates select="p:content"/>
              <xsl:comment/>
            </div>
            </div>
        </div>
        <xsl:call-template name="footer"/>
        <xsl:call-template name="util:googleanalytics">
          <xsl:with-param name="cookies" select="'false'"/>
        </xsl:call-template>
      </body>
    </html>
  </xsl:template>

  <xsl:template name="navigation-first-level">
    <xsl:if test="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
      <ul class="header_menu">
        <xsl:for-each select="//f:frame/h:hierarchy/h:item">
          <xsl:if test="not(@hidden='true')">
            <li class="header_menu_item">
              <a>
                <xsl:attribute name="class">
                  <xsl:text>header_menu_link</xsl:text>
                  <xsl:choose>
                    <xsl:when test="//p:page/@id=@page"><xsl:text> is-selected</xsl:text></xsl:when>
                    <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> is-active</xsl:text></xsl:when>
                  </xsl:choose>
                </xsl:attribute>
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:if>
        </xsl:for-each>
      </ul>
    </xsl:if>
  </xsl:template>

  <xsl:template name="navigation-second-level">
    <xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
      <div class="layout_aside">
      <ul class="submenu">
        <xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
          <xsl:if test="not(@hidden='true')">
            <li class="submenu_item">
              <a>
                <xsl:attribute name="class">
                  <xsl:text>submenu_link</xsl:text>
                  <xsl:choose>
                    <xsl:when test="//p:page/@id=@page"><xsl:text> is-selected</xsl:text></xsl:when>
                    <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> is-active</xsl:text></xsl:when>
                  </xsl:choose>
                </xsl:attribute>
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:if>
        </xsl:for-each>
      </ul>
      </div>
    </xsl:if>
  </xsl:template>

  <xsl:template name="top">
    <div class="oo_topbar">
      <a class="oo_topbar_logo" href="https://www.onlineobjects.com/en/">
        <em class="oo_topbar_logo_icon oo_icon_onlineobjects"><xsl:comment/></em>
        <span class="oo_topbar_logo_text"><span class="oo_topbar_logo_part">Online</span>Objects</span>
      </a>
      <ul class="oo_topbar_menu oo_topbar_left">
        <li class="oo_topbar_menu_item oo_topbar_words">
          <a class="oo_topbar_item oo_topbar_menu_link" data-icon="app_words" href="https://words.{$onlineobjects_domain}/en/">Words</a>
        </li>
        <li class="oo_topbar_menu_item oo_topbar_photos">
          <a class="oo_topbar_item oo_topbar_menu_link" data-icon="app_photos" href="https://photos.{$onlineobjects_domain}/en/">Photos</a>
        </li>
        <li class="oo_topbar_menu_item oo_topbar_people">
          <a class="oo_topbar_item oo_topbar_menu_link" data-icon="app_people" href="https://people.{$onlineobjects_domain}/en/">People</a>
        </li>
        <li class="oo_topbar_menu_item oo_topbar_knowledge">
          <a class="oo_topbar_item oo_topbar_menu_link" data-icon="app_knowledge" href="https://knowledge.{$onlineobjects_domain}/en/">Knowledge</a></li>
        </ul>
        <ul class="oo_topbar_right">
          <xsl:comment/>
          <!--
          <li class="oo_topbar_right_item">
            <a href="javascript://" class="oo_topbar_item oo_topbar_login" data="login">Log in</a>
          </li>
            -->
        </ul>
      </div>
  </xsl:template>

  <xsl:template name="footer">
    <div class="oo_footer">
      <p class="oo_footer_links">
        <xsl:choose>
          <xsl:when test="$language='en'"><strong>English</strong></xsl:when>
          <xsl:when test="//p:page/p:context/p:translation[@language='en']">
            <xsl:for-each select="//p:page/p:context/p:translation[@language='en']">
              <a class="oo_link">
                <xsl:call-template name="util:link"/>
                <span>English</span>
              </a>
            </xsl:for-each>
          </xsl:when>
          <xsl:otherwise><del>English</del></xsl:otherwise>
        </xsl:choose>
        <span class="oo_footer_separator"> · </span>
        <xsl:choose>
          <xsl:when test="$language='da'"><strong>Dansk</strong></xsl:when>
          <xsl:when test="//p:page/p:context/p:translation[@language='da']">
            <xsl:for-each select="//p:page/p:context/p:translation[@language='da']">
              <a class="oo_link">
                <xsl:call-template name="util:link"/>
                <span>Dansk</span>
              </a>
            </xsl:for-each>
          </xsl:when>
          <xsl:otherwise><del>Dansk</del></xsl:otherwise>
        </xsl:choose>
        <span class="oo_footer_separator"> · </span>
        <a class="oo_link js-signup" href="https://account.{$onlineobjects_domain}/en/signup" data-test="footerSignup"><span>Sign up</span></a>
        <span class="oo_footer_separator"> · </span>
        <a class="oo_link" href="https://www.{$onlineobjects_domain}/en/about"><span>About OnlineObjects</span></a>
        <span class="oo_footer_separator"> · </span>
        <a class="oo_link js-agreements" href="https://account.{$onlineobjects_domain}/en/agreements" data-test="footerAgreements"><span>Terms and privacy policy</span></a>
      </p>
      <p class="oo_footer_logo">
        <a href="http://www.humanise.dk/"><span class="oo_icon oo_icon_humanise"><xsl:comment/></span><strong>Humanise</strong></a>
      </p>
    </div>
  </xsl:template>
</xsl:stylesheet>