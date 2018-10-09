Create table RestfulApi2.oauth_personal_access_clients(
id int(9) not null auto_increment,
client_id int(9) not null,
created_at timestamp(0),
updated_at timestamp(0),
primary key(id)
    )

create table ionicBackend.oauth_clients(
id int(9) not null auto_increment,
user_id int(9) default null,
name varchar(255),
secret varchar(255) not null,
redirect text,
personal_access_client boolean,
password_client boolean,
revoked boolean,
created_at timestamp(0),
updated_at timestamp(0),
primary key(id)
);

create table ionicBackend.oauth_access_tokens(
tok_id int(9) not null auto_increment,
id varchar(255) not null,
user_id int(9) not null,
client_id int(9) not null,
scopes varchar(255),
revoked boolean,
created_at timestamp(0),
updated_at timestamp(0),
expires_at timestamp(0),
primary key(tok_id)
);

create table ionicBackend.oauth_refresh_tokens( tok_id int(9) not null auto_increment, id varchar(255) not null, access_token_id varchar(255) not null, revoked boolean, expires_at timestamp(0), primary key(tok_id) )