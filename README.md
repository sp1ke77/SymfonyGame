untitled ancient world strategy
====

STRUCTURAL NOTES
- GameBundle\Game\Services\NewgameService contains the entire basic schema for the database - this will be refactored into Command when it is no longer needed on a daily basis, as it is now.
- GameBundle\Game\Model contains all the classes that are populated from the database. Pay particular attention to GameEntity.
- GameBundle\Game\Services contains as many db queries as could be kicked and shoved into the box
- GameBundle\Game\Rules contains the set of functionality required by game-entities with specific rule-bound logic, including functions used by both player characters and computer-controlled entities
- GameBundle\Game\Simulation contains the clockwork parts of the game that are driven by cron, the behavior scripts for the various computer-controlled game-entities, and encapsulates the hooks required to fire RandomEvents and enforce game-boundary conditions (EnforceParams)
- GameBundle\Game\Resources\views\Game\mapview.html.twig contains more or less the entire UI used in gameplay

NEEDED SOON
- Character creation/selection
- City interface
- Diplomacy and trade interfaces
 
DESIGN CAVE PAINTING

![Alt text](/git docs/adon_webmodel.png "Optional title")
	
