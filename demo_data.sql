--William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik, Michael Koboldt
--CS 3380: Database
--Final Project
--Insertion File
--Last Update: 4/30/15

--first run table creation file.
\i tables.sql;
--this will make sure you are using the current version of tables for the insert statements.

-- project insertions
-- template:
/*	owner varchar( 50 ) NOT NULL,
	project_name varchar( 50 ) NOT NULL,
	project_id serial NOT NULL PRIMARY KEY,
	created_by varchar( 50 ) NOT NULL,
	producer varchar( 30 ),
	start_date date NOT NULL DEFAULT current_date
*/
INSERT INTO quickscript.project
	VALUES( 'dan',
			'Undergrads',
			DEFAULT,
			'Ryan Doyle & Josh Noble',
			'Ryan Doyle & Josh Noble',
			'2014-10-01' );
INSERT INTO quickscript.project
	VALUES( 'kenny',
			'Hotline Miami',
			DEFAULT,
			'Kenny Clark',
			'Kenny Clark',
			'2015-02-01' );
INSERT INTO quickscript.project
	VALUES( 'dan',
			'Multiverse',
			DEFAULT,
			'Dan Hart',
			NULL,
			'2015-02-20' );
INSERT INTO quickscript.project
	VALUES( 'will',
			'The Florist',
			DEFAULT,
			'Ryan Doyle & Dan Hart',
			NULL,
			'2014-09-10' );

-- episode insertions
-- template:
/*	project_id serial NOT NULL,
	episode_name varchar( 30 ) NOT NULL DEFAULT 'Episode',
	episode_id serial NOT NULL PRIMARY KEY,
	director varchar( 30 ) NOT NULL,
	writer varchar( 100 ) NOT NULL,
	editor varchar( 100 ) NOT NULL
*/
INSERT INTO quickscript.episode
	VALUES( 1,
			'Pilot',
			DEFAULT,
			'Josh Noble',
			'Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES( 1,
			'Rats',
			DEFAULT,
			'Josh Noble',
			'Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES( 1,
			'Tander',
			DEFAULT,
			'Will Minard',
			'Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES(	1,
			'Double Date',
			DEFAULT,
			'Josh Noble',
			'Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES( 1,
			'The Birthday Party',
			DEFAULT,
			'Josh Noble',
			'Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES( 2,
			DEFAULT,
			DEFAULT,
			'Jd Noble, Bess McCulloch, & Christina',
			'Kenny Clark & Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES( 3,
			DEFAULT,
			DEFAULT,
			'Ryan Doyle',
			'Dan Hart',
			'Jason Statham' );
INSERT INTO quickscript.episode
	VALUES( 4,
			DEFAULT,
			DEFAULT,
			'Ryan Doyle',
			'Dan Hart',
			'Jason Statham' );
			
-- shooting day insertions
-- template:
/*	episode_id serial NOT NULL,
	sd_date date NOT NULL DEFAULT current_date,
	sd_id serial NOT NULL PRIMARY KEY,

	-- crew
	cam_ops varchar( 30 ),
	dop varchar( 30 ),
	scripty varchar( 30 ),
	audio varchar( 30 ),
	gaffer varchar( 30 ),
	ad varchar( 30 ),
	key_grip varchar( 30 ),
*/
INSERT INTO quickscript.shooting_day
	VALUES( 1,
			'2014-10-25',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 1,
			'2014-10-30',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 1,
			'2014-11-08',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 2,
			'2014-11-20',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 3,
			'2014-11-30',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 4,
			'2014-12-08',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 5,
			'2015-02-01',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 5,
			'2015-03-01',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 6,
			'2015-03-02',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 7,
			'2015-02-20',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );
INSERT INTO quickscript.shooting_day
	VALUES( 8,
			'2015-02-28',
			DEFAULT,
			'Kenny Clark & Thomas',
			'Thomas B.',
			'Carsen Sikyta',
			'Skylar Newberry',
			'J. MO',
			NULL,
			'Catherine' );

