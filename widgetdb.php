<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!class_exists("widget")) {
	class widget {
		function __construct($Id, $Type, $Block, $Title, $Function, $HeaderFunction, $Section, $Position) {
			$this->Id = $Id;
			$this->Type = $Type;
			$this->Block = $Block;
			$this->Title = $Title;
			$this->Function = $Function;
			$this->HeaderFunction = $HeaderFunction;
			$this->Section = $Section;
			$this->Position = $Position;
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
			// Open Database
			try {
				$db = new PDO('sqlite:settings.db');
				// Create the database
				$db->exec("CREATE TABLE Widgets (Id TEXT PRIMARY KEY, Type TEXT, Parts TEXT, Block TEXT, Title TEXT, Function TEXT, Call TEXT, Interval INTEGER, HeaderFunction TEXT, Stylesheet TEXT, Script TEXT, Section INTEGER, Position INTEGER)");
				// Add widget to database
				$db->exec("INSERT INTO Widgets (Id, Type, Block, Title, HeaderFunction, Function, Call, Interval, Section, Position) VALUES ('".$this->Id."', '".$this->Type."', '".$this->Block."', '".$this->Title."', '".$this->HeaderFunction."', '".$this->Function."', '".$this->Call."', '".$this->Interval."', '".$this->Section."', '".$this->Position."');");
			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}
			// Close the database connection
			$db = NULL;
		}		
		public function getWidget() {
			try {
				// Create the database
				$db = new PDO('sqlite:settings.db');

				/*** fetch into an PDOStatement object ***/			
				$request = $db->prepare("SELECT * FROM Widgets WHERE Id='".$this->Id."'");
				$request->execute();

				/* Exercise PDOStatement::fetch styles */
				$widget = $request->fetch(PDO::FETCH_ASSOC);
    			/*** close the database connection ***/
    			$db = null;

			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}
			return $widget;
		}
		public function updateWidget($column, $value) {
			try {
				// Create the database
				$db = new PDO('sqlite:settings.db');

				/*** fetch into an PDOStatement object ***/			
				$request = $db->prepare("UPDATE Widgets SET $column='$value' WHERE Id='".$this->Id."'");
				$request->execute();//(array(Block,"'RSS'", $this->Id));


			
    			$db = null;

			} catch(PDOException $e) {
				print 'Exception : '.$e->getMessage();	
			}

		}								
	}
}

$wRSS = new widget("wRSS", "inline", "rsswrapper", "RSS Feeds", "widgetRSS();", "widgetRSSHeader();", 3, 4);
$wRSS->addWidget();
$wRSS->updateWidget('Function', 'RSS Feeds');
$widget = $wRSS->getWidget();
echo print_r($widget,1);

?>
		</div><!-- #main -->
    	<script type="text/javascript" src="js/jquery.js"></script>
    	<script type="text/javascript" src="js/widget.js"></script>
		</body>
</html>
