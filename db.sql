create table words(id int auto_increment, address varchar(100), word varchar(100), caseSensitive smallint, found int, primary key(id));
create table sentences(id int auto_increment, word_id int, sentence text, primary key(id));