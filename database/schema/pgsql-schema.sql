--
-- PostgreSQL database dump
--

-- Dumped from database version 14.7 (Debian 14.7-1.pgdg110+1)
-- Dumped by pg_dump version 14.7 (Ubuntu 14.7-1.pgdg22.04+1)

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
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: lists; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.lists (
    id uuid NOT NULL,
    team_id uuid NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: offer_campaigns; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.offer_campaigns (
    id uuid NOT NULL,
    offer_id uuid NOT NULL,
    campaign_id uuid NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    date_created timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: offers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.offers (
    id uuid NOT NULL,
    team_id uuid NOT NULL,
    name character varying(255),
    url character varying(255) NOT NULL,
    profit integer,
    date_created timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deleted_at timestamp(0) without time zone
);


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id uuid NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id uuid,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: sms_campaign_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_campaign_logs (
    id uuid NOT NULL,
    caller_type character varying(255),
    caller_id uuid,
    text character varying(255) NOT NULL,
    meta json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: sms_campaign_sends; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_campaign_sends (
    id uuid NOT NULL,
    campaign_id uuid,
    country_id uuid,
    send_vars json,
    status integer,
    date_created timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    deleted_at timestamp(0) without time zone
);


--
-- Name: sms_campaign_texts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_campaign_texts (
    id uuid NOT NULL,
    campaign_id uuid,
    text character varying(255),
    is_active boolean,
    parts integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: sms_campaigns; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_campaigns (
    id uuid NOT NULL,
    team_id uuid,
    name character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: sms_route; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_route (
    id uuid NOT NULL,
    company_id uuid NOT NULL,
    name character varying(255) NOT NULL,
    route_company_id uuid NOT NULL,
    connection_type character varying(255) NOT NULL,
    connection_id uuid NOT NULL,
    meta json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    is_active boolean DEFAULT true NOT NULL
);


--
-- Name: sms_route_connection_smpp; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_route_connection_smpp (
    id uuid NOT NULL,
    url character varying(255) NOT NULL,
    username character varying(255),
    password character varying(255),
    port character varying(255),
    dlr_url character varying(255),
    dlr_port character varying(255),
    workers_count integer,
    workers_delay double precision,
    deleted_at timestamp(0) without time zone
);


--
-- Name: sms_route_rates; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_route_rates (
    id uuid NOT NULL,
    route_id uuid NOT NULL,
    world_country_id integer NOT NULL,
    rate numeric(8,4) NOT NULL,
    meta json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: sms_routing_plan_rules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_routing_plan_rules (
    id uuid NOT NULL,
    routing_plan_id uuid NOT NULL,
    route_id uuid NOT NULL,
    country_id bigint NOT NULL,
    network_id bigint NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    priority character varying(255) DEFAULT '0'::character varying NOT NULL,
    action character varying(255) DEFAULT 'send'::character varying NOT NULL,
    action_vars json,
    is_platform_plan boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: sms_routing_plans; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sms_routing_plans (
    id uuid NOT NULL,
    company_id uuid NOT NULL,
    name character varying(255) NOT NULL,
    is_platform_plan boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: team_invitations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.team_invitations (
    id uuid NOT NULL,
    team_id uuid NOT NULL,
    email character varying(255) NOT NULL,
    role character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: team_user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.team_user (
    id bigint NOT NULL,
    team_id uuid NOT NULL,
    user_id uuid NOT NULL,
    role character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: team_user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.team_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: team_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.team_user_id_seq OWNED BY public.team_user.id;


--
-- Name: teams; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.teams (
    id uuid NOT NULL,
    user_id uuid NOT NULL,
    name character varying(255) NOT NULL,
    personal_team boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: telescope_entries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telescope_entries (
    sequence bigint NOT NULL,
    uuid uuid NOT NULL,
    batch_id uuid NOT NULL,
    family_hash character varying(255),
    should_display_on_index boolean DEFAULT true NOT NULL,
    type character varying(20) NOT NULL,
    content text NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: telescope_entries_sequence_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.telescope_entries_sequence_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: telescope_entries_sequence_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.telescope_entries_sequence_seq OWNED BY public.telescope_entries.sequence;


--
-- Name: telescope_entries_tags; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telescope_entries_tags (
    entry_uuid uuid NOT NULL,
    tag character varying(255) NOT NULL
);


--
-- Name: telescope_monitoring; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.telescope_monitoring (
    tag character varying(255) NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id uuid NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    current_team_id uuid,
    profile_photo_path character varying(2048),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone
);


--
-- Name: world_cities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_cities (
    id bigint NOT NULL,
    country_id bigint NOT NULL,
    state_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    country_code character varying(3) NOT NULL
);


--
-- Name: world_cities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_cities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_cities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_cities_id_seq OWNED BY public.world_cities.id;


--
-- Name: world_countries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_countries (
    id bigint NOT NULL,
    iso2 character varying(2) NOT NULL,
    name character varying(255) NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL,
    phone_code character varying(5) NOT NULL,
    iso3 character varying(3) NOT NULL,
    region character varying(255) NOT NULL,
    subregion character varying(255) NOT NULL
);


--
-- Name: world_countries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_countries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_countries_id_seq OWNED BY public.world_countries.id;


--
-- Name: world_currencies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_currencies (
    id bigint NOT NULL,
    country_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    "precision" smallint DEFAULT '2'::smallint NOT NULL,
    symbol character varying(255) NOT NULL,
    symbol_native character varying(255) NOT NULL,
    symbol_first smallint DEFAULT '1'::smallint NOT NULL,
    decimal_mark character varying(1) DEFAULT '.'::character varying NOT NULL,
    thousands_separator character varying(1) DEFAULT ','::character varying NOT NULL
);


--
-- Name: world_currencies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_currencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_currencies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_currencies_id_seq OWNED BY public.world_currencies.id;


--
-- Name: world_languages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_languages (
    id bigint NOT NULL,
    code character(2) NOT NULL,
    name character varying(255) NOT NULL,
    name_native character varying(255) NOT NULL,
    dir character(3) NOT NULL
);


--
-- Name: world_languages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_languages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_languages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_languages_id_seq OWNED BY public.world_languages.id;


--
-- Name: world_mobile_networks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_mobile_networks (
    id bigint NOT NULL,
    mcc integer,
    mnc integer,
    world_country_id bigint,
    type character varying(255),
    country_name character varying(255),
    country_code character varying(255),
    brand character varying(255),
    operator character varying(255),
    status character varying(255),
    bands character varying(255),
    notes character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: world_mobile_networks_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_mobile_networks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_mobile_networks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_mobile_networks_id_seq OWNED BY public.world_mobile_networks.id;


--
-- Name: world_states; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_states (
    id bigint NOT NULL,
    country_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    country_code character varying(3) NOT NULL
);


--
-- Name: world_states_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_states_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_states_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_states_id_seq OWNED BY public.world_states.id;


--
-- Name: world_timezones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.world_timezones (
    id bigint NOT NULL,
    country_id bigint NOT NULL,
    name character varying(255) NOT NULL
);


--
-- Name: world_timezones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.world_timezones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: world_timezones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.world_timezones_id_seq OWNED BY public.world_timezones.id;


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: team_user id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_user ALTER COLUMN id SET DEFAULT nextval('public.team_user_id_seq'::regclass);


--
-- Name: telescope_entries sequence; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries ALTER COLUMN sequence SET DEFAULT nextval('public.telescope_entries_sequence_seq'::regclass);


--
-- Name: world_cities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_cities ALTER COLUMN id SET DEFAULT nextval('public.world_cities_id_seq'::regclass);


--
-- Name: world_countries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_countries ALTER COLUMN id SET DEFAULT nextval('public.world_countries_id_seq'::regclass);


--
-- Name: world_currencies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_currencies ALTER COLUMN id SET DEFAULT nextval('public.world_currencies_id_seq'::regclass);


--
-- Name: world_languages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_languages ALTER COLUMN id SET DEFAULT nextval('public.world_languages_id_seq'::regclass);


--
-- Name: world_mobile_networks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_mobile_networks ALTER COLUMN id SET DEFAULT nextval('public.world_mobile_networks_id_seq'::regclass);


--
-- Name: world_states id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_states ALTER COLUMN id SET DEFAULT nextval('public.world_states_id_seq'::regclass);


--
-- Name: world_timezones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_timezones ALTER COLUMN id SET DEFAULT nextval('public.world_timezones_id_seq'::regclass);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: lists lists_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lists
    ADD CONSTRAINT lists_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: offer_campaigns offer_campaigns_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offer_campaigns
    ADD CONSTRAINT offer_campaigns_pkey PRIMARY KEY (id);


--
-- Name: offers offers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offers
    ADD CONSTRAINT offers_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: sms_campaign_logs sms_campaign_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_campaign_logs
    ADD CONSTRAINT sms_campaign_logs_pkey PRIMARY KEY (id);


--
-- Name: sms_campaign_sends sms_campaign_sends_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_campaign_sends
    ADD CONSTRAINT sms_campaign_sends_pkey PRIMARY KEY (id);


--
-- Name: sms_campaign_texts sms_campaign_texts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_campaign_texts
    ADD CONSTRAINT sms_campaign_texts_pkey PRIMARY KEY (id);


--
-- Name: sms_campaigns sms_campaigns_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_campaigns
    ADD CONSTRAINT sms_campaigns_pkey PRIMARY KEY (id);


--
-- Name: sms_route_connection_smpp sms_route_connection_smpp_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_route_connection_smpp
    ADD CONSTRAINT sms_route_connection_smpp_pkey PRIMARY KEY (id);


--
-- Name: sms_route sms_route_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_route
    ADD CONSTRAINT sms_route_pkey PRIMARY KEY (id);


--
-- Name: sms_route_rates sms_route_rates_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_route_rates
    ADD CONSTRAINT sms_route_rates_pkey PRIMARY KEY (id);


--
-- Name: sms_routing_plan_rules sms_routing_plan_rules_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_routing_plan_rules
    ADD CONSTRAINT sms_routing_plan_rules_pkey PRIMARY KEY (id);


--
-- Name: sms_routing_plans sms_routing_plans_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_routing_plans
    ADD CONSTRAINT sms_routing_plans_pkey PRIMARY KEY (id);


--
-- Name: team_invitations team_invitations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_invitations
    ADD CONSTRAINT team_invitations_pkey PRIMARY KEY (id);


--
-- Name: team_invitations team_invitations_team_id_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_invitations
    ADD CONSTRAINT team_invitations_team_id_email_unique UNIQUE (team_id, email);


--
-- Name: team_user team_user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_user
    ADD CONSTRAINT team_user_pkey PRIMARY KEY (id);


--
-- Name: team_user team_user_team_id_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_user
    ADD CONSTRAINT team_user_team_id_user_id_unique UNIQUE (team_id, user_id);


--
-- Name: teams teams_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.teams
    ADD CONSTRAINT teams_pkey PRIMARY KEY (id);


--
-- Name: telescope_entries telescope_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_pkey PRIMARY KEY (sequence);


--
-- Name: telescope_entries telescope_entries_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_uuid_unique UNIQUE (uuid);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: world_cities world_cities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_cities
    ADD CONSTRAINT world_cities_pkey PRIMARY KEY (id);


--
-- Name: world_countries world_countries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_countries
    ADD CONSTRAINT world_countries_pkey PRIMARY KEY (id);


--
-- Name: world_currencies world_currencies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_currencies
    ADD CONSTRAINT world_currencies_pkey PRIMARY KEY (id);


--
-- Name: world_languages world_languages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_languages
    ADD CONSTRAINT world_languages_pkey PRIMARY KEY (id);


--
-- Name: world_mobile_networks world_mobile_networks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_mobile_networks
    ADD CONSTRAINT world_mobile_networks_pkey PRIMARY KEY (id);


--
-- Name: world_states world_states_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_states
    ADD CONSTRAINT world_states_pkey PRIMARY KEY (id);


--
-- Name: world_timezones world_timezones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.world_timezones
    ADD CONSTRAINT world_timezones_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: sms_campaign_logs_caller_type_caller_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_campaign_logs_caller_type_caller_id_index ON public.sms_campaign_logs USING btree (caller_type, caller_id);


--
-- Name: sms_route_connection_type_connection_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_route_connection_type_connection_id_index ON public.sms_route USING btree (connection_type, connection_id);


--
-- Name: sms_route_rates_route_id_world_country_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_route_rates_route_id_world_country_id_index ON public.sms_route_rates USING btree (route_id, world_country_id);


--
-- Name: sms_routing_plan_rules_is_platform_plan_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_routing_plan_rules_is_platform_plan_index ON public.sms_routing_plan_rules USING btree (is_platform_plan);


--
-- Name: sms_routing_plan_rules_route_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_routing_plan_rules_route_id_index ON public.sms_routing_plan_rules USING btree (route_id);


--
-- Name: sms_routing_plan_rules_routing_plan_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_routing_plan_rules_routing_plan_id_index ON public.sms_routing_plan_rules USING btree (routing_plan_id);


--
-- Name: sms_routing_plans_company_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sms_routing_plans_company_id_index ON public.sms_routing_plans USING btree (company_id);


--
-- Name: teams_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX teams_user_id_index ON public.teams USING btree (user_id);


--
-- Name: telescope_entries_batch_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_batch_id_index ON public.telescope_entries USING btree (batch_id);


--
-- Name: telescope_entries_created_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_created_at_index ON public.telescope_entries USING btree (created_at);


--
-- Name: telescope_entries_family_hash_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_family_hash_index ON public.telescope_entries USING btree (family_hash);


--
-- Name: telescope_entries_tags_entry_uuid_tag_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_tags_entry_uuid_tag_index ON public.telescope_entries_tags USING btree (entry_uuid, tag);


--
-- Name: telescope_entries_tags_tag_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_tags_tag_index ON public.telescope_entries_tags USING btree (tag);


--
-- Name: telescope_entries_type_should_display_on_index_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX telescope_entries_type_should_display_on_index_index ON public.telescope_entries USING btree (type, should_display_on_index);


--
-- Name: world_mobile_networks_world_country_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX world_mobile_networks_world_country_id_index ON public.world_mobile_networks USING btree (world_country_id);


--
-- Name: lists lists_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.lists
    ADD CONSTRAINT lists_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id);


--
-- Name: offer_campaigns offer_campaigns_campaign_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offer_campaigns
    ADD CONSTRAINT offer_campaigns_campaign_id_foreign FOREIGN KEY (campaign_id) REFERENCES public.sms_campaigns(id);


--
-- Name: offer_campaigns offer_campaigns_offer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.offer_campaigns
    ADD CONSTRAINT offer_campaigns_offer_id_foreign FOREIGN KEY (offer_id) REFERENCES public.offers(id);


--
-- Name: sms_routing_plan_rules sms_routing_plan_rules_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_routing_plan_rules
    ADD CONSTRAINT sms_routing_plan_rules_country_id_foreign FOREIGN KEY (country_id) REFERENCES public.world_countries(id);


--
-- Name: sms_routing_plan_rules sms_routing_plan_rules_network_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sms_routing_plan_rules
    ADD CONSTRAINT sms_routing_plan_rules_network_id_foreign FOREIGN KEY (network_id) REFERENCES public.world_mobile_networks(id);


--
-- Name: team_invitations team_invitations_team_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.team_invitations
    ADD CONSTRAINT team_invitations_team_id_foreign FOREIGN KEY (team_id) REFERENCES public.teams(id);


--
-- Name: telescope_entries_tags telescope_entries_tags_entry_uuid_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.telescope_entries_tags
    ADD CONSTRAINT telescope_entries_tags_entry_uuid_foreign FOREIGN KEY (entry_uuid) REFERENCES public.telescope_entries(uuid) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 14.7 (Debian 14.7-1.pgdg110+1)
-- Dumped by pg_dump version 14.7 (Ubuntu 14.7-1.pgdg22.04+1)

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

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_reset_tokens_table	1
3	2014_10_12_200000_add_two_factor_columns_to_users_table	1
4	2018_08_08_100000_create_telescope_entries_table	1
5	2019_08_19_000000_create_failed_jobs_table	1
6	2019_12_14_000001_create_personal_access_tokens_table	1
7	2020_05_21_100000_create_teams_table	1
8	2020_05_21_200000_create_team_user_table	1
9	2020_05_21_300000_create_team_invitations_table	1
10	2020_07_07_055656_create_countries_table	1
11	2020_07_07_055725_create_cities_table	1
12	2020_07_07_055746_create_timezones_table	1
13	2021_10_19_071730_create_states_table	1
14	2021_10_23_082414_create_currencies_table	1
15	2022_01_22_034939_create_languages_table	1
16	2022_05_15_102152_seed_countries	1
17	2023_05_15_090851_create_sessions_table	1
18	2023_05_15_093546_create_campaigns_structure	1
19	2023_05_15_093554_create_routes_structure	1
20	2023_05_15_131232_create_lists	1
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 20, true);


--
-- PostgreSQL database dump complete
--