-- scene insertions
-- template:
/* 	sd_id serial NOT NULL,
	scene_id serial NOT NULL PRIMARY KEY,

	scene_number varchar( 10 ) NOT NULL,
	description varchar( 100 ) NOT NULL,
	location varchar( 30 ) NOT NULL,
*/
INSERT INTO quickscript.scene
	VALUES( 1,
			DEFAULT,
			'1a',
			'Something happens.',
			'140 E. Plank RD.' );
INSERT INTO quickscript.scene
	VALUES( 1,
			DEFAULT,
			'1b',
			'Something new happens.',
			'140 E. Plank RD.' );
INSERT INTO quickscript.scene
	VALUES( 1,
			DEFAULT,
			'10',
			'Something else happens.',
			'The mall' );
INSERT INTO quickscript.scene
	VALUES( 1,
			DEFAULT,
			'14',
			'Something cool happens.',
			'my stinky butt' );
INSERT INTO quickscript.scene
	VALUES(	1,
			DEFAULT,
			'15',
			'Something cool happens.',
			'nada' );
INSERT INTO quickscript.scene
	VALUES( 7,
			DEFAULT,
			'1b',
			'Nick and Dan banging',
			'1021 Ashland Rd. apt 1401' );
INSERT INTO quickscript.scene
	VALUES(	7,
			DEFAULT,
			'2a',
			'Nick and Dan spooning',
			'1021 Ashland Rd. duh' );
INSERT INTO quickscript.scene
	VALUES( 7,
			DEFAULT,
			'2b',
			'Nick and Dan watching tv.',
			'1021 Ashland Rd.' );
INSERT INTO quickscript.scene
	VALUES( 8,
			DEFAULT,
			'2',
			'FLowers',
			'A flower shop' );

-- take insertions
-- template:
/*	scene_id serial NOT NULL,
	take_number serial NOT NULL,
	cam1_file varchar( 10 ) NOT NULL,
	cam2_file varchar( 10 ),
	cam3_file varchar( 10 ),
	audio_file varchar( 10 ),
	notes varchar( 100 ),
	rating integer NOT NULL CHECK( rating > -1 AND rating < 11 ),
	is_dead boolean,
	is_bloop boolean
*/
INSERT INTO quickscript.take
VALUES( 1,
		1,
		'1234',
		NULL,
		NULL,
		'2',
		'Notes go here',
		5,
		FALSE,
		TRUE,
		DEFAULT );
INSERT INTO quickscript.take
VALUES( 1, 
		2,
		'1235',
		NULL,
		NULL,
		'3',
		'Notes go here',
		7,
		TRUE,
		TRUE,
		DEFAULT  );
INSERT INTO quickscript.take
VALUES( 1,
		3,
		'1236',
		NULL,
		NULL,
		'4',
		'Notes go here',
		8,
		TRUE,
		FALSE,
		DEFAULT  );
INSERT INTO quickscript.take
VALUES( 1,
		4,
		'1239',
		NULL,
		NULL,
		'5',
		'Notes go here',
		9,
		FALSE,
		FALSE,
		DEFAULT  );
INSERT INTO quickscript.take
VALUES( 1,
		5,
		'1240',
		NULL,
		NULL,
		'6',
		'Notes go here',
		1,
		FALSE,
		FALSE,
		DEFAULT  );
INSERT INTO quickscript.take
VALUES(	1,
		6,
		'1241',
		NULL,
		NULL,
		'7','Notes go here',
		2,
		TRUE,
		TRUE,
		DEFAULT  );
INSERT INTO quickscript.take
VALUES( 1,
		7,
		'1242',
		NULL,
		NULL,
		'8',
		'Notes go here',
		3,
		FALSE,
		FALSE,
		DEFAULT  );
INSERT INTO quickscript.take
VALUES( 1,
		8,
		'1244',
		NULL,
		NULL,
		'9',
		'Notes go here',
		4,
		FALSE,
		FALSE,
		DEFAULT  );