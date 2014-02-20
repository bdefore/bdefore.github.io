<?php
include("squible_options.php");

// Include string definitions
// For use in the page positioning stuff.
// DON'T MODIFY!
// ps: yes it's messy and ugly and bloat. but i cbf fixing it properly-like right now.

if ($midpanel_left_1 != "") {
	$include_midleft1 = "elements/" . $midpanel_left_1 . ".php";
} else {
	$include_midleft1 = "elements/nil.php";
}
if ($midpanel_left_2 != "") {
	$include_midleft2 = "elements/" . $midpanel_left_2 . ".php";
} else {
	$include_midleft2 = "elements/nil.php";
}
if ($midpanel_left_3 != "") {
	$include_midleft3 = "elements/" . $midpanel_left_3 . ".php";
} else {
	$include_midleft3 = "elements/nil.php";
}
if ($midpanel_left_4 != "") {
	$include_midleft4 = "elements/" . $midpanel_left_4 . ".php";
} else {
	$include_midleft4 = "elements/nil.php";
}

if ($midpanel_right_1 != "") {
	$include_midright1 = "elements/" . $midpanel_right_1 . ".php";
} else {
	$include_midright1 = "elements/nil.php";
}
if ($midpanel_right_2 != "") {
	$include_midright2 = "elements/" . $midpanel_right_2 . ".php";
} else {
	$include_midright2 = "elements/nil.php";
}
if ($midpanel_right_3 != "") {
	$include_midright3 = "elements/" . $midpanel_right_3 . ".php";
} else {
	$include_midright3 = "elements/nil.php";
}
if ($midpanel_right_4 != "") {
	$include_midright4 = "elements/" . $midpanel_right_4 . ".php";
} else {
	$include_midright4 = "elements/nil.php";
}

if ($bottompanel_left_1 != "") {
	$include_botleft_1 = "elements/" . $bottompanel_left_1 . ".php";
} else {
	$include_botleft_1 = "elements/nil.php";
}
if ($bottompanel_left_2 != "") {
	$include_botleft_2 = "elements/" . $bottompanel_left_2 . ".php";
} else {
	$include_botleft_2 = "elements/nil.php";
}
if ($bottompanel_left_3 != "") {
	$include_botleft_3 = "elements/" . $bottompanel_left_3 . ".php";
} else {
	$include_botleft_3 = "elements/nil.php";
}
if ($bottompanel_left_4 != "") {
	$include_botleft_4 = "elements/" . $bottompanel_left_4 . ".php";
} else {
	$include_botleft_4 = "elements/nil.php";
}

if ($bottompanel_right_1 != "") {
	$include_botright_1 = "elements/" . $bottompanel_right_1 . ".php";
} else {
	$include_botright_1 = "elements/nil.php";
}
if ($bottompanel_right_2 != "") {
	$include_botright_2 = "elements/" . $bottompanel_right_2 . ".php";
} else {
	$include_botright_2 = "elements/nil.php";
}
if ($bottompanel_right_3 != "") {
	$include_botright_3 = "elements/" . $bottompanel_right_3 . ".php";
} else {
	$include_botright_3 = "elements/nil.php";
}
if ($bottompanel_right_4 != "") {
	$include_botright_4 = "elements/" . $bottompanel_right_4 . ".php";
} else {
	$include_botright_4 = "elements/nil.php";
}

?>

