<?php
if (!isset($GLOBALS['basePath'])) {
   header('HTTP/1.1 403 Forbidden');
 exit;
}

$HUMANISE_EDITOR_CLASSES = array (
  'all' =>
  array (
    'NewsService' => 'Services/NewsService.php',
    'ConfigurationService' => 'Services/ConfigurationService.php',
    'PartService' => 'Services/PartService.php',
    'PublishingService' => 'Services/PublishingService.php',
    'FileSystemService' => 'Services/FileSystemService.php',
    'XslService' => 'Services/XslService.php',
    'OnlineObjectsService' => 'Services/OnlineObjectsService.php',
    'PageService' => 'Services/PageService.php',
    'ShellService' => 'Services/ShellService.php',
    'HierarchyService' => 'Services/HierarchyService.php',
    'AuthenticationService' => 'Services/AuthenticationService.php',
    'EventService' => 'Services/EventService.php',
    'ApiService' => 'Services/ApiService.php',
    'FileService' => 'Services/FileService.php',
    'SchemaService' => 'Services/SchemaService.php',
    'RenderingService' => 'Services/RenderingService.php',
    'TestService' => 'Services/TestService.php',
    'ObjectLinkService' => 'Services/ObjectLinkService.php',
    'HeartBeatService' => 'Services/HeartBeatService.php',
    'CacheService' => 'Services/CacheService.php',
    'ClassService' => 'Services/ClassService.php',
    'DesignService' => 'Services/DesignService.php',
    'IssueService' => 'Services/IssueService.php',
    'XmlService' => 'Services/XmlService.php',
    'RelationsService' => 'Services/RelationsService.php',
    'ToolService' => 'Services/ToolService.php',
    'RemoteDataService' => 'Services/RemoteDataService.php',
    'JsonService' => 'Services/JsonService.php',
    'ZipService' => 'Services/ZipService.php',
    'ClientService' => 'Services/ClientService.php',
    'ModelService' => 'Services/ModelService.php',
    'StatisticsService' => 'Services/StatisticsService.php',
    'SettingService' => 'Services/SettingService.php',
    'TemplateService' => 'Services/TemplateService.php',
    'LogService' => 'Services/LogService.php',
    'ReportService' => 'Services/ReportService.php',
    'ClipboardService' => 'Services/ClipboardService.php',
    'FontService' => 'Services/FontService.php',
    'MailService' => 'Services/MailService.php',
    'ImageService' => 'Services/ImageService.php',
    'OptimizationService' => 'Services/OptimizationService.php',
    'ObjectService' => 'Services/ObjectService.php',
    'FrameService' => 'Services/FrameService.php',
    'Graph' => 'Modules/Graphs/Graph.php',
    'GraphNode' => 'Modules/Graphs/GraphNode.php',
    'Graphviz' => 'Modules/Graphs/Graphviz.php',
    'ReviewService' => 'Modules/Review/ReviewService.php',
    'ReviewCombo' => 'Modules/Review/ReviewCombo.php',
    'LinkService' => 'Modules/Links/LinkService.php',
    'LinkView' => 'Modules/Links/LinkView.php',
    'LinkInfo' => 'Modules/Links/LinkInfo.php',
    'LinkQuery' => 'Modules/Links/LinkQuery.php',
    'NewsArticle' => 'Modules/News/NewsArticle.php',
    'ConsoleReporter' => 'Modules/Testing/ConsoleReporter.php',
    'Inspection' => 'Modules/Inspection/Inspection.php',
    'Inspector' => 'Modules/Inspection/Inspector.php',
    'InspectionService' => 'Modules/Inspection/InspectionService.php',
    'FetchStage' => 'Modules/Workflows/FetchStage.php',
    'PopulateStreamStage' => 'Modules/Workflows/PopulateStreamStage.php',
    'CleanMarkupStage' => 'Modules/Workflows/CleanMarkupStage.php',
    'WorkflowParser' => 'Modules/Workflows/WorkflowParser.php',
    'ReadFileStage' => 'Modules/Workflows/ReadFileStage.php',
    'MapStage' => 'Modules/Workflows/MapStage.php',
    'ExtractUrlsStage' => 'Modules/Workflows/ExtractUrlsStage.php',
    'StripTagsStage' => 'Modules/Workflows/StripTagsStage.php',
    'StreamStage' => 'Modules/Workflows/StreamStage.php',
    'ParseFeedStage' => 'Modules/Workflows/ParseFeedStage.php',
    'WorkflowState' => 'Modules/Workflows/WorkflowState.php',
    'DataStage' => 'Modules/Workflows/DataStage.php',
    'WorkflowService' => 'Modules/Workflows/WorkflowService.php',
    'ParseJSONStage' => 'Modules/Workflows/ParseJSONStage.php',
    'WorkflowDescription' => 'Modules/Workflows/WorkflowDescription.php',
    'WorkflowStage' => 'Modules/Workflows/WorkflowStage.php',
    'Gradient' => 'Modules/Images/Gradient.php',
    'ImageTransformationService' => 'Modules/Images/ImageTransformationService.php',
    'ImageTransformationRecipe' => 'Modules/Images/ImageTransformationRecipe.php',
    'WatermeterSummary' => 'Modules/Water/WatermeterSummary.php',
    'StatisticsQuery' => 'Modules/Statistics/StatisticsQuery.php',
    'SitemapTemplateController' => 'Templates/SitemapTemplateController.php',
    'WeblogTemplateController' => 'Templates/WeblogTemplateController.php',
    'DocumentTemplateEditor' => 'Templates/DocumentTemplateEditor.php',
    'WeblogTemplate' => 'Templates/WeblogTemplate.php',
    'CalendarTemplateController' => 'Templates/CalendarTemplateController.php',
    'AuthenticationTemplateController' => 'Templates/AuthenticationTemplateController.php',
    'HtmlTemplate' => 'Templates/HtmlTemplate.php',
    'HtmlTemplateController' => 'Templates/HtmlTemplateController.php',
    'DocumentTemplateInspector' => 'Templates/DocumentTemplateInspector.php',
    'AuthenticationTemplate' => 'Templates/AuthenticationTemplate.php',
    'SearchTemplateController' => 'Templates/SearchTemplateController.php',
    'TemplateController' => 'Templates/TemplateController.php',
    'SearchTemplate' => 'Templates/SearchTemplate.php',
    'DocumentTemplateController' => 'Templates/DocumentTemplateController.php',
    'CustomPart' => 'Parts/CustomPart.php',
    'HeaderPart' => 'Parts/HeaderPart.php',
    'ImagePartController' => 'Parts/ImagePartController.php',
    'TextPart' => 'Parts/TextPart.php',
    'MailinglistPartController' => 'Parts/MailinglistPartController.php',
    'HorizontalrulePartController' => 'Parts/HorizontalrulePartController.php',
    'RichtextPart' => 'Parts/RichtextPart.php',
    'FilePart' => 'Parts/FilePart.php',
    'PosterPart' => 'Parts/PosterPart.php',
    'PartController' => 'Parts/PartController.php',
    'HtmlPart' => 'Parts/HtmlPart.php',
    'NewsPartController' => 'Parts/NewsPartController.php',
    'ImagegalleryPart' => 'Parts/ImagegalleryPart.php',
    'PosterPartController' => 'Parts/PosterPartController.php',
    'TablePart' => 'Parts/TablePart.php',
    'WidgetPart' => 'Parts/WidgetPart.php',
    'ListPartController' => 'Parts/ListPartController.php',
    'MoviePart' => 'Parts/MoviePart.php',
    'HeaderPartController' => 'Parts/HeaderPartController.php',
    'RichtextPartController' => 'Parts/RichtextPartController.php',
    'PersonPart' => 'Parts/PersonPart.php',
    'ListingPartController' => 'Parts/ListingPartController.php',
    'WidgetPartController' => 'Parts/WidgetPartController.php',
    'HtmlPartController' => 'Parts/HtmlPartController.php',
    'MailinglistPart' => 'Parts/MailinglistPart.php',
    'HorizontalrulePart' => 'Parts/HorizontalrulePart.php',
    'FormulaPartController' => 'Parts/FormulaPartController.php',
    'TextPartController' => 'Parts/TextPartController.php',
    'AuthenticationPart' => 'Parts/AuthenticationPart.php',
    'ListPart' => 'Parts/ListPart.php',
    'FilePartController' => 'Parts/FilePartController.php',
    'MapPartController' => 'Parts/MapPartController.php',
    'ImagegalleryPartController' => 'Parts/ImagegalleryPartController.php',
    'TablePartController' => 'Parts/TablePartController.php',
    'PersonPartController' => 'Parts/PersonPartController.php',
    'ListingPart' => 'Parts/ListingPart.php',
    'PartContext' => 'Parts/PartContext.php',
    'AuthenticationPartController' => 'Parts/AuthenticationPartController.php',
    'NewsPart' => 'Parts/NewsPart.php',
    'Part' => 'Parts/Part.php',
    'MapPart' => 'Parts/MapPart.php',
    'ImagePart' => 'Parts/ImagePart.php',
    'FormulaPart' => 'Parts/FormulaPart.php',
    'MenuPartController' => 'Parts/MenuPartController.php',
    'CustomPartController' => 'Parts/CustomPartController.php',
    'MenuPart' => 'Parts/MenuPart.php',
    'MoviePartController' => 'Parts/MoviePartController.php',
    'HierarchyItem' => 'Model/HierarchyItem.php',
    'DocumentRow' => 'Model/DocumentRow.php',
    'Link' => 'Model/Link.php',
    'DocumentSection' => 'Model/DocumentSection.php',
    'DocumentColumn' => 'Model/DocumentColumn.php',
    'ObjectLink' => 'Model/ObjectLink.php',
    'ModelObject' => 'Model/ModelObject.php',
    'SpecialPage' => 'Model/SpecialPage.php',
    'Hierarchy' => 'Model/Hierarchy.php',
    'Frame' => 'Model/Frame.php',
    'Page' => 'Model/Page.php',
    'PartLink' => 'Model/PartLink.php',
    'Template' => 'Model/Template.php',
    'Entity' => 'Model/Entity.php',
    'Parameter' => 'Model/Parameter.php',
    'Dates' => 'Utilities/Dates.php',
    'StopWatch' => 'Utilities/StopWatch.php',
    'EventUtils' => 'Utilities/EventUtils.php',
    'DOMUtils' => 'Utilities/DOMUtils.php',
    'MarkupUtils' => 'Utilities/MarkupUtils.php',
    'SelectBuilder' => 'Utilities/SelectBuilder.php',
    'Strings' => 'Utilities/Strings.php',
    'ValidateUtils' => 'Utilities/ValidateUtils.php',
    'Console' => 'Utilities/Console.php',
    'StringBuilder' => 'Utilities/StringBuilder.php',
    'TextDecorator' => 'Utilities/TextDecorator.php',
    'DatabaseUtil' => 'Utilities/DatabaseUtil.php',
    'GuiUtils' => 'Utilities/GuiUtils.php',
    'News' => 'Objects/News.php',
    'Design' => 'Objects/Design.php',
    'Personrole' => 'Objects/Personrole.php',
    'Milestone' => 'Objects/Milestone.php',
    'Event' => 'Objects/Event.php',
    'Cachedurl' => 'Objects/Cachedurl.php',
    'Calendarsource' => 'Objects/Calendarsource.php',
    'Product' => 'Objects/Product.php',
    'Newsgroup' => 'Objects/Newsgroup.php',
    'Testphrase' => 'Objects/Testphrase.php',
    'Review' => 'Objects/Review.php',
    'Address' => 'Objects/Address.php',
    'Issuestatus' => 'Objects/Issuestatus.php',
    'File' => 'Objects/File.php',
    'Watermeter' => 'Objects/Watermeter.php',
    'Issue' => 'Objects/Issue.php',
    'Pageblueprint' => 'Objects/Pageblueprint.php',
    'User' => 'Objects/User.php',
    'Filegroup' => 'Objects/Filegroup.php',
    'Person' => 'Objects/Person.php',
    'Path' => 'Objects/Path.php',
    'Listener' => 'Objects/Listener.php',
    'Securityzone' => 'Objects/Securityzone.php',
    'Producttype' => 'Objects/Producttype.php',
    'Weblogentry' => 'Objects/Weblogentry.php',
    'Task' => 'Objects/Task.php',
    'Stream' => 'Objects/Stream.php',
    'Project' => 'Objects/Project.php',
    'Remotepublisher' => 'Objects/Remotepublisher.php',
    'Productgroup' => 'Objects/Productgroup.php',
    'Waterusage' => 'Objects/Waterusage.php',
    'Productoffer' => 'Objects/Productoffer.php',
    'Streamitem' => 'Objects/Streamitem.php',
    'View' => 'Objects/View.php',
    'Calendar' => 'Objects/Calendar.php',
    'Persongroup' => 'Objects/Persongroup.php',
    'Newssource' => 'Objects/Newssource.php',
    'Problem' => 'Objects/Problem.php',
    'Imagegroup' => 'Objects/Imagegroup.php',
    'Source' => 'Objects/Source.php',
    'Mailinglist' => 'Objects/Mailinglist.php',
    'Phonenumber' => 'Objects/Phonenumber.php',
    'Emailaddress' => 'Objects/Emailaddress.php',
    'Webloggroup' => 'Objects/Webloggroup.php',
    'Feedback' => 'Objects/Feedback.php',
    'Workflow' => 'Objects/Workflow.php',
    'Image' => 'Objects/Image.php',
    'Newssourceitem' => 'Objects/Newssourceitem.php',
    'AbstractObjectTest' => 'Tests/AbstractObjectTest.php',
    'WebResponse' => 'Network/WebResponse.php',
    'Feed' => 'Network/Feed.php',
    'UserAgentAnalyzer' => 'Network/UserAgentAnalyzer.php',
    'RemoteFile' => 'Network/RemoteFile.php',
    'FeedSerializer' => 'Network/FeedSerializer.php',
    'HttpClient' => 'Network/HttpClient.php',
    'WebRequest' => 'Network/WebRequest.php',
    'RemoteData' => 'Network/RemoteData.php',
    'FileUpload' => 'Network/FileUpload.php',
    'FeedItem' => 'Network/FeedItem.php',
    'ImportResult' => 'Network/ImportResult.php',
    'FeedParser' => 'Network/FeedParser.php',
    'GoogleAnalytics' => 'Integration/GoogleAnalytics.php',
    'VEvent' => 'Formats/VEvent.php',
    'CSVWriter' => 'Formats/CSVWriter.php',
    'VRecurrenceRule' => 'Formats/VRecurrenceRule.php',
    'DBUCalendarParser' => 'Formats/DBUCalendarParser.php',
    'VCalParser' => 'Formats/VCalParser.php',
    'VCalendar' => 'Formats/VCalendar.php',
    'HtmlDocument' => 'Formats/HtmlDocument.php',
    'ZipFile' => 'Formats/ZipFile.php',
    'DBUCalendar' => 'Formats/DBUCalendar.php',
    'ZipFileItem' => 'Formats/ZipFileItem.php',
    'DBUCalendarEvent' => 'Formats/DBUCalendarEvent.php',
    'HtmlTableParser' => 'Formats/HtmlTableParser.php',
    'VCalSerializer' => 'Formats/VCalSerializer.php',
    'InternalSession' => 'Core/InternalSession.php',
    'Response' => 'Core/Response.php',
    'ExternalSession' => 'Core/ExternalSession.php',
    'Database' => 'Core/Database.php',
    'SearchResult' => 'Core/SearchResult.php',
    'ClassPropertyInfo' => 'Core/ClassPropertyInfo.php',
    'SystemInfo' => 'Core/SystemInfo.php',
    'TemporaryFolder' => 'Core/TemporaryFolder.php',
    'PageQuery' => 'Core/PageQuery.php',
    'Request' => 'Core/Request.php',
    'ClassInfo' => 'Core/ClassInfo.php',
    'ModelEventListener' => 'Core/ModelEventListener.php',
    'Log' => 'Core/Log.php',
    'ClassRelationInfo' => 'Core/ClassRelationInfo.php',
    'Loadable' => 'Core/Loadable.php',
    'Query' => 'Core/Query.php',
    'ModelAuditor' => 'Core/ModelAuditor.php',
    'ListWriter' => 'Interface/ListWriter.php',
    'DiagramEdge' => 'Interface/DiagramEdge.php',
    'DiagramNode' => 'Interface/DiagramNode.php',
    'UI' => 'Interface/UI.php',
    'DiagramData' => 'Interface/DiagramData.php',
    'ItemsWriter' => 'Interface/ItemsWriter.php',
  ),
  'interfaces' =>
  array (
    'Inspector' =>
    array (
      0 => 'DocumentTemplateInspector',
    ),
    'Loadable' =>
    array (
      0 => 'HierarchyItem',
      1 => 'DocumentRow',
      2 => 'Link',
      3 => 'DocumentSection',
      4 => 'DocumentColumn',
      5 => 'ModelObject',
      6 => 'Hierarchy',
      7 => 'Frame',
      8 => 'Parameter',
      9 => 'News',
      10 => 'Design',
      11 => 'Personrole',
      12 => 'Milestone',
      13 => 'Event',
      14 => 'Cachedurl',
      15 => 'Calendarsource',
      16 => 'Product',
      17 => 'Newsgroup',
      18 => 'Testphrase',
      19 => 'Review',
      20 => 'Address',
      21 => 'Issuestatus',
      22 => 'File',
      23 => 'Watermeter',
      24 => 'Issue',
      25 => 'Pageblueprint',
      26 => 'User',
      27 => 'Filegroup',
      28 => 'Person',
      29 => 'Path',
      30 => 'Listener',
      31 => 'Securityzone',
      32 => 'Producttype',
      33 => 'Weblogentry',
      34 => 'Task',
      35 => 'Stream',
      36 => 'Project',
      37 => 'Remotepublisher',
      38 => 'Productgroup',
      39 => 'Waterusage',
      40 => 'Productoffer',
      41 => 'Streamitem',
      42 => 'View',
      43 => 'Calendar',
      44 => 'Persongroup',
      45 => 'Newssource',
      46 => 'Problem',
      47 => 'Imagegroup',
      48 => 'Source',
      49 => 'Mailinglist',
      50 => 'Phonenumber',
      51 => 'Emailaddress',
      52 => 'Webloggroup',
      53 => 'Feedback',
      54 => 'Workflow',
      55 => 'Image',
      56 => 'Newssourceitem',
    ),
    'ModelEventListener' =>
    array (
      0 => 'ModelAuditor',
    ),
  ),
  'parents' =>
  array (
    'SimpleReporter' =>
    array (
      0 => 'ConsoleReporter',
    ),
    'SimpleScorer' =>
    array (
      0 => 'ConsoleReporter',
    ),
    'WorkflowStage' =>
    array (
      0 => 'FetchStage',
      1 => 'PopulateStreamStage',
      2 => 'CleanMarkupStage',
      3 => 'ReadFileStage',
      4 => 'MapStage',
      5 => 'ExtractUrlsStage',
      6 => 'StripTagsStage',
      7 => 'StreamStage',
      8 => 'ParseFeedStage',
      9 => 'DataStage',
      10 => 'ParseJSONStage',
    ),
    'TemplateController' =>
    array (
      0 => 'SitemapTemplateController',
      1 => 'WeblogTemplateController',
      2 => 'CalendarTemplateController',
      3 => 'AuthenticationTemplateController',
      4 => 'HtmlTemplateController',
      5 => 'SearchTemplateController',
      6 => 'DocumentTemplateController',
    ),
    'Part' =>
    array (
      0 => 'CustomPart',
      1 => 'HeaderPart',
      2 => 'TextPart',
      3 => 'RichtextPart',
      4 => 'FilePart',
      5 => 'PosterPart',
      6 => 'HtmlPart',
      7 => 'ImagegalleryPart',
      8 => 'TablePart',
      9 => 'WidgetPart',
      10 => 'MoviePart',
      11 => 'PersonPart',
      12 => 'MailinglistPart',
      13 => 'HorizontalrulePart',
      14 => 'AuthenticationPart',
      15 => 'ListPart',
      16 => 'ListingPart',
      17 => 'NewsPart',
      18 => 'MapPart',
      19 => 'ImagePart',
      20 => 'FormulaPart',
      21 => 'MenuPart',
    ),
    'Entity' =>
    array (
      0 => 'CustomPart',
      1 => 'HeaderPart',
      2 => 'TextPart',
      3 => 'RichtextPart',
      4 => 'FilePart',
      5 => 'PosterPart',
      6 => 'HtmlPart',
      7 => 'ImagegalleryPart',
      8 => 'TablePart',
      9 => 'WidgetPart',
      10 => 'MoviePart',
      11 => 'PersonPart',
      12 => 'MailinglistPart',
      13 => 'HorizontalrulePart',
      14 => 'AuthenticationPart',
      15 => 'ListPart',
      16 => 'ListingPart',
      17 => 'NewsPart',
      18 => 'Part',
      19 => 'MapPart',
      20 => 'ImagePart',
      21 => 'FormulaPart',
      22 => 'MenuPart',
      23 => 'HierarchyItem',
      24 => 'DocumentRow',
      25 => 'Link',
      26 => 'DocumentSection',
      27 => 'DocumentColumn',
      28 => 'ObjectLink',
      29 => 'ModelObject',
      30 => 'SpecialPage',
      31 => 'Hierarchy',
      32 => 'Frame',
      33 => 'Page',
      34 => 'PartLink',
      35 => 'Template',
      36 => 'Parameter',
      37 => 'News',
      38 => 'Design',
      39 => 'Personrole',
      40 => 'Milestone',
      41 => 'Event',
      42 => 'Cachedurl',
      43 => 'Calendarsource',
      44 => 'Product',
      45 => 'Newsgroup',
      46 => 'Testphrase',
      47 => 'Review',
      48 => 'Address',
      49 => 'Issuestatus',
      50 => 'File',
      51 => 'Watermeter',
      52 => 'Issue',
      53 => 'Pageblueprint',
      54 => 'User',
      55 => 'Filegroup',
      56 => 'Person',
      57 => 'Path',
      58 => 'Listener',
      59 => 'Securityzone',
      60 => 'Producttype',
      61 => 'Weblogentry',
      62 => 'Task',
      63 => 'Stream',
      64 => 'Project',
      65 => 'Remotepublisher',
      66 => 'Productgroup',
      67 => 'Waterusage',
      68 => 'Productoffer',
      69 => 'Streamitem',
      70 => 'View',
      71 => 'Calendar',
      72 => 'Persongroup',
      73 => 'Newssource',
      74 => 'Problem',
      75 => 'Imagegroup',
      76 => 'Source',
      77 => 'Mailinglist',
      78 => 'Phonenumber',
      79 => 'Emailaddress',
      80 => 'Webloggroup',
      81 => 'Feedback',
      82 => 'Workflow',
      83 => 'Image',
      84 => 'Newssourceitem',
    ),
    'PartController' =>
    array (
      0 => 'ImagePartController',
      1 => 'MailinglistPartController',
      2 => 'HorizontalrulePartController',
      3 => 'NewsPartController',
      4 => 'PosterPartController',
      5 => 'ListPartController',
      6 => 'HeaderPartController',
      7 => 'RichtextPartController',
      8 => 'ListingPartController',
      9 => 'WidgetPartController',
      10 => 'HtmlPartController',
      11 => 'FormulaPartController',
      12 => 'TextPartController',
      13 => 'FilePartController',
      14 => 'MapPartController',
      15 => 'ImagegalleryPartController',
      16 => 'TablePartController',
      17 => 'PersonPartController',
      18 => 'AuthenticationPartController',
      19 => 'MenuPartController',
      20 => 'CustomPartController',
      21 => 'MoviePartController',
    ),
    'ModelObject' =>
    array (
      0 => 'News',
      1 => 'Design',
      2 => 'Personrole',
      3 => 'Milestone',
      4 => 'Event',
      5 => 'Cachedurl',
      6 => 'Calendarsource',
      7 => 'Product',
      8 => 'Newsgroup',
      9 => 'Testphrase',
      10 => 'Review',
      11 => 'Address',
      12 => 'Issuestatus',
      13 => 'File',
      14 => 'Watermeter',
      15 => 'Issue',
      16 => 'Pageblueprint',
      17 => 'User',
      18 => 'Filegroup',
      19 => 'Person',
      20 => 'Path',
      21 => 'Listener',
      22 => 'Securityzone',
      23 => 'Producttype',
      24 => 'Weblogentry',
      25 => 'Task',
      26 => 'Stream',
      27 => 'Project',
      28 => 'Remotepublisher',
      29 => 'Productgroup',
      30 => 'Waterusage',
      31 => 'Productoffer',
      32 => 'Streamitem',
      33 => 'View',
      34 => 'Calendar',
      35 => 'Persongroup',
      36 => 'Newssource',
      37 => 'Problem',
      38 => 'Imagegroup',
      39 => 'Source',
      40 => 'Mailinglist',
      41 => 'Phonenumber',
      42 => 'Emailaddress',
      43 => 'Webloggroup',
      44 => 'Feedback',
      45 => 'Workflow',
      46 => 'Image',
      47 => 'Newssourceitem',
    ),
    'UnitTestCase' =>
    array (
      0 => 'AbstractObjectTest',
    ),
    'SimpleTestCase' =>
    array (
      0 => 'AbstractObjectTest',
    ),
  ),
)
?>