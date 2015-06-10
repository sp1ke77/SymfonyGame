function NewsBox() {

    this.populate = function(newsbox, data) {

        // If there's some news, clear and populate; otherwise, just clear
        if (data['news'].length > 0) {

            // Clear the newsbox
            while (newsbox.firstChild) newsbox.removeChild(newsbox.firstChild);
            var ulstr = "";

            // Populate the newsbox with the supplied data
            for (i = 0; i < data['news'].length; i++) {
                ulstr += "<li>" + data['news'][i].text + "</li>";
            }

            newsbox.innerHTML = ulstr;
        } else {
            while (newsbox.firstChild) newsbox.removeChild(newsbox.firstChild);
        }
    };
}
