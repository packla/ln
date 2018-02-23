function addClass(o, c) {
    var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
    if (re.test(o.className)) return;
    o.className = (o.className + " " + c).replace(/\s+/g, " ").replace(/(^ | $)/g, "")
}
function removeClass(o, c) {
    var re = new RegExp("(^|\\s)" + c + "(\\s|$)", "g");
    o.className = o.className.replace(re, "$1").replace(/\s+/g, " ").replace(/(^ | $)/g, "")
}
function getElementsByAttr(block, attr) {
    var allElements = block.getElementsByTagName('*');
    var elements = [];
    for (var i = 0, n = allElements.length; i < n; i++) {
        if (allElements[i].getAttribute(attr) !== null) {
            elements.push(allElements[i]);
        }
    }
    return elements;
}
function validate(form) {
    var allElements = getElementsByAttr(form, 'required');
    var res = true;
    for (var i = 0, n = allElements.length; i < n; i++) {
        if (!allElements[i].checkValidity()) {
            addClass(allElements[i], 'invalid');
            res = false;
        } else {
            removeClass(allElements[i], 'invalid');
        }
    }
    return res;
}
(function () {
    var installPage = document.getElementById('installationPage');
    var installForm = installPage.getElementsByTagName('form')[0];
    var inputs = getElementsByAttr(installForm, 'required');
    for (i = 0; i < inputs.length; i++) {
        inputs[i].onchange = function (e) {
            removeClass(e.target, 'invalid');
        }
    }

    var mainBlock = installForm.getElementsByClassName('main')[0];
    mainBlock.getElementsByClassName('next')[0].onclick = function () {
        next(mainBlock, 'template');
    };
    var templateBlock = installForm.getElementsByClassName('template')[0];
    templateBlock.getElementsByClassName('next')[0].onclick = function () {
        next(templateBlock, 'domains');
    };

    function next(current, nextClass) {
        if (!validate(current)) {
            return false;
        }
        addClass(current, 'hidden');
        removeClass(installForm.getElementsByClassName(nextClass)[0], 'hidden');
        var menu = installPage.getElementsByClassName('menu')[0].getElementsByTagName('small');
        for (i = 0; i < menu.length; i++) {
            removeClass(menu[i], 'active');
            if (nextClass === menu[i].getAttribute('data-target')) {
                addClass(menu[i], 'active');
            }
        }
    }
})();