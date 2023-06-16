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

import {
    SmsRouteCompaniesCollection,
    SmsRouteCompanyResource,
    SmsRouteSmppConnectionResource,
    SmsRoutingCompaniesCreatePayload,
    SmsRoutingCompaniesDeletePayload,
    SmsRoutingCompaniesUpdatePayload,
    SmsRoutingRouteCollection,
    SmsRoutingRouteResource,
    SmsRoutingRoutesCreatePayload,
    SmsRoutingRoutesDeletePayload,
    SmsRoutingRoutesSmppConnectionsCreatePayload,
    SmsRoutingRoutesSmppConnectionsTestCreatePayload,
    SmsRoutingRoutesUpdatePayload,
} from "./data-contracts";
import {ApiConfig, ContentType, HttpClient, RequestParams} from "./http-client";
import {FetchError} from "ofetch";

export class V1<SecurityDataType = unknown> extends HttpClient<SecurityDataType> {
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesList
     * @request GET:/v1/sms/routing/companies
     */
    smsRoutingCompaniesList = (params: RequestParams = {}) =>
        this.request<
            {
                data: SmsRouteCompaniesCollection;
            },
            any
        >({
            path: `/v1/sms/routing/companies`,
            method: "GET",
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesCreate
     * @request POST:/v1/sms/routing/companies
     */
    smsRoutingCompaniesCreate = (data: SmsRoutingCompaniesCreatePayload, params: RequestParams = {}) =>
        this.request<
            {
                data: SmsRouteCompanyResource;
            },
            {
                /** Errors overview. */
                message: string;
                /** A detailed description of each field that failed validation. */
                errors: Record<string, string[]>;
            }
        >({
            path: `/v1/sms/routing/companies`,
            method: "POST",
            body: data,
            type: ContentType.Json,
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesCreateList
     * @request GET:/v1/sms/routing/companies/create
     */
    smsRoutingCompaniesCreateList = (params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/companies/create`,
            method: "GET",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesDetail
     * @request GET:/v1/sms/routing/companies/{company}
     */
    smsRoutingCompaniesDetail = (company: string, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/companies/${company}`,
            method: "GET",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesUpdate
     * @request PUT:/v1/sms/routing/companies/{company}
     */
    smsRoutingCompaniesUpdate = (company: string, data: SmsRoutingCompaniesUpdatePayload, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/companies/${company}`,
            method: "PUT",
            body: data,
            type: ContentType.Json,
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesDelete
     * @request DELETE:/v1/sms/routing/companies/{company}
     */
    smsRoutingCompaniesDelete = (company: string, data: SmsRoutingCompaniesDeletePayload, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/companies/${company}`,
            method: "DELETE",
            body: data,
            type: ContentType.Json,
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingCompanies
     * @name SmsRoutingCompaniesEditDetail
     * @request GET:/v1/sms/routing/companies/{company}/edit
     */
    smsRoutingCompaniesEditDetail = (company: string, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/companies/${company}/edit`,
            method: "GET",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesList
     * @request GET:/v1/sms/routing/routes
     */
    smsRoutingRoutesList = (params: RequestParams = {}) =>
        this.request<
            {
                data: SmsRoutingRouteCollection;
            },
            any
        >({
            path: `/v1/sms/routing/routes`,
            method: "GET",
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesCreate
     * @request POST:/v1/sms/routing/routes
     */
    smsRoutingRoutesCreate = (data: SmsRoutingRoutesCreatePayload, params: RequestParams = {}) =>
        this.request<
            {
                data: SmsRoutingRouteResource;
            },
            {
                /** Errors overview. */
                message: string;
                /** A detailed description of each field that failed validation. */
                errors: Record<string, string[]>;
            }
        >({
            path: `/v1/sms/routing/routes`,
            method: "POST",
            body: data,
            type: ContentType.Json,
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesCreateList
     * @request GET:/v1/sms/routing/routes/create
     */
    smsRoutingRoutesCreateList = (params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/routes/create`,
            method: "GET",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesDetail
     * @request GET:/v1/sms/routing/routes/{route}
     */
    smsRoutingRoutesDetail = (route: string, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/routes/${route}`,
            method: "GET",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesUpdate
     * @request PUT:/v1/sms/routing/routes/{route}
     */
    smsRoutingRoutesUpdate = (route: string, data: SmsRoutingRoutesUpdatePayload, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/routes/${route}`,
            method: "PUT",
            body: data,
            type: ContentType.Json,
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesDelete
     * @request DELETE:/v1/sms/routing/routes/{route}
     */
    smsRoutingRoutesDelete = (route: string, data: SmsRoutingRoutesDeletePayload, params: RequestParams = {}) =>
        this.request<string, any>({
            path: `/v1/sms/routing/routes/${route}`,
            method: "DELETE",
            body: data,
            type: ContentType.Json,
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingRoutes
     * @name SmsRoutingRoutesEditDetail
     * @request GET:/v1/sms/routing/routes/{route}/edit
     */
    smsRoutingRoutesEditDetail = (route: string, params: RequestParams = {}) =>
        this.request<any, any>({
            path: `/v1/sms/routing/routes/${route}/edit`,
            method: "GET",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingSmppConnections
     * @name SmsRoutingRoutesSmppConnectionsCreate
     * @request POST:/v1/sms/routing/routes/smpp-connections
     */
    smsRoutingRoutesSmppConnectionsCreate = (
        data: SmsRoutingRoutesSmppConnectionsCreatePayload,
        params: RequestParams = {},
    ) =>
        this.request<
            {
                data: SmsRouteSmppConnectionResource;
            },
            {
                /** Errors overview. */
                message: string;
                /** A detailed description of each field that failed validation. */
                errors: Record<string, string[]>;
            }
        >({
            path: `/v1/sms/routing/routes/smpp-connections`,
            method: "POST",
            body: data,
            type: ContentType.Json,
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingSmppConnections
     * @name SmsRoutingRoutesSmppConnectionsTestCreate
     * @request POST:/v1/sms/routing/routes/smpp-connections/test
     */
    smsRoutingRoutesSmppConnectionsTestCreate = (
        data: SmsRoutingRoutesSmppConnectionsTestCreatePayload,
        params: RequestParams = {},
    ) =>
        this.request<
            {
                success: string;
            },
            {
                /** Errors overview. */
                message: string;
                /** A detailed description of each field that failed validation. */
                errors: Record<string, string[]>;
            }
        >({
            path: `/v1/sms/routing/routes/smpp-connections/test`,
            method: "POST",
            body: data,
            type: ContentType.Json,
            format: "json",
            ...params,
        });
    /**
     * No description
     *
     * @tags SmsRoutingSmppConnections
     * @name SmsRoutingRoutesSmppConnectionsViewDetail
     * @request GET:/v1/sms/routing/routes/smpp-connections/{smsRouteSmppConnection}/view
     */
    smsRoutingRoutesSmppConnectionsViewDetail = (smsRouteSmppConnection: number, params: RequestParams = {}) =>
        this.request<
            {
                data: SmsRouteSmppConnectionResource;
            },
            | {
            /**
             * Error overview.
             * @example ""
             */
            message: string;
        }
            | {
            /** Error overview. */
            message: string;
        }
        >({
            path: `/v1/sms/routing/routes/smpp-connections/${smsRouteSmppConnection}/view`,
            method: "GET",
            format: "json",
            ...params,
        });
}


