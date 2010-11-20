<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!class_exists("widget")) {
	class widget {
		function __construct($widget_init) {
			$this->Id = $widget_init['Id'];
			$this->Type = $widget_init['Type'];
			$this->Block = $widget_init['Block'];
			$this->Title = $widget_init['Title'];
			$this->Function = $widget_init['Function'];
			$this->HeaderFunction = $widget_init['HeaderFunction'];
			$this->Section = $widget_init['Section'];
			$this->Position = $widget_init['Position'];
		}
		public $Id;
		public $Type;
		public $Block;
		public $Title;
		public $HeaderFunction;
		public $Function;
		public $Call;
		public $Interval;
		public $Stylesheet;
		public $Script;
		public $Section;
		public $Position;
		public $Class;
		public $MobileTitle;
		public $MobileHeader;
		public $MobileFunction;
		public $MobileSection;
		public $MovilePosition;
		public $MobileClass;
		public function addWidget() {

				// Open the database
			try {   $db = new PDO('sqlite:settings.db');

				// Create the database
				$db->exec("CREATE TABLE Widgets (Id TEXT PRIMARY KEY, Type TEXT, Parts TEXT, Block TEXT, Title TEXT, Function TEXT, Call TEXT, Interval INTEGER, HeaderFunction TEXT, Stylesheet TEXT, Script TEXT, Section INTEGER, Position INTEGER)");
				// Add widget to database
				$db->exec("INSERT INTO Widgets (Id, Type, Block, Title, HeaderFunction, Function, Call, Interval, Section, Position) VALUES ('$this->Id', '$this->Type', '$this->Block', '$this->Title', '$this->HeaderFunction', '$this->Function', '$this->Call', '$this->Interval', '$this->Section', '$this->Position');");

			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}

			// Close the database connection
			$db = NULL;
		}		
		public function getWidget() {

				// Open the database
			try {	$db = new PDO('sqlite:settings.db');

				//Fetch into an PDOStatement object			
				$request = $db->prepare("SELECT * FROM Widgets WHERE Id='".$this->Id."'");
				$request->execute();

				// Into array
				$widget = $request->fetch(PDO::FETCH_ASSOC);

    			// Close the database connection 
    			$db = null;

			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}
			return $widget;
		}
		public function updateWidget($column, $value) {

				// Open the database
			try {	$db = new PDO('sqlite:settings.db');

				// Replace value in specified column for this widget
				$request = $db->prepare("UPDATE Widgets SET $column='$value' WHERE Id='".$this->Id."'");
				$request->execute();

    			// Close the database connection 
    			$db = null;

			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}
		}								
	}
}
?>
