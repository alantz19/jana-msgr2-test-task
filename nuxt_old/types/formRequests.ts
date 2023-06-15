export type UserSignupCreateRequest = {
    name: string;
    website: string;
    email: string;
    password: string;
};
export type SmsRoutingRouteCreateRequest = {
    name: string;
    description?: string;
    sms_route_company_id: string;
    smpp_connection_id: string;
};
export type SmsRoutingCompanyCreateRequest = {
    name: string;
};
export type SmsRouteSmppConnectionCreateRequest = {
    url: string;
    username: string;
    password: string;
    port: number;
    dlr_url?: string;
    dlr_port?: number;
    workers_count?: number;
    workers_delay?: string;
};
export type SmsCampaignTextCreateRequest = {
    sms_campaign_id: number;
    text: string;
};
export type LoginRequest = {
    email: string;
    password: string;
};
