--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: migration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migration (
    version character varying(180) NOT NULL,
    apply_time integer
);


ALTER TABLE public.migration OWNER TO postgres;

--
-- Name: milk_fill; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.milk_fill (
    id integer NOT NULL,
    user_name character varying(255) NOT NULL,
    volume integer NOT NULL,
    tank_id integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.milk_fill OWNER TO postgres;

--
-- Name: milk_fill_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.milk_fill_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.milk_fill_id_seq OWNER TO postgres;

--
-- Name: milk_fill_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.milk_fill_id_seq OWNED BY public.milk_fill.id;


--
-- Name: tank; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tank (
    id integer NOT NULL,
    capacity integer NOT NULL,
    current_volume integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.tank OWNER TO postgres;

--
-- Name: tank_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tank_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tank_id_seq OWNER TO postgres;

--
-- Name: tank_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tank_id_seq OWNED BY public.tank.id;


--
-- Name: milk_fill id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_fill ALTER COLUMN id SET DEFAULT nextval('public.milk_fill_id_seq'::regclass);


--
-- Name: tank id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tank ALTER COLUMN id SET DEFAULT nextval('public.tank_id_seq'::regclass);


--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migration (version, apply_time) FROM stdin;
m000000_000000_base	1753628402
m250727_153551_create_tank_table	1753639010
m250727_153552_insert_initial_tanks	1753639010
m250727_160000_create_milk_fill_table	1753644324
\.


--
-- Data for Name: milk_fill; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.milk_fill (id, user_name, volume, tank_id, created_at) FROM stdin;
\.


--
-- Data for Name: tank; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tank (id, capacity, current_volume) FROM stdin;
4	300	0
5	300	0
3	300	0
1	300	0
2	300	0
\.


--
-- Name: milk_fill_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.milk_fill_id_seq', 15, true);


--
-- Name: tank_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tank_id_seq', 5, true);


--
-- Name: migration migration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (version);


--
-- Name: milk_fill milk_fill_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_fill
    ADD CONSTRAINT milk_fill_pkey PRIMARY KEY (id);


--
-- Name: tank tank_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tank
    ADD CONSTRAINT tank_pkey PRIMARY KEY (id);


--
-- Name: milk_fill fk-milk_fill-tank_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.milk_fill
    ADD CONSTRAINT "fk-milk_fill-tank_id" FOREIGN KEY (tank_id) REFERENCES public.tank(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

