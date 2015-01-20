// JavaScript Document

function getObject(objID)
 {if (document.getElementById && document.getElementById(objID)) 
 {return document.getElementById(objID);} 
 else 
 {if (document.all && document.all(objID)) 
 {return document.all(objectId);} 
 else 
 {if (document.layers && document.layers[objID]) 
 {return document.layers[objID];} 
 else
 {return false;}
 }
 }
 }
function onelist(n)
{
for (var i = 1; i <= 4; i++)
{
if (i == n) 
{
getObject("onelist" + i).className = "selected";
getObject("onelistDIV" + i).style.display = "";
} 
else
{
getObject("onelist" + i).className = "";
getObject("onelistDIV" + i).style.display = "none";
}
}
}
function twolist(n)
{
for (var i = 1; i <= 4; i++)
{
if (i == n) 
{
getObject("twolist" + i).className = "selected";
getObject("twolistDIV" + i).style.display = "";
} 
else
{
getObject("twolist" + i).className = "";
getObject("twolistDIV" + i).style.display = "none";
}
}
}

function foureqh(n)
{
for (var i = 1; i <= 4; i++)
{
if (i == n) 
{
getObject("foureqh" + i).className = "selected";
getObject("foureqhDIV" + i).style.display = "";
} 
else
{
getObject("foureqh" + i).className = "";
getObject("foureqhDIV" + i).style.display = "none";
}
}
}

function kaoshi(n)
{
for (var i = 1; i <= 2; i++)
{
if (i == n) 
{
getObject("kaoshi" + i).className = "selected";
getObject("kaoshiDIV" + i).style.display = "";
} 
else
{
getObject("kaoshi" + i).className = "";
getObject("kaoshiDIV" + i).style.display = "none";
}
}
}
function kaoshione(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshione" + i).className = "selected";
getObject("kaoshioneDIV" + i).style.display = "";
} 
else
{
getObject("kaoshione" + i).className = "";
getObject("kaoshioneDIV" + i).style.display = "none";
}
}
}
function kaoshitwo(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshitwo" + i).className = "selected";
getObject("kaoshitwoDIV" + i).style.display = "";
} 
else
{
getObject("kaoshitwo" + i).className = "";
getObject("kaoshitwoDIV" + i).style.display = "none";
}
}
}
function kaoshithree(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshithree" + i).className = "selected";
getObject("kaoshithreeDIV" + i).style.display = "";
} 
else
{
getObject("kaoshithree" + i).className = "";
getObject("kaoshithreeDIV" + i).style.display = "none";
}
}
}

function kaoshifive(n)
{
for (var i = 1; i <= 5; i++)
{
if (i == n) 
{
getObject("kaoshifive" + i).className = "selected";
getObject("kaoshifiveDIV" + i).style.display = "";
} 
else
{
getObject("kaoshifive" + i).className = "";
getObject("kaoshifiveDIV" + i).style.display = "none";
}
}
}



function kaoshinews(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinews" + i).className = "selected";
getObject("kaoshinewsDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinews" + i).className = "";
getObject("kaoshinewsDIV" + i).style.display = "none";
}
}
}

function kaoshinewsone(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewsone" + i).className = "selected";
getObject("kaoshinewsoneDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewsone" + i).className = "";
getObject("kaoshinewsoneDIV" + i).style.display = "none";
}
}
}

function kaoshinewstwo(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewstwo" + i).className = "selected";
getObject("kaoshinewstwoDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewstwo" + i).className = "";
getObject("kaoshinewstwoDIV" + i).style.display = "none";
}
}
}

function kaoshinewsthree(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewsthree" + i).className = "selected";
getObject("kaoshinewsthreeDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewsthree" + i).className = "";
getObject("kaoshinewsthreeDIV" + i).style.display = "none";
}
}
}


function countriest(n)
{
for (var i = 1; i <= 2; i++)
{
if (i == n) 
{
getObject("countriest" + i).className = "selected";
getObject("countriestDIV" + i).style.display = "";
} 
else
{
getObject("countriest" + i).className = "";
getObject("countriestDIV" + i).style.display = "none";
}
}
}

function kaoshinewsfour(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewsfour" + i).className = "selected";
getObject("kaoshinewsfourDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewsfour" + i).className = "";
getObject("kaoshinewsfourDIV" + i).style.display = "none";
}
}
}

function kaoshinewsfive(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewsfive" + i).className = "selected";
getObject("kaoshinewsfiveDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewsfive" + i).className = "";
getObject("kaoshinewsfiveDIV" + i).style.display = "none";
}
}
}

function kaoshinewssex(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewssex" + i).className = "selected";
getObject("kaoshinewssexDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewssex" + i).className = "";
getObject("kaoshinewssexDIV" + i).style.display = "none";
}
}
}

function kaoshinewsseven(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("kaoshinewsseven" + i).className = "selected";
getObject("kaoshinewssevenDIV" + i).style.display = "";
} 
else
{
getObject("kaoshinewsseven" + i).className = "";
getObject("kaoshinewssevenDIV" + i).style.display = "none";
}
}
}
function othernews1(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("othernews" + i).className = "selected";
getObject("othernewsDIV" + i).style.display = "";
} 
else
{
getObject("othernews" + i).className = "";
getObject("othernewsDIV" + i).style.display = "none";
}
}
}
function othernews2(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("othernewsa" + i).className = "selected";
getObject("othernewsaDIV" + i).style.display = "";
} 
else
{
getObject("othernewsa" + i).className = "";
getObject("othernewsaDIV" + i).style.display = "none";
}
}
}
function othernews3(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("othernewsb" + i).className = "selected";
getObject("othernewsbDIV" + i).style.display = "";
} 
else
{
getObject("othernewsb" + i).className = "";
getObject("othernewsbDIV" + i).style.display = "none";
}
}
}

function contentxg(n)
{
for (var i = 1; i <= 3; i++)
{
if (i == n) 
{
getObject("contentxg" + i).className = "selected";
getObject("contentxgDIV" + i).style.display = "";
} 
else
{
getObject("contentxg" + i).className = "";
getObject("contentxgDIV" + i).style.display = "none";
}
}
}