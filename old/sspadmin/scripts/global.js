// SSP Admin
// Global JS Functions

// Declarations
var curSize;

// Load Pic into iFrame
function editPic(i,a)
{
	var e = document.getElementById('pic-holder');
	e.src = 'edit-pic.php?pid='+i+'&aid='+a;
	clearClasses();
	document.getElementById(i).className += ' selected';
}

// Make sure of Delete
function albumDeleteConfirm(i)
{
	var r = confirm('Are you sure you want to delete this album?');
	
	if (r == true) {
		location.href = 'delete-album-exe.php?aid='+i;
	}
}

function dynAlbumDeleteConfirm(i)
{
	var r = confirm('Are you sure you want to delete this dynamic album?');
	
	if (r == true) {
		location.href = 'delete-dynamic-album-exe.php?did='+i;
	}
}

// Resize Thumbs
function sizeThumbs(sw)
{
	if (sw)
		var elem = document.getElementById('pic-spread');
	else
		var elem = document.getElementById('pic-spread-full');
	var sel = document.getElementById('thumb-size');
	var a = elem.getElementsByTagName('IMG');
	var b = elem.getElementsByTagName('DIV');
	
	var s = sel.value;
	curSize = s;
	
	for (var i=0; i < a.length; i++)
	{
		a[i].height = s;
	}
	for (var i=0; i < b.length; i++)
	{
		b[i].style.height = (s-10)+'px';
		if (sw) { b[i].getElementsByTagName('A')[0].style.height = (s-16)+'px' };
	}
	if (sw==true)
	{
		elem.style.height = ((s*2)+30)+'px';
	}
}

function clearClasses ()
{
	var elem = document.getElementById('pic-spread');
	var a = elem.getElementsByTagName('IMG');
	var b = elem.getElementsByTagName('DIV');
	
	for (i=0; i < a.length; i++)
	{
		a[i].className = '';
	}
	
	for (i=0; i < b.length; i++)
	{
		myString = new String(b[i].className)
		rExp = /selected/gi;
		newString = new String ("")
		results = myString.replace(rExp, newString)
		b[i].className = results;
	}
}

function saveOrderDB(ai) {
	var elem = document.getElementById('pic-spread');
	var a = document.getElementsByTagName('LI');
	var qStr = '';
	for (var i=0; i < a.length; i++)
	{
		qStr += (i+1)+','+a[i].id+'|';
	}
	location.href = 'album-order-exe.php?q='+qStr+'&aid='+ai;
}

function saveAlbOrder() {
	var elem = document.getElementById('albums');
	var a = elem.getElementsByTagName('LI');
	var qStr = '';
	for (var i=0; i < a.length; i++)
	{
		qStr += (i+1)+','+a[i].id+'|';
	}
	location.href = 'album-display-order-exe.php?q='+qStr;
}

function saveDynAlbOrder(did) {
	var elem = document.getElementById('albums');
	var a = elem.getElementsByTagName('LI');
	var qStr = '';
	for (var i=0; i < a.length; i++)
	{
		qStr += (i+1)+','+a[i].id+'|';
	}
	location.href = 'dynamic-display-order-exe.php?q='+qStr+'&did='+did;
}