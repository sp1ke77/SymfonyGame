untitled ancient world strategy
====

DESIGN CAVE PAINTING

![Alt text](/git docs/adon_webmodel.png "Optional title")
	

DATABASE TABLES

	MUST BE CREATED AT GAME START

			map (1600 entries)
			x, y, mapzone (fk: mapzone)

			mapzone (1600 entries)
			id, geotype (enum), tradegoods (fk: tradegood), persons, buildings (fk: building)

			kingdom (4 entries)
			name, imglarge, imgsmall, img-face, dynasty (fk: tribe), {{ ...stats... }}, {{ ...traits.... }}

			city (32 entries)
			name, imglarge, imgsmall, depot (fk: depot), king (fk: character), priest (fk: character)

			region (32 entries)
			name, imglarge, description, city (fk: city), ruledby (fk: nation)
			
			tradegood (~35 entries)
			name, imgfull, img-icon, description, value, tgtype (enum)

			tribe (~40ish entries)
			name, culture (enum), {{... ptype vectors... }}


	CAN BE CREATED DURING PLAY

			user
			username, displayname, email, dob, password

			playercharacter
			user (fk: user), mapzone (fk: mapzone), name, {{ ... stats... }} , {{ ...traits ... }} , depot (fk: depot)

			clan (one-per-tribe created at game start)
			name, mapzone (fk: mapzone), ptype (enum), tribe (fk: tribe), population, fighters, depot (fk: depot)

			building
			name, mapzone (fk: mapzone), owner (fk: character), region (fk: region), topuser (fk: clan)

			unit
			name, mapzone (fk: mapzone), ptype (enum), owner (fk: character), fighters

			depot 
			wheat, wine, olives, cattle, copper, cedar, incense, {{ etc... }}



	MUST BE CREATED DURING PLAY

			diplomatic_relation
			characterid, target, modifier, reason

			diplomatic_status
			tribe (fk: tribe), target (fk: tribe), status (enum)
