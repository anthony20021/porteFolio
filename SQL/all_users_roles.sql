--
-- PostgreSQL database dump
--

-- Dumped from database version 12.3 (Ubuntu 12.3-1.pgdg19.10+1)
-- Dumped by pg_dump version 12.3 (Ubuntu 12.3-1.pgdg19.10+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
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
-- Name: permissions; Type: TABLE; Schema: public; Owner: recrutement
--

CREATE TABLE public.permissions (
    id integer NOT NULL,
    nom character varying NOT NULL,
    description character varying
);


ALTER TABLE public.permissions OWNER TO recrutement;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: recrutement
--

CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permissions_id_seq OWNER TO recrutement;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: recrutement
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;

CREATE TABLE public.users_permissions (
    user_id integer NOT NULL,
    permission_id integer NOT NULL
);


ALTER TABLE public.users_permissions OWNER TO recrutement;


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: recrutement
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO recrutement;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: recrutement
--

CREATE TABLE public.roles (
    id integer DEFAULT nextval('public.roles_id_seq'::regclass) NOT NULL,
    nom character varying(60),
    description character varying(255)
);


ALTER TABLE public.roles OWNER TO recrutement;

--
-- Name: roles_permissions; Type: TABLE; Schema: public; Owner: recrutement
--

CREATE TABLE public.roles_permissions (
    role_id integer NOT NULL,
    permission_id integer NOT NULL
);


ALTER TABLE public.roles_permissions OWNER TO recrutement;

--
-- Name: users; Type: TABLE; Schema: public; Owner: recrutement
--

CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    last_con timestamp without time zone,
    remember_token character varying(60),
    name character varying(255),
    deleted_at timestamp without time zone,
    firstname character varying(255)
);


ALTER TABLE public.users OWNER TO recrutement;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: recrutement
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1000
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 2147483647
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO recrutement;

--

--
-- Name: users_roles; Type: TABLE; Schema: public; Owner: recrutement
--

CREATE TABLE public.users_roles (
    user_id integer NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.users_roles OWNER TO recrutement;

--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: recrutement
--

COPY public.permissions (id, nom, description) FROM stdin;
1	toto	granted by user
2	titi	granted by role
3	voir projet	voir projet
4	modifier projet	modifier projet
5	voir contact	voir contact
6	modifier contact	modifier contact
7	voir historique	voir historique
8	modifier historique	modifier historique
9	tableaux	acces au tableaux PSG
10	cartographie_propriete	acces propriete webmapping
11	cartographie_travaux	acces travaux webmapping
12	users_management	gestion des users
\.

--
-- Data for Name: users_permissions; Type: TABLE DATA; Schema: public; Owner: recrutement
--

COPY public.users_permissions (user_id, permission_id) FROM stdin;
1	1
\.

--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: recrutement
--

COPY public.roles (id, nom, description) FROM stdin;
1	administrateur	administrateur de l'application
2	client	Compte client
3	chef_de_projet	Chef de projet
4	CRM	Toutes les permissions pour le CRM
5	salarie	utilisateur salarie planfor
6	gestionnaire	client gestionnaire
7	utilisateur	Utilisateur basique
\.


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: recrutement
--

SELECT pg_catalog.setval('public.permissions_id_seq', 12, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: recrutement
--

SELECT pg_catalog.setval('public.roles_id_seq', 7, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: recrutement
--

SELECT pg_catalog.setval('public.users_id_seq', 1000, true);



--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: roles_permissions roles_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.roles_permissions
    ADD CONSTRAINT roles_permissions_pkey PRIMARY KEY (role_id, permission_id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_roles users_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.users_roles
    ADD CONSTRAINT users_roles_pkey PRIMARY KEY (user_id, role_id);

ALTER TABLE ONLY public.users_permissions
    ADD CONSTRAINT users_permissions_pkey PRIMARY KEY (user_id, permission_id);


--
-- Name: fki_fk_up_permissions; Type: INDEX; Schema: public; Owner: recrutement
--

CREATE INDEX fki_fk_up_permissions ON public.users_permissions USING btree (permission_id);


--
-- Name: fki_fk_up_users; Type: INDEX; Schema: public; Owner: recrutement
--

CREATE INDEX fki_fk_up_users ON public.users_permissions USING btree (user_id);


--
-- Name: users_permissions fk_up_permissions; Type: FK CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.users_permissions
    ADD CONSTRAINT fk_up_permissions FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: users_permissions fk_up_users; Type: FK CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.users_permissions
    ADD CONSTRAINT fk_up_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;

--
-- Name: fki_fk_rp_permissions; Type: INDEX; Schema: public; Owner: recrutement
--

CREATE INDEX fki_fk_rp_permissions ON public.roles_permissions USING btree (permission_id);


--
-- Name: fki_fk_rp_roles; Type: INDEX; Schema: public; Owner: recrutement
--

CREATE INDEX fki_fk_rp_roles ON public.roles_permissions USING btree (role_id);


--
-- Name: fki_fk_ur_roles; Type: INDEX; Schema: public; Owner: recrutement
--

CREATE INDEX fki_fk_ur_roles ON public.users_roles USING btree (role_id);


--
-- Name: fki_fk_ur_users; Type: INDEX; Schema: public; Owner: recrutement
--

CREATE INDEX fki_fk_ur_users ON public.users_roles USING btree (user_id);


--
-- Name: roles_permissions fk_rp_permissions; Type: FK CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.roles_permissions
    ADD CONSTRAINT fk_rp_permissions FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: roles_permissions fk_rp_roles; Type: FK CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.roles_permissions
    ADD CONSTRAINT fk_rp_roles FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: users_roles fk_ur_roles; Type: FK CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.users_roles
    ADD CONSTRAINT fk_ur_roles FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: users_roles fk_ur_users; Type: FK CONSTRAINT; Schema: public; Owner: recrutement
--

ALTER TABLE ONLY public.users_roles
    ADD CONSTRAINT fk_ur_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

