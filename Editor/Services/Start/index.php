<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../Include/Private.php';

$user = User::load(InternalSession::getUserId());

$gui='
<gui xmlns="uri:hui" padding="10" title="Start">
	<style url="style.css"/>
	<controller name="controller" url="controller.js"/>
	<source name="taskSource" url="data/TaskList.php"/>
	<source name="developerFeed" url="data/DeveloperFeed.php"/>
	<source name="commitFeed" url="data/CommitFeed.php"/>
	<source name="newsFeed" url="data/NewsFeedArticles.php"/>
	<source name="warningsList" url="data/WarningsList.php?status=warning"/>
	<source name="errorsList" url="data/WarningsList.php?status=error"/>
	<source name="chartSource" url="data/StatisticsChart.php"/>

	<div class="box">
		<div class="header">
			<span class="date"> version: '.SystemInfo::getFormattedDate().'</span>
			<span class="user">
				<icon icon="common/user" size="16"/>
				<strong>'.Strings::escapeXml($user->getTitle()).'</strong>
				<em>('.Strings::escapeXml($user->getUsername()).')</em>
				<button mini="true" variant="light" text="{Settings ; da:Indstillinger}" name="userSettings"/>
			</span>
		</div>
		<div class="root">
			<tiles space="10">
				<tile width="30" height="100" top="0" left="0" variant="light" name="taskTile">
					<actions>
						<!--icon icon="monochrome/info"/-->
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Tasks ; da: Opgaver }</title>
					<pages name="issuePages" height="full">
						<page>
							<overflow height="full" background="sand_grey">
							<list source="taskSource" name="taskList" variant="transparent" selectable="false">
								<empty>
									<space all="10">
									<text>
										<p><strong>Der er ingen opgaver lige nu</strong></p>
										<p>Du kan oprette noter for sider under visningen af en side.</p>
										<p>Det gøres under fanebladet "Avanceret".</p>
									</text>
									</space>
								</empty>
							</list>
							</overflow>
						</page>
						<page>
							<overflow height="full" shadow-variant="white">
								<list source="warningsList" selectable="false">
								<empty>
									<space all="10">
									<text>
										<p><strong>Der er ingen advarsler</strong></p>
									</text>
									</space>
								</empty>
								</list>
							</overflow>
						</page>
						<page>
							<overflow height="full" shadow-variant="white">
								<list source="errorsList" selectable="false">
									<empty>
										<space all="10">
										<text>
											<p><strong>Der er ikke fundet fejl</strong></p>
										</text>
										</space>
									</empty>
								</list>
							</overflow>
						</page>
					</pages>
				</tile>
				<tile width="30" height="40" top="0" left="30" variant="light">
					<actions>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Statistics; da:Statistik}</title>
					<chart name="stats" source="chartSource" height="full"/>
				</tile>
				<tile width="30" height="60" top="40" left="30" variant="light" name="developmentTile">
					<actions>
						<icon icon="monochrome/round_arrow_left" key="previous"/>
						<icon icon="monochrome/round_arrow_right" key="next"/>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Development ; da: Udvikling }</title>
					<pages name="developmentPages" height="full">
						<page>
							<overflow height="full" shadow-variant="white">
								<list source="newsFeed" name="newsList" selectable="false" indent="10">
									<error text="{It was not possible to list news; da:Det lykkedes ikke at hente nyheder}"/>
								</list>
							</overflow>
						</page>
						<page>
							<overflow height="full" shadow-variant="white">
								<list source="developerFeed" selectable="false" indent="10">
									<error text="{It was not possible to list news; da:Det lykkedes ikke at hente nyheder}"/>
								</list>
							</overflow>
						</page>
						<page>
							<overflow height="full" shadow-variant="white">
								<list source="commitFeed" selectable="false" indent="10">
									<error text="{It was not possible to list news; da:Det lykkedes ikke at hente nyheder}"/>
								</list>
							</overflow>
						</page>
					</pages>
				</tile>

				<tile width="40" height="50" top="0" left="60" variant="light">
					<actions>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>Feedback</title>
					<overflow height="full" shadow-variant="white">
						<pages name="feedbackPages">
							<page>
								<formula padding="10" name="feedbackForm">
									<fields labels="above">
										<field label="{Write us with improvements and questions ; da: Skriv til os med ris, ros eller spørgsmål }">
											<text-input breaks="true" key="message"/>
										</field>
									</fields>
									<buttons>
										<button text="Send" submit="true" name="sendFeedback"/>
									</buttons>
								</formula>
							</page>
							<page>
								<text align="center" top="20">
									<h>Tak for det</h>
									<p>Du vil hurtigst muligt blive kontaktet med et svar.</p>
								</text>
								<buttons align="center" small="true">
									<button text="OK" click="feedbackPages.previous()"/>
								</buttons>
							</page>
						</pages>
					</overflow>
				</tile>
				<tile width="40" height="50" top="50" left="60" variant="light" name="helpTile">
					<actions>
						<icon icon="monochrome/expand" key="expand"/>
					</actions>
					<title>{Help ; da: Hjælp}</title>
					<div class="help">
						<columns flexible="true">
							<column>
								<icon icon="common/lifebuoy" size="64" text="{User manual ; da: Brugervejledning}" name="userManual"/>
							</column>
							<column>
								<icon icon="common/stamp" size="64" text="{Contact the developers ; da : Kontakt udviklerne}" name="contact"/>
							</column>
						</columns>
					</div>
				</tile>
			</tiles>
		</div>
	</div>

	<boundpanel name="settingsPanel" variant="light" width="200" padding="10" modal="true">
		<formula name="settingsFormula">
			<fields>
				<field label="{Language; da: Sprog}">
					<dropdown key="language" value="'.InternalSession::getLanguage().'">
						<option text="{Danish; da: Dansk}" value="da"/>
						<option text="{English; da: Engelsk}" value="en"/>
					</dropdown>
				</field>
			</fields>
		</formula>
		<buttons align="right" small="true">
      <button text="{Change password; da:Skift kodeord}" variant="light" name="changePassword"/>
			<button variant="light" text="OK" name="saveSettings" highlighted="true"/>
		</buttons>
	</boundpanel>

	<box title="{Change password; da:Skift kode}" closable="true" name="passwordBox" absolute="true" width="400" modal="true" padding="10">
		<formula name="passwordFormula">
			<fields>
				<field label="{Existing password; da: Nuværende kode}">
					<text-input key="old" secret="true"/>
				</field>
				<field label="{New password; da: Ny kode}">
					<text-input key="password" secret="true"/>
				</field>
				<field label="{New password; da: Ny kode igen}">
					<text-input key="password2" secret="true"/>
				</field>
			</fields>
			<buttons align="right">
				<button text="{Cancel; da: Annuller}" name="cancelPassword"/>
				<button text="{Change; da : Skift}" highlighted="true" submit="true" name="submitPassword"/>
			</buttons>
		</formula>
	</box>
</gui>';

UI::render($gui);
?>