untitled ancient world strategy
====

FILES WITH THE MOST ACTION
* **GameBundle\Game\Services\NewgameService** - The database structuring routines at the bottom of the page are responsible for structuring all the procedural data in a new game. These begin by totally trashing the existing records, so handle carefully.
* **GameBundle\Game\Rules\Rules** - A strategy-like approach, still pretty rough. Note that the Rules need only concern 	actions that the player might conceivably take -- i.e. that might be coming from a Controller -- some of these 	are moves that are common to the Clan and Army entities too so rules->submit($request) is designed to present a class-agnostic facade. (Moves that are irrelevant to the player are handled by Simulation, a pure facade with fewer limits on its internal logic. For reasons of Unix these must be presented as chains of Commands to be triggered by the server clock.)
* __GameBundle\Controller\AdminController_ - a page for testing the code



NOTES ON THE CURRENT BUILD

* It is still not clear whether UPDATE and SET queries should be in an insert()/update() method on game entity classes or whether database i/o should be kept on two strictly one-way paths. 
* Clans, Armies and Characters will need to be updated with enums representing their current AI states.
* Still need to write a strategy for Simulation/RandomEvent that will allow selection-by-criteria from an open-ended, external collection of events.
* It will be necessary to move all the "broadcast"-type News logic into its own class and then make it available from the service container. It will need to be accessed from throughout the Rules and Simulation classes, and the complexity of its strategy is likely to grow in the future.
 
DESIGN CAVE PAINTING

![Alt text](/git docs/adon_webmodel.png "Optional title")
	
