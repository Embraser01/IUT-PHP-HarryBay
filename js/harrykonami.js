function replaceAllWords(newWord) {
    for (var i = 0; i < document.childNodes.length; i++) {
        checkNode(document.childNodes[i]);
    }
    function checkNode(node) {
        var nodeName = node.nodeName.toLowerCase();
        if(nodeName === 'script' || nodeName === 'style') {return;}
        if (node.nodeType === 3) {
            var text = node.nodeValue;
            var newText = text.replace(/\b\w+/g, newWord);
            node.nodeValue = newText;
        }
        if (node.childNodes.length > 0) {
            for (var j = 0; j < node.childNodes.length; j++) {
                checkNode(node.childNodes[j]);
            }
        }
    }
}

var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
    n = 0;
var audio = new Audio('../leviosa.mp3');
$(document).keydown(function (e) {
    if (e.keyCode === k[n++]) {
        if (n === k.length) {
            audio.play();
            replaceAllWords("Leviosaaa");
            n = 0;
            return false;
        }
    }
    else {
        n = 0;
    }
});