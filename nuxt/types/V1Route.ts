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

export namespace V1 {
  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesList
   * @request GET:/v1/sms/routing/companies
   */
  export namespace SmsRoutingCompaniesList {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = {
      data: SmsRouteCompaniesCollection;
    };
  }

  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesCreate
   * @request POST:/v1/sms/routing/companies
   */
  export namespace SmsRoutingCompaniesCreate {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingCompaniesCreatePayload;
    export type RequestHeaders = {};
    export type ResponseBody = {
      data: SmsRouteCompanyResource;
    };
  }

  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesCreateList
   * @request GET:/v1/sms/routing/companies/create
   */
  export namespace SmsRoutingCompaniesCreateList {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesDetail
   * @request GET:/v1/sms/routing/companies/{company}
   */
  export namespace SmsRoutingCompaniesDetail {
    export type RequestParams = {
      company: string;
    };
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesUpdate
   * @request PUT:/v1/sms/routing/companies/{company}
   */
  export namespace SmsRoutingCompaniesUpdate {
    export type RequestParams = {
      company: string;
    };
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingCompaniesUpdatePayload;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesDelete
   * @request DELETE:/v1/sms/routing/companies/{company}
   */
  export namespace SmsRoutingCompaniesDelete {
    export type RequestParams = {
      company: string;
    };
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingCompaniesDeletePayload;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingCompanies
   * @name SmsRoutingCompaniesEditDetail
   * @request GET:/v1/sms/routing/companies/{company}/edit
   */
  export namespace SmsRoutingCompaniesEditDetail {
    export type RequestParams = {
      company: string;
    };
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesList
   * @request GET:/v1/sms/routing/routes
   */
  export namespace SmsRoutingRoutesList {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = {
      data: SmsRoutingRouteCollection;
    };
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesCreate
   * @request POST:/v1/sms/routing/routes
   */
  export namespace SmsRoutingRoutesCreate {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingRoutesCreatePayload;
    export type RequestHeaders = {};
    export type ResponseBody = {
      data: SmsRoutingRouteResource;
    };
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesCreateList
   * @request GET:/v1/sms/routing/routes/create
   */
  export namespace SmsRoutingRoutesCreateList {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesDetail
   * @request GET:/v1/sms/routing/routes/{route}
   */
  export namespace SmsRoutingRoutesDetail {
    export type RequestParams = {
      route: string;
    };
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesUpdate
   * @request PUT:/v1/sms/routing/routes/{route}
   */
  export namespace SmsRoutingRoutesUpdate {
    export type RequestParams = {
      route: string;
    };
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingRoutesUpdatePayload;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesDelete
   * @request DELETE:/v1/sms/routing/routes/{route}
   */
  export namespace SmsRoutingRoutesDelete {
    export type RequestParams = {
      route: string;
    };
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingRoutesDeletePayload;
    export type RequestHeaders = {};
    export type ResponseBody = string;
  }

  /**
   * No description
   * @tags SmsRoutingRoutes
   * @name SmsRoutingRoutesEditDetail
   * @request GET:/v1/sms/routing/routes/{route}/edit
   */
  export namespace SmsRoutingRoutesEditDetail {
    export type RequestParams = {
      route: string;
    };
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = any;
  }

  /**
   * No description
   * @tags SmsRoutingSmppConnections
   * @name SmsRoutingRoutesSmppConnectionsCreate
   * @request POST:/v1/sms/routing/routes/smpp-connections
   */
  export namespace SmsRoutingRoutesSmppConnectionsCreate {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingRoutesSmppConnectionsCreatePayload;
    export type RequestHeaders = {};
    export type ResponseBody = {
      data: SmsRouteSmppConnectionResource;
    };
  }

  /**
   * No description
   * @tags SmsRoutingSmppConnections
   * @name SmsRoutingRoutesSmppConnectionsTestCreate
   * @request POST:/v1/sms/routing/routes/smpp-connections/test
   */
  export namespace SmsRoutingRoutesSmppConnectionsTestCreate {
    export type RequestParams = {};
    export type RequestQuery = {};
    export type RequestBody = SmsRoutingRoutesSmppConnectionsTestCreatePayload;
    export type RequestHeaders = {};
    export type ResponseBody = {
      success: string;
    };
  }

  /**
   * No description
   * @tags SmsRoutingSmppConnections
   * @name SmsRoutingRoutesSmppConnectionsViewDetail
   * @request GET:/v1/sms/routing/routes/smpp-connections/{smsRouteSmppConnection}/view
   */
  export namespace SmsRoutingRoutesSmppConnectionsViewDetail {
    export type RequestParams = {
      /** The sms route smpp connection ID */
      smsRouteSmppConnection: number;
    };
    export type RequestQuery = {};
    export type RequestBody = never;
    export type RequestHeaders = {};
    export type ResponseBody = {
      data: SmsRouteSmppConnectionResource;
    };
  }
}
