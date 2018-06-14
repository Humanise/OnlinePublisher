<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:map="http://uri.in2isoft.com/onlinepublisher/part/map/1.0/"
 exclude-result-prefixes="map"
 >

  <xsl:template match="map:map">
    <span class="part_map">
      <xsl:if test="@width!=''">
        <xsl:attribute name="style">
          <xsl:text>max-width:</xsl:text>
          <xsl:value-of select="@width"/>
          <xsl:text>px;</xsl:text>
        </xsl:attribute>
      </xsl:if>

      <xsl:call-template name="util:wrap-in-frame">
        <xsl:with-param name="variant" select="@frame"/>
        <xsl:with-param name="adaptive" select="'true'"/>
        <xsl:with-param name="content">
          <xsl:call-template name="map:internal"/>
        </xsl:with-param>
      </xsl:call-template>
    </span>
  </xsl:template>

  <xsl:template name="map:internal">
    <xsl:choose>
      <xsl:when test="@provider='google-interactive'">
        <xsl:call-template name="map:google-interactive"/>
      </xsl:when>
      <xsl:otherwise>
        <xsl:call-template name="map:google-static"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template name="map:google-static">
    <xsl:variable name="height">
      <xsl:choose>
        <xsl:when test="number(@height)&gt;=640">
          <xsl:value-of select="number(640)"/>
        </xsl:when>
        <xsl:when test="number(@height)&gt;0 and number(@height)&lt;=640">
          <xsl:value-of select="number(@height)"/>
        </xsl:when>
        <xsl:otherwise>400</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="fake-height">
      <xsl:choose>
        <xsl:when test="$height&lt;610">
          <xsl:value-of select="$height+30"/>
        </xsl:when>
        <xsl:otherwise><xsl:value-of select="$height"/></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="width">
      <xsl:choose>
        <xsl:when test="number(@width)&gt;=640">
          <xsl:value-of select="number(640)"/>
        </xsl:when>
        <xsl:when test="number(@width)&gt;0">
          <xsl:value-of select="number(@width)"/>
        </xsl:when>
        <xsl:otherwise>640</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="url">
      <xsl:value-of select="concat($protocol,'://maps.googleapis.com/maps/api/staticmap?center=',@latitude,',',@longitude,'&amp;zoom=',@zoom,'&amp;size=',$width,'x',$fake-height,'&amp;sensor=false&amp;maptype=',@maptype,'&amp;key=AIzaSyAJEsQcWdC9lpcA9BTmNaeVbRWF-XqCyh0')"/>
    </xsl:variable>
    <span class="part_map_static" style="padding-bottom: {$height div $width * 100}%;">
      <a class="part_map_static_pin"><xsl:comment/></a>
      <img class="part_map_static_image" src="{$url}" srcset="{$url} 1x, {$url}&amp;scale=2 2x" style="width: {$width}px; height: {$fake-height}px;"/>
      <xsl:if test="map:text">
        <span class="part_map_static_text"><xsl:value-of select="map:text"/></span>
      </xsl:if>
    </span>
  </xsl:template>

  <xsl:template name="map:google-interactive">
    <span class="part_map_interactive" id="map_{../../@id}">
      <xsl:attribute name="style">
        <xsl:text>min-height: 30px;</xsl:text>
        <xsl:if test="@width">
          <xsl:text>width: </xsl:text><xsl:value-of select="@width"/><xsl:text>;</xsl:text>
        </xsl:if>
        <xsl:if test="not(@width)">
          <xsl:text>width:100%;</xsl:text>
        </xsl:if>
        <xsl:if test="@height">
          <xsl:text>height: </xsl:text><xsl:value-of select="@height"/><xsl:text>;</xsl:text>
        </xsl:if>
        <xsl:if test="not(@height)">
          <xsl:text>height: 300px;</xsl:text>
        </xsl:if>
      </xsl:attribute>
      <xsl:comment/>
    </span>
    <xsl:if test="map:text">
      <span class="part_map_text"><xsl:value-of select="map:text"/></span>
    </xsl:if>
    <script>
      _editor.loadPart({name:'Map',$ready:function() {
        var options = {
          element : 'map_<xsl:value-of select="../../@id"/>',
          markers : [],
          zoom : <xsl:value-of select="@zoom"/>,
          type : '<xsl:value-of select="@maptype"/>'
          <xsl:if test="@longitude and @latitude">
            ,center : {latitude:<xsl:value-of select="@latitude"/>,longitude:<xsl:value-of select="@longitude"/>}
          </xsl:if>
        };
        <xsl:for-each select="map:marker">
          options.markers.push({
            text : '<xsl:value-of select="@text"/>',
            latitude : <xsl:value-of select="@latitude"/>,
            longitude : <xsl:value-of select="@longitude"/>
          })
        </xsl:for-each>
        new op.part.Map(options);
      }})
    </script>
  </xsl:template>

</xsl:stylesheet>