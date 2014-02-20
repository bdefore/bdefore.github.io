<? 
// name of your database 
$database = "bdefore_lmc";
$blogid = "3";

// connect to database
$db = mysql_connect("internal-db.s12027.gridserver.com", "bdefore_admin", "weakpassword")or die ('I cannot connect to the database.'); 
mysql_select_db("$database",$db);



?>
