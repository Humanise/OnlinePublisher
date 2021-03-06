<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:doc="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 xmlns:style="http://uri.in2isoft.com/onlinepublisher/style/1.0/"
 xmlns:ph="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="doc n p i o part ph style"
 >

  <xsl:include href="part_header.xsl"/>
  <xsl:include href="part_text.xsl"/>
  <xsl:include href="part_html.xsl"/>
  <xsl:include href="part_horizontalrule.xsl"/>
  <xsl:include href="part_image.xsl"/>
  <xsl:include href="part_listing.xsl"/>
  <xsl:include href="part_news.xsl"/>
  <xsl:include href="part_person.xsl"/>
  <xsl:include href="part_richtext.xsl"/>
  <xsl:include href="part_imagegallery.xsl"/>
  <xsl:include href="part_mailinglist.xsl"/>
  <xsl:include href="part_file.xsl"/>
  <xsl:include href="part_list.xsl"/>
  <xsl:include href="part_formula.xsl"/>
  <xsl:include href="part_poster.xsl"/>
  <xsl:include href="part_table.xsl"/>
  <xsl:include href="part_map.xsl"/>
  <xsl:include href="part_movie.xsl"/>
  <xsl:include href="part_menu.xsl"/>
  <xsl:include href="part_widget.xsl"/>
  <xsl:include href="part_authentication.xsl"/>
  <xsl:include href="part_custom.xsl"/>

  <xsl:template match="doc:content">
    <div class="document">
      <xsl:apply-templates/>
      <xsl:comment/>
    </div>
  </xsl:template>

  <xsl:template match="doc:row">
    <xsl:variable name="id" select="concat('document_row-',position())" />
    <xsl:variable name="style">
      <xsl:if test="@top!=''">
        <xsl:text>margin-top:</xsl:text><xsl:value-of select="@top"/><xsl:text>;</xsl:text>
      </xsl:if>
      <xsl:if test="@bottom!=''">
        <xsl:text>margin-bottom:</xsl:text><xsl:value-of select="@bottom"/><xsl:text>;</xsl:text>
      </xsl:if>
    </xsl:variable>
    <div data-id="{@id}">
      <xsl:attribute name="class">
        <xsl:text>document_row </xsl:text>
        <xsl:value-of select="$id"/>
        <xsl:if test="@class!=''">
          <xsl:text> </xsl:text><xsl:value-of select="@class"/>
        </xsl:if>
      </xsl:attribute>
      <xsl:if test="$style!=''">
        <xsl:attribute name="style"><xsl:value-of select="$style"/></xsl:attribute>
      </xsl:if>
      <xsl:apply-templates select="*[not(self::style:style)]"/>
      <xsl:comment/>
    </div>
    <xsl:call-template name="doc:row-style"/>
  </xsl:template>

  <xsl:template match="doc:column">
    <div class="document_column" data-id="{@id}">
      <xsl:apply-templates select="*[not(self::style:style)]"/>
      <xsl:comment/>
    </div>
  </xsl:template>

  <!-- table layout -->

  <xsl:template match="doc:row[count(doc:column)>1]">

    <xsl:variable name="id" select="concat('document_row-',@id)" />
    <div class="document_row {$id} {@class}" data-id="{@id}">
      <div class="document_table_container">
        <xsl:if test="@spacing!=''">
          <xsl:attribute name="style">
            <xsl:text>margin: 0 -</xsl:text><xsl:value-of select="@spacing"/><xsl:text>;</xsl:text>
          </xsl:attribute>
        </xsl:if>
        <div class="document_table">
          <xsl:variable name="style">
            <xsl:if test="@spacing!=''">
              <xsl:text>border-collapse: separate;</xsl:text>
              <xsl:text>border-spacing:</xsl:text><xsl:value-of select="@spacing"/><xsl:text> 0;</xsl:text>
            </xsl:if>
            <xsl:if test="@top!=''">
              <xsl:text>margin-top:</xsl:text><xsl:value-of select="@top"/><xsl:text>;</xsl:text>
            </xsl:if>
            <xsl:if test="@bottom!=''">
              <xsl:text>margin-bottom:</xsl:text><xsl:value-of select="@bottom"/><xsl:text>;</xsl:text>
            </xsl:if>
          </xsl:variable>
          <xsl:if test="$style!=''">
            <xsl:attribute name="style"><xsl:value-of select="$style"/></xsl:attribute>
          </xsl:if>
          <div class="document_table_body">
            <xsl:apply-templates/>
            <xsl:comment/>
          </div>
        </div>
      </div>
    </div>
    <xsl:call-template name="doc:row-style"/>
  </xsl:template>

  <xsl:template match="doc:row[count(doc:column)>1]/doc:column">
    <xsl:variable name="style">
      <xsl:choose>
        <xsl:when test="@width='min'">
          <xsl:text>width: 1%;</xsl:text>
        </xsl:when>
        <xsl:when test="@width='max'">
          <xsl:text>width: 100%;</xsl:text>
        </xsl:when>
        <xsl:when test="contains(@width,'%') or contains(@width,'px')">
          <xsl:text>width: </xsl:text><xsl:value-of select="@width"/><xsl:text>;</xsl:text>
        </xsl:when>
        <xsl:when test="@width">
          <xsl:text>width: </xsl:text><xsl:value-of select="@width"/><xsl:text>px;</xsl:text>
        </xsl:when>
      </xsl:choose>
        <xsl:if test="@top!=''">
          <xsl:text>padding-top: </xsl:text><xsl:value-of select="@top"/><xsl:text>;</xsl:text>
        </xsl:if>
        <xsl:if test="@bottom!=''">
          <xsl:text>padding-bottom: </xsl:text><xsl:value-of select="@bottom"/><xsl:text>;</xsl:text>
        </xsl:if>
        <xsl:if test="@left!=''">
          <xsl:text>padding-left: </xsl:text><xsl:value-of select="@left"/><xsl:text>;</xsl:text>
        </xsl:if>
        <xsl:if test="@right!=''">
          <xsl:text>padding-right: </xsl:text><xsl:value-of select="@right"/><xsl:text>;</xsl:text>
        </xsl:if>
    </xsl:variable>
    <div data-id="{@id}">
      <xsl:if test="$style!=''">
        <xsl:attribute name="style"><xsl:value-of select="$style"/></xsl:attribute>
      </xsl:if>
      <xsl:attribute name="class">
        <xsl:text>document_column document_table_column</xsl:text>
        <xsl:if test="position()=1"> document_column_first</xsl:if>
      </xsl:attribute>

      <xsl:apply-templates select="*[not(self::style:style)]"/>
      <xsl:comment/>
    </div>
  </xsl:template>


  <!-- Flex layout -->

  <xsl:template name="doc:row-style">
    <xsl:variable name="id" select="concat('document_row-',@id)" />
    <xsl:if test="style:style/style:if">
    <style data-for-row="{@id}">
      <xsl:for-each select="style:style/style:if">
        <xsl:call-template name="util:media-before"/>
        <xsl:for-each select="style:feature[@name='reverse']">
          <xsl:value-of select="concat('.', $id, '{flex-wrap: wrap-reverse;}')"/>
        </xsl:for-each>
        <xsl:for-each select="style:component[@name='row']">
          <xsl:value-of select="concat('.', $id, '{')"/>
            <xsl:call-template name="util:rules"/>
          <xsl:text>}</xsl:text>
        </xsl:for-each>
        <xsl:for-each select="style:component[@name='column']">
          <xsl:value-of select="concat('.', $id, '_column {')"/>
            <xsl:call-template name="util:rules"/>
          <xsl:text>}</xsl:text>
        </xsl:for-each>
        <xsl:call-template name="util:media-after"/>
      </xsl:for-each>
    </style>
    </xsl:if>
  </xsl:template>

  <xsl:template name="doc:column-style">
    <xsl:variable name="id" select="concat('document_column-',generate-id())" />
    <xsl:if test="style:style/style:if">
    <style>
      <xsl:for-each select="style:style/style:if">
        <xsl:call-template name="util:media-before"/>
        <xsl:for-each select="style:component[@name='column']">
          <xsl:value-of select="concat('.', $id, '{')"/>
            <xsl:call-template name="util:rules"/>
          <xsl:text>}</xsl:text>
        </xsl:for-each>
        <xsl:call-template name="util:media-after"/>
      </xsl:for-each>
    </style>
    </xsl:if>
  </xsl:template>

  <xsl:template match="doc:row[@layout='flexible']">
    <xsl:variable name="id" select="concat('document_row-',@id)" />
    <div class="document_row document_row-flexible {$id} {@class}" data-id="{@id}">
      <xsl:apply-templates select="*[not(self::style:style)]"/>
      <xsl:comment/>
    </div>
    <xsl:call-template name="doc:row-style"/>
  </xsl:template>

  <xsl:template match="doc:row[@layout='flexible']/doc:column">
    <xsl:variable name="id" select="concat('document_column-',generate-id())" />
    <div class="document_column document_column-flexible {$id} document_row-{../@id}_column" data-id="{@id}">
      <xsl:apply-templates select="*[not(self::style:style)]"/>
      <xsl:comment/>
    </div>

    <xsl:call-template name="doc:column-style"/>
  </xsl:template>

  <!-- sections -->

  <xsl:template match="doc:section">
    <xsl:variable name="id" select="concat('part_section-',generate-id())" />
    <xsl:variable name="style">
      <xsl:if test="@left"> padding-left: <xsl:value-of select="@left"/>;</xsl:if>
      <xsl:if test="@right"> padding-right: <xsl:value-of select="@right"/>;</xsl:if>
      <xsl:if test="@top"> padding-top: <xsl:value-of select="@top"/>;</xsl:if>
      <xsl:if test="@bottom"> padding-bottom: <xsl:value-of select="@bottom"/>;</xsl:if>
      <xsl:if test="@float"> float: <xsl:value-of select="@float"/>;</xsl:if>
      <xsl:if test="@width"> width: <xsl:value-of select="@width"/>;</xsl:if>
    </xsl:variable>
    <div>
      <xsl:if test="$style!=''">
        <xsl:attribute name="style"><xsl:value-of select="$style"/></xsl:attribute>
      </xsl:if>
      <xsl:if test="$preview='true'">
        <xsl:attribute name="data">
          <xsl:text>{&quot;id&quot;:</xsl:text><xsl:value-of select="@id"/><xsl:text>}</xsl:text>
        </xsl:attribute>
      </xsl:if>
      <xsl:if test="$preview='true' and part:part">
        <xsl:attribute name="id"><xsl:text>part-</xsl:text><xsl:value-of select="part:part/@id"/></xsl:attribute>
      </xsl:if>
      <xsl:attribute name="class">
        <xsl:choose>
          <xsl:when test="part:part">part_section part_section_<xsl:value-of select="part:part/@type"/>
          <!-- Hack to make headers margins work -->
          <xsl:if test="part:part/@type='header'"> part_section_header-<xsl:value-of select="part:part/part:sub/ph:header/@level"/></xsl:if>
          <xsl:if test="@class!=''"><xsl:text> </xsl:text><xsl:value-of select="@class"/></xsl:if>
        </xsl:when>
        </xsl:choose>
        <xsl:if test="style:style">
          <xsl:value-of select="concat(' ', $id)"/>
        </xsl:if>
      </xsl:attribute>
      <xsl:if test="style:style/style:if/style:build-in">
        <xsl:attribute name="data-build-in"><xsl:value-of select="style:style/style:if/style:build-in/@effect"/></xsl:attribute>
      </xsl:if>
      <xsl:apply-templates select="*[not(self::style:style)]"/>
      <xsl:comment/>
    </div>

    <xsl:if test="style:style/style:if">
    <style>
      <xsl:for-each select="style:style/style:if">
        <xsl:call-template name="util:media-before"/>
        <xsl:for-each select="style:component[@name='section']">
          <xsl:value-of select="concat('.', $id, '{')"/>
            <xsl:call-template name="util:rules"/>
          <xsl:text>}</xsl:text>
        </xsl:for-each>
        <xsl:call-template name="util:media-after"/>
      </xsl:for-each>
    </style>
    </xsl:if>
  </xsl:template>


  <!--          Part            -->

  <xsl:template match="part:part">
    <xsl:apply-templates select="part:sub/*"/>
  </xsl:template>

</xsl:stylesheet>