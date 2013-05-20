CREATE TABLE phpmyauctioneer_auction (
    category int(7),
    clicks int(7),
    description text,
    creator_ip tinytext,
    name tinytext,
    quantity int(3),
    start_bid float,
    starts varchar(20),
    ends varchar(20),
    seller int(7),
    payment tinytext,
    packship tinytext,
    picture VARCHAR(100),
    status varchar(10),
    highlight_option int(3),
    bold_option int(3),
    pid int(7) NOT NULL auto_increment,
    resell int(3) DEFAULT '0',
    PRIMARY KEY (pid)
);


CREATE TABLE phpmyauctioneer_bid (
    id int(7) NOT NULL AUTO_INCREMENT,
    articleid int(7) NOT NULL,
    date varchar(20),
    bid decimal(10 , 2 ),
    bidder_ip varchar(16),
    username varchar(32),
    PRIMARY KEY (id)
);


CREATE TABLE phpmyauctioneer_user (
    creator_ip tinytext,
    firstname varchar(30),
    lastname varchar(30),
    username varchar(30),
    password varchar(32),
    email varchar(30),
    address tinytext,
    city varchar(30),
    country varchar(30),
    zipcode varchar(12),
    phone varchar(15),
    status varchar(10),
    created TIMESTAMP,
    icqnumber varchar(20),
    birthdate varchar(20),
    PRIMARY KEY (username)
);


CREATE TABLE phpmyauctioneer_category (
    id int(7),
    description text,
    caption tinytext,
    parent int(7)
);


CREATE TABLE phpmyauctioneer_userrate (
    id int(7) NOT NULL auto_increment,
    from_user int(7) NOT NULL,
    to_user int(7) NOT NULL,
    articleid int(7) NOT NULL,
    date varchar(14),
    rate varchar(255),
    rateint tinyint(4),
    ip VARCHAR(24),
    PRIMARY KEY (id)
);

CREATE TABLE phpmyauctioneer_page (
    code text,
    authentification int,
    name varchar(10),
    title varchar(25),
    PRIMARY KEY (name)
);

CREATE TABLE phpmyauctioneer_menu (
    name varchar(10),
    position tinyint(4),
    title varchar(25),
    PRIMARY KEY (name)
);

CREATE TABLE phpmyauctioneer_session (
    created BIGINT(14),
    id VARCHAR(32) NOT NULL,
    ip VARCHAR(24),
    username VARCHAR(30),
    PRIMARY KEY (id)
);
