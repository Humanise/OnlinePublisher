<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class NewsPartController extends PartController
{
	function NewsPartController() {
		parent::PartController('news');
	}

	function createPart() {
		$part = new NewsPart();
		$part->setTitle(GuiUtils::getTranslated(array('Seneste nyt','da'=>'Latest news')));
		$part->setVariant('box');
		$part->setMode('single');
		$part->setSortDir('ascending');
		$part->setSortBy('startdate');
		$part->setTimeType('always');
		$part->save();
		return $part;
	}

	function buildSub($part,$context) {
		$data='<news xmlns="'.$this->getNamespace().'">';
		if (Strings::isNotBlank($part->getVariant())) {
			$data.='<'.$part->getVariant().'>';
			if (Strings::isNotBlank($part->getTitle())) {
				$data.='<title>'.Strings::escapeEncodedXML($part->getTitle()).'</title>';
			}
			$maxitems = $part->getMaxItems(); // TODO: Build this into sql PERFORMANCE!
			$sql = $this->buildSql($part);
			if ($sql!='') {
				$result = Database::select($sql);
				while ($newsRow = Database::next($result)) {
					$data.=$newsRow['data'];
					$maxitems--;
					if ($maxitems==0) break;
				}
				Database::free($result);
			}

			$data.='</'.$part->getVariant().'>';
		}
		$data.='</news>';
		return $data;
	}

	function isDynamic($part) {
		return true;
	}

	function getFromRequest($id) {
		$part = NewsPart::load($id);
		$part->setTitle(Request::getString('title'));
		$part->setVariant(Request::getString('variant'));
		$part->setMode(Request::getString('mode'));
		$part->setSortBy(Request::getString('sortby'));
		$part->setSortDir(Request::getString('sortdir'));
		$part->setTimeType(Request::getString('timetype'));
		$part->setTimeCount(Request::getInt('timecount'));
		$part->setMaxItems(Request::getInt('maxitems'));
		$part->setNewsId(Request::getInt('news'));
		$part->setNewsGroupIds(Request::getIntArrayComma('groups'));
		return $part;
	}

	function display($part,$context) {
		return $this->render($part,$context);
	}

	function getIndex($part) {
		return $part->getTitle();
	}

	function editor($part,$context) {

		$groups = $part->getNewsGroupIds();

		return
		'<input type="hidden" name="title" value="'.Strings::escapeEncodedXML($part->getTitle()).'"/>'.
		'<input type="hidden" name="mode" value="'.$part->getMode().'"/>'.
		'<input type="hidden" name="news" value="'.$part->getNewsId().'"/>'.
		'<input type="hidden" name="groups" value="'.implode(',',$groups).'"/>'.
		'<input type="hidden" name="align" value="'.$part->getAlign().'"/>'.
		'<input type="hidden" name="sortby" value="'.$part->getSortBy().'"/>'.
		'<input type="hidden" name="sortdir" value="'.$part->getSortDir().'"/>'.
		'<input type="hidden" name="maxitems" value="'.$part->getMaxItems().'"/>'.
		'<input type="hidden" name="timetype" value="'.$part->getTimeType().'"/>'.
		'<input type="hidden" name="timecount" value="'.$part->getTimeCount().'"/>'.
		'<input type="hidden" name="variant" value="'.$part->getVariant().'"/>'.
		'<div id="part_news_preview">'.
		$this->render($part,$context).
		'</div>'.
		'<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/news/editor.js"></script>';
	}

	function getEditorUI($part,$context) {
		return '
		<window title="{News; da:Nyheder}" name="newsWindow" width="300">
			<tabs small="true" centered="true">
				<tab title="{Settings; da:Indstillinger}" padding="10">
					<formula>
						<fields labels="above">
							<field label="{Title; da:Titel}">
								<text-input value="'.Strings::escapeEncodedXML($part->getTitle()).'" name="newsTitle"/>
							</field>
							<field label="Variant">
								<radiobuttons value="'.$part->getVariant().'" name="newsVariant">
									<option text="{List; da:Liste}" value="list"/>
									<option text="{Box; da:Boks}" value="box"/>
								</radiobuttons>
							</field>
							<!--
							<field label="{Alignment; da:Justering}">
								<radiobuttons value="'.$part->getAlign().'" name="newsAlign">
									<option text="{Left; da:Venstre}" value="left"/>
									<option text="{Center; da:Midte}" value="center"/>
									<option text="{Right; da:Højre}" value="right"/>
								</radiobuttons>
							</field>
							-->
						</fields>
						<space height="10"/>
						<fieldset legend="{News; da:Nyheder}">
							<fields labels="above">
								<field label="{Groups; da:Grupper}">
									<checkboxes name="newsGroups">
									'.
                  UI::buildOptions('newsgroup').
                  '
									</checkboxes>
								</field>
								<field label="{News; da:Nyheder}">
									<dropdown name="newsNews">
									'.
                  UI::buildOptions('news').
                  '
									</dropdown>
								</field>
							</fields>
						</fieldset>
					</formula>
				</tab>
				<tab title="{Appearance; da:Visning}" padding="10">
					<formula>
						<fields labels="above">
							<field label="{Direction; da:Retning}">
								<radiobuttons value="'.$part->getSortDir().'" name="newsSortDir">
									<option text="{Descending; da:Faldende}" value="descending"/>
									<option text="{Ascending; da:Stigende}" value="ascending"/>
								</radiobuttons>
							</field>
							<field label="{Ordering; da:Sortering}">
								<radiobuttons value="'.$part->getSortBy().'" name="newsSortBy">
									<option text="{Start date; da:Startdato}" value="startdate"/>
									<option text="{End date; da:Slutdato}" value="enddate"/>
									<option text="{Title; da:Titel}" value="title"/>
								</radiobuttons>
							</field>
							<field label="{Maximum number of items; da:Maksimalt antal}">
								<number-input name="newsMaxItems" value="'.$part->getMaxItems().'"/>
							</field>
							<field label="{Time; da:Tid}">
								<dropdown name="newsTimeType" value="'.$part->getTimeType().'">
									<option text="{Always; da:Altid}" value="always"/>
									<option text="{Now; da:Lige nu}" value="now"/>
									<option text="{Latest hours...; da:Seneste timer...}" value="hours"/>
									<option text="{Latest days...; da:Seneste dage...}" value="days"/>
									<option text="{Latest weeks...; da:Seneste uger...}" value="weeks"/>
									<option text="{Latest months...; da:Seneste måneder...}" value="months"/>
									<option text="{Latest years...; da:Seneste år...}" value="years"/>
								</dropdown>
							</field>
							<field label="{Count; da:Antal}">
								<number-input name="newsTimeCount" value="'.$part->getTimeCount().'"/>
							</field>
						</fields>
					</formula>
				</tab>
			</tabs>
		</window>
		';
	}

	function buildSql($part) {
		$sql = '';
		if ($part->getMode() == 'single' && $part->getNewsId()!='') {
			$sql="select * from object where id=".$part->getNewsId();
		}
		else if ($part->getMode() == 'groups') {
			$sortBy = $part->getSortBy();
			// Find sort direction
			if ($part->getSortDir()=='descending') {
				$sortDir = 'DESC';
			}
			else {
				$sortDir = 'ASC';
			}
			$timetype = $part->getTimeType();
			if ($timetype=='always') {
				$timeSql=''; // no time managing for always
			}
			else if ($timetype=='now') {
				// Create sql for active news
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
			}
			else {
				$count=$part->getTimeCount();
				if ($timetype=='interval') {
					$start = intval($part->getStartdate());
					$end = intval($part->getEnddate());
				}
				else if ($timetype=='hours') {
					$start = mktime(date("H")-$count,date("i"),date("s"),date("m"),date("d"),date("Y"));
					$end = time();
				}
				else if ($timetype=='days') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$count,date("Y"));
					$end = time();
				}
				else if ($timetype=='weeks') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-($count*7),date("Y"));
					$end = time();
				}
				else if ($timetype=='months') {
					$start = mktime(date("H"),date("i"),date("s"),date("m")-$count,date("d"),date("Y"));
					$end = time();
				}
				else if ($timetype=='years') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")-$count);
					$end = time();
				}
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
			}
			$groups = $part->getNewsGroupIds();
			if (isset($groups) && count($groups)>0) {
				$groupSql = " and newsgroup_news.newsgroup_id in (".implode($groups,',').")";
			} else {
				$groupSql = " and newsgroup_news.newsgroup_id=part_news_newsgroup.newsgroup_id and part_news_newsgroup.part_id=".$this->id;
			}
			$sql = "select distinct object.data from object,news, newsgroup_news, part_news_newsgroup where object.id=news.object_id and news.object_id=newsgroup_news.news_id".$groupSql.$timeSql." order by ".$sortBy." ".$sortDir;
		}
		return $sql;
	}
}
?>