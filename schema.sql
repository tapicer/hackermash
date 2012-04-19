--
-- PostgreSQL database dump
--

-- Started on 2011-07-09 23:37:45 ART

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 1798 (class 1262 OID 16586)
-- Name: hackermash; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE hackermash WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.utf8' LC_CTYPE = 'en_US.utf8';


\connect hackermash

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1499 (class 1259 OID 16587)
-- Dependencies: 3
-- Name: category; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE category (
    id character varying NOT NULL,
    name character varying NOT NULL,
    "position" integer NOT NULL
);


--
-- TOC entry 1503 (class 1259 OID 49154)
-- Dependencies: 3
-- Name: content; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE content (
    id bigint NOT NULL,
    categoryid character varying NOT NULL,
    url character varying NOT NULL,
    fetched boolean NOT NULL,
    title character varying,
    description character varying,
    ignored boolean,
    lastseen timestamp without time zone NOT NULL,
    slug character varying,
    comments character varying
);


--
-- TOC entry 1502 (class 1259 OID 49152)
-- Dependencies: 1503 3
-- Name: content_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE content_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1801 (class 0 OID 0)
-- Dependencies: 1502
-- Name: content_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE content_id_seq OWNED BY content.id;


--
-- TOC entry 1802 (class 0 OID 0)
-- Dependencies: 1502
-- Name: content_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('content_id_seq', 14623, true);


--
-- TOC entry 1501 (class 1259 OID 24578)
-- Dependencies: 3
-- Name: source; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE source (
    id bigint NOT NULL,
    categoryid character varying NOT NULL,
    url character varying NOT NULL,
    parser character varying NOT NULL,
    parser_params character varying NOT NULL
);


--
-- TOC entry 1500 (class 1259 OID 24576)
-- Dependencies: 3 1501
-- Name: source_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE source_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


--
-- TOC entry 1803 (class 0 OID 0)
-- Dependencies: 1500
-- Name: source_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE source_id_seq OWNED BY source.id;


--
-- TOC entry 1804 (class 0 OID 0)
-- Dependencies: 1500
-- Name: source_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('source_id_seq', 18, true);


--
-- TOC entry 1782 (class 2604 OID 49157)
-- Dependencies: 1502 1503 1503
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE content ALTER COLUMN id SET DEFAULT nextval('content_id_seq'::regclass);


--
-- TOC entry 1781 (class 2604 OID 24581)
-- Dependencies: 1501 1500 1501
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE source ALTER COLUMN id SET DEFAULT nextval('source_id_seq'::regclass);


--
-- TOC entry 1793 (class 0 OID 16587)
-- Dependencies: 1499
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO category VALUES ('programming', 'Programming', 1);
INSERT INTO category VALUES ('technology', 'Technology', 2);
INSERT INTO category VALUES ('science', 'Science', 3);
INSERT INTO category VALUES ('linux', 'Linux', 4);
INSERT INTO category VALUES ('android', 'Android', 5);
INSERT INTO category VALUES ('gaming', 'Gaming', 6);


--
-- TOC entry 1795 (class 0 OID 49154)
-- Dependencies: 1503
-- Data for Name: content; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 1794 (class 0 OID 24578)
-- Dependencies: 1501
-- Data for Name: source; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO source VALUES (1, 'programming', 'http://www.reddit.com/r/programming/.rss', 'reddit', '[]');
INSERT INTO source VALUES (2, 'technology', 'http://www.reddit.com/r/technology/.rss', 'reddit', '[]');
INSERT INTO source VALUES (3, 'science', 'http://www.reddit.com/r/science/.rss', 'reddit', '[]');
INSERT INTO source VALUES (4, 'gaming', 'http://www.reddit.com/r/gaming/.rss', 'reddit', '[]');
INSERT INTO source VALUES (5, 'linux', 'http://www.reddit.com/r/linux/.rss', 'reddit', '[]');
INSERT INTO source VALUES (6, 'android', 'http://www.reddit.com/r/android.rss', 'reddit', '[]');
INSERT INTO source VALUES (7, 'programming', 'http://slashdot.org/tag/programming', 'slashdottag', '[]');
INSERT INTO source VALUES (8, 'technology', 'http://slashdot.org/tag/technology', 'slashdottag', '[]');
INSERT INTO source VALUES (9, 'science', 'http://science.slashdot.org/', 'slashdothome', '[]');
INSERT INTO source VALUES (10, 'gaming', 'http://games.slashdot.org/', 'slashdothome', '[]');
INSERT INTO source VALUES (11, 'linux', 'http://linux.slashdot.org/', 'slashdothome', '[]');
INSERT INTO source VALUES (12, 'android', 'http://slashdot.org/tag/android', 'slashdottag', '[]');
INSERT INTO source VALUES (13, 'programming', 'http://news.ycombinator.com/rss', 'ycombinator', '["python", "php", "java", "c#", "c\\\\+\\\\+", "objective-c", "postgres(ql)?", "mysql", "memcached", "ruby", "javascript", "haskell", "coding", "programming"]');
INSERT INTO source VALUES (14, 'technology', 'http://news.ycombinator.com/rss', 'ycombinator', '["technology", "ipad", "ipod", "iphone", "htc", "samsung", "motorola", "xoom", "apple"]');
INSERT INTO source VALUES (15, 'science', 'http://news.ycombinator.com/rss', 'ycombinator', '["science", "biology", "physics", "stud(ies|y)", "math", "nasa"]');
INSERT INTO source VALUES (16, 'gaming', 'http://news.ycombinator.com/rss', 'ycombinator', '["games?", "gaming", "ps3", "play ?station", "wii", "xbox", "nintendo"]');
INSERT INTO source VALUES (17, 'linux', 'http://news.ycombinator.com/rss', 'ycombinator', '["linux", "(k|x|ed)?ubuntu", "redhat", "centos", "suse", "opensuse", "fedora", "debian", "gentoo", "linus"]');
INSERT INTO source VALUES (18, 'android', 'http://news.ycombinator.com/rss', 'ycombinator', '["android", "cyanogen(mod)?"]');


--
-- TOC entry 1784 (class 2606 OID 16594)
-- Dependencies: 1499 1499
-- Name: pk_category; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY category
    ADD CONSTRAINT pk_category PRIMARY KEY (id);


--
-- TOC entry 1790 (class 2606 OID 49162)
-- Dependencies: 1503 1503
-- Name: pk_content; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY content
    ADD CONSTRAINT pk_content PRIMARY KEY (id);


--
-- TOC entry 1786 (class 2606 OID 24586)
-- Dependencies: 1501 1501
-- Name: pk_source; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY source
    ADD CONSTRAINT pk_source PRIMARY KEY (id);


--
-- TOC entry 1787 (class 1259 OID 81945)
-- Dependencies: 1503
-- Name: idx_content_lastseen; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE INDEX idx_content_lastseen ON content USING btree (lastseen DESC);

ALTER TABLE content CLUSTER ON idx_content_lastseen;


--
-- TOC entry 1788 (class 1259 OID 81944)
-- Dependencies: 1503
-- Name: idx_content_slug; Type: INDEX; Schema: public; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX idx_content_slug ON content USING btree (slug);


--
-- TOC entry 1792 (class 2606 OID 49163)
-- Dependencies: 1499 1503 1783
-- Name: fk_content_category; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY content
    ADD CONSTRAINT fk_content_category FOREIGN KEY (categoryid) REFERENCES category(id);


--
-- TOC entry 1791 (class 2606 OID 24587)
-- Dependencies: 1499 1501 1783
-- Name: fk_source_category; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY source
    ADD CONSTRAINT fk_source_category FOREIGN KEY (categoryid) REFERENCES category(id);


--
-- TOC entry 1800 (class 0 OID 0)
-- Dependencies: 3
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2011-07-09 23:37:45 ART

--
-- PostgreSQL database dump complete
--

