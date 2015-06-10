function MapView() {
    var imgrsc = new ImageResource();

    this.drawCities = function (ctx, data) {
        for (var n = 0; n < (data['cities'].length); n++) {
            ctx.drawImage(imgrsc.city, (data['cities'][n].x * imgrsc.plains.width) + 28, (data['cities'][n].y * imgrsc.plains.height) + 10);
            ctx.fillText(data['cities'][n].named, (data['cities'][n].x * imgrsc.plains.width) + 33, (data['cities'][n].y * imgrsc.plains.height) + 20);
        }
    };

    this.drawMap = function(ctx, data) {

        for (var i = 0; i < data['mapzones'].length; i++) {
            var x2 = data['mapzones'][i].x * imgrsc.plains.width;
            var y2 = data['mapzones'][i].y * imgrsc.plains.height;
            var gt = data['mapzones'][i].geotype;

            switch (gt) {
                case 'plains':
                    ctx.drawImage(imgrsc.plains, x2, y2);
                    break;
                case 'hills':
                    ctx.drawImage(imgrsc.hills, x2, y2);
                    break;
                case 'mountains':
                    ctx.drawImage(imgrsc.mountains, x2, y2);
                    break;
                case 'desert':
                    ctx.drawImage(imgrsc.desert, x2, y2);
                    break;
                case 'swamp':
                    ctx.drawImage(imgrsc.swamp, x2, y2);
                    break;
                case 'forest':
                    ctx.drawImage(imgrsc.forest, x2, y2);
                    break;
                case 'deepsea':
                    ctx.drawImage(imgrsc.deepsea, x2, y2);
                    break;
                case 'shallowsea':
                    ctx.drawImage(imgrsc.shallowsea, x2, y2);
                    break;
            }
            ctx.fillText('id=' + data['mapzones'][i].id, x2 + 15, y2 + 15);
        }
    };

    this.drawClans = function(ctx, data) {
        for (var n = 0; n < (data['clans'].length); n++) {
            var fx = Math.floor((Math.random() * 60) + -10);
            var fy = Math.floor((Math.random() * 20) + 50);
            ctx.drawImage(imgrsc.clan, (data['clans'][n].x * imgrsc.plains.width) + fx, (data['clans'][n].y * imgrsc.plains.height + fy));
            ctx.fillText(data['clans'][n].named, (data['clans'][n].x * imgrsc.plains.width) + fx + 8, (data['clans'][n].y * imgrsc.plains.height) + fy + 10);
        }
    };

    this.handleClick = function(which, ev) {
        switch (which)
        {
            case 1: // Mouse-left
                // Get the click event context
                var $img = $(ev.target);
                var offset = $img.offset();

                // Get the pixel location of the click ...
                var x = ev.clientX - offset.left;
                var y = ev.clientY - offset.top;

                // ... and render that into abstract coordinates
                x = Math.floor(x / 100);
                y = Math.floor(y / 100);

                // Get the data out of storage
                var data = JSON.parse(sessionStorage.getItem("data"));

                // If there's some data, fill in the inspector with whatever is in the
                // current square. Cities take precedence, with mapzone properties as a
                // fallback only
                if (typeof data != 'undefined') {
                    var name = document.getElementById("txt-inspector-name");
                    var citySelected = false;

                    if (data['cities'].length > 0) {
                        for (var i = 0; i < data['cities'].length; i++) {
                            if ((data['cities'][i].x == x) && (data['cities'][i].y == y)) {
                                name.innerText = data['cities'][i].named;
                                citySelected = true;
                            }
                        }
                    }

                    if (!citySelected) {
                        for (i = 0; i < data['mapzones'].length; i++) {
                            if ((data['mapzones'][i].x == x) && (data['mapzones'][i].y == y)) {
                                name.innerText = data['mapzones'][i].geotype;
                            }
                        }
                    }
                }
                break;
            case 2:
                break;
            case 3: // Mouse-right
                var $infopanes = $('#info-panes');
                $infopanes.stop();
                $infopanes.fadeToggle();
                break;
            default:
                break;
        }
    }
}