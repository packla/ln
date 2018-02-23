(function () {
    var grid = document.getElementById('domainsGrid');
    var inputs = grid.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].onchange = function (e) {
            var value = e.target.value;
            var attr = e.target.getAttribute('data-attribute');
            var xhr = new XMLHttpRequest();
            var params = 'attribute=' + encodeURIComponent(attr) +
                '&value=' + encodeURIComponent(value);
            xhr.open("GET", e.target.getAttribute('data-url') + '&' + params, true);
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function () {
                if (this.readyState != 4) return;
                var data = JSON.parse(this.responseText);
                if (data.result) {
                    e.target.style.border = '1px solid green';
                    setTimeout(function () {
                        e.target.style.border = '0'
                    }, 3000);
                } else {
                    e.target.style.border = '1px solid red';
                    setTimeout(function () {
                        e.target.style.border = '0'
                    }, 3000);
                }
            };
            xhr.send();
        }
    }
})();