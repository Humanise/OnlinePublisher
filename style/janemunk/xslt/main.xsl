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
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 xmlns:document="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"
 xmlns:imagegallery="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 exclude-result-prefixes="p f h n o util widget document imagegallery"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

<xsl:include href="../../basic/xslt/util.xsl"/>
<xsl:include href="exhibit.xsl"/>


<xsl:template match="p:page">
  <xsl:call-template name="util:doctype"/>
  <html>
    <xsl:call-template name="util:html-attributes"/>
    <head>
      <title><xsl:value-of select="@title"/> - <xsl:value-of select="f:frame/@title"/></title>
      <xsl:choose>
        <xsl:when test="//widget:exhibition or //document:section[@class='artgallery']//imagegallery:imagegallery">
          <meta name="viewport" content="user-scalable=no, initial-scale = 1, maximum-scale = 1, minimum-scale = 1"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:call-template name="util:viewport"/>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:call-template name="util:metatags"/>
      <xsl:call-template name="util:css"/>
      <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,600|Amatic+SC|Indie+Flower' rel='stylesheet' type='text/css'/>
      <xsl:call-template name="util:js"/>
    </head>
    <body>
      <xsl:choose>
        <xsl:when test="//widget:exhibition | //document:section[@class='artgallery']//imagegallery:imagegallery">
          <xsl:attribute name="class">exhibit exhibit-concrete</xsl:attribute>
          <xsl:apply-templates select="//widget:widget"/>
          <xsl:apply-templates select="//document:section[@class='artgallery']//imagegallery:imagegallery"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:if test="//p:page/p:context/p:home[@page=//p:page/@id]">
            <xsl:attribute name="class">is-front</xsl:attribute>
          </xsl:if>
          <xsl:call-template name="new"/>          
        </xsl:otherwise>
      </xsl:choose>
      <xsl:call-template name="util:googleanalytics"/>
    </body>
  </html>
</xsl:template>

