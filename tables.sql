--William D Minard, Daniel J Hart, Kenny Clark, Matthew Pokoik, Michael Koboldt
--CS 3380: Database
--Final Project
--Table Creation
--Last Update: 4/15/15

/* WILL'S VERSION 1 */

--make a clean slate every time you run the script
DROP SCHEMA IF EXISTS quickScript CASCADE;

--set path for script run
CREATE SCHEMA quickScript;
SET search_path = quickScript;

--create table shows
CREATE TABLE project
(
	owner varchar( 50 ) NOT NULL,
	project_name varchar( 50 ) NOT NULL,
	project_id serial NOT NULL PRIMARY KEY,
	created_by varchar( 50 ) NOT NULL,
	producer varchar( 30 ),
	start_date date NOT NULL DEFAULT current_date,

	FOREIGN KEY( owner ) REFERENCES users.user_info( username ) 
);

-- episode
CREATE TABLE episode
(
	project_id serial NOT NULL,
	episode_name varchar( 50 ) NOT NULL DEFAULT 'Episode',
	episode_id serial NOT NULL PRIMARY KEY,
	director varchar( 50 ) NOT NULL,
	writer varchar( 100 ) NOT NULL,
	editor varchar( 100 ) NOT NULL,

	FOREIGN KEY( project_id ) REFERENCES project( project_id ) ON DELETE CASCADE
);

-- create table shooting_day
CREATE TABLE shooting_day
(
	episode_id serial NOT NULL,
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

	FOREIGN KEY( episode_id ) REFERENCES episode( episode_id ) ON DELETE CASCADE
);

--create table scene
CREATE TABLE scene
(
	sd_id serial NOT NULL,
	scene_id serial NOT NULL PRIMARY KEY,

	scene_number varchar( 10 ) NOT NULL,
	description varchar( 200 ) NOT NULL,
	location varchar( 30 ) NOT NULL,

	FOREIGN KEY( sd_id ) REFERENCES shooting_day( sd_id ) ON DELETE CASCADE
);

--create table take
CREATE TABLE take
(
	scene_id serial NOT NULL,
	take_number integer NOT NULL,

	-- video
	cam1_file integer NOT NULL,
	cam2_file integer,
	cam3_file integer,

	-- audio
	audio_file integer,

	notes varchar( 200 ),
	rating integer NOT NULL CHECK( rating > -1 AND rating < 11 ),
	is_dead boolean,
	is_bloop boolean,

	take_id serial PRIMARY KEY NOT NULL,

	FOREIGN KEY( scene_id ) REFERENCES scene( scene_id ) ON DELETE CASCADE
);

CREATE TABLE project_image (
	path varchar(100) NOT NULL PRIMARY KEY,
	image_id integer NOT NULL REFERENCES project(project_id) ON DELETE CASCADE,
	size integer NOT NULL,
	type varchar(15),
	name varchar(50),
	description varchar(100)
);
