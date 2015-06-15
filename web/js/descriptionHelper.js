function DescriptionHelper() {

    this.getCultureDescription = function(culture) {
        switch (culture)
        {
            case "Kanani":
                return "The Kananu live in the fertile coasts and valleys of the southlands. They are a hardworking " +
                        "and well-fed bunch, most dwelling in cities and on the grounds of prosperous rural estates. " +
                        "Kananu are proudest of their temples, and swear upon a bewildering array of names coming from " +
                        "all the lands of the world.";
            case "Hurri":
                return "The Hurru come from the eastern deserts in the hinterlands of Babylonia. Among them are the " +
                        "former palace guards of Haran, countless exiled nobles of Mitanni and a mass of the displaced. " +
                        "Dusty, uncouth and speaking in an incomprehensible accent, Hurru are reputed as tamers and " +
                        "thieves of horses.";
            case "Luwwiya":
                return "The Luwwiyu are the folk of the Tin Lands to the distant north, a refined and shrewd lot whose " +
                        "immigration has been out of opportunism rather than desperation. Famous for their knowledge of " +
                        "both the stars and distant lands, gleaned from their far-flung trading expeditions, Luwwiyans " +
                        "have also acquired a reputation as not a little bit subtle and untrustworthy.";
            case "Tejeni":
                return "The Tejenu are recent arrivals from beyond the Great Green in the west. Few have heard of them " +
                        "even a thousand steps inland; most people who have met a Tejeni were robbed. Giant brutes " +
                        "without any regard for the law or cleanliness, Tejenu like loud-talking, reciting " +
                        "crude poetry and carrying weapons in public.";
            case "Kefti":
                return "The Keftiu are the dwellers of the Isles in the Great Green. They are said to be the most " +
                        "learned people in the world, more erudite even than the Egyptians. Among the works of their palaces " +
                        "are many mysterious things, likenesses whose blue and green hues dazzle the eye and amulets " +
                        "sealed with the blood of human sacrifice.";
            case "Amurri":
                return "The Amurru are the military princes of the mountainous north. Like the Hurru, they come from " +
                        "Babylonia. Tenacious desert fighters by tradition -- and a vaunted tradition it is -- Amurrit " +
                        "tribes are feared on the field even by the bronze-bedecked armies of the great kings. Many who " +
                        "dismissed their tribal alliance was broken by it.";
            case "Shasi":
                return "The Shasu are the nomads of the Qedem, the remote deserts of the south and of Goshen in Egypt. " +
                        "Deeply secretive and insular, Shasu are rarely seen outside their tribal camps in the wild and " +
                        "waste. Their priests are said to be terrifying magicians, capable of summoning forth gods from smoke.";
        }
    };

    this.getCityDescription = function(city) {
        switch (city)
        {
            case "Ugarit":
                return "Ugarit is one of the oldest cities in the world. Standing at the crossroads of the " +
                        "mountainous Tin Lands and the passes to the eastern deserts, Ugarit has long " +
                        "commanded the attention of traders from afar.";
            case "Tyre":
                return "Tyre is a fortress of the Kanani built on the shores of the sea, just south of the " +
                        "sacred mountains. Called the 'Purple City' and the 'Oyster City'. Tyrians worship " +
                        "Melkart the giver of fertile soil.";
            case "Gubla":
                return "Gubla is a port founded long ago by the Egyptians. Its protected place between " +
                        "the coast and the sacred mountains has long shielded it from traveling invaders.";
            case "Qedesh":
                return "Qedesh is a city in the northeastern reaches beside the sacred springs of Qedeshtu, " +
                        "the lady of love. Conquered two hundred years ago by the Amurru and not relinquished " +
                        "thereafter.";
            case "Asqalun":
                return "Asqalun is a low-coastal town in which the worst of the worst congregate: thieves, " +
                        "ill-fated travelers, widows of slain chieftains and hungry men with nothing to lose. " +
                        "Raiding Tejenu consider it their home away from home.";
            case "Megiddo":
                return "[Coming soon]";
            case "Shechem":
                return "[Coming soon]";
        }
    };

    this.getLiegeDescription = function(liege) {
        switch (liege)
        {
            case "Egypt":
                return "King Amunkaufenre of Egypt is a cypher-eyed youth wearing an overized crown. " +
                        "He allows a minister to speak for him. The minister speaks in grandiloquent and noncommittal " +
                        "phrases, illustrating with effusive hand-waves.<br><br>" +
                        "<i>Your land is beset by demons and unclean. We offer you the protection of Horus but with an </i>" +
                        "<i>obligation: you will ensure that the ships keep coming laden with what we desire.</i>";
            case "Babylon":
                return "King Sarganulil of the Civilized Cities is a glowering bearded man wearing a cloak-of-many-animals. " +
                        "A sword lies flat on the table next to heaping plates of food, and " +
                        "he sits eating greasy chicken with his eyes screwed up and doesn't address you for a long time. <br><br>" +
                        "<i>Let's be clear: you will swear loyalty to me </i>" +
                        "<i>and receive my protection or I will ride you like a donkey.</i>";
            case "Hatti":
                return "King Talulimiuma of Hattusa laughs heartily. His belt is made of " +
                        "giant silver disks that clink when he walks.<br><br> <i>Even the gods will attest to my fortune. </i>" +
                        "<i>Join me and rise with my star, or the next time my war parties find your town </i>" +
                        "<i>my spear will find your butt.</i><br><br> His goons, with whom the room is packed from wall to wall, " +
                        "laugh uproariously.";
        }
    };
}