function DescriptionHelper() {

    this.getCultureDescription = function(culture) {
        switch (culture)
        {
            case "Kanani":
                return "The Kananu live in the fertile coasts and valleys of the southlands. " +
                        "An urbane lot above all, so much so that they never call themselves 'Kananu', preferring Tyrit, " +
                        "Megidit, Asqalunit and so forth and expecting foreigners to know or care what these words mean. " +
                        "The people of these mighty cities are proud, querelsome and vain. All of their favorite gods are cattle.";
            case "Hurri":
                return "The Hurru come from the northern deserts in the hinterlands of Babylonia. Among them are the " +
                        "former palace guards of Haran, countless exiled nobles of Mitanni and a mass of the displaced. " +
                        "Dusty, uncouth and speaking in an incomprehensible accent, Hurru are first among horse-tamers and " +
                        "sneak thieves and last to understand references to popular poems.";
            case "Luwwiya":
                return "The Luwwiyu are the folk of the Tin Lands to the distant north, a refined and shrewd lot whose " +
                        "immigration has been out of opportunism rather than desperation. Armed with knowledge of " +
                        "the stars and of distant lands gleaned from their far-flung trading expeditions, Luwwiyu " +
                        "have also acquired a reputation for being somewhat subtle and untrustworthy.";
            case "Tejeni":
                return "Tejenu are recent arrivals from beyond the western seas. Few have heard of them " +
                        "a thousand leagues inland. Most people who have encountered a Tejeni were robbed, swindled or " +
                        "given treasure stolen from a baal's house in exchange for olives and wine. They like loud " +
                        "fights, crude poetry and carrying weapons in public and are, for some reason, almost all men.";
            case "Kefti":
                return "The Keftiu live on isles in the great green ocean and are the most " +
                        "erudite people in the world. Never double-cross a Kefti, so goes the Egyptian fable, or " +
                        "your brother might eat poisoned oysters and die an agonizing " +
                        "death. They are said to be able to locate gold by singing and to steal and " +
                        "sacrifice other peoples' children in caves. ";
            case "Amurri":
                return "The Amurru are the military princes of the mountains. Like the Hurru, they come from " +
                        "the wastes at the footsteps of Babylon. Amurrit tribes observe scrupulous honor codes " +
                        "and as such have many enemies " +
                        "who spread calumnies about them far and wide such as that they are hard-nosed, violent-tempered " +
                        "and imperious, given to putting hapless travelers on trial according to obscure offenses known " +
                        "only to their elders, and that moreover they won't marry their children to foreigners and nobody knows " +
                        "what happens on their secret holiday; how boring.";
            case "Shasi":
                return "The Shasu are the nomads of the Sinai, the remote deserts of the south and of Goshen in Egypt. " +
                        "Deeply secretive and insular, Shasu are rarely seen outside their pastoral camps. Their priests " +
                        "are rumored to be terrifying magicians who consort with djinni and breed magical vipers for " +
                        "use in regicides, but most of the people spreading these rumors have never encountered a shas " +
                        "outside the context of buying smoked meat at a market.";
        }
    };

    this.getCityDescription = function(city) {
        switch (city)
        {
            case "Ugarit":
                return "Ugarit is one of the oldest cities in the world. Standing at the crossroads of the " +
                        "mountainous Tin Lands and the passes to the eastern deserts, in time it has amassed " +
                        "a small forest of victory stelae by long forgotten warlords.";
            case "Tyre":
                return "Tyre on the shores of the sea sits behind a massive rampart, the such largest edifice on " +
                        "the Mediterranean coast. Called the 'Purple City' and the 'Oyster City'. Tyrits swear oaths " +
                        "on Melkart but generally prefer to worship things closer to hand, like money.";
            case "Gubla":
                return "Gubla is a port founded long ago by the Egyptians. Protected by mountains on one side and " +
                        "sea on the other, Gublids are sheltered from the wild and wanton and have the smug attitude " +
                        "that safety engenders.";
            case "Qedesh":
                return "Qedesh is a sleepy town about which every passing warlord develops passionate religious " +
                        "beliefs. Not coinidentally, it is surrounded by the bones of the thousands of battles over " +
                        "who gets to camp there.";
            case "Asqalun":
                return "Asqalun is a low-coastal town in which the worst of the worst congregate: thieves, " +
                        "ill-fated travelers, slain chieftains' vengeful widows and the demon-possessed. " +
                        "Of late it's become so bad that it has even begun to attract seafaring Tejenu .";
            case "Megiddo":
                return "Megiddo is a proud city, and because it takes its pride seriously, it is the major instigator " +
                        "of alliance wars in the Jordan valley. ";
            case "Shechem":
                return "Shechem sits at the entrance to the Nahal Iron, the dry-river road. Its market is the major " +
                        "market for mercanaries in the south, probably due to its status as a repeat target of alliances.";
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