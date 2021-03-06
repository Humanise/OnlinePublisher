<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:pn="http://uri.in2isoft.com/onlinepublisher/part/news/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="pn n o util"
 >

<xsl:template match="pn:news">
	<div class="part_news common_font">
	<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="pn:box">
	<div class="part_news_box">
		<div class="part_news_box_top"><div><div><xsl:comment/></div></div></div>
		<div class="part_news_box_middle">
			<xsl:if test="pn:title">
				<div class="part_news_title">
					<xsl:value-of select="pn:title"/>
					<xsl:comment/>
				</div>
			</xsl:if>
			<xsl:apply-templates select="o:object"/>
			<xsl:comment/>
		</div>
		<div class="part_news_box_bottom"><div><div><xsl:comment/></div></div></div>
	</div>
</xsl:template>

<xsl:template match="pn:list">
	<div class="part_news_list">
	<xsl:if test="pn:title">
		<div class="part_news_title">
			<xsl:value-of select="pn:title"/>
		</div>
	</xsl:if>
	<xsl:apply-templates select="o:object"/>
	</div>
</xsl:template>

<xsl:template match="pn:box/o:object | pn:list/o:object">
	<div class="part_news_item part_news_item_alt_{(position() mod 2)+1} part_news_item_{position()}">
	<div class="part_news_item_title">
		<xsl:value-of select="o:title"/>
	</div>
	<xsl:if test="o:note/text()!=''">
		<div class="part_news_item_description">
			<xsl:apply-templates select="o:note"/>
			<xsl:comment/>
		</div>
	</xsl:if>
	<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
	<xsl:if test="o:links">
		<div class="part_news_item_links">
			<xsl:apply-templates select="o:links/o:link"/>
		</div>
	</xsl:if>
	</div>
</xsl:template>

<xsl:template match="pn:news//o:break">
	<br/>
</xsl:template>

<xsl:template match="pn:news//n:startdate">
	<span class="part_news_item_date"><xsl:value-of select="number(@day)"/>/<xsl:value-of select="number(@month)"/>/<xsl:value-of select="substring(@year,3,2)"/></span>
</xsl:template>

<xsl:template match="pn:news//o:link">
	<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
	<a title="{@alternative}" class="common common_link part_news_link"><xsl:call-template name="util:link"/>
		<span class="common_link_text part_news_link_text"><xsl:value-of select="@title"/></span>
	</a>
</xsl:template>

</xsl:stylesheet>