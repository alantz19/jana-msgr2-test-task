/* eslint-disable */
/* tslint:disable */
/*
 * ---------------------------------------------------------------
 * ## THIS FILE WAS GENERATED VIA SWAGGER-TYPESCRIPT-API        ##
 * ##                                                           ##
 * ## AUTHOR: acacode                                           ##
 * ## SOURCE: https://github.com/acacode/swagger-typescript-api ##
 * ---------------------------------------------------------------
 */

/** SmsRouteCompaniesCollection */
export interface SmsRouteCompaniesCollection {
  data: SmsRouteCompanyResource[];
  links: {
    store: {
      url: string;
    };
  };
}

/** SmsRouteCompanyResource */
export interface SmsRouteCompanyResource {
  id: string;
  team_id: string;
  name: string;
  created_at: object | null;
  updated_at: object | null;
  links: string[];
}

/** SmsRoutingRouteCollection */
export interface SmsRoutingRouteCollection {
  data: SmsRoutingRouteResource[];
  links: {
    create: {
      url: string;
      label: string;
    };
  };
}

/** SmsRoutingRouteResource */
export interface SmsRoutingRouteResource {
  id: string;
  name: string;
  company_id: string;
  company?: SmsRouteCompanyResource;
  connection: string;
  created_at: string;
  links: {
    delete: string;
  };
}

/** SmsRouteSmppConnectionResource */
export interface SmsRouteSmppConnectionResource {
  id: string;
  url: string;
  username: string | null;
  password: string | null;
  port: string | null;
  dlr_url: string | null;
  dlr_port: string | null;
  workers_count: number | null;
  workers_delay: number | null;
  created_at: object | null;
  type: string;
}

export interface SmsRoutingCompaniesCreatePayload {
  name: string;
}

export type SmsRoutingCompaniesUpdatePayload = object;

export type SmsRoutingCompaniesDeletePayload = object;

export interface SmsRoutingRoutesCreatePayload {
  name: string;
  sms_route_company_id: string;
  connection_id: string;
  connection_type: "smpp";
}

export type SmsRoutingRoutesUpdatePayload = object;

export type SmsRoutingRoutesDeletePayload = object;

export interface SmsRoutingRoutesSmppConnectionsCreatePayload {
  url: string;
  username: string;
  password: string;
  port: number;
  dlr_url?: string | null;
  dlr_port?: number | null;
  workers_count?: number | null;
  workers_delay?: number | null;
}

export interface SmsRoutingRoutesSmppConnectionsTestCreatePayload {
  url: string;
  username: string;
  password: string;
  port: number;
  dlr_url?: string | null;
  dlr_port?: number | null;
  workers_count?: number | null;
  workers_delay?: number | null;
}