<xsl:template name="new">

  <xsl:choose>
    <xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
      <div class="layout_navigation">
        <h1 class="layout_title">Jane Munk</h1>
        <ul class="layout_menu">
          <xsl:for-each select="f:frame/h:hierarchy/h:item[not(@hidden='true')]">
            <xsl:variable name="style">
              <xsl:choose>
                <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
                <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
                <xsl:otherwise><xsl:text>normal</xsl:text></xsl:otherwise>
              </xsl:choose>
            </xsl:variable>
            <li class="layout_menu_item layout_menu_item-{$style}">
              <a class="layout_menu_link layout_menu_link-{$style}">
                <xsl:call-template name="util:link"/>
                <xsl:value-of select="@title"/>
              </a>
            </li>
          </xsl:for-each>
        </ul>
      </div>
      <div class="painting painting-1"><div class="painting_body painting_body-1"><xsl:comment/></div></div>
      <div class="painting painting-2"><div class="painting_body painting_body-2"><xsl:comment/></div></div>
      <div class="painting painting-3"><div class="painting_body painting_body-3"><xsl:comment/></div></div>
      <div class="painting painting-4"><div class="painting_body painting_body-4"><xsl:comment/></div></div>
    </xsl:when>
    <xsl:otherwise>
      <div class="layout">
        <div class="layout_header">
          <p class="layout_header_title">Jane Munk</p>
          <ul class="layout_header_menu">
            <xsl:for-each select="f:frame/h:hierarchy/h:item[not(@hidden='true')]">
              <xsl:variable name="style">
                <xsl:choose>
                  <xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
                  <xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
                  <xsl:otherwise><xsl:text>normal</xsl:text></xsl:otherwise>
                </xsl:choose>
              </xsl:variable>
              <li class="layout_header_menu_item layout_header_menu_item-{$style}">
                <a class="layout_header_menu_link layout_header_menu_link-{$style}">
                  <xsl:call-template name="util:link"/>
                  <xsl:value-of select="@title"/>
                </a>
              </li>
            </xsl:for-each>
          </ul>
        </div>
        <div class="layout_content">
          <xsl:apply-templates select="p:content/*"/>
        </div>
      </div>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template match="widget:contact">
  <div class="contact" xmlns:xlink="http://www.w3.org/1999/xlink">
    <svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg">
    <defs>
    <symbol id="icon-facebook" viewBox="0 0 29 32">
    <title>facebook</title>
    <path class="path1" d="M2.24 4.251c0.018 0.095-0.018 0.151 0.116 0.123-0.053-0.141-0.133-0.261-0.236-0.359-0.011 0.021-0.017 0.046-0.017 0.072 0 0.081 0.058 0.149 0.135 0.163z"></path>
    <path class="path2" d="M4.368 29.292c0.018 0.172 0.18 0.172 0.356 0.123 0.282 0.053 0.701 0.342-0.12-0.475-0.084 0.123-0.162 0.239-0.236 0.352z"></path>
    <path class="path3" d="M0 14.29v0.239h0.356c-0.028-0.162-0.176-0.229-0.356-0.239z"></path>
    <path class="path4" d="M0.112 22.55h-0.072v0.126c0.354 0.405 0.862-0.363 0.978-0.595-0.028-0.003-0.060-0.005-0.093-0.005-0.347 0-0.649 0.189-0.811 0.469z"></path>
    <path class="path5" d="M5.195 28.471c-0.042-0.081-0.081-0.158-0.12-0.232 0.035 0.137-0.077 0.243 0.12 0.232z"></path>
    <path class="path6" d="M5.548 27.284v0.243h0.113c0.014-0.023 0.022-0.052 0.022-0.081 0-0.080-0.057-0.147-0.133-0.161z"></path>
    <path class="path7" d="M5.305 28.115c0.15-0.146 0.243-0.35 0.243-0.575 0-0.005-0-0.009-0-0.014-0.282 0.075-0.243 0.3-0.243 0.589z"></path>
    <path class="path8" d="M2.709 4.84c-0.035-0.078-0.074-0.155-0.116-0.236 0.039 0.144-0.074 0.257 0.116 0.236z"></path>
    <path class="path9" d="M8.38 29.999c-0.035 0.278 0.046 0.395 0.239 0.356 0.060-0.345 0.18-0.764-0.239-0.356z"></path>
    <path class="path10" d="M15.977 4.486v0z"></path>
    <path class="path11" d="M17.122 0c-0.785 0-0.095 1.089 0.12 1.3-0.324-0.698 0.021-0.014-0.12-1.3z"></path>
    <path class="path12" d="M17.122 1.42c-0.348-0.042-0.43 0.236-0.236 0.82 0.225 0 0.387-0.673 0.236-0.82z"></path>
    <path class="path13" d="M9.602 3.427h0.074c0-0.354-0.054-1.401-0.565-1.714 0.152 0.458 0.137 1.248 0.491 1.593z"></path>
    <path class="path14" d="M3.184 5.431c-0.106-0.173-0.387-0.338-0.116-0.116-0.095 0.071 0.007 0.116 0.116 0.116z"></path>
    <path class="path15" d="M16.767 2.6v0.352h0.12c0.049-0.169 0.049-0.338-0.12-0.352z"></path>
    <path class="path16" d="M19.37 29.534v0.233h0.113c-0.039-0.078-0.074-0.155-0.113-0.233z"></path>
    <path class="path17" d="M3.416 9.453c-0.088 0.257 0.148 0.352 0.479 0.352-0.023-0.203-0.193-0.36-0.401-0.36-0.028 0-0.055 0.003-0.081 0.008z"></path>
    <path class="path18" d="M20.194 31.183h-0.116c0 0.377-0.042 0.433 0.352 0.599v-0.359h-0.236z"></path>
    <path class="path19" d="M6.907 5.673c0.023 0.029 0.045 0.053 0.068 0.081-0.059-0.084-0.121-0.166-0.183-0.248 0.040 0.065 0.078 0.119 0.118 0.172z"></path>
    <path class="path20" d="M28.933 15.121c-2.29 0-3.579-1.218-1.064-2.719-0.595-1.067-5.077-2.466-0.452-3.424-0.007-0-0.015-0-0.023-0-0.415 0-0.81 0.088-1.166 0.247l0.018-0.131h-1.062v0.123c-2.479-0.12-2.645-1.353-2.937-3.071 0.299 1.708-3.424-1.659-1.666-1.659-1.497 0-1.608 2.572-3.316 0.176-0.567-0.803-3.641 1.613-3.035-0.768-1.271 0.222-1.795 1.423-3.211 2.015v-0.003h-0.039c-0.662-0.708-0.729-1.332-1.18-2.307-0.948 0.676 0.88 1.89 0.232 2.749-1.444 0-2.013 0.657-3.056-0.609 0.469 0.659 0.835 1.363 0.813 2.048-1.649 1.402-1.673 2.015-3.892 2.015 0.102 0.585 1.218 0.523 1.427 1.091 0.292 0.806-1.018 0.543-1.155 0.991-0.613 2.004-1.085 2.406-3.347 2.406 0.557 0.571 2.927-0.095 2.684 1.293-0.134 0.704-1.060 1.289-0.088 1.898 0 1.561-0.366 0.701 0.236 2.466 0.229 0.673-2.335 1.966-2.716 2.135 0.701 0.535 3.667-2.364 3.667 0.469h-0.12c-0.176 0.354-0.589 1.736-0.134 2.138-0.236-0.208 1.003-0.36 1.003-0.356v-0.236c2.125 0.726 0.043 1.881 0.332 3.188 0.782 0 1.693-2.561 2.806-0.863 0.947 1.455 2.495 1.701 2.495 3.47 0.317 0 0.455-0.148 0.709-0.239 1.356-0.44 2.605-1.458 4.148-1.536 0.796 1.169 2.968 1.233 3.676 0.824v0.34h-0.036c0.381 0 0.74 0.354 0.828 1.062h0.27v0.014c0.072 0.717 0.498 1.32 1.097 1.639 0.011-1.023-1.241-2.008-1.484-3.070h0.032v-0.338c-0.354-0.433-0.673-1.553 0.433-1.398 2.339 0.348-0.299-1.321 1.952-1.321 0.639 0.252 1.095 0.837 1.156 1.536l0 0.123c0.708-0.127 0.867 0.349 1.283 0.349 0-0.001 0-0.003 0-0.005 0-0.524-0.316-0.973-0.768-1.169-0.649-0.38-1.634-2.37-0.155-2.37 0.366 0 3.62 1.715 2.788 0.711 0-0.148-0.793-0.486-0.941-0.711 0.282-0.282 0.915-0.719 1.067-1.064-1.138-0.366-1.309-1.24-1.062-2.243 0.391-0.264 0.476-1.078 0.772-1.462 0.31-0.391 1.483 0.483 1.807 0.225 0.085-0.064-1.483-1.712-1.631-2.543-1.846-1.973 2.889-0.782 2.005-2.124zM19.164 12.28h-2.649c-0.461 0.026-0.833 0.377-0.892 0.826l-0.001 2.007h3.541v2.479h-3.541v7.791h-2.479v-7.791h-3.187v-2.479h3.187v-1.708c0-2.181 1.292-3.958 3.371-3.958h2.649z"></path>
    <path class="path21" d="M5.076 2.961c0 0.606 0.942 1.521 1.716 2.544-0.504-0.815-0.682-2.289-1.716-2.544z"></path>
    <path class="path22" d="M19.958 30.944v0.24h0.12c0.042-0.113-0.004-0.191-0.12-0.24z"></path>
    <path class="path23" d="M19.838 30.827c0.060 0.057-0.056 0.166 0.12 0.116-0.023-0.283-0.155-0.53-0.355-0.703 0.055 0.222 0.136 0.418 0.241 0.598z"></path>
    <path class="path24" d="M10.31 31.299c0.354-0.004 0.282-0.078 0.356-0.116-0.113-0.282-0.002-0.532-0.356-0.828z"></path>
    <path class="path25" d="M8.852 27.995v0.243h0.123c0.012-0.022 0.018-0.049 0.018-0.076 0-0.084-0.061-0.153-0.141-0.167z"></path>
    <path class="path26" d="M19.243 29.415c-0.035-0.077-0.074-0.162-0.113-0.236 0.035 0.138-0.074 0.247 0.113 0.236z"></path>
    <path class="path27" d="M19.482 30.239h0.12c-0.039-0.074-0.077-0.159-0.12-0.24z"></path>
    <path class="path28" d="M19.546 29.823c-0.13-0.109-0.078 0.088-0.064 0.176v-0.176z"></path>
    <path class="path29" d="M8.852 28.939v-0.469h-0.116c-0.060 0.215-0.053 0.416 0.116 0.469z"></path>
    </symbol>
    <symbol id="icon-tumblr" viewBox="0 0 29 32">
    <title>tumblr</title>
    <path class="path1" d="M20.108 0.953c-0.005 0.145-0.035 0.176-0.092 0.092 0 0.195 0.941-0.092 0.092-0.092z"></path>
    <path class="path2" d="M23.050 3.144c-0.131 0.254-0.37 0.427-0.37 0.729 0.133-0.125 0.585-0.495 0.37-0.729z"></path>
    <path class="path3" d="M21.155 0.633c-0.166 0-0.685 0.071-0.685 0.32 0.31 0 0.685 0.17 0.685-0.32z"></path>
    <path class="path4" d="M15.188 2.313c-0.441 0.056-0.46 0.297-0.46 0.685 0.343-0.116 0.405-0.363 0.46-0.685z"></path>
    <path class="path5" d="M3.879 24.111c0.253-0.014 0.464-0.179 0.545-0.405-0.252 0.007-0.464 0.173-0.544 0.401z"></path>
    <path class="path6" d="M1.779 15.081c0 0.279 0.547 0.276 0.639 0.276-0.145 0.331-0.329 0.615-0.55 0.866-0.173 0.151-0.284 0.377-0.284 0.629 0 0.185 0.060 0.356 0.16 0.494 0.194 0.153 1.829-2.265 0.034-2.265z"></path>
    <path class="path7" d="M7.994 30.055q-0.118 0.767 0.626 0.441c0.367-0.091-0.139-1.236-0.626-0.441z"></path>
    <path class="path8" d="M0 15.127c0 0.496 1.237 0.136 1.463 0.136 0.074-0.015 0.15-0.030 0.226-0.043 0-0.196-1.689-0.387-1.689-0.092z"></path>
    <path class="path9" d="M24.443 7.829c0.049 0.258 0.218 0.297 0.454 0.458 0-0.389-0.090-0.267-0.454-0.458z"></path>
    <path class="path10" d="M24.996 6.964c-0.117 0-0.264-0.102-0.37 0 0.19 0.26 0.31 0.26 0.37 0z"></path>
    <path class="path11" d="M24.098 1.137c-0.226 0.456-0.685 0.877-0.685 1.416 0.12-0.124 0.813-1.416 0.685-1.416z"></path>
    <path class="path12" d="M24.691 0c-0.089 0.126-0.142 0.283-0.142 0.452 0 0.033 0.002 0.066 0.006 0.098 0.234-0.157 0.378-0.311 0.136-0.551z"></path>
    <path class="path13" d="M24.28 1.137c-0.030-0.058-0.068-0.121-0.095-0.182 0 0.107-0.15 0.182 0.095 0.182z"></path>
    <path class="path14" d="M24.46 0.547c-0.033 0.073-0.063 0.15-0.090 0.227 0.209-0.065 0.242-0.14 0.090-0.227z"></path>
    <path class="path15" d="M28.414 17.044c0.082 0.174 0.574 0.109 0.68 0z"></path>
    <path class="path16" d="M25.768 20.737c-0.346-0.613-0.549-1.347-0.549-2.127 0-0.054 0.001-0.108 0.003-0.161-0-1.68 2.826-0.941 2.826-1.36-0.441 0.030-2.247-0.209-2.5-0.42-0.566-0.473-0.207-1.815 0.582-1.815-0.386-0.966-1.588-1.189-0.911-2.275 0.49-0.784-0.547-0.377-0.547-0.824 0.177-0.075 0.324-0.149 0.465-0.232 0.371-0.589-0.855-0.158-0.923-0.094-0.234-0.534-0.863-0.879-0.249-1.453 0.794-0.738 0.481 0.32 1.208 0.32-0.13-0.295-0.282-0.55-0.461-0.783 0.452-0.090 1.239-0.714 1.239-1.224-2.005 0.767-2.744 1.481-4.333-0.265-1.175-1.289 1.325-2.35 0.914-3.612-1.096 0-1.074 3.018-2.151 2.736-1.278-0.333-0.462-1.537-0.944-2.126-0.332-0.399-1.009 0.914-1.469 0.757-0.405-0.135-0.677-1.027-1.088-0.998-0.392 0.027-1.072 1.237-1.431 0.603-0.112-0.363-0.333-0.665-0.623-0.877-0.030 0.522-0.385 0.959-0.867 1.105-1.295 0.178-0.882-0.297-1.325-1.201-0.87-0.435 0.117 1.911-0.985 1.58-1.744-0.525-0.381 0.356-1.692 0.904-0.378 0.159-0.435-1.208-0.971-1.208-0.683 0 0.582 2.992-2.646 1.686 0 0.812-0.294 0.322-0.773 0.185 0.057 0.495 2.113 1.25 1.695 1.682-1.096 1.126-1.194-1.376-1.194 0.87 0 2.034-2.426 1.108-2.462 0.813 0.054 0.466 0.704 0.794 0.976 1.125 0.345 0.42 0.024 1.092 0.223 1.591 0.296 0.745-1.368 1.351-1.186 1.519 2.081 1.911-1.588 2.743 1.167 4.575 0.536 0.357-0.092 3.029-0.092 3.786 0.781-0.354 1.548-0.809 2.184 0.053 0.356 0.486-0.416 1.357-0.364 1.409 0.372 0.382 1.926-0.619 1.626 0.604-0.117 0.484-0.481 1.083 0.724 0.812 0.887-0.2 2.241 1.818 2.63 1.818-0.007 0.055-0.011 0.118-0.011 0.182 0 0.146 0.021 0.288 0.060 0.421 0.454-0.011 0.009-1.508 0.653-1.332 0.922 0.252 0.62 1.178 0.62 2.145 0.908-0.25 0.468-2.521 1.732-1.917 0.058 0.661 0.162 1.264 0.311 1.849 0.176-0.868 2.283-3.245 2.286-1.486 0.319-0.016 1.909-0.118 2.067-0.757 0.193-0.79 1.901-1.863 1.901-0.562 0.909-0.227-0.095-1.416 0.919-2.026 0.925-0.556 1.344 0.721 1.912 0.155 0.215-0.46-0.43-1.118-0.166-1.915 0.256-0.779 1.999-2.203 1.988-2.234zM19.307 22.717c-0.468 0.234-1.015 0.44-1.585 0.589-0.524 0.122-1.056 0.184-1.604 0.184-0.017 0-0.034-0-0.051-0-0.024 0.001-0.055 0.001-0.086 0.001-0.516 0-1.011-0.090-1.471-0.254-0.46-0.149-0.885-0.388-1.249-0.696-0.309-0.251-0.558-0.576-0.72-0.947-0.138-0.405-0.214-0.852-0.214-1.318 0-0.064 0.001-0.128 0.004-0.192l-0-4.962h-1.926v-2.007c0.61-0.175 1.143-0.442 1.612-0.789 0.379-0.324 0.7-0.728 0.934-1.184 0.263-0.549 0.439-1.161 0.504-1.806l2.018-0.023v3.278h3.277v2.532h-3.277v3.634c-0.011 0.137-0.017 0.297-0.017 0.458 0 0.379 0.034 0.75 0.099 1.111 0.108 0.221 0.295 0.431 0.529 0.57 0.304 0.181 0.663 0.286 1.047 0.286 0.012 0 0.025-0 0.037-0 0.802-0.018 1.542-0.277 2.152-0.707z"></path>
    <path class="path17" d="M26.812 7.648c-0.22 0.113-0.862 0.319-0.862 0.639 0.185-0.075 0.862-0.388 0.862-0.639z"></path>
    <path class="path18" d="M25.583 29.539c0 0.275 0.275-0.139 0.275-0.275-0.109 0.075-0.2 0.166-0.273 0.271z"></path>
    <path class="path19" d="M26.812 21.924c-0.065 0.248 0.030 0.384 0.278 0.407 0.256 0-0.011-0.407-0.278-0.407z"></path>
    <path class="path20" d="M14.959 29.722c-0.035 0-0.075-0.095-0.117-0.239-0.036 0.147-0.006 0.239 0.117 0.239z"></path>
    <path class="path21" d="M27.775 22.47c-0.283 0.577 0.906 0.469 0 0z"></path>
    <path class="path22" d="M10.994 30.496c0.31-0.126 0.606-0.828 0.5-1.050-0.073-1.267-0.487 0.947-0.5 1.050z"></path>
    <path class="path23" d="M12.223 29.446c0.067-0.063 0.108-0.152 0.108-0.25s-0.042-0.188-0.108-0.25c-0.286 0.271-0.286 0.437-0 0.5z"></path>
    <path class="path24" d="M12.362 28.945c0-0.425 0.481-0.651 0.087-1.050 0 0.113-0.326 1.050-0.087 1.050z"></path>
    <path class="path25" d="M10.537 31.728c0.057 0.091 0.122 0.184 0.185 0.272 0.054-0.176 0.163-0.379 0-0.547-0.015 0.119-0.085 0.218-0.183 0.274z"></path>
    <path class="path26" d="M16.738 28.945c0.001-0.016 0.002-0.035 0.002-0.054 0-0.153-0.053-0.293-0.142-0.404-0.024 0.047-0.038 0.102-0.038 0.159 0 0.128 0.071 0.24 0.176 0.298z"></path>
    <path class="path27" d="M17.146 28.079l-0-0.003c-0.070 0.004-0.081 0.003 0 0.003z"></path>
    </symbol>
    <symbol id="icon-globe" viewBox="0 0 31 32">
    <title>globe</title>
    <path class="path1" d="M0.004 15.237c-0.111-4.764 1.901-8.455 5.235-11.453 2.56-2.363 5.994-3.812 9.766-3.812 0.844 0 1.671 0.072 2.475 0.212q-0.058 0.131-0.031 0.275c-0.637 0.024-1.275 0.043-1.913 0.072-1.31 0.071-2.54 0.306-3.704 0.685-0.916 0.285-1.773 0.89-2.758 1.238-0.827 0.267-1.545 0.606-2.208 1.025-1.484 0.979-2.69 2.351-3.467 3.967-0.652 1.203-1.199 2.532-1.597 3.924-0.085 0.319-0.161 0.475-0.26 0.615-0.475 0.712-0.843 1.549-1.054 2.447-0.020 0.379-0.207 0.66-0.479 0.802z"></path>
    <path class="path2" d="M15.827 31.555c-0.121 0.264-0.383 0.445-0.687 0.445-0.045 0-0.089-0.004-0.133-0.012-0.817-0.125-1.658 0.010-2.472-0.284-0.504-0.157-1.156-0.316-1.821-0.441-1.669-0.45-3.024-1.118-4.219-1.99-1.121-0.829-2.112-1.819-2.942-2.935-0.178-0.232-0.298-0.464-0.037-0.674 0.024-0.007 0.052-0.012 0.081-0.012 0.123 0 0.228 0.077 0.268 0.186 0.417 0.921 1.426 1.188 2.060 1.884 0.626 0.687 1.565 0.889 2.444 1.137 0.145 0.025 0.274 0.085 0.38 0.171 1.090 1.23 2.654 1.286 4.090 1.658 0.033 0.008 0.073-0.003 0.105 0.007 0.827 0.362 1.794 0.659 2.799 0.846z"></path>
    <path class="path3" d="M25.402 28.262c0.909-2.468 2.751-4.205 4.629-5.922 0.096 1.916-2.8 5.616-4.629 5.922z"></path>
    <path class="path4" d="M5.239 3.785c-3.334 2.998-5.346 6.689-5.235 11.453 0.277-0.144 0.464-0.425 0.474-0.751 0.221-0.951 0.59-1.788 1.085-2.533 0.078-0.107 0.154-0.263 0.202-0.432 0.435-1.542 0.982-2.871 1.66-4.119 0.752-1.571 1.957-2.943 3.444-3.922 0.66-0.418 1.378-0.758 2.138-1.005 1.053-0.368 1.91-0.973 2.928-1.286 1.062-0.351 2.292-0.586 3.565-0.655 0.674-0.031 1.312-0.050 1.949-0.074q-0.027-0.143-0.055-0.287c-0.718-0.127-1.545-0.199-2.389-0.199-3.772 0-7.206 1.449-9.776 3.821z"></path>
    <path class="path5" d="M31.066 19.814c0.222-0.497 0.352-1.076 0.352-1.686 0-0.084-0.002-0.167-0.007-0.249-0.71 0.927-0.753 1.288-0.344 1.936z"></path>
    <path class="path6" d="M25.402 28.262c1.829-0.306 4.725-4.006 4.629-5.922-1.878 1.717-3.72 3.454-4.629 5.922z"></path>
    <path class="path7" d="M12.839 30.689c-1.437-0.371-3-0.427-4.092-1.659-0.104-0.085-0.233-0.146-0.373-0.17-0.883-0.249-1.823-0.451-2.448-1.137-0.634-0.695-1.643-0.962-2.059-1.882-0.041-0.111-0.146-0.188-0.269-0.188-0.029 0-0.057 0.004-0.083 0.012-0.259 0.209-0.139 0.442 0.012 0.636 0.856 1.154 1.848 2.144 2.966 2.971 1.198 0.875 2.553 1.542 4.015 1.953 0.871 0.164 1.524 0.323 2.162 0.515 0.679 0.259 1.521 0.124 2.342 0.25 0.038 0.007 0.083 0.011 0.128 0.011 0.304 0 0.566-0.18 0.685-0.44-1.088-0.205-2.055-0.503-2.97-0.898 0.056 0.023 0.017 0.035-0.017 0.027z"></path>
    <path class="path8" d="M31.411 17.889c0.004 0.071 0.007 0.154 0.007 0.238 0 0.61-0.129 1.19-0.362 1.713-0.398-0.675-0.355-1.035 0.356-1.951z"></path>
    <path class="path9" d="M26.95 13.88c-0.95-5.277-5.507-9.229-10.988-9.229-6.161 0-11.155 4.994-11.155 11.155s4.994 11.155 11.155 11.155c0.689 0 1.363-0.062 2.017-0.182 5.198-0.945 9.14-5.495 9.14-10.966 0-0.683-0.061-1.352-0.179-2.002zM23.783 9.767l-2.894 0.51c-0.487-1.081-1.058-2.013-1.729-2.862-0.371-0.462-0.798-0.895-1.264-1.282 2.402 0.472 4.451 1.785 5.871 3.612zM21.264 17.194l-4.271 0.754-0.975-5.529 4.109-0.724c0.363 0.917 0.671 2.003 0.874 3.125 0.137 0.763 0.225 1.543 0.261 2.337zM18.206 8.227c0.526 0.661 0.996 1.407 1.38 2.203l-3.787 0.744-0.892-5.055c1.096 0.073 2.244 0.796 3.299 2.108zM15.748 18.167l-4.191 0.74c-0.196-0.597-0.383-1.343-0.522-2.105-0.185-1.021-0.281-2.067-0.281-3.135 0-0.113 0.001-0.225 0.003-0.337l4.016-0.691zM13.667 6.365l0.887 5.029-3.725 0.657c0.287-2.777 1.326-4.953 2.838-5.685zM7.881 10.149c0.818-1.157 1.846-2.108 3.036-2.815-0.766 1.408-1.282 3.107-1.376 4.916l-2.977 0.553c0.33-1.012 0.778-1.891 1.339-2.685zM6.245 17.531c-0.094-0.51-0.147-1.097-0.147-1.697 0-0.598 0.053-1.184 0.155-1.753l3.232-0.511c0.007 1.984 0.309 3.894 0.865 5.693l-3.442 0.463c-0.282-0.631-0.512-1.367-0.654-2.134zM7.505 20.904l3.222-0.568c0.544 1.468 1.263 2.737 2.15 3.869 0.386 0.479 0.833 0.928 1.323 1.326-2.851-0.516-5.241-2.226-6.67-4.582zM13.829 23.389c-0.739-0.941-1.362-2.023-1.817-3.188l3.956-0.79 1.071 6.077c-1.069-0.105-2.183-0.822-3.21-2.099zM18.288 25.292l-1.076-6.1 4.063-0.716c-0.105 3.337-1.235 6.037-2.987 6.817zM21.115 24.243c0.902-1.665 1.432-3.646 1.432-5.75 0-0.085-0.001-0.17-0.003-0.254l3.12-0.538c-0.562 2.784-2.225 5.101-4.506 6.517zM25.83 16.389l-3.312 0.584c-0.052-0.847-0.148-1.624-0.286-2.386-0.202-1.115-0.503-2.202-0.893-3.246l3.215-0.428c0.814 1.394 1.295 3.070 1.295 4.857 0 0.218-0.007 0.434-0.021 0.648z"></path>
    </symbol>
    <symbol id="icon-phone" viewBox="0 0 32 32">
    <title>phone</title>
    <path class="path1" d="M7.51 28.927c-1.169-0.818-2.544-1.31-3.386-2.589-0.462-0.702-1.097-1.284-1.532-2.038-0.379-0.658-1.018-1.169-0.996-2.022-0.776-0.991-0.569-2.317-1.162-3.392-0.138-0.37-0.218-0.797-0.218-1.244 0-0.128 0.007-0.255 0.020-0.38 0.003-0.081 0.005-0.193 0.005-0.307 0-0.545-0.056-1.077-0.162-1.59-0.313-1.156 0.356-2.207 0.541-3.331 0.069-0.419 0.244-0.86 0.321-1.296 0.217-1.226 0.929-2.211 1.519-3.256 0.502-0.889 0.967-1.78 1.942-2.315 0.487-0.267 0.765-0.853 1.204-1.253 0.631-0.632 1.387-1.139 2.228-1.481 0.598-0.212 0.948-0.699 1.509-0.933 0.722-0.326 1.626-0.661 2.553-0.943 1.199-0.367 2.382-0.553 3.608-0.553 0.036 0 0.072 0 0.107 0 0.961 0.027 1.925 0.046 2.891 0.109 0.296 0.027 0.567 0.077 0.829 0.149 2.277 0.581 4.304 1.519 6.106 2.76 0.528 0.253 1.018 0.675 1.382 1.191 0.074 0.138 0.18 0.234 0.309 0.287 0.867 0.243 1.219 1.058 1.615 1.672 1.133 1.759 2.208 3.589 2.621 5.683 0.228 1.106 0.409 2.443 0.505 3.804 0.103 1.469-0.27 2.774-0.235 4.122-0.052 0.697-0.24 1.339-0.537 1.916-0.472 1.194-0.895 2.485-1.745 3.513-0.323 0.464-0.661 1.011-0.971 1.575-0.419 0.745-1.010 1.229-1.718 1.452-0.118 0.032-0.261 0.058-0.295 0.129-0.404 0.818-1.361 0.903-1.95 1.484-0.451 0.444-1.132 0.462-1.696 0.709-1.213 0.602-2.626 1.011-4.118 1.147-0.861 0.054-1.607 0.143-2.338 0.272-0.804 0.133-1.666-0.173-2.549-0.329-0.486-0.124-1.049-0.206-1.628-0.228-0.62-0.033-1.136-0.383-1.402-0.886 0.335-0.077 0.726-0.115 1.125-0.115 0.427 0 0.844 0.044 1.246 0.129 1.3 0.16 2.622 0.453 3.962 0.607 1.185 0.136 2.314-0.268 3.437-0.583 0.591-0.137 1.109-0.322 1.597-0.559 0.83-0.465 2.091-0.741 2.38-1.721 0.38-1.292 1.497-1.607 2.293-2.335 0.686-0.567 1.233-1.277 1.598-2.087 0.253-0.586 0.593-1.050 1.013-1.426 0.561-0.583 0.965-1.314 1.148-2.129 0.199-0.837 0.349-1.654 0.533-2.464 0.056-0.206 0.089-0.442 0.089-0.685 0-0.156-0.013-0.309-0.039-0.459-0.081-0.405-0.128-0.888-0.128-1.383 0-0.055 0.001-0.11 0.002-0.164-0.012-2.173-0.719-4.188-1.911-5.826-0.438-0.663-0.635-1.486-1.181-2.119-0.111-0.13-0.171-0.335-0.307-0.406-0.775-0.408-1.168-1.243-1.912-1.674-0.245-0.14-0.444-0.334-0.586-0.566-1.080-1.774-2.902-2.568-4.718-2.909-2.093-0.393-4.256-0.844-6.448-0.399-1.79 0.363-3.63 0.572-5.084 1.823-0.862 0.742-1.777 1.433-2.581 2.226-0.54 0.618-1.010 1.318-1.385 2.074-0.545 0.955-1.289 1.679-1.867 2.531-0.201 0.223-0.362 0.487-0.469 0.777-0.121 0.647-0.37 1.205-0.723 1.681-0.217 0.311-0.351 0.71-0.351 1.14 0 0.225 0.037 0.441 0.104 0.643 0.163 0.465 0.26 1.017 0.26 1.591 0 0.399-0.047 0.788-0.135 1.161-0.019 0.193-0.033 0.456-0.033 0.722 0 0.639 0.084 1.259 0.242 1.849 0.172 0.936 0.494 1.815 0.936 2.618 0.065 0.086 0.145 0.236 0.207 0.395 0.361 1.478 1.115 2.729 2.145 3.688 0.919 0.796 1.292 2.072 2.372 2.748z"></path>
    <path class="path2" d="M0.233 17.277c-0.012 0.109-0.018 0.236-0.018 0.364 0 0.446 0.080 0.874 0.226 1.269 0.585 1.049 0.377 2.375 1.154 3.366-0.022 0.854 0.617 1.365 0.996 2.022 0.435 0.754 1.070 1.336 1.532 2.038 0.842 1.279 2.218 1.771 3.386 2.589-1.081-0.676-1.454-1.952-2.368-2.744-1.034-0.963-1.788-2.214-2.134-3.625-0.079-0.225-0.158-0.375-0.252-0.515-0.414-0.749-0.735-1.628-0.91-2.558-0.155-0.595-0.239-1.214-0.239-1.854 0-0.266 0.015-0.529 0.043-0.788 0.079-0.307 0.125-0.695 0.125-1.095 0-0.575-0.097-1.127-0.275-1.641-0.053-0.153-0.090-0.369-0.090-0.594 0-0.43 0.134-0.829 0.363-1.157 0.341-0.459 0.59-1.017 0.702-1.623 0.116-0.331 0.277-0.595 0.48-0.82 0.576-0.85 1.32-1.574 1.84-2.472 0.401-0.813 0.871-1.513 1.42-2.143 0.794-0.781 1.71-1.472 2.571-2.214 1.454-1.251 3.294-1.46 5.084-1.823 2.192-0.445 4.355 0.006 6.448 0.399 1.817 0.341 3.639 1.135 4.714 2.902 0.147 0.239 0.346 0.433 0.582 0.569 0.753 0.436 1.146 1.271 1.92 1.679 0.136 0.071 0.196 0.277 0.307 0.406 0.546 0.633 0.744 1.455 1.201 2.148 1.172 1.61 1.88 3.624 1.891 5.803-0.001 0.049-0.002 0.104-0.002 0.159 0 0.495 0.048 0.978 0.138 1.447 0.016 0.085 0.029 0.239 0.029 0.395 0 0.244-0.032 0.48-0.093 0.704-0.179 0.791-0.329 1.608-0.523 2.415-0.189 0.845-0.593 1.576-1.151 2.157-0.423 0.377-0.762 0.842-0.991 1.366-0.39 0.871-0.937 1.582-1.613 2.141-0.806 0.736-1.923 1.051-2.303 2.343-0.289 0.98-1.55 1.255-2.425 1.74-0.444 0.217-0.961 0.402-1.502 0.529-1.174 0.326-2.303 0.729-3.488 0.593-1.34-0.154-2.662-0.447-4.001-0.614-0.363-0.077-0.78-0.122-1.207-0.122-0.399 0-0.79 0.039-1.168 0.113 0.309 0.506 0.825 0.856 1.426 0.888 0.598 0.022 1.161 0.104 1.703 0.24 0.827 0.145 1.689 0.45 2.615 0.299 0.61-0.111 1.356-0.2 2.112-0.248 1.597-0.142 3.010-0.551 4.293-1.184 0.494-0.215 1.176-0.233 1.626-0.678 0.589-0.581 1.545-0.666 1.95-1.484 0.034-0.070 0.177-0.097 0.274-0.123 0.728-0.228 1.319-0.713 1.682-1.345 0.367-0.677 0.705-1.224 1.069-1.751 0.809-0.965 1.232-2.256 1.717-3.478 0.284-0.549 0.472-1.191 0.523-1.871-0.034-1.365 0.339-2.669 0.242-4.038-0.101-1.461-0.283-2.798-0.544-4.107-0.379-1.891-1.454-3.722-2.587-5.481-0.395-0.614-0.747-1.429-1.611-1.671-0.133-0.054-0.239-0.15-0.303-0.273-0.374-0.532-0.863-0.954-1.433-1.238-1.76-1.21-3.787-2.147-5.966-2.707-0.36-0.093-0.631-0.143-0.909-0.169-0.985-0.064-1.949-0.084-2.915-0.111-0.030-0-0.066-0-0.102-0-1.226 0-2.409 0.186-3.521 0.531-1.014 0.304-1.918 0.639-2.795 1.026-0.406 0.173-0.757 0.66-1.309 0.856-0.886 0.358-1.642 0.866-2.273 1.497-0.439 0.4-0.718 0.986-1.204 1.253-0.974 0.535-1.44 1.426-1.942 2.315-0.589 1.045-1.302 2.029-1.519 3.256-0.077 0.435-0.252 0.877-0.321 1.296-0.185 1.124-0.854 2.175-0.532 3.382 0.098 0.463 0.153 0.995 0.153 1.54 0 0.113-0.002 0.226-0.007 0.338z"></path>
    <path class="path3" d="M12.14 7.173c-1.165-0.669-2.099 0.359-3.445 1.762l-0.002 0.040c-0.147 0.216-0.28 0.463-0.384 0.724-1.18 2.556-0.166 6.611 2.684 10.222 2.417 3.062 5.567 4.915 8.173 5.099l0.015 0.008 0.011-0.003c0.128 0.013 0.277 0.020 0.428 0.020 0.955 0 1.842-0.294 2.574-0.797l0.021 0.001c1.316-0.922 2.174-1.652 1.734-2.775-0.734-1.861-2.348-2.644-3.943-3.254-1.044-0.394-1.87 0.353-2.625 1.577-1.257-0.726-2.317-1.647-3.174-2.735-0.734-0.903-1.324-1.923-1.739-3.028 1.459-0.646 2.483-1.368 2.238-2.527-0.337-1.658-0.862-3.359-2.565-4.334zM19.192 25.026c0.128 0.013 0.278 0.020 0.428 0.020 0.955 0 1.842-0.294 2.574-0.797-0.732 0.503-1.619 0.797-2.574 0.797-0.151 0-0.3-0.007-0.447-0.022z"></path>
    </symbol>
    <symbol id="icon-pinterest" viewBox="0 0 28 32">
    <title>pinterest</title>
    <path class="path1" d="M20.71 27.27c1.259-0.873 1.88-0.651 3.163-0.863 0.294-0.048-0.36-3.216 1.478-2.786 1.568 0.368 0.97-0.221 0.432-1.091-0.704-1.133 1.467-1.189 1.2-1.917-0.421-1.16-0.664-0.967 0.382-1.927 1.938-1.78-1.312-2.138-0.27-3.396 1.789-2.153-2.405-3.797-1.069-5.134 0.091-0.091 2.389-1.723 1.92-1.871-0.464-0.144-1.944 0.728-2.275 0.947-1.936 0.968-1.16-1.527-1.674-2.353-1.509-2.41-0.763-0.959-2.885-1.345-0.739-0.136-1.861-0.52-2.261-1.227-0.296-0.523 0.235-1.127-0.315-1.509-0.566-0.396-1.091 0.864-1.616 0.205-0.246-0.304-0.539-1.028-0.955-1.148 0.232 0.067-1.32 3.56-2.011 1.661-0.269-0.736 0.152-3.259-0.853-3.516 0 1.249 0.147 3.865-1.648 3.317-2.226-0.677-2.283 0.729-3.344 2.057-0.52 0.652-2.675-0.261-3.384-0.261-1.035 0-1.011-1.998-1.562-2.554 0 1.711 1.4 2.825 1.955 4.311 0.579 1.539-1.837 1.894-2.656 2.447-1.307 0.882 0.336 2.229-0.432 3.456-0.296 0.488-2.229-0.183-1.938 0.575 0.381 0.984 2.168 1.397 1.117 2.773-0.469 0.481-1.885 1.023-0.816 1.833 2.072 1.572 1.248 2.122-0.21 3.564 1.603 0.645 3.048 0.979 3.010 2.969-0.019 1.054 1.496 0.501 2.077 1.264 0.517 0.68 1.683 3.685 0.832 4.049 0.81-0.347 0.64-2.71 2.256-1.427 0.539 0.428 1.275 1.692 2.163 1.115 1.563-1.019 1.344 0.355 1.461 1.421 0.101 0.917 1.688 1.747 1.688 0.336 0-0.713-1.059-1.069-0.171-1.669 0.693-0.208 1.392-0.409 2.088-0.599 0.666 0.439 1.016 2.465 1.565 0.361 1.013-3.896 3.048 1.907 3.405 1.907 1.038-1.389-1.285-2.55-0.155-3.715zM16.86 27.394c0.822 0.421-0.632 0.95 0 0zM15.909 22.009c-1.143 0-2.217-0.618-2.586-1.319 0 0-0.615 2.44-0.746 2.911-0.268 0.862-0.629 1.613-1.083 2.297-0.479-0.182-0.894-0.339-1.296-0.522 0.055-0.128 0.049-0.308 0.049-0.49 0-0.675 0.073-1.332 0.211-1.965 0.191-0.8 1.35-5.71 1.35-5.71-0.214-0.478-0.339-1.037-0.339-1.624 0-0.018 0-0.035 0-0.053-0-1.567 0.909-2.739 2.041-2.739 0.964 0 1.428 0.723 1.428 1.59 0 0.969-0.616 2.417-0.934 3.759-0.032 0.12-0.050 0.259-0.050 0.401 0 0.906 0.734 1.64 1.64 1.64 0.029 0 0.057-0.001 0.085-0.002 2.003 0 3.354-2.577 3.354-5.63 0-2.321-1.564-4.059-4.405-4.059-3.213 0-5.214 2.396-5.214 5.072-0.002 0.038-0.003 0.083-0.003 0.128 0 0.743 0.265 1.423 0.705 1.953 0.112 0.090 0.186 0.233 0.186 0.394 0 0.069-0.014 0.135-0.039 0.196-0.050 0.192-0.167 0.661-0.215 0.847-0.029 0.175-0.179 0.307-0.36 0.307-0.062 0-0.121-0.016-0.172-0.043-1.477-0.605-2.166-2.224-2.166-4.045 0-3.009 2.538-6.616 7.57-6.616 4.041 0 6.703 2.927 6.703 6.066 0 4.154-2.309 7.257-5.715 7.257z"></path>
    </symbol>
    <symbol id="icon-mail" viewBox="0 0 31 32">
    <title>mail</title>
    <path class="path1" d="M12.34 0.539c0.932-0.349 2.008-0.552 3.132-0.552 0.35 0 0.695 0.020 1.035 0.058 1.698 0.14 3.306 0.506 4.82 1.068 1.017 0.346 2.008 0.893 2.878 1.585 0.428 0.352 1.029 0.545 1.444 0.939 0.873 0.779 1.659 1.617 2.366 2.519 0.278 0.472 0.577 0.84 0.926 1.152 0.948 0.683 1.028 1.829 1.407 2.789 0.627 1.697 0.989 3.657 0.989 5.702 0 0.128-0.001 0.256-0.004 0.383 0.007 0.115 0.010 0.273 0.010 0.431 0 1.219-0.213 2.388-0.604 3.472-0.067 0.158-0.138 0.427-0.178 0.705-0.070 0.45-0.038 0.925-0.461 1.236-0.098 0.090-0.158 0.218-0.158 0.361 0 0.001 0 0.003 0 0.004-0.011 0.641-0.24 1.228-0.615 1.69-0.585 0.895-0.887 2.009-1.681 2.744-0.703 0.65-0.979 1.703-1.939 1.99-0.151 0.269 0.163 0.429 0.006 0.667-0.079 0.026-0.17 0.042-0.265 0.046-0.202-0.015-0.448-0.161-0.589 0.031-0.636 0.873-1.746 0.846-2.582 1.364-0.809 0.45-1.755 0.771-2.759 0.904-0.702 0.12-1.453 0.373-2.116-0.135-0.071-0.041-0.156-0.065-0.247-0.065-0.054 0-0.107 0.009-0.156 0.025-1.818 0.621-3.626 0.111-5.318-0.358-2.767-0.78-5.137-2.212-7.024-4.123-1.097-1.11-2.216-2.281-2.674-3.879-0.141-0.417-0.345-0.777-0.603-1.091-0.983-1.285-1.269-2.78-1.382-4.455 0.246 0.141 0.412 0.399 0.424 0.695 0.37 1.935 1.369 3.569 2.372 5.222 0.843 1.319 1.806 2.459 2.899 3.457 0.786 0.75 1.772 1.064 2.628 1.622 1.196 0.717 2.6 1.228 4.099 1.444 0.33 0.058 0.592 0.152 0.86 0.219 0.292 0.112 0.629 0.176 0.981 0.176 0.301 0 0.591-0.047 0.863-0.135 0.152-0.040 0.348-0.067 0.552-0.067 0.050 0 0.1 0.002 0.149 0.005 0.054 0.002 0.125 0.003 0.196 0.003 0.756 0 1.478-0.141 2.143-0.398 0.453-0.152 1.078-0.316 1.716-0.442 0.543-0.147 0.917-0.295 1.274-0.47 0.342-0.174 0.576-0.672 0.901-0.695 0.008 0 0.018 0 0.028 0 0.371 0 0.69-0.225 0.828-0.545 0.043-0.057 0.105-0.089 0.175-0.089 0.002 0 0.004 0 0.006 0 1.409 0.12 1.767-1.098 2.421-1.921 0.26-0.328 0.454-0.694 0.908-0.788 0.208-0.057 0.376-0.195 0.471-0.378 0.528-0.905 1.331-1.649 1.547-2.725 0.159-0.791 0.739-1.436 0.776-2.274 0.035-0.132 0.056-0.284 0.056-0.44 0-0.059-0.003-0.116-0.008-0.173-0.066-0.154-0.105-0.34-0.105-0.536 0-0.386 0.151-0.737 0.398-0.996 0.151-0.154 0.105-0.383 0.136-0.586 0.069-0.441 0.083-0.887 0.19-1.329 0.343-1.418-0.041-2.749-0.487-4.096-0.327-0.988-1.020-1.792-1.27-2.803-0.011-0.072-0.037-0.137-0.075-0.193-1.244-1.425-1.897-3.373-3.76-4.259-0.188-0.1-0.343-0.238-0.461-0.404-0.667-0.923-1.676-1.317-2.662-1.707-0.755-0.299-1.517-0.595-2.27-0.87-0.426-0.125-0.916-0.197-1.423-0.197-0.119 0-0.238 0.004-0.355 0.012-0.139 0.010-0.321 0.016-0.503 0.016-0.794 0-1.56-0.119-2.282-0.339-0.149-0.087-0.389-0.147-0.643-0.147-0.216 0-0.423 0.043-0.611 0.121-0.183 0.096-0.443-0.049-0.741-0.201z"></path>
    <path class="path2" d="M17.004 31.653c0.046-0.015 0.098-0.024 0.152-0.024 0.091 0 0.176 0.024 0.249 0.067 0.66 0.507 1.412 0.254 2.073 0.138 1.045-0.138 1.99-0.458 2.84-0.929 0.795-0.497 1.905-0.47 2.541-1.343 0.14-0.192 0.386-0.046 0.587-0.031 0.096-0.004 0.188-0.020 0.274-0.048 0.15-0.236-0.165-0.396-0.014-0.665 0.96-0.287 1.236-1.34 1.939-1.99 0.794-0.735 1.097-1.849 1.685-2.749 0.371-0.457 0.6-1.043 0.611-1.683 0-0.004 0-0.005 0-0.007 0-0.143 0.061-0.271 0.158-0.361 0.423-0.312 0.391-0.787 0.459-1.216 0.043-0.299 0.114-0.568 0.212-0.823 0.36-0.987 0.573-2.156 0.573-3.375 0-0.158-0.004-0.316-0.011-0.472 0.003-0.086 0.005-0.214 0.005-0.342 0-2.045-0.362-4.005-1.027-5.82-0.342-0.843-0.421-1.988-1.365-2.668-0.354-0.316-0.653-0.683-0.888-1.092-0.75-0.966-1.535-1.804-2.39-2.566-0.434-0.411-1.035-0.604-1.483-0.971-0.85-0.677-1.84-1.224-2.914-1.59-1.457-0.541-3.066-0.907-4.739-1.047-0.364-0.039-0.71-0.058-1.059-0.058-1.124 0-2.201 0.202-3.196 0.572 0.362 0.131 0.622 0.276 0.815 0.176 0.178-0.074 0.384-0.118 0.6-0.118 0.254 0 0.495 0.060 0.708 0.166 0.657 0.202 1.423 0.32 2.217 0.32 0.183 0 0.364-0.006 0.544-0.019 0.077-0.006 0.195-0.010 0.315-0.010 0.507 0 0.997 0.072 1.46 0.206 0.716 0.266 1.478 0.562 2.233 0.861 0.986 0.39 1.995 0.784 2.659 1.703 0.12 0.171 0.276 0.309 0.456 0.405 1.87 0.889 2.524 2.837 3.768 4.264 0.037 0.054 0.063 0.119 0.074 0.189 0.25 1.013 0.943 1.817 1.27 2.806 0.446 1.347 0.83 2.678 0.487 4.096-0.107 0.442-0.121 0.888-0.19 1.329-0.031 0.202 0.015 0.432-0.137 0.586-0.246 0.259-0.397 0.609-0.397 0.995 0 0.196 0.039 0.383 0.11 0.553 0.002 0.040 0.004 0.098 0.004 0.157 0 0.156-0.020 0.308-0.059 0.452-0.034 0.826-0.614 1.471-0.773 2.262-0.216 1.076-1.019 1.82-1.545 2.72-0.097 0.187-0.265 0.326-0.468 0.381-0.46 0.095-0.653 0.462-0.913 0.789-0.654 0.823-1.012 2.041-2.421 1.921-0.002-0-0.004-0-0.006-0-0.070 0-0.132 0.032-0.173 0.083-0.141 0.327-0.459 0.552-0.831 0.552-0.010 0-0.020-0-0.030-0-0.324 0.023-0.558 0.521-0.944 0.714-0.314 0.156-0.688 0.304-1.075 0.42-0.794 0.157-1.418 0.32-2.027 0.519-0.509 0.211-1.232 0.352-1.988 0.352-0.071 0-0.142-0.001-0.213-0.004-0.032-0.003-0.082-0.004-0.132-0.004-0.203 0-0.4 0.026-0.587 0.076-0.236 0.078-0.526 0.126-0.827 0.126-0.352 0-0.69-0.065-1.001-0.183-0.248-0.060-0.51-0.154-0.78-0.205-1.56-0.223-2.963-0.734-4.211-1.48-0.805-0.53-1.791-0.844-2.564-1.582-1.106-1.010-2.069-2.15-2.871-3.401-1.043-1.721-2.043-3.355-2.413-5.288-0.011-0.298-0.177-0.556-0.42-0.695 0.109 1.673 0.395 3.168 1.383 4.459 0.254 0.308 0.458 0.668 0.592 1.061 0.464 1.622 1.584 2.793 2.679 3.901 1.888 1.913 4.258 3.344 6.914 4.097 1.803 0.496 3.611 1.006 5.432 0.384z"></path>
    <path class="path3" d="M20.259 25.691c-1.565 0.723-3.395 1.145-5.325 1.145-0.159 0-0.318-0.003-0.476-0.009-5.219 0.001-9.828-3.757-9.828-9.945 0-6.441 4.705-12.030 11.809-12.030 5.589 0 9.567 3.82 9.567 9.125 0 4.61-2.589 7.546-5.999 7.546-0.064 0.006-0.139 0.009-0.215 0.009-1.371 0-2.484-1.103-2.501-2.47l-0.063-0.002c-1.010 1.61-2.4 2.463-4.105 2.463-2.021 0-3.536-1.547-3.536-4.168-0.001-0.035-0.001-0.075-0.001-0.116 0-3.983 3.229-7.212 7.212-7.212 0.073 0 0.147 0.001 0.22 0.003 0.014-0.001 0.044-0.001 0.074-0.001 1.317 0 2.569 0.279 3.699 0.781l-1.005 5.85c-0.284 1.831-0.063 2.715 0.821 2.747 1.358 0.063 3.063-1.705 3.063-5.336 0-4.136-2.652-7.294-7.546-7.294-4.862 0-9.062 3.757-9.062 9.819 0 5.304 3.347 8.272 8.051 8.272 0.031 0 0.068 0 0.105 0 1.633 0 3.182-0.361 4.571-1.007zM17.48 12.651c-0.293-0.078-0.63-0.123-0.977-0.126-2.086 0-3.728 2.052-3.728 4.483 0 1.2 0.537 1.926 1.547 1.926 1.2 0 2.431-1.452 2.684-3.284z"></path>
    </symbol>
    </defs>
    </svg>

    <a class="contact_profile contact_profile-facebook" href="https://www.facebook.com/janebrinkmann.munk">
      <svg class="contact_profile_icon"><use xlink:href="#icon-facebook"></use></svg>
      <span class="contact_profile_label">Facebook</span>
    </a>
    <a class="contact_profile contact_profile-pinterest" href="https://dk.pinterest.com/janemunk/">
      <svg class="contact_profile_icon"><use xlink:href="#icon-pinterest"></use></svg>
      <span class="contact_profile_label">Pinterest</span></a>
    <a class="contact_profile  contact_profile-tumblr" href="http://janemunk.tumblr.com/">
      <svg class="contact_profile_icon"><use xlink:href="#icon-tumblr"></use></svg>
      <span class="contact_profile_label">Tumblr</span>
    </a>
    <div class="contact_items">
      <a class="contact_item contact_item-mail" href="mailto:janemunk@stofanet.dk">
        <svg class="contact_item_icon"><use xlink:href="#icon-mail"></use></svg>
        <span class="contact_item_label">janemunk@stofanet.dk</span>
      </a>
      <a class="contact_item contact_item-address" href="https://maps.apple.com/?address=Apotekervænget%2016,%209370%20Hals,%20Danmark&amp;ll=56.993730,10.306931&amp;q=Apotekervænget%2016">
        <svg class="contact_item_icon"><use xlink:href="#icon-globe"></use></svg>
        <span class="contact_item_label">Apotekervænget 16<br/>9370 Hals, Denmark</span>
      </a>
      <a class="contact_item contact_item-phone" href="tel:004523640079">
        <svg class="contact_item_icon"><use xlink:href="#icon-phone"></use></svg>
        <span class="contact_item_label">+45 23 64 00 79</span>
      </a>
    </div>
  </div>
</xsl:template>

</xsl:stylesheet>