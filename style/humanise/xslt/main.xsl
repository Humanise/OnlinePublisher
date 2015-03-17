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
 exclude-result-prefixes="p f h n o util widget"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>
<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<xsl:call-template name="util:doctype"/>
<html>
	<xsl:call-template name="util:html-attributes"/>
	<head>
		<title>
			<xsl:choose>
				<xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
					<xsl:text>Humanise - </xsl:text>
					<xsl:choose>
						<xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>Software for humans</xsl:text></xsl:when>
						<xsl:otherwise><xsl:text>Software til mennesker</xsl:text></xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="@title"/><xsl:text> - </xsl:text><xsl:value-of select="f:frame/@title"/>
				</xsl:otherwise>
			</xsl:choose>
		</title>
		<meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
		<link rel="shortcut icon" href="{$path}style/in2isoft/gfx/favicon.ico" type="image/x-icon" />
		<xsl:call-template name="util:metatags"/>
		<xsl:call-template name="util:watermark"/>
        
        <xsl:call-template name="util:style-inline">
			<xsl:with-param name="compiled">body{padding:0;margin:0;font-family:Arial,'Helvetica',sans-serif}body.font{font-family:Lato,'Helvetica Neue','Helvetica','Arial','Lucida Grande','Lucida Sans Unicode',sans-serif}form{margin:0}img{border:none}a{word-break:break-word}a.common{color:#ddd;color:rgba(0,0,0,0.15);cursor:pointer;font-weight:normal}a.common span{color:#0079cd}a.common:hover,a.common:hover span{color:#0169b2}h1.common{font-weight:400;color:#324d60}p.common,ul.common{font-size:12pt;line-height:160%;text-align:justify}.retina.webkit a.common{text-decoration:none;background-image:-moz-linear-gradient(top,transparent 99%,rgba(0,0,0,0.5) 99%);background-image:-webkit-linear-gradient(top,transparent 98%,rgba(0,0,0,0.5) 98%);background-image:linear-gradient(top,transparent 98%,rgba(0,0,0,0.5) 98%);background-repeat:repeat-x}.part_header{color:#333;font-weight:300}.part_header_1{color:#3080CB}h1.part_header,textarea.part_header_1{font-size:38px;line-height:42px}h2.part_header,textarea.part_header_2{font-size:19pt;line-height:21pt}h3.part_header,textarea.part_header_3{font-size:14pt;line-height:16pt;font-weight:400}h4.part_header,textarea.part_header_4,h5.part_header,textarea.part_header_5,h6.part_header,textarea.part_header_6{font-weight:400}div.part_section_header_3{padding-bottom:5px}body.windows .part_header{font-weight:normal}body.windows h1.part_header{font-weight:300}p.part_poster_text{font-size:14px;font-weight:300}p.part_poster_link{font-size:14px}p.part_poster_title{font-weight:400}.part_text,div.part_richtext{font-size:12pt;font-weight:300;word-spacing:1px}.part_text strong,div.part_richtext strong{color:#000;font-weight:400}div.part_richtext p{margin:10px 0}div.part_listing,textarea.part_listing{font-size:11pt;font-weight:300;word-spacing:1px}div.part_listing span.part_listing_first{font-size:12pt;font-weight:normal}body.windows .part_text,body.windows .part_text{font-weight:normal}div.part_list h3{font-weight:normal}hr.part_horizontalrule{border:none;border-bottom:solid 1px #DDD}div.part_news_item_title{font-size:10pt;font-weight:400}.windows div.part_news_item_title{font-weight:bold}div.part_news_item_description{font-size:10pt;font-weight:300}.windows div.part_news_item_description{font-weight:400}.layout_menu{margin:0;padding:5px 10px;list-style:none;text-align:center}.layout_menu_item{display:inline-block}.layout_menu_link{padding:0 5px;height:32px;line-height:32px;text-decoration:none;color:#333}.layout_menu_item_first{display:block;text-align:center}.layout_menu_link_first{background:url(/style/humanise/gfx/graphics.png) -139px -636px;display:inline-block;width:160px;height:40px}.layout_menu_link_first > span{display:none}.retina .layout_menu_link_first{background-image:url(/style/humanise/gfx/graphics_2x.png);background-size:400px 907px}@media screen and (min-width:760px){.layout_menu{text-align:left}.layout_menu > li{vertical-align:top}.layout_menu_link{font-size:20px;line-height:44px;padding:0 10px;color:#324d60}.layout_menu_item_first{display:inline-block}}@media screen and (min-width:1000px){.layout_menu{padding:5px 0}}.layout_search{margin:0;font-size:0}.layout_search_body{display:none}.layout_search_icon{position:absolute;top:10px;right:10px;display:inline-block;width:30px;height:30px}.layout_search_icon > path{fill:#aaa}.layout_middle,.layout_head_body,.layout_base{position:relative;max-width:980px;margin:0 auto}.layout_head_body{position:relative}.layout_head{background:#f6f6f9}.layout_sub_navigation{margin:0;padding:5px 5px;list-style:none;border-bottom:1px solid #eee}.layout_sub_navigation > li{display:inline-block}.layout_sub_navigation > li > a{padding:0 5px;height:32px;line-height:32px;text-decoration:none;color:#333}.layout_side_navigation{margin:0;padding:5px 5px;list-style:none;border-bottom:1px solid #eee}.layout_side_navigation > li{display:inline-block}.layout_side_navigation > li > a{padding:0 5px;height:32px;line-height:32px;text-decoration:none;color:#333}.layout_inner_content{padding:10px}@media screen and (min-width:560px){.layout_inner_content{padding:20px}.layout_sub_navigation{padding:5px 15px}}@media screen and (min-width:760px){.layout_inner_content_sidebar{margin-right:30%}.layout_sidebar{width:30%;float:right}}@media screen and (min-width:1000px){.layout_sub_navigation,.layout_inner_content{padding-left:0}.layout_sub_navigation li:first-child{margin-left:-5px}}@media screen and (min-width:1000px){#poster{display:block;width:980px;height:310px;background:#333}#poster_loader{background:#000;color:#FFF;left:50%;margin-left:-25px;position:absolute;text-align:center;top:150px;width:50px;height:24px;line-height:22px}}.call-to-action{background:#fafafa;border:1px solid #eee;border-radius:3px;padding:20px 10px;text-align:center}.call-to-action-text{margin:-5px 0 0;color:#666;font-weight:300;font-size:32px;line-height:1}.call-to-action-button{background:#3080CB;color:#fff;height:34px;line-height:34px;padding:0 20px;box-sizing:border-box;border:none;border-radius:3px;display:inline-block;font-size:18px;margin-top:20px;text-decoration:none;cursor:pointer}.call-to-action-button:hover{background:#206AB0}.call-to-action-button:active{-webkit-transform:scale(0.95);outline:none}.common_header{margin:0;color:#333;font-weight:300}.part_text{font-size:12pt;font-weight:300;word-spacing:1px}div.document_column{display:block}@media screen and (max-width:700px){.document_column{width:auto!important;padding:0!important}}@media screen and (min-width:700px){.document_row{display:table;width:100%}.document_row_body{display:table-row}div.document_column{display:table-cell;vertical-align:top}}</xsl:with-param>
        </xsl:call-template>
					
		<xsl:call-template name="util:style-lt-ie9"/>
		<xsl:call-template name="util:scripts-build"/>
		<xsl:call-template name="util:style-build"/>
		<xsl:call-template name="util:load-font">
			<xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lato:300,400,700'"/>
            <xsl:with-param name="family" select="'Lato'"/>
            <xsl:with-param name="weights" select="'300,400,700'"/>
		</xsl:call-template>
	</head>
	<body>
        <xsl:call-template name="util:script-inline">
            <xsl:with-param name="file" select="'style/humanise/js/inline.js'"/>
            <xsl:with-param name="compiled"><![CDATA[_editor.defer(function(){hui.browser.windows&&hui.cls.add(document.body,"windows"),hui.browser.msie&&hui.cls.add(document.body,"msie"),hui.browser.webkit&&hui.cls.add(document.body,"webkit")}),window.devicePixelRatio>1&&(document.body.className+=" retina")
]]></xsl:with-param>
        </xsl:call-template>
		<div class="layout">
			<div class="layout_head">
				<div class="layout_head_body">
					<ul class="layout_menu">
						<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
					</ul>
					<xsl:call-template name="search"/>
				</div>
			</div>
			<div class="layout_middle">
				<div class="layout_middle_top">
					<xsl:call-template name="secondlevel"/>
					<xsl:call-template name="util:languages"/>
					<xsl:comment/>
				</div>
				<div class="layout_body">
					<xsl:if test="//p:page/@id=//p:context/p:home/@page">
						<div id="poster">
							<div id="poster_body">
							<div id="poster_loader">0%</div>
							<div id="poster_left"><div id="poster_left_inner"><xsl:comment/></div></div>
							<div id="poster_right"><div id="poster_right_inner"><xsl:comment/></div></div>
							</div>
						</div>
                        <script type="text/javascript">require(['Poster'],function() {new Poster();});</script>
					</xsl:if>
					<xsl:apply-templates select="p:content"/>
					<xsl:choose>
						<xsl:when test="//p:page/@id=//p:context/p:home/@page">
							<ul class="layout_placards">
								<li class="layout_placards_item">
									<a href="{$path}produkter/onlinepublisher/" class="layout_placards_link">
                                        <strong>Humanise <em>Editor</em></strong> - <span class="layout_placards_text layout_placards_text_editor">Simpelt værktøj til opbygning og redigering af hjemmesider</span>
                                    </a>
								</li>
								<li class="layout_placards_item">
									<a href="{$path}produkter/onlineobjects/" class="layout_placards_link">
                                        <strong>Online<em>Objects</em></strong> - <span class="layout_placards_text layout_placards_text_objects">Fleksibelt grundsystem til web-applikationer</span>
                                    </a>
								</li>
								<li class="layout_placards_item">
									<a href="{$path}teknologi/hui/" class="layout_placards_link layout_placards_link_last">
                                        <strong>Humanise <em>UI</em></strong> - <span class="layout_placards_text layout_placards_text_hui">Intuitiv, avanceret og effektiv brugerflade</span>
                                    </a>
								</li>
							</ul>
						</xsl:when>
					</xsl:choose>
				</div>
			</div>
			
			<div class="layout_base">
				<div class="layout_info">
					<div class="layout_info_about">
						<xsl:choose>
							<xsl:when test="//p:page/p:meta/p:language='en'">
								<h2 class="layout_info_header">About Humanise</h2>
								<p>We focus on user experience and design. We seek out the most simple and essential solution. We believe that machines should work for people. We think that knowledge should be free and accessible to all. We hope you agree :-)</p>
								<p class="layout_info_more"><a href="{$path}om/" class="common"><span>More about Humanise »</span></a></p>
							</xsl:when>
							<xsl:otherwise>
                                <xsl:call-template name="util:parameter">
                                    <xsl:with-param name="name" select="'about'"/>
                                    <xsl:with-param name="default">
        								<h2 class="layout_info_header">Om Humanise</h2>
        								<p>Vores focus er på brugeroplevelse og design. Vi leder altid efter den mest enkle og essentielle løsning. Vi tror på at maskinen skal arbejde for mennesket. Vi mener at viden bør være fri og tilgængelig for alle. Vi håber du er enig :-)</p>                                        
                                    </xsl:with-param>
                                </xsl:call-template>
                                <p class="layout_info_more"><a href="{$path}om/" class="common"><span>Mere om Humanise »</span></a></p>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="layout_info_contact">
						<xsl:choose>
							<xsl:when test="//p:page/p:meta/p:language='en'"><h2>Contact</h2></xsl:when>
							<xsl:otherwise><h2 class="layout_info_header">Kontakt</h2></xsl:otherwise>
						</xsl:choose>
                        <xsl:call-template name="util:parameter">
                            <xsl:with-param name="name" select="'contact'"/>
                            <xsl:with-param name="default">
        						<p><strong class="layout_info_name">Jonas Brinkmann Munk</strong></p>
        						<p><a href="mailto:jonasmunk@me.com" class="common"><span>jonasmunk@me.com</span></a></p>
        						<p><a href="tel:004528776365" class="layout_info_phone">+45 28 77 63 65</a></p>
        						<p><strong class="layout_info_name">Kenni Graversen</strong></p>
        						<p><a href="mailto:gr@versen.dk" class="common"><span>gr@versen.dk</span></a></p>
        						<p><a href="tel:004522486153" class="layout_info_phone">+45 22 48 61 53</a></p>
                            </xsl:with-param>
                        </xsl:call-template>
					</div>
				</div>
			</div>
		</div>
		<div class="layout_footer">
			<a class="layout_design">Designet og udviklet af Humanise</a>
			<!--
				<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
				<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
				-->
		</div>
		<xsl:call-template name="util:googleanalytics"/>
	</body>
</html>
</xsl:template>

<xsl:template match="p:content">
<div class="layout_content">
	<xsl:if test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
		<div class="layout_sidebar">
			<xsl:call-template name="thirdlevel"/>
			<xsl:apply-templates select="../f:frame/f:newsblock"/>
			<xsl:comment/>
		</div>
	</xsl:if>
	<div>
		<xsl:choose>
			<xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
				<xsl:attribute name="class">layout_inner_content layout_inner_content_sidebar</xsl:attribute>
			</xsl:when>
			<xsl:otherwise>
				<xsl:attribute name="class">layout_inner_content</xsl:attribute>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:if test="//p:context/p:translation">
			<p class="layout_translation">
			<xsl:for-each select="//p:context/p:translation">
				<a class="common">
					<xsl:call-template name="util:link"/>
					<xsl:choose>
						<xsl:when test="@language='da'">
							<span><xsl:text>Denne side på dansk</xsl:text></span>
						</xsl:when>
						<xsl:when test="@language='en'">
							<span><xsl:text>This page in english</xsl:text></span>
						</xsl:when>
						<xsl:otherwise>
							<span>This page in <xsl:value-of select="@language"/></span>
						</xsl:otherwise>
					</xsl:choose>
				</a>
			</xsl:for-each>
			</p>
		</xsl:if>
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
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
		<li>
			<xsl:attribute name="class">
				<xsl:text>layout_menu_item</xsl:text>
			<xsl:choose>
				<xsl:when test="position()>1 and //p:page/@id=@page"> layout_menu_item_selected</xsl:when>
				<xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id"> layout_menu_item_highlighted</xsl:when>
				<xsl:when test="position()=1"> layout_menu_item_first</xsl:when>
			</xsl:choose>
			</xsl:attribute>
			<a>
			<xsl:attribute name="class">
				<xsl:text>layout_menu_link</xsl:text>
			<xsl:choose>
				<xsl:when test="position()>1 and //p:page/@id=@page"> layout_menu_link_selected</xsl:when>
				<xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id"> layout_menu_link_highlighted</xsl:when>
				<xsl:when test="position()=1"> layout_menu_link_first</xsl:when>
			</xsl:choose>
			</xsl:attribute>
				<xsl:call-template name="util:link"/>
				<span><xsl:value-of select="@title"/></span>
			</a>
		</li>
	</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<ul>
			<xsl:attribute name="class">
				<xsl:text>layout_sub_navigation</xsl:text>
				<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[descendant-or-self::*/@page=//p:page/@id]">
					<xsl:text> layout_sub_navigation_selected</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul>
		<xsl:attribute name="class">
			<xsl:text>layout_side_navigation</xsl:text>
			<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[descendant-or-self::*/@page=//p:page/@id]">
				<xsl:text> layout_side_navigation_selected</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
	<xsl:variable name="style">
		<xsl:choose>
			<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
			<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
			<xsl:otherwise>normal</xsl:otherwise>
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
	<div class="layout_links">
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
	<div class="layout_news">
		<h2><xsl:value-of select="@title"/></h2>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
	<div class="layout_news_item">
		<h3><xsl:value-of select="o:title"/></h3>
		<p class="layout_news_text">
			<xsl:apply-templates select="o:note"/>
		</p>
		<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
		<xsl:apply-templates select="o:links"/>
	</div>
</xsl:template>

<xsl:template match="f:newsblock//o:links">
	<p class="layout_news_links">
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
	<p class="layout_news_date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></p>
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



<!--                  Search                     -->


<xsl:template name="search">
	<xsl:if test="f:frame/f:search">
		<form action="{$path}" method="get" class="layout_search" accept-charset="UTF-8">
			<svg class="layout_search_icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M19.129,18.164l-4.518-4.52c1.152-1.373,1.852-3.143,1.852-5.077c0-4.361-3.535-7.896-7.896-7.896 c-4.361,0-7.896,3.535-7.896,7.896s3.535,7.896,7.896,7.896c1.934,0,3.705-0.698,5.078-1.853l4.52,4.519 c0.266,0.268,0.699,0.268,0.965,0C19.396,18.863,19.396,18.431,19.129,18.164z M8.567,15.028c-3.568,0-6.461-2.893-6.461-6.461 s2.893-6.461,6.461-6.461c3.568,0,6.46,2.893,6.46,6.461S12.135,15.028,8.567,15.028z"></path></svg>
			<label class="layout_search_label" for="layout_search">Søgning</label>
			<span class="layout_search_body">
				<input type="text" class="layout_search_text" id="layout_search" name="query" placeholder="Search here"/>
				<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
				<xsl:for-each select="f:frame/f:search/f:types/f:type">
				<input type="hidden" name="{@unique}" value="on"/>
				</xsl:for-each>
				<input type="submit" class="layout_search_submit" value="Søg" tabindex="-1"/>
			</span>
		</form>
	</xsl:if>
</xsl:template>



<!--                    Widgets                 -->

<xsl:template match="widget:happy-xmas">
    <div class="happy-xmas">
        <h1 style="color: red;"><xsl:value-of select="widget:title"/></h1>
    </div>
</xsl:template>

<xsl:template match="widget:call-to-action">
    <div class="call-to-action">
        <p class="call-to-action-text"><xsl:value-of select="widget:text"/></p>
        <xsl:apply-templates select="widget:button"/>
    </div>
</xsl:template>

<xsl:template match="widget:call-to-action/widget:button">
    <a class="call-to-action-button">
        <xsl:call-template name="util:link-href"/>
        <xsl:value-of select="."/>
    </a>
</xsl:template>

</xsl:stylesheet>